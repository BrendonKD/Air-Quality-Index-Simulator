@extends('admin.layouts.app')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Data Simulation Management</h2>
        </div>
        <div class="card-body">
            <form id="simulation-config-form">
                @csrf
                
                <div class="mb-4">
                    <h4>Configuration Parameters</h4>
                    <hr>
                </div>
                
                <!-- Frequency -->
                <div class="mb-3">
                    <label for="frequency" class="form-label">Data Generation Frequency</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="frequency" name="frequency" min="1" value="5">
                        <select class="form-select" id="frequency_unit" name="frequency_unit" style="max-width: 150px;">
                            <option value="seconds">Seconds</option>
                            <option value="minutes" selected>Minutes</option>
                            <option value="hours">Hours</option>
                        </select>
                    </div>
                    <div class="form-text">How often new data points should be generated</div>
                </div>
                
                <!-- Baseline AQI Settings -->
                <div class="mb-3">
                    <label for="baseline_aqi" class="form-label">Baseline AQI Level</label>
                    <input type="range" class="form-range" id="baseline_aqi" name="baseline_aqi" 
                           min="0" max="500" value="50" oninput="updateAqiValue(this.value)">
                    <div class="d-flex justify-content-between">
                        <span class="badge bg-success">0</span>
                        <span class="badge bg-success">50</span>
                        <span class="badge bg-warning">100</span>
                        <span class="badge bg-danger">150</span>
                        <span class="badge bg-danger">300</span>
                        <span class="badge bg-dark">500</span>
                    </div>
                    <div class="form-text">Current value: <span id="aqi_value">50</span> (Good)</div>
                </div>
                
                <!-- Variation Pattern Settings -->
                <div class="mb-3">
                    <label class="form-label">Variation Pattern</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="variation_pattern" id="pattern_random" value="random" checked>
                        <label class="form-check-label" for="pattern_random">
                            Random (±20% variation from baseline)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="variation_pattern" id="pattern_trending" value="trending">
                        <label class="form-check-label" for="pattern_trending">
                            Trending (gradually increase/decrease over time)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="variation_pattern" id="pattern_cyclic" value="cyclic">
                        <label class="form-check-label" for="pattern_cyclic">
                            Cyclic (day/night pattern simulation)
                        </label>
                    </div>
                </div>
                
                <!-- Variation Amount -->
                <div class="mb-3">
                    <label for="variation_amount" class="form-label">Maximum Variation Amount (%)</label>
                    <input type="range" class="form-range" id="variation_amount" name="variation_amount" 
                           min="5" max="50" value="20" oninput="updateVariationValue(this.value)">
                    <div class="form-text">Current value: <span id="variation_value">20</span>%</div>
                </div>

                <!-- Simulation Control Buttons -->
                <div class="d-flex justify-content-between mt-4">
                    <div>
                        <button type="button" id="save-config-btn" class="btn btn-primary me-2">
                            <i class="fas fa-save"></i> Save Configuration
                        </button>
                    </div>
                    <div>
                        <button type="button" id="start-simulation-btn" class="btn btn-success me-2">
                            <i class="fas fa-play"></i> Start Simulation
                        </button>
                        <button type="button" id="stop-simulation-btn" class="btn btn-danger" disabled>
                            <i class="fas fa-stop"></i> Stop Simulation
                        </button>
                    </div>
                </div>
            </form>
            
            <!-- Simulation Status -->
            <div class="mt-4 p-3 bg-light rounded">
                <h5>Simulation Status</h5>
                <div class="d-flex align-items-center mb-2">
                    <div id="status-indicator" class="me-2" style="width: 15px; height: 15px; border-radius: 50%; background-color: #dc3545;"></div>
                    <span id="simulation-status">Inactive</span>
                </div>
                <div>Last data point generated: <span id="last-data-time">Never</span></div>
                <div>Total data points generated: <span id="data-point-count">0</span></div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function updateAqiValue(val) {
        document.getElementById('aqi_value').textContent = val;
        
        // Update the description based on AQI value
        let description = "";
        if (val <= 50) description = "Good";
        else if (val <= 100) description = "Moderate";
        else if (val <= 150) description = "Unhealthy for Sensitive Groups";
        else if (val <= 200) description = "Unhealthy";
        else if (val <= 300) description = "Very Unhealthy";
        else description = "Hazardous";
        
        document.getElementById('aqi_value').textContent = val + " (" + description + ")";
    }
    
    function updateVariationValue(val) {
        document.getElementById('variation_value').textContent = val;
    }
    
    // For demo purposes only - these would be connected to real functions in a complete implementation
    document.getElementById('start-simulation-btn').addEventListener('click', function() {
        // Update UI to show simulation is running
        document.getElementById('status-indicator').style.backgroundColor = '#28a745';
        document.getElementById('simulation-status').textContent = 'Active';
        this.disabled = true;
        document.getElementById('stop-simulation-btn').disabled = false;
        
        // Show a notification
        alert('Simulation started with the configured parameters.');
    });
    
    document.getElementById('stop-simulation-btn').addEventListener('click', function() {
        // Update UI to show simulation is stopped
        document.getElementById('status-indicator').style.backgroundColor = '#dc3545';
        document.getElementById('simulation-status').textContent = 'Inactive';
        this.disabled = true;
        document.getElementById('start-simulation-btn').disabled = false;
        
        // Show a notification
        alert('Simulation stopped.');
    });
    
    document.getElementById('save-config-btn').addEventListener('click', function() {
        // Show a notification
        alert('Configuration saved successfully!');
    });
</script>
@endpush