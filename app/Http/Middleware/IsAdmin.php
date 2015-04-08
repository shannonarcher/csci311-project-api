<?php namespace App\Http\Middleware;

use Closure;

use \Response;

use App\User;

class IsAdmin {

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
		if ($user == null || $user->is_admin == 0)
			return Response::json(['message' => 'Unauthorised request'], 400);

		return $next($request);
	}

}
