<?php

namespace App\Http\Middleware\Level;

use Closure, Auth, Help;
use Illuminate\Http\Request;

class Guru
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function handle(Request $request, Closure $next)
	{
		// if (Auth::user() && Auth::user()->level_user === 3) {
		if (Auth::user() && in_array(Auth::user()->level_user,[3,5])) {
			return $next($request);
		}
		if ($request->ajax()) {
			return Help::resHttp(['code' => 401, 'message' => 'Oops, Anda tidak punya akses!']);
		}
		return redirect()->route('error.401');
	}
}
