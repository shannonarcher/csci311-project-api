<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Project;
use App\User;

class ProjectController extends Controller {
	public function __construct()
	{
		
	}

	// Sprint 1
	public function create() {
	}
	public function getAll() {
		return Project::with('users')->get();
	}
	public function get(Project $project) {
		$project->load('users','tasks','createdBy');
		return $project;
	}
	public function update(Project $project) {}

	public function archive(Project $project) {}
	public function unarchive(Project $project) {}

	public function assignManager(Project $project, User $user) {}
	public function assignUser(Project $project, User $user) {}

	// Sprint 2
	public function createTask(Project $project) {}
	public function getTasks(Project $project) {
		return $project->tasks;
	}

	public function createMilestone(Project $project) {}
	public function getMilestones(Project $project) {}

	public function getComments(Project $project) {}
}