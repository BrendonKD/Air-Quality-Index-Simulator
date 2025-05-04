@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4>Active Alerts</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sensor Location</th>
                                    <th>Alert Level</th>
                                    <th>AQI Value</th>
                                    <th>Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($alerts as $alert)
                                <tr style="background-color: {{ $alert->alertThreshold->color_code }}20;">
                                    <td>{{ $alert->sensorLocation->name }}</td>
                                    <td>
                                        <span class="badge" style="background-color: {{ $alert->alertThreshold->color_code }}">
                                            {{ $alert->alertThreshold->level }}
                                        </span>
                                    </td>
                                    <td>{{ $alert->aqi_value }}</td>
                                    <td>{{ $alert->created_at->diffForHumans() }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.simulation.alerts.mark-read', $alert->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">Mark as Read</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No active alerts</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <a href="{{ route('admin.simulation.simulation') }}" class="btn btn-secondary">Back to Simulation Settings</a>
            </div>
        </div>
    </div>
</div>
@endsection