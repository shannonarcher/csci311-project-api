<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class MilestoneController extends Controller {

	public function __construct()
	{

	}

	public function get(App\Milestone $milestone) {}
	public function update(App\Milestone $milestone) {}
	public function delete(App\Milestone $milestone) {}

	public function getTasks(App\Milestone $milestone) {}
}
