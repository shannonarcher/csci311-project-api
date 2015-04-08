<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use \Auth;
use \Input;
use \Hash;
use \Response;

use App\User;

class UserController extends Controller {

	public function __construct(Request $request)
	{	
		$this->request = $request;
	}

	// Sprint 1
	public function create() {
		$password = str_random(8);

		$user = User::where('email', '=', $this->request->input('email', ''))->first();
		if ($user != null)
			return Response::json([
				"message" => "Email already in use."], 401);

		$user = new User([
			"name" => $this->request->input("name"), 
			"email" => $this->request->input("email"),
			"password" => Hash::make($password)
		]);
		$user->save();

		return Response::json([
			"user" => $user,
			"password" => $password
		]);
	}

	public function getAll() {
		return User::all();
	}

	public function get(User $user) {
		return $user;
	}

	public function update(User $user) {
		$user->name = $this->request->input("name", $user->name);
		$user->email = $this->request->input("email", $user->email);

		if ($this->request->has("password"))
			$user->password = Hash::make($this->request->input("password"));

		$user->is_admin = $this->request->input("is_admin", $user->is_admin);

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
		if (Auth::validate($this->request->all())) {

			$user = User::where('email', '=', $this->request->input('email'))->first();
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
				"message" => "Invalid Credentials"], 401);
		}
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
}