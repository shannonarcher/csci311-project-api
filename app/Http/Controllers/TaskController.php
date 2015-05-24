<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Task;
use App\User;
use App\TaskComment;

use \DateTime;
use \Response;

class TaskController extends Controller {

	private $request = null;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function get(Task $task) {
		$task->load('comments', 'comments.createdBy', 'project', 'createdBy', 'parent', 'children', 'dependencies', 'resources');
		return $task;
	}

	public function update(Task $task) {		
		$user = User::where('session_token', '=', $this->request->input('session_token'))->first();

		$task->title = $this->request->input("title");
		$task->description = $this->request->input("description");
		$task->started_at = $this->request->input("started_at");
		
		$task->optimistic_duration = $this->request->input("optimistic_duration");
		$task->estimation_duration = $this->request->input("estimation_duration");
		$task->pessimistic_duration = $this->request->input("pessimistic_duration");

		$task->progress = $this->request->input("progress");
		$task->priority = $this->request->input("priority");
		
		$cp_count = 0;		
		if ($this->request->input("parent")) {
			if (!$this->findCircularParent(Task::find($this->request->input("parent")), $task))
				$task->parent_id = $this->request->input("parent");
			else 
				$cp_count = 1;
		} else {
			$task->parent_id = null;
		}

		if ($this->request->input('is_approved') && ($user->is_admin || $task->project->managers->contains($user->id))) {

			if ($task->approved_at == null) {	
				$task->approved_at = new DateTime('now');
				$task->approved_by = $user->id;
			} 
			
		} else if (!$this->request->input('is_approved')) {
			$task->approved_at = null;
			$task->approved_by = null;
		}

		$task->save();

		// add dependencies
		$cd_count = 0;
		$task->dependencies()->detach();
		if ($this->request->input("dependencies") != null) {
			$dependencies = array_unique(array_values($this->request->input("dependencies")));

			$safe_dep = [];
			foreach ($dependencies as $dep) {
				$is_circular = $this->findCircularDependency(Task::find($dep), $task);
				if (!$is_circular)
					$safe_dep[] = $dep;
				else
					$cd_count++;
			}

			$task->dependencies()->attach($safe_dep);
		}

		$task->load('parent', 'dependencies');

		return Response::json([
			'circular_dependencies' => $cd_count,
			'circular_parent' => $cp_count,
			'task' => $task], 200);
	}

	private function findCircularDependency($t1, $t2) 
	{
		$t1->load('dependencies');

		if ($t1->id == $t2->id) {
			return true;
		} else if (count($t1->dependencies) > 0) {

			foreach ($t1->dependencies as $dep) {
				if ($this->findCircularDependency($dep, $t2)) {
					return true;
				}
			}

		}
		return false;
	}

	private function findCircularParent($t1, $t2) 
	{
		$t1->load('parent');
		if ($t1->id == $t2->id) {
			return true;
		} else if ($t1->parent != null) {
			return $this->findCircularParent($t1->parent, $t2);
		}
		return false;
	}

	public function assignUser(Task $task, User $user) {
		$auth = User::where('session_token', '=', $this->request->input('session_token'))->first();

		if ($auth != null && ($auth->is_admin || $task->project->managers->contains($auth->id))) {

			if (!$task->resources->contains($user->id))
				$task->resources()->attach($user->id);	
			return Response::json(['message' => "Assigned to task."], 200);

		} else {
			return Response::json(['message' => "Administrator or manager privileges required to assign user to task."], 401);
		}
	}

	public function unassignUser(Task $task, User $user) {		
		$auth = User::where('session_token', '=', $this->request->input('session_token'))->first();

		if ($auth != null && ($auth->is_admin || $task->project->managers->contains($auth->id))) {

			$task->resources()->detach($user->id);
			return Response::json(['message' => "Unassigned from task."], 200);

		} else {
			return Response::json(['message' => "Administrator or manager privileges required to unassign user from task."], 401);
		}
	}

	public function createComment(Task $task) {
		$user = User::where('session_token', '=', $this->request->input('session_token'))->first();		

		$comment = new TaskComment([
			'comment' => $this->request->input('comment'),
			'created_by' => $user->id ]);
		$task->comments()->save($comment);
		$task->load('comments','comments.createdBy');
		return $task->comments;
	}
	
	public function getComments(Task $task) {
		$task->load('comments', 'comments.createdBy');
		return $task->comments;
	}

	public function completeTask(Task $task) {
		$task->progress = 100;
		$task->save();
		return $task;
	}
}