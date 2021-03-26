<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessagesController extends Controller
{
    // protect route
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function inbox()
    {
        // get this users messages
        return view('messages.inbox');
    }
}
