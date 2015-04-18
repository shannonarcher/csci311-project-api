<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Project;
use App\User;

use \DateTime;

class ProjectController extends Controller {
	private $request = null;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	// Sprint 1
	public function create() {
		$user = User::where('session_token', '=', $this->request->input('session_token'))->first();
		$managers = $this->request->input('managers');

		if (count($managers) > 0) {

			$project = new Project([
					"name" => $this->request->input('name'),
					"started_at" => new DateTime($this->request->input('started_at')),
					"expected_completed_at" => new DateTime($this->request->input('expected_completed_at')),
					"created_by" => $user->id
				]);

			$project->save();

			// clean managers
			$attachments = [];
			foreach ($managers as $key => $value) {
				$attachments[$value] = ['is_manager' => true];
			}

			// assign managers
			$project->managers()->attach($attachments);

			return $project;
		} else {
			return Response::json([
				'message' => 'Project requires at least one manager.'], 400);
		}
	}
	public function getAll() {
		return Project::with('users')->get();
	}
	public function get(Project $project) {
		$project->load('users','managers','tasks','createdBy','milestones');
		return $project;
	}
	public function update(Project $project) {
		$project->name = $this->request->input('name', $project->name);
		$project->started_at = $this->request->input('started_at', $project->started_at);
		$project->expected_completed_at = $this->request->input('expected_completed_at', $project->expected_completed_at);
		$project->actual_completed_at = $this->request->input('actual_completed_at', $project->actual_completed_at);

		$project->save();

		// if users included
		

		return $project;
	}

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