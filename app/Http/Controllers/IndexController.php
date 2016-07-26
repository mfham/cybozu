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
        $cybozuValue = $request->cookie('CBSESSID');
        return view('welcome');
    }
    public function result(Request $request) {
        $name = $request->input('username');
        $password = $request->input('password');
        $cybozu = new Cybozu();
        if ($cybozuValue = $cybozu->UtilLogin($name, $password)) {
            // success
        } else {
            // error
        }
        return response()->view('welcome2')->withCookie('CBSESSID', $cybozuValue, 5);
    }
}
