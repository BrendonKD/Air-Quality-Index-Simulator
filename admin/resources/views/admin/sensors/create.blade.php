@extends('admin.layouts.app')

@section('content')
<style>
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .form-floating {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .form-floating input,
    .form-floating textarea {
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 1rem;
        transition: border-color 0.3s;
    }

    .form-floating input:focus,
    .form-floating textarea:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    }

    .form-floating label {
        position: absolute;
        top: 0;
        left: 1rem;
        padding: 0.75rem 0.25rem;
        background: white;
        transition: all 0.2s;
        pointer-events: none;
    }

    .form-floating input:focus + label,
    .form-floating input:not(:placeholder-shown) + label,
    .form-floating textarea:focus + label,
    .form-floating textarea:not(:placeholder-shown) + label {
        transform: translateY(-0.5rem);
        font-size: 0.85rem;
        color: #007bff;
    }

    .btn-primary {
        padding: 0.75rem 2rem;
        border-radius: 8px;
        background: linear-gradient(45deg, #007bff, #00aaff);
        border: none;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,123,255,0.3);
    }

    .alert {
        border-radius: 8px;
        margin-bottom: 1.5rem;
        animation: slideIn 0.3s ease-in;
    }

    .invalid-feedback {
        display: none;
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }

    .is-invalid ~ .invalid-feedback {
        display: block;
    }

    .tooltip-icon {
        cursor: help;
        color: #6c757d;
        margin-left: 0.5rem;
    }

    @keyframes slideIn {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card p-4">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 text-primary">Add New Sensor</h3>
                        <a href="{{ route('admin.sensors.index') }}" class="btn btn-outline-secondary rounded-pill">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.sensors.store') }}" method="POST" id="sensorForm">
                        @csrf
                        <div class="form-floating">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Sensor Name" required>
                            <label for="name">Sensor Name<i class="fas fa-info-circle tooltip-icon" data-bs-toggle="tooltip" title="Enter a unique name for the sensor"></i></label>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating">
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" placeholder="City" required>
                            <label for="city">City<i class="fas fa-info-circle tooltip-icon" data-bs-toggle="tooltip" title="Enter the city where the sensor is located"></i></label>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude') }}" placeholder="Latitude" required>
                                    <label for="latitude">Latitude<i class="fas fa-info-circle tooltip-icon" data-bs-toggle="tooltip" title="Enter latitude (e.g., 40.7128)"></i></label>
                                    @error('latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude') }}" placeholder="Longitude" required>
                                    <label for="longitude">Longitude<i class="fas fa-info-circle tooltip-icon" data-bs-toggle="tooltip" title="Enter longitude (e.g., -74.0060)"></i></label>
                                    @error('longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-floating">
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" placeholder="Description" rows="4">{{ old('description') }}</textarea>
                            <label for="description">Description<i class="fas fa-info-circle tooltip-icon" data-bs-toggle="tooltip" title="Provide additional details about the sensor"></i></label>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <span class="button-text">Save Sensor</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    // Form submission handling
    document.getElementById('sensorForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        const spinner = submitBtn.querySelector('.spinner-border');
        const buttonText = submitBtn.querySelector('.button-text');

        submitBtn.disabled = true;
        spinner.classList.remove('d-none');
        buttonText.textContent = 'Saving...';

        // Reset button after 3 second
        setTimeout(() => {
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
            buttonText.textContent = 'Save Sensor';
        }, 3000);
    });
</script>
@endsection
@endsection