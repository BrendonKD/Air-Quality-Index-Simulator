@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card sensor-card">
                <div class="card-header sensor-card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="sensor-title">Sensor Management</h3>
                        <a href="{{ route('admin.sensors.create') }}" class="btn btn-primary sensor-add-btn">
                            <i class="fas fa-plus"></i> Add New Sensor
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success sensor-alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table sensor-table">
                            <thead class="sensor-table-header">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>City</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th width="230">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sensors as $sensor)
                                <tr class="sensor-table-row">
                                    <td>{{ $sensor->id }}</td>
                                    <td>{{ $sensor->name }}</td>
                                    <td>{{ $sensor->city }}</td>
                                    <td>{{ $sensor->latitude }}</td>
                                    <td>{{ $sensor->longitude }}</td>
                                    <td>{{ Str::limit($sensor->description, 50) }}</td>
                                    <td>
                                        <span class="badge {{ $sensor->is_active ? 'bg-success' : 'bg-danger' }} sensor-status">
                                            {{ $sensor->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $sensor->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $sensor->updated_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <div class="btn-group sensor-actions" role="group">
                                            <form action="{{ route('admin.sensors.toggle-active', $sensor) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $sensor->is_active ? 'btn-warning' : 'btn-success' }}">
                                                    {{ $sensor->is_active ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.sensors.edit', $sensor) }}" class="btn btn-sm btn-info mx-1">Edit</a>
                                            <form action="{{ route('admin.sensors.destroy', $sensor) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this sensor?')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center sensor-empty">
                                        <i class="fas fa-sensor-slash"></i> No sensors found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Card Styling */
    .sensor-card {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        border: none;
    }
    
    .sensor-card-header {
        background-color: #4e73df;
        color: white;
        border-radius: 8px 8px 0 0 !important;
        padding: 1rem 1.5rem;
    }
    
    .sensor-title {
        font-weight: 600;
        font-size: 1.3rem;
        margin-bottom: 0;
    }
    
    .sensor-add-btn {
        font-weight: 500;
        border-radius: 4px;
    }
    
    /* Alert Styling */
    .sensor-alert {
        border-radius: 6px;
        padding: 0.75rem 1.25rem;
        border-left: 4px solid #28a745;
    }
    
    .sensor-alert i {
        margin-right: 8px;
    }
    
    /* Table Styling */
    .sensor-table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }
    
    .sensor-table-header {
        background-color: #f8f9fc;
        color: #5a5c69;
    }
    
    .sensor-table-header th {
        border-bottom: 2px solid #e3e6f0;
        padding: 12px 15px;
        font-weight: 600;
    }
    
    .sensor-table-row td {
        padding: 12px 15px;
        vertical-align: middle;
        border-bottom: 1px solid #e3e6f0;
    }
    
    .sensor-table-row:hover {
        background-color: #f8f9fc;
    }
    
    /* Status Badge */
    .sensor-status {
        padding: 0.35em 0.65em;
        font-size: 0.875em;
        font-weight: 600;
    }
    
    /* Action Buttons */
    .sensor-actions .btn {
        border-radius: 4px;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    /* Empty State */
    .sensor-empty {
        padding: 2rem;
        color: #6c757d;
    }
    
    .sensor-empty i {
        margin-right: 8px;
    }
</style>
@endsection