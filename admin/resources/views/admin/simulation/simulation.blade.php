@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4>AQI Simulation Settings</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('admin.simulation.update') }}">
                        @csrf
                        <div class="row mb-3">
                            <label for="aqi_baseline" class="col-md-4 col-form-label text-md-end">AQI Baseline</label>
                            <div class="col-md-6">
                                <input id="aqi_baseline" type="number" class="form-control @error('aqi_baseline') is-invalid @enderror" 
                                    name="aqi_baseline" value="{{ old('aqi_baseline', $settings->aqi_baseline) }}" step="0.1" required>
                                <small class="form-text text-muted">Base AQI value for simulation (0-500)</small>
                                @error('aqi_baseline')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="fluctuation_range" class="col-md-4 col-form-label text-md-end">Fluctuation Range</label>
                            <div class="col-md-6">
                                <input id="fluctuation_range" type="number" class="form-control @error('fluctuation_range') is-invalid @enderror" 
                                    name="fluctuation_range" value="{{ old('fluctuation_range', $settings->fluctuation_range) }}" step="0.1" required>
                                <small class="form-text text-muted">How much AQI values can fluctuate (+/-)</small>
                                @error('fluctuation_range')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="frequency_seconds" class="col-md-4 col-form-label text-md-end">Update Frequency (seconds)</label>
                            <div class="col-md-6">
                                <input id="frequency_seconds" type="number" class="form-control @error('frequency_seconds') is-invalid @enderror" 
                                    name="frequency_seconds" value="{{ old('frequency_seconds', $settings->frequency_seconds) }}" min="10" required>
                                <small class="form-text text-muted">How often to generate new readings (min 10 seconds)</small>
                                @error('frequency_seconds')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Update Settings
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-center mt-4">
                        @if ($settings->is_running)
                            <form method="POST" action="{{ route('admin.simulation.stop') }}">
                                @csrf
                                <button type="submit" class="btn btn-danger">Stop Simulation</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.simulation.start') }}">
                                @csrf
                                <button type="submit" class="btn btn-success">Start Simulation</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h4>Alert Thresholds</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Level</th>
                                    <th>Min Value</th>
                                    <th>Max Value</th>
                                    <th>Color</th>
                                    <th>Active</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($thresholds as $threshold)
                                <tr>
                                    <td>{{ $threshold->level }}</td>
                                    <td>{{ $threshold->min_value }}</td>
                                    <td>{{ $threshold->max_value }}</td>
                                    <td>
                                        <div style="width: 20px; height: 20px; background-color: {{ $threshold->color_code }}; border: 1px solid #ddd;"></div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $threshold->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $threshold->is_active ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.alerts.thresholds') }}" class="btn btn-primary">Manage Alert Thresholds</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection