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

		$task->priority = $this->request->input("priority");
		
		if ($this->request->input("parent") != $task->id) {
			$task->parent_id = $this->request->input("parent");
		}

		if ($this->request->input('is_approved')) {

			if ($task->approved_at == null) {	
				$task->approved_at = new DateTime('now');
				$task->approved_by = $user->id;
			} 
			
		} else {
			$task->approved_at = null;
			$task->approved_by = null;
		}

		$task->save();

		// add dependencies
		$task->dependencies()->detach();
		if ($this->request->input("dependencies") != null) {
			$task->dependencies()->attach(array_unique(array_values($this->request->input("dependencies"))));
		}

		$task->load('parent', 'dependencies');

		return $task;
	}

	/*
	public function delete(Task $task) {}
	*/

	public function assignUser(Task $task, User $user) {
		if (!$task->resources->contains($user->id))
			$task->resources()->attach($user->id);	
		return Response::json(['message' => "Assigned to task."], 200);
	}

	public function unassignUser(Task $task, User $user) {
		$task->resources()->detach($user->id);
		return Response::json(['message' => "Unassigned from task."], 200);
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
/*
	public function createDependency(Task $task, Task $dependency) {}
	public function deleteDependency(Task $task, Task $dependency) {}*/
}