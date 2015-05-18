<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Project;
use App\User;
use App\Task;
use App\Milestone;
use App\Role;
use App\FunctionPoint;

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
		$project->load('users','users.skills','users.roles','managers','users','tasks','createdBy','milestones','functionPoints');
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

	public function attachUser(Project $project, User $user) {
		$actor = User::where('session_token', '=', $this->request->input('session_token'))->first();

		$is_manager = $this->request->input("manager", 0);
		$roles = $this->request->input("roles", 0);

		// check if already attached
		foreach ($project->users as $p_user) {
			if ($p_user->id == $user->id) {
				return Response::json([
					'message' => "$user->name is already in $project->name's team."], 200);
			}
		}

		$project->users()->attach($user->id);

		foreach ($roles as $value) {
			$role = Role::where('name', '=', $value)->first();
			if ($role == null) {
				$role = Role::create(['name'=>$value]);
			}

			$role_ids[$role->id] = ['assigned_by' => $actor->id, 'assigned_for' => $project->id];
		}

		$user->roles()->attach($role_ids);

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

		if ($user->is_admin) {
			$task->approved_at = new DateTime('now');
			$task->approved_by = $user->id;
		} else {
			foreach ($project->managers as $key => $manager) {
				if ($manager->id == $user->id) {
					$task->approved_at = new DateTime('now');
					$task->approved_by = $user->id;
				}
			}
		}

		$project->tasks()->save($task);

		// add dependencies
		if ($this->request->input("dependencies") != null)
			$task->dependencies()->attach(array_unique(array_values($this->request->input("dependencies"))));

		$task->load('parent', 'dependencies');

		return $task;
	}

	public function getTasks(Project $project) {
		$project->load('tasks.dependencies');
		return $project->tasks;
	}

	public function addRoleToUser(Project $project, User $user) {
		$actor = User::where('session_token', '=', $this->request->input('session_token'))->first();
		$roles = $this->request->input('roles');

		$role_ids = [];

		if (is_array($roles)) {
			foreach ($roles as $value) {
				$role = Role::where('name', '=', $value)->first();
				if ($role == null) {
					$role = Role::create(['name'=>$value]);
				}

				$exists = false;
				foreach ($user->roles as $existing) {
					if ($existing->id == $role->id && $existing->pivot->assigned_for == $project->id)
						$exists = true;
				}

				if (!$exists)
					$role_ids[$role->id] = ['assigned_by' => $actor->id, 'assigned_for' => $project->id];
			}

			$user->roles()->attach($role_ids);
		}

		return Response::json([
			'message' => "Roles added to $user->name."], 200);
	}

	public function removeRoleFromUser(Project $project, User $user) {
		$roles = $this->request->input('roles');

		foreach ($user->roles as $role) {
			if ($role->pivot->assigned_for == $project->id) {
				foreach ($roles as $to_delete) {
					if ($to_delete == $role->id) {
						\DB::delete('delete from users_roles where id = ?', [$role->pivot->id]);
						continue;
					}
				}
			}
		}

		return Response::json([
			'message' => "Roles removed from $user->name."]);
	}

	public function createMilestone(Project $project) {
		$user = User::where('session_token', '=', $this->request->input('session_token'))->first();
		$milestone = new Milestone([
			'created_by' => $user->id,
			'title' => $this->request->input('title'),
			'completed_at' => new DateTime('@'.strtotime($this->request->input('completed_at')))
		]);

		$project->milestones()->save($milestone);

		return $milestone;
	}

	public function getMilestones(Project $project) {
		return $project->milestones;
	}

	public function getComments(Project $project) {}

	public function saveFunctionPoints(Project $project) {
		$functionPoint = $project->functionPoints;

		if ($functionPoint == null) {
			$functionPoint = new FunctionPoint();
		}

		$functionPoint->low_ilf = $this->request->input('low_ilf');
		$functionPoint->med_ilf = $this->request->input('med_ilf');
		$functionPoint->hi_ilf = $this->request->input('hi_ilf');

		$functionPoint->low_eif = $this->request->input('low_eif');
		$functionPoint->med_eif = $this->request->input('med_eif');
		$functionPoint->hi_eif = $this->request->input('hi_eif');

		$functionPoint->low_ei = $this->request->input('low_ei');
		$functionPoint->med_ei = $this->request->input('med_ei');
		$functionPoint->hi_ei = $this->request->input('hi_ei');

		$functionPoint->low_eo = $this->request->input('low_eo');
		$functionPoint->med_eo = $this->request->input('med_eo');
		$functionPoint->hi_eo = $this->request->input('hi_eo');

		$functionPoint->low_eq = $this->request->input('low_eq');
		$functionPoint->med_eq = $this->request->input('med_eq');
		$functionPoint->hi_eq = $this->request->input('hi_eq');

		$functionPoint->gsc_1 = $this->request->input('gsc_1');
		$functionPoint->gsc_2 = $this->request->input('gsc_2');
		$functionPoint->gsc_3 = $this->request->input('gsc_3');
		$functionPoint->gsc_4 = $this->request->input('gsc_4');
		$functionPoint->gsc_5 = $this->request->input('gsc_5');
		$functionPoint->gsc_6 = $this->request->input('gsc_6');
		$functionPoint->gsc_7 = $this->request->input('gsc_7');
		$functionPoint->gsc_8 = $this->request->input('gsc_8');
		$functionPoint->gsc_9 = $this->request->input('gsc_9');
		$functionPoint->gsc_10 = $this->request->input('gsc_10');
		$functionPoint->gsc_11 = $this->request->input('gsc_11');
		$functionPoint->gsc_12 = $this->request->input('gsc_12');
		$functionPoint->gsc_13 = $this->request->input('gsc_13');
		$functionPoint->gsc_14 = $this->request->input('gsc_14');

		$functionPoint->save();

		return $this->get($project);
	}
}