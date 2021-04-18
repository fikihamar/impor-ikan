<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Helper\Response;
use Closure;
use Illuminate\Support\Facades\Auth;

class DefaultPassword
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$user = Auth::user();

		if ($user->default_password === "1")
			return Response::error('Your account password is still the default');

		return $next($request);
	}
}
