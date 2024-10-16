<?php

namespace App\Http\Middleware\Level;

use Closure;
use Illuminate\Http\Request;
use Auth;

class GuruPiket
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
		if (Auth::user() && Auth::user()->piket) {
			return $next($request);
		}
		if ($request->ajax()) {
			return Help::resHttp(['code' => 401, 'message' => 'Oops, Anda tidak punya akses!']);
		}
		return redirect()->route('error.401');
    }
}
