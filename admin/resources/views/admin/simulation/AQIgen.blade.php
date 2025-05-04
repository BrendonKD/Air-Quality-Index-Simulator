@extends('admin.layouts.app')

@section('content')
<div class="container mt-5">
    <h2>AQI Location Simulator</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Location</th>
                <th>Sensor Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($locations as $index => $location)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $location->name }}</td>
                    <td>
                        @if ($location->is_active == 1)
                            <span style="background-color: green; color: white; padding: 0.2em 0.5em; border-radius: 5px;">Active</span>
                        @else
                            <span style=" background-color: red; color: white; padding: 0.2em 0.5em; border-radius: 5px;">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('simulate.location', $location->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm" 
                                @if (!$location->is_active) disabled @endif>
                                Simulate
                            </button>
                        </form>
                        <form action="{{ route('admin.simulation.stop') }}" method="POST" style="display:inline-block;">
                            @csrf
                            <input type="hidden" name="location_id" value="{{ $location->id }}">
                            <button type="submit" class="btn btn-danger btn-sm"
                                @if (!$location->is_active) disabled @endif>
                                Stop
                            </button>
                    </td>
                </tr>
            @endforeach
            @if ($locations->isEmpty())
                <tr>
                    <td colspan="4" class="text-center">No sensor locations found.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection
