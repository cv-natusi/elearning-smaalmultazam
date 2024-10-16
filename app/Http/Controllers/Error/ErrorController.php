<?php

namespace App\Http\Controllers\Error;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ErrorController extends Controller
{
    function error401() {
        return view('error.401');
    }
}
