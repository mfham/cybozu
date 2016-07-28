<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Cybozu;

class IndexController extends Controller
{
    public function login(Request $request) {
        # ToDo: validation
        $cybozuSessionId = $request->cookie('CBSESSID');
        $cybozu = new Cybozu($cybozuSessionId);

        if ($cybozu->isLogin()) {
            # logged in
            return redirect()->route('home');
        } else {
            # not login
            # ToDo: show error message
            return view('login');
        }
    }
    public function home(Request $request) {
        $cybozu = new Cybozu();

        if ($request->isMethod('get')) {
            # ToDo: validation
            $cybozuSessionId = $request->cookie('CBSESSID');
            $cybozu->setCybozuCookie($cybozuSessionId);

            if ($cybozu->isLogin()) {
                # logged in
                return view('home');
            } else {
                # not login
                return redirect()->route('login');
            }

        } else if ($request->isMethod('post')) {
            $name = $request->input('username');
            $password = $request->input('password');

            if ($cybozuValue = $cybozu->UtilLogin($name, $password)) {
                # success
                return response()->view('home')->withCookie('CBSESSID', $cybozuValue, 5);
            } else {
                # error
                # ToDo: show error message
                return redirect()->route('login');
            }

        }
    }
}
