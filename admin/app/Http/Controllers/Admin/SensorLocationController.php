<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SensorLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SensorLocationController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:admin');
    }

    public function index()
    {
        $sensors = SensorLocation::orderBy('created_at', 'desc')->get();
        return view('admin.sensors.index', compact('sensors'));
    }

    public function create()
    {
        return view('admin.sensors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'description' => 'nullable|string',
        ]);
    
        $sensor = SensorLocation::create($validated);
    
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Sensor location created successfully.',
                'sensor' => $sensor
            ], 201); 
        }
    
        return redirect()->route('admin.sensors.index')
            ->with('success', 'Sensor location created successfully.');
    }
    

    public function show(SensorLocation $sensor)
    {
        return view('admin.sensors.show', compact('sensor'));
    }

    public function edit(SensorLocation $sensor)
    {
        return view('admin.sensors.edit', compact('sensor'));
    }

    public function update(Request $request, SensorLocation $sensor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $sensor->update($validated);

        return redirect()->route('admin.sensors.index')
            ->with('success', 'Sensor location updated successfully.');
    }

    public function getLocations()
    {
        $sensors = SensorLocation::all()->map(function ($sensor) {
            return [
                'id' => $sensor->id,
                'name' => $sensor->name,
                'city' => $sensor->city,
                'latitude' => $sensor->latitude,
                'longitude' => $sensor->longitude,
                'is_active' => $sensor->is_active,
            ];
        });

        return response()->json($sensors);
    }

    public function destroy(SensorLocation $sensor)
    {
        $sensor->delete();

        return redirect()->route('admin.sensors.index')
            ->with('success', 'Sensor location deleted successfully.');
    }

    public function toggleActive(SensorLocation $sensor)
    {
        $sensor->update([
            'is_active' => !$sensor->is_active
        ]);

        return redirect()->route('admin.sensors.index')
            ->with('success', 'Sensor status updated successfully.');
    }
}