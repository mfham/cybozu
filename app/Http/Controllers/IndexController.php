<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Cybozu;

class IndexController extends Controller
{
    //
    public function index(Request $request) {
        $cybozuSessionId = $request->cookie('CBSESSID');
        $cybozu = new Cybozu($cybozuSessionId);
        if ($cybozu->isLogin()) {
            # logged in
            return view('home');
        } else {
            # not login
            return view('welcome');
        }
    }
    public function result(Request $request) {
        $name = $request->input('username');
        $password = $request->input('password');
        $cybozu = new Cybozu();
        if ($cybozuValue = $cybozu->UtilLogin($name, $password)) {
            // success
            return response()->view('home')->withCookie('CBSESSID', $cybozuValue, 5);
        } else {
            // error
            return view('welcome');
        }
    }
}
