<?php namespace App\Http\Middleware;

use Closure;

use \Response;

use App\User;

class IsManager {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		return $next($request);
	}

}
