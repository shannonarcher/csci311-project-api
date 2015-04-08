<?php namespace App\Http\Middleware;

use Closure;

use \Response;
use \Route;

use App\User;

class IsUserOwner {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$user = User::where('session_token', '=', $request->input('session_token'))->first();

		if ($user->id == Route::input('user')->id || $user->is_admin == 1)
			return $next($request);
		else 
			return Response::json(["message" => "User cannot edit another user without admin privileges"], 400);
	}

}
