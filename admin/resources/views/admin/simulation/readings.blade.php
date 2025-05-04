@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4>AQI Readings</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse ($latestReadings as $reading)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card">
                                <div class="card-header" style="background-color: {{ getColorByStatus($reading->status) }}20;">
                                    <h5>{{ $reading->sensorLocation->name }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-center mb-3">
                                        <div class="aqi-gauge" data-value="{{ $reading->value }}">
                                            <div class="aqi-value">{{ number_format($reading->value, 1) }}</div>
                                            <div class="aqi-status">{{ $reading->status }}</div>
                                        </div>
                                    </div>
                                    <div class="small text-muted text-center">Last updated: {{ $reading->created_at->diffForHumans() }}</div>
                                    <div class="mt-3 text-center">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                No AQI readings available. Please start the simulation to generate readings.
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <a href="{{ route('admin.simulation.simulation') }}" class="btn btn-secondary">Back to Simulation Settings</a>
            </div>
        </div>
    </div>
</div>

<!-- Reading History -->
<div class="modal fade" id="readingHistoryModal" tabindex="-1" aria-labelledby="readingHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="readingHistoryModalLabel">AQI Reading History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3" id="sensorName"></div>
                <div id="historyChart" style="height: 300px;"></div>
                <div class="table-responsive mt-4">
                    <table class="table table-sm table-striped" id="historyTable">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>AQI Value</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be load in tbody -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .aqi-gauge {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 10px solid #f0f0f0;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    
    .aqi-value {
        font-size: 24px;
        font-weight: bold;
    }
    
    .aqi-status {
        font-size: 14px;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function getColorByStatus(status) {
        const colorMap = {
            'Good': '#00E400',
            'Moderate': '#FFFF00',
            'Unhealthy for Sensitive Groups': '#FF7E00',
            'Unhealthy': '#FF0000',
            'Very Unhealthy': '#99004C',
            'Hazardous': '#7E0023'
        };
        
        return colorMap[status] || '#cccccc';
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Update colors based on AQI value
        document.querySelectorAll('.aqi-gauge').forEach(gauge => {
            const value = parseFloat(gauge.getAttribute('data-value'));
            let color;
            
            if (value <= 50) color = '#00E400';
            else if (value <= 100) color = '#FFFF00';
            else if (value <= 150) color = '#FF7E00';
            else if (value <= 200) color = '#FF0000';
            else if (value <= 300) color = '#99004C';
            else color = '#7E0023';
            
            gauge.style.borderColor = color;
        });
        
        // History button click
        document.querySelectorAll('.show-history').forEach(button => {
            button.addEventListener('click', function() {
                const sensorId = this.getAttribute('data-sensor-id');
                fetchReadingHistory(sensorId);
            });
        });
        
        let historyChart = null;
        
        function fetchReadingHistory(sensorId) {
            fetch(`/admin/readings/history/${sensorId}`)
                .then(response => response.json())
                .then(data => {
                    // Find sensor with name
                    const sensorElement = document.querySelector(`[data-sensor-id="${sensorId}"]`).closest('.card').querySelector('.card-header h5');
                    const sensorName = sensorElement ? sensorElement.textContent : `Sensor #${sensorId}`;
                    
                    document.getElementById('sensorName').textContent = sensorName;
                    
                    // Prepare chart data 
                    const chartLabels = data.map(reading => {
                        const date = new Date(reading.created_at);
                        return date.toLocaleTimeString();
                    }).reverse();
                    
                    const chartValues = data.map(reading => reading.value).reverse();
                    const chartColors = data.map(reading => {
                        let color;
                        const value = reading.value;
                        
                        if (value <= 50) color = '#00E400';
                        else if (value <= 100) color = '#FFFF00';
                        else if (value <= 150) color = '#FF7E00';
                        else if (value <= 200) color = '#FF0000';
                        else if (value <= 300) color = '#99004C';
                        else color = '#7E0023';
                        
                        return color;
                    }).reverse();
                    
                    // Create or update chart
                    const ctx = document.getElementById('historyChart').getContext('2d');
                    
                    if (historyChart) {
                        historyChart.destroy();
                    }
                    
                    historyChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: chartLabels,
                            datasets: [{
                                label: 'AQI Value',
                                data: chartValues,
                                borderColor: 'rgba(75, 192, 192, 1)',
                                tension: 0.1,
                                fill: false
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                    
                    // Populate table
                    const tableBody = document.getElementById('historyTable').querySelector('tbody');
                    tableBody.innerHTML = '';
                    
                    data.forEach(reading => {
                        const row = document.createElement('tr');
                        const date = new Date(reading.created_at);
                        
                        row.innerHTML = `
                            <td>${date.toLocaleString()}</td>
                            <td>${reading.value.toFixed(1)}</td>
                            <td><span class="badge" style="background-color: ${getColorByStatus(reading.status)}">${reading.status || 'Unknown'}</span></td>
                        `;
                        
                        tableBody.appendChild(row);
                    });
                    
                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('readingHistoryModal'));
                    modal.show();
                })
                .catch(error => console.error('Error fetching reading history:', error));
        }
    });
</script>
@endsection

@php
function getColorByStatus($status) {
    $colorMap = [
        'Good' => '#00E400',
        'Moderate' => '#FFFF00',
        'Unhealthy for Sensitive Groups' => '#FF7E00',
        'Unhealthy' => '#FF0000',
        'Very Unhealthy' => '#99004C',
        'Hazardous' => '#7E0023'
    ];
    
    return $colorMap[$status] ?? '#cccccc';
}
@endphp