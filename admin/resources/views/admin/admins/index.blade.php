@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card admin-card">
                <div class="card-header d-flex justify-content-between align-items-center admin-card-header">
                    <span class="admin-title">Admin Management</span>
                    <a href="{{ route('admins.create') }}" class="btn btn-primary btn-sm admin-add-btn">
                        <i class="fas fa-plus"></i> Add New Admin
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success admin-alert" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    <table class="table admin-table">
                        <thead class="admin-table-header">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($admins as $admin)
                                <tr class="admin-table-row">
                                    <td>{{ $admin->id }}</td>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>
                                        <span class="admin-date">
                                            {{ $admin->created_at ? $admin->created_at->format('d M Y H:i') : 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('admins.destroy', $admin->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm admin-delete-btn" onclick="return confirm('Are you sure you want to delete this admin?')">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center admin-empty">
                                        <i class="fas fa-users-slash"></i> No admins found
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

<style>

    .admin-card {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        border: none;
    }
    
    .admin-card-header {
        background-color: #4e73df;
        color: white;
        border-radius: 8px 8px 0 0 !important;
        padding: 1rem 1.5rem;
    }
    
    .admin-title {
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .admin-add-btn {
        font-weight: 500;
        border-radius: 4px;
    }
    
    /* Alert Styling */
    .admin-alert {
        border-radius: 6px;
        padding: 0.75rem 1.25rem;
        border-left: 4px solid #28a745;
    }
    
    .admin-alert i {
        margin-right: 8px;
    }
    
    /* Table Styling */
    .admin-table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }
    
    .admin-table-header {
        background-color: #f8f9fc;
        color: #5a5c69;
    }
    
    .admin-table-header th {
        border-bottom: 2px solid #e3e6f0;
        padding: 12px 15px;
        font-weight: 600;
    }
    
    .admin-table-row td {
        padding: 12px 15px;
        vertical-align: middle;
        border-bottom: 1px solid #e3e6f0;
    }
    
    .admin-table-row:hover {
        background-color: #f8f9fc;
    }
    
    /* Date Styling */
    .admin-date {
        font-family: monospace;
        color: #5a5c69;
    }
    
    /* Button Styling */
    .admin-delete-btn {
        border-radius: 4px;
        padding: 0.25rem 0.5rem;
    }
    
    /* Empty State */
    .admin-empty {
        padding: 2rem;
        color: #6c757d;
    }
    
    .admin-empty i {
        margin-right: 8px;
    }
</style>
@endsection