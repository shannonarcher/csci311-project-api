<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class TaskCommentController extends Controller {

	public function __construct()
	{

	}

	public function get(App\TaskComment $comment) {}
	public function update(App\TaskComment $comment) {}

	public function archive(App\TaskComment $comment) {}
	public function unarchive(App\TaskComment $comment) {}
}
