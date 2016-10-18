<?php

namespace App\Http\Middleware;

use Closure;
use App\Cybozu;

class LoginCheck
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
        $previousUrl = url()->previous();
        // used when from login to home
        if ($request->isMethod('post') && preg_match('/\/login\z/', $previousUrl)) {
            $cybozu = new Cybozu();
            $name = $request->input('username');
            $password = $request->input('password');
            $cybozuValue = $cybozu->UtilLogin($name, $password);
            if ($cybozuValue) {
                # success
                session(['cybozu' => $cybozu]);
                return response()->view('home')->withCookie('CBSESSID', $cybozuValue, 5);
            } else {
                # error
                # ToDo: show error message
                return redirect()->route('login');
            }
        }

        $cybozu = session('cybozu');
        if (isset($cybozu) && $cybozu->isLogin()) {
            if ($request->is('login')) {
                return redirect()->route('home');
            } else {
                return $next($request);
            }
        }

        $cybozu = new Cybozu($request->cookie('CBSESSID'));
        if ($cybozu->isLogin()) {
            session(['cybozu' => $cybozu]);
            if ($request->is('login')) {
                return redirect()->route('home');
            } else {
                return $next($request);
            }
        }

        return redirect()->route('login');
    }
}
