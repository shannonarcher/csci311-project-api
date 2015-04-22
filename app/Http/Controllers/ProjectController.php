<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Project;
use App\User;
use App\Task;
use App\Milestone;

use \DateTime;
use \Response;

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
		$project->load('users','users.skills','managers','tasks','createdBy','milestones');
		return $project;
	}
	public function update(Project $project) {
		$project->name = $this->request->input('name', $project->name);
		$project->started_at = $this->request->input('started_at', $project->started_at);
		$project->expected_completed_at = $this->request->input('expected_completed_at', $project->expected_completed_at);
		$project->actual_completed_at = $this->request->input('actual_completed_at', $project->actual_completed_at);
		if ($project->actual_completed_at == '')
			$project->actual_completed_at = null;

		$project->save();

		return $project;
	}

	public function archive(Project $project) {}
	public function unarchive(Project $project) {}

	public function attachUser(Project $project, User $user) {
		$is_manager = $this->request->input("manager", 0);

		// check if already attached
		foreach ($project->users as $p_user) {
			if ($p_user->id == $user->id) {
				return Response::json([
					'message' => "$user->name is already in $project->name's team."], 200);
			}
		}

		$project->users()->attach($user->id);
		return Response::json([
			'message' => "$user->name added to $project->name's team."], 200);
	}
	public function detachUser(Project $project, User $user) {
		$has_manager = false;
		foreach ($project->users as $p_user) {
			if ($p_user->id != $user->id && $p_user->pivot->is_manager) 
				$has_manager = true;
		}

		if (count($project->users) < 5)
			return Response::json([
				'message' => "$project->name must have at least five users."], 401);

		if (!$has_manager)
			return Response::json([
				'message' => "$project->name must have at least one manager."], 401);

		$project->users()->detach($user->id);
		return Response::json([
			'message' => "$user->name removed from $project->name."], 200);
	}

	public function promoteUser(Project $project, User $user) {		
		$project->users()->updateExistingPivot($user->id, ['is_manager' => true]);
		return Response::json([
			'message' => "$user->name promoted to manager."
			]);
	}

	public function demoteUser(Project $project, User $user) {		
		$has_manager = false;
		foreach ($project->users as $p_user) {
			if ($p_user->id != $user->id && $p_user->pivot->is_manager) 
				$has_manager = true;
		}

		if (!$has_manager)
			return Response::json([
				'message' => "$project->name must have at least one manager."], 401);

		$project->users()->updateExistingPivot($user->id, ['is_manager' => false]);
		return Response::json([
			'message' => "$user->name demoted from manager."
			]);
	}

	// Sprint 2
	public function createTask(Project $project) {
		$user = User::where('session_token', '=', $this->request->input('session_token'))->first();

		$task = new Task($this->request->all());
		$task->created_by = $user->id;
		$task->parent_id = $this->request->input("parent");
		$project->tasks()->save($task);

		// add dependencies
		if ($this->request->input("dependencies") != null)
			$task->dependencies()->attach(array_unique(array_values($this->request->input("dependencies"))));

		$task->load('parent', 'dependencies');

		return $task;
	}

	public function getTasks(Project $project) {
		return $project->tasks;
	}

	public function createMilestone(Project $project) {}
	public function getMilestones(Project $project) {}

	public function getComments(Project $project) {}
}