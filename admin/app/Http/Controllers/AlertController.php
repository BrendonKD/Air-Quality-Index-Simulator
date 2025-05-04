<?php

namespace App\Http\Controllers;

use App\Models\AlertThreshold;
use App\Models\Alert;
use Illuminate\Http\Request;
use App\Models\Threshold;
use Illuminate\Support\Facades\Validator;

class AlertController extends Controller
{
    /**
     * Display alert thresholds management page
     */
    public function index()
    {
        $thresholds = AlertThreshold::all();
        return view('admin.simulation.thresholds', compact('thresholds'));
    }
    
    /**
     * Store or update an alert threshold
     */
    public function storeThreshold(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'level' => 'required|string|max:50',
            'min_value' => 'required|numeric|min:0',
            'max_value' => 'required|numeric|min:0|gte:min_value', 
            'color_code' => 'required|string|max:20',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $thresholdData = [
            'level' => $request->level,
            'min_value' => $request->min_value,
            'max_value' => $request->max_value,
            'color_code' => $request->color_code,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];
        
        if ($request->has('id')) {
            $threshold = AlertThreshold::findOrFail($request->id);
            $threshold->update($thresholdData);
            $message = 'Alert threshold updated successfully.';
        } else {
            AlertThreshold::create($thresholdData);
            $message = 'Alert threshold created successfully.';
        }
        
        return redirect()->route('admin.alerts.thresholds')->with('success', $message);
    }
    
    /**
     * Delete an alert 
     */
    public function deleteThreshold($id)
    {
        $threshold = AlertThreshold::findOrFail($id);
        $threshold->delete();
        
        return redirect()->route('admin.alerts.thresholds')->with('success', 'Alert threshold deleted successfully.');
    }
    
    /**
     * Display active alerts
     */
    public function activeAlerts()
    {
        $alerts = Alert::with(['sensorLocation', 'alertThreshold'])
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.simulation.active', compact('alerts'));
    }
    
    public function markAsRead($id)
    {
        $alert = Alert::findOrFail($id);
        $alert->is_read = true;
        $alert->save();
        
        return redirect()->back()->with('success', 'Alert marked as read.');
    }
    
    public function getAlertsCount()
    {
        $count = Alert::where('is_read', false)->count();
        return response()->json(['count' => $count]);
    }
}
