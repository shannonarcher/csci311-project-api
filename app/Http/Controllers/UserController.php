<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class UserController extends Controller {

	public function __construct()
	{

	}

	public function create() {}
	public function getAll() {}
	public function get(App\User $user) {}
	public function update(App\User $user) {}

	public function archive(App\User $user) {}
	public function unarchive(App\User $user) {}

	public function login() {}
	public function updatePassword(App\User $user = null) {}
	public function resetPassword(App\User $user = null) {}
	public function setPassword(App\User $user = null) {}
}