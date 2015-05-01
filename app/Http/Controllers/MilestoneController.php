<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Response;
use App\Milestone;

class MilestoneController extends Controller {

	public function __construct()
	{

	}

	public function get(Milestone $milestone) {}
	public function update(Milestone $milestone) {}

	public function delete(Milestone $milestone) {
		$milestone->delete();
		return Response::json(["message" => "Milestone deleted."], 200);
	}
}
