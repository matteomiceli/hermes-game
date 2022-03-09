<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    function Index () {
        return response()->json(['greeting' => 'Hello!']);
    }
}
