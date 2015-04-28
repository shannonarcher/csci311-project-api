<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Skill;

class SkillController extends Controller {

	private $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function getAll() {
		return Skill::all();
	}

	public function create() {
		$skill = Skill::create([
			'name' => $this->request->input("name")]);
		return $skill;
	}
}
