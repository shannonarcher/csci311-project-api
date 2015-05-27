<?php namespace App\Http\Middleware;

use Closure;

use \Response;

use App\User;

class Authenticate {

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
		if ($user == null)
			return Response::json(['message' => 'Unauthorised request'], 401);
		else if ($user->is_archived == 1)
			return Response::json(['message' => 'Unauthorised request (archived)'], 401);
		return $next($request);
	}

}
