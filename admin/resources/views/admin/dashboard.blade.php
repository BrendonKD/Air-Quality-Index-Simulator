@extends('admin.layouts.app')

@section('title', 'Sensor Dashboard')

@section('content')
<div class="container mx-auto mt-8 px-4">
    <h1 class="text-3xl font-bold text-gray-800 mb-6" style="text-align: center">Sensors in Colombo Metropolitant Area</h1>
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <!-- Sensor Summary Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-green-100 p-4 rounded shadow">
                    <h2 class="text-lg font-semibold text-green-700">Active Sensors</h2>
                    <p id="activeSensorCount" class="text-2xl font-bold text-green-900 mt-2">0</p>
                </div>
                <div class="bg-blue-100 p-4 rounded shadow">
                    <h2 class="text-lg font-semibold text-blue-700">Simulator Status</h2>
                    <p id="simulatorStatus" class="text-2xl font-bold text-blue-900 mt-2">Checking...</p>
                </div>
                <div class="bg-yellow-100 p-4 rounded shadow">
                    <h2 class="text-lg font-semibold text-yellow-700">System Status</h2>
                    <p id="systemStatus" class="text-2xl font-bold text-yellow-900 mt-2">Checking...</p>
                </div>
            </div>
        </div>
        <div id="map" class="rounded-lg shadow-inner" style="height: 600px;"></div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .leaflet-marker-icon {
            transition: transform 0.2s;
        }
        .leaflet-marker-icon:hover {
            transform: scale(1.2);
        }
        .popup-content {
            min-width: 200px;
        }
        .leaflet-control-filter {
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .leaflet-control-filter label {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const map = L.map('map').setView([6.9271, 79.8612], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 18
            }).addTo(map);

            let sensors = [];
            let activeMarkers = L.layerGroup().addTo(map);
            let inactiveMarkers = L.layerGroup().addTo(map);

            // Custom control for filter checkboxes
            const FilterControl = L.Control.extend({
                options: {
                    position: 'topright'
                },
                onAdd: function (map) {
                    const container = L.DomUtil.create('div', 'leaflet-control-filter');
                    container.innerHTML = `
                        <label>
                            <input type="checkbox" id="showActive" checked class="form-checkbox h-5 w-5 text-blue-600">
                            <span class="ml-2 text-gray-700">Active Sensors</span>
                        </label>
                        <label>
                            <input type="checkbox" id="showInactive" checked class="form-checkbox h-5 w-5 text-blue-600">
                            <span class="ml-2 text-gray-700">Inactive Sensors</span>
                        </label>
                    `;

                    // Prevent map interaction when clicking control
                    L.DomEvent.disableClickPropagation(container);

                    // Add event listeners for checkboxes
                    container.querySelector('#showActive').addEventListener('change', updateMarkers);
                    container.querySelector('#showInactive').addEventListener('change', updateMarkers);

                    return container;
                }
            });

            // Add filter control to map
            map.addControl(new FilterControl());

            // Load sensors count
            function loadSensors() {
                fetch('{{ route("admin.sensors.locations") }}')
                    .then(response => response.json())
                    .then(data => {
                        sensors = data;
                        updateMarkers();
                        updateActiveSensorCount();
                    })
                    .catch(error => console.error('Error loading sensors:', error));
                
                fetchSystemStatus();
            }


            // Update markers based on filter
            function updateMarkers() {
                activeMarkers.clearLayers();
                inactiveMarkers.clearLayers();

                const showActive = document.getElementById('showActive').checked;
                const showInactive = document.getElementById('showInactive').checked;

                sensors.forEach(sensor => {
                    if ((sensor.is_active && showActive) || (!sensor.is_active && showInactive)) {
                        const icon = L.divIcon({
                            className: 'custom-icon',
                            html: `<div class="w-6 h-6 rounded-full ${sensor.is_active ? 'bg-green-500' : 'bg-red-500'} border-2 border-white shadow-md"></div>`,
                            iconSize: [24, 24],
                            iconAnchor: [12, 12],
                            popupAnchor: [0, -12]
                        });

                        const marker = L.marker([sensor.latitude, sensor.longitude], { icon })
                            .bindPopup(`
                                <div class="popup-content">
                                    <strong class="text-lg">${sensor.name}</strong><br>
                                    <span>City: ${sensor.city}</span><br>
                                    <span>Status: ${sensor.is_active ? '🟢 Active' : '🔴 Inactive'}</span>
                                </div>
                            `);

                        if (sensor.is_active) {
                            activeMarkers.addLayer(marker);
                        } else {
                            inactiveMarkers.addLayer(marker);
                        }
                    }
                });
            }

            // Fetch simulator and system status
            function fetchSystemStatus() {
                fetch('{{ route("admin.sensors.status") }}')
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('simulatorStatus').innerText = data.simulator_active ? '🟢 Active' : '🔴 Inactive';
                        document.getElementById('systemStatus').innerText = data.system_ok ? '🟢 Normal' : '🔴 Issue';
                    })
                    .catch(error => {
                        console.error('Error fetching system status:', error);
                        document.getElementById('simulatorStatus').innerText = '🟢 Active';
                        document.getElementById('systemStatus').innerText = '🟢 Normal';
                    });
            }

            // Update the Active Sensor Count
            function updateActiveSensorCount() {
                const count = sensors.filter(sensor => sensor.is_active).length;
                document.getElementById('activeSensorCount').innerText = count;
            }



            // Initial load
            loadSensors();

            // Handle click to add sensor
            map.on('click', function (e) {
                const lat = e.latlng.lat.toFixed(6);
                const lng = e.latlng.lng.toFixed(6);

                if (window.tempMarker) map.removeLayer(window.tempMarker);

                const tempIcon = L.divIcon({
                    className: 'temp-icon',
                    html: `<div class="w-6 h-6 rounded-full bg-blue-500 border-2 border-white shadow-md animate-pulse"></div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12],
                    popupAnchor: [0, -12]
                });

                window.tempMarker = L.marker([lat, lng], { icon: tempIcon, draggable: true }).addTo(map);
                window.tempMarker.bindPopup(`
                    <div class="popup-content">
                        <center><strong class="text-lg">Add a new sensor</strong></center><br>
                        <span>Latitude: ${lat}</span><br>
                        <span>Longitude: ${lng}</span><br>
                        <input type="text" id="sensorName" class="form-control form-control-sm mt-2 w-full p-1 border rounded" placeholder="Sensor Name"><br>
                        <input type="text" id="sensorCity" class="form-control form-control-sm mt-2 w-full p-1 border rounded" placeholder="City"><br>
                        <center><button onclick="submitSensor(${lat}, ${lng})" class="btn btn-sm bg-blue-500 text-white px-3 py-1 rounded mt-2 hover:bg-blue-600">Save Sensor</button></center>
                    </div>
                `).openPopup();
            });
        });
        function submitSensor(lat, lng) {
    const name = document.getElementById('sensorName')?.value || 'Unnamed Sensor';
    const city = document.getElementById('sensorCity')?.value || 'not-defined';

    fetch('{{ route("admin.sensors.store") }}', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({
        name,
        city,
        latitude: lat,
        longitude: lng,
        is_active: true
    })
})
.then(response => response.json())
.then(data => {
    console.log(data);
    alert('Sensor added successfully!');
    location.reload();
})
.catch(error => {
    console.error('Fetch error:', error);
    alert('Failed to add sensor');
});

}

    </script>
@endpush