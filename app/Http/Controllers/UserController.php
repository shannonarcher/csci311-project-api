<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use \Auth;
use \Input;
use \Hash;
use \Response;

use App\User;
use App\Skill;

class UserController extends Controller {

	public function __construct(Request $request)
	{	
		$this->request = $request;
	}

	// Sprint 1
	public function create() {
		$password = str_random(8);

		if (strlen(trim($this->request->input("name"))) <= 0 || 
			strlen(trim($this->request->input("email"))) <= 0)
			return Response::json([
				"message" => "Email or name cannot be blank"], 400);

		$user = User::where('email', '=', $this->request->input('email', ''))->first();
		if ($user != null)
			return Response::json([
				"message" => "Email already in use."], 401);

		$user = new User([
			"name" => $this->request->input("name"), 
			"email" => $this->request->input("email"),
			"password" => Hash::make($password),
			"is_admin" => $this->request->input("is_admin"),
			"is_archived" => $this->request->input("is_archived")
		]);
		$user->save();

		return Response::json([
			"user" => $user,
			"password" => $password
		]);
	}

	public function getAll() {
		return User::with('projects', 'skills', 'roles')->get();
	}

	public function get(User $user) {
		$user->load('projects', 'skills', 'roles');
		return $user;
	}

	public function getAuthed() {
		$session_token = $this->request->input('session_token');
		$user = User::where('session_token', '=', $session_token)->with('projects')->first();
		return $user;
	}

	public function update(User $user) {
		$user->name = $this->request->input("name", $user->name);
		$user->email = $this->request->input("email", $user->email);
		$user->lang = $this->request->input("lang", $user->lang);

		if ($this->request->has("password"))
			$user->password = Hash::make($this->request->input("password"));

		$user->is_admin = ($this->request->input("is_admin", $user->is_admin) == 1);
		$user->is_archived = ($this->request->input("is_archived", $user->is_archived) == 1);

		$user->save();

		return $user;
	}

	public function archive(User $user) {
		$user->is_archived = true;
		$user->save();
		return $user;
	}
	public function unarchive(User $user) {
		$user->is_archived = false;
		$user->save();
		return $user;
	}

	public function login() {
		$user = User::where('email', '=', $this->request->input('email'))->first();

		if (Auth::validate(["email" => $this->request->input('email'), "password" => $this->request->input('password')])) {

			$user = User::where('email', '=', $this->request->input('email'))->with('projects')->first();
			if ($user->is_archived == 0) {
				$user->session_token = str_random(32);
				$user->save();
				return Response::json([
					"message" => "User authenticated",
					"session_token" => $user->session_token,
					"user" => $user ], 200);
			} else {
				return Response::json([
					"message" => "Unable to authenticate, user archived."], 400);
			}

		} else {
			return Response::json([
				"message" => "Invalid Credentials."], 401);
		}
	}

	public function logout() {
		$session_token = $this->request->input('session_token');
		$user = User::where('session_token', '=', $session_token);

		if ($user != null) {
			$user->session_token = "";
			$user->save();
		}
		return Response::json([
			"message" => "Logged out user."], 200);
	}
	
	public function resetPassword(User $user) {
		$new_password = str_random(8);
		$user->password = Hash::make($new_password);
		$user->save();

		return Response::json([
			"user" => $user,
			"password" => $new_password
			], 200);
	}

	public function attachSkill(User $user, Skill $skill) {
		$auth = User::where('session_token', '=', $this->request->input('session_token'))->first();

		if ($auth != null && ($auth->is_admin || $auth->id == $user->id)) {
			if (!$user->skills->contains($skill->id))
				$user->skills()->attach($skill->id);
			$user->load('skills');
			return $user->skills;
		} else {
			return Response::json([
				'message' => "$auth->name must be this user or an administrator."], 401);
		}
	}

	public function detachSkill(User $user, Skill $skill) {
		$auth = User::where('session_token', '=', $this->request->input('session_token'))->first();

		if ($auth != null && ($auth->is_admin || $auth->id == $user->id)) {
			$user->skills()->detach($skill->id);
			$user->load('skills');
			return $user->skills;
		} else {
			return Response::json([
				'message' => "$auth->name must be this user or an administrator."], 401);
		}
	}
}