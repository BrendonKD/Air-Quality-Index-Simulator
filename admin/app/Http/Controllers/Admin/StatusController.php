<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * Return the simulator and system status.
     */
    public function index()
    {
        return response()->json([
            'simulator_active' => true,
            'system_ok' => true,
        ]);
    }
}
