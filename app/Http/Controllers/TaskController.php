<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class TaskController extends Controller {

	public function __construct()
	{

	}

	public function get(App\Task $task) {}
	public function update(App\Task $task) {}
	public function delete(App\Task $task) {}

	public function approveDeletion(App\Task $task) {}
	public function rejectDeletion(App\Task $task) {}

	public function approveTask(App\Task $task) {}

	public function assignUser(App\Task $task, App\User $user) {}

	public function createComment(App\Task $task) {}
	public function getComments(App\Task $task) {}

	public function createRelationWithMilestone(App\Task $task, App\Milestone $milestone) {}
	public function deleteRelationWithMilestone(App\Task $task, App\Milestone $milestone) {}

	public function createDependency(App\Task $task, App\Task $dependency) {}
	public function deleteDependency(App\Task $task, App\Task $dependency) {}
}