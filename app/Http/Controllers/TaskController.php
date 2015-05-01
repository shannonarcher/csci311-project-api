<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Task;
use App\User;
use App\TaskComment;

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
		$task->estimation_duration = $this->request->input("estimation_duration");
		$task->parent_id = $this->request->input("parent");
		$task->save();

		// add dependencies
		if ($this->request->input("dependencies") != null) {
			$task->dependencies()->detach();
			$task->dependencies()->attach(array_unique(array_values($this->request->input("dependencies"))));
		}

		$task->load('parent', 'dependencies');

		return $task;
	}
	/*
	public function delete(Task $task) {}

	public function approveDeletion(Task $task) {}
	public function rejectDeletion(Task $task) {}

	public function approveTask(Task $task) {}
	public function unapproveTask(Task $task) {}

	public function assignUser(Task $task, User $user) {}
	public function unassignUser(Task $task, User $user) {}
*/
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