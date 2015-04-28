<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Role;

class RoleController extends Controller {

	private $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function getAll() {
		return Role::all();
	}

	public function create() {
		$role = Role::create([
			'name' => $this->request->input("name")]);
		return $role;
	}
}
