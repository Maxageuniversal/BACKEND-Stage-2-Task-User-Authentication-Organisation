<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Response;

class Controller extends BaseController
{
    public function index()
    {
        return response()->json(['message' => 'Hello from API']);
    }

}
