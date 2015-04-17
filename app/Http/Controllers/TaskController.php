<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Task;

class TaskController extends Controller {

	public function __construct()
	{

	}

	public function get(Task $task) {
		$task->load('comments', 'comments.createdBy', 'project', 'createdBy', 'parent', 'children', 'dependencies', 'resources');
		return $task;
	}
	/*public function update(Task $task) {}
	public function delete(Task $task) {}

	public function approveDeletion(Task $task) {}
	public function rejectDeletion(Task $task) {}

	public function approveTask(Task $task) {}
	public function unapproveTask(Task $task) {}

	public function assignUser(Task $task, User $user) {}
	public function unassignUser(Task $task, User $user) {}

	public function createComment(Task $task) {}
	public function getComments(Task $task) {}

	public function createDependency(Task $task, Task $dependency) {}
	public function deleteDependency(Task $task, Task $dependency) {}*/
}