<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Cybozu;

class IndexController extends Controller
{
    //
    public function index() {
        return view('welcome');
    }
    public function result(Request $request) {
        $name = $request->input('username');
        $password = $request->input('password');
        $cybozu = new Cybozu();
        if ($cybozu->UtilLogin($name, $password)) {
            // success
        } else {
            // error
        }
        return view('welcome2');
    }
}
