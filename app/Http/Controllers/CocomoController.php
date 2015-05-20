<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Response;
use App\SystemType;

class CocomoController extends Controller {

	public function __construct()
	{
		
	}

	public function getSystemTypes() {
		return SystemType::all();
	}
}
