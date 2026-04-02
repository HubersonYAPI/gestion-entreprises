<?php

namespace App\Http\Controllers;

use App\Models\Declaration;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function dashboard()
    {
        $declarations = Declaration::latest()->get();

        return view('agent.dashboard', compact('declarations'));
    }
}
