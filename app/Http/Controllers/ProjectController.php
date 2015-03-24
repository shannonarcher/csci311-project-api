<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ProjectController extends Controller {

	public function __construct()
	{

	}

	public function create() {}
	public function getAll() {}
	public function get(App\Project $project) {}
	public function update(App\Project $project) {}

	public function archive(App\Project $project) {}
	public function unarchive(App\Project $project) {}

	public function assignManager(App\Project $project, App\User $user) {}
	public function assignUser(App\Project $project, App\User $user) {}

	public function createTask(App\Project $project) {}
	public function getTasks(App\Project $project) {}

	public function createMilestone(App\Project $project) {}
	public function getMilestones(App\Project $project) {}

	public function getComments(App\Project $project) {}
}