<!DOCTYPE html>
<html>
<head>
    <title>AQI Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #f0f4f8, #d9e2ec);
            font-family: 'Poppins', sans-serif;
            color: #333;
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            font-weight: 600;
            font-size: 28px;
            margin: 20px 0;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.05);
        }
        .container {
            display: flex;
            flex-direction: row;
            gap: 20px;
            padding: 0 20px;
            max-width: 1400px;
            margin: 0 auto;
        }
        #map {
            flex: 1;
            height: 600px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        #map:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }
        .label-tooltip {
            background-color: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 6px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            color: #2c3e50;
            font-weight: 500;
            font-size: 12px;
            padding: 4px 8px;
            transition: opacity 0.3s ease;
        }
        .popup-content {
            max-width: 320px;
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            font-family: 'Poppins', sans-serif;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, opacity 0.3s ease;
        }
        .popup-content p {
            margin: 8px 0;
            color: #555;
            font-size: 14px;
            line-height: 1.5;
        }
        .popup-content b {
            color: #222;
            font-weight: 600;
        }
        .popup-content .aqi-value {
            font-size: 48px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.5);
        }
        .popup-content .aqi-category {
            font-size: 16px;
            color: #7f8c8d;
            margin-bottom: 10px;
            font-weight: 400;
        }
        .popup-content .sensor-info {
            font-size: 13px;
            color: #95a5a6;
            margin-top: 12px;
            border-top: 1px solid #ecf0f1;
            padding-top: 10px;
            line-height: 1.6;
        }
        .popup-content .weekly-trend {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid rgb(33, 179, 215);
        }
        .popup-content a {
            color: #0078d7;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .popup-content a:hover {
            color: #005bb5;
            text-decoration: underline;
        }
        /* Vertical Legend Styles */
        #legend {
            width: 200px;
            padding: 15px;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }
        #legend:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        #legend b {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 10px;
            display: block;
            font-size: 16px;
        }
        #legend div {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            transition: background-color 0.3s ease;
            padding: 5px;
            border-radius: 4px;
        }
        #legend div:hover {
            background-color: #f5f7fa;
        }
        #legend span {
            width: 16px;
            height: 16px;
            display: inline-block;
            margin-right: 10px;
            border-radius: 4px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        .weekly-trend {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
            padding-top: 8px;
            border-top: 2px solid rgb(12, 12, 12);
        }
        .day-entry {
            width: 45%; /* Two items per row */
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 6px;
            background-color: #f9fafb;
            border-radius: 6px;
            font-size: 12px;
        }
        .day-name {
            font-weight: 500;
            color: rgb(3, 3, 3);
        }
        .day-value {
            font-weight: 600;
        }
    </style>
</head>
<body>
    <h2>Real-Time AQI Map - Colombo</h2>
    <div class="container">
        <div id="legend">
            <b>AQI Legend</b>
            <div><span style="background-color: #00e400;"></span> Good (0–50)</div>
            <div><span style="background-color: #ffff00;"></span> Moderate (51–100)</div>
            <div><span style="background-color: #ff7e00;"></span> Unhealthy for Sensitive Groups (101–150)</div>
            <div><span style="background-color: #ff0000;"></span> Unhealthy (151–200)</div>
            <div><span style="background-color: #8f3f97;"></span> Very Unhealthy (201–300)</div>
            <div><span style="background-color: #7e0023;"></span> Hazardous (301+)</div>
        </div>
        <div id="map"></div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.2/dist/axios.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize the map centered on Colombo
            const map = L.map('map').setView([6.9271, 79.8612], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            setTimeout(() => {
                map.invalidateSize();
            }, 100);

            let sensorMarkers = [];

            // AQI color scale based on US EPA standard
            function getAQIColor(aqi) {
                if (aqi <= 50) return '#00e400';
                if (aqi <= 100) return '#ffff00';
                if (aqi <= 150) return '#ff7e00';
                if (aqi <= 200) return '#ff0000';
                if (aqi <= 300) return '#8f3f97';
                return '#7e0023';
            }

            // AQI category description
            function getAQICategory(aqi) {
                if (aqi <= 50) return 'Good';
                if (aqi <= 100) return 'Moderate';
                if (aqi <= 150) return 'Unhealthy for Sensitive Groups';
                if (aqi <= 200) return 'Unhealthy';
                if (aqi <= 300) return 'Very Unhealthy';
                return 'Hazardous';
            }

            // Function to get day name from a date string
            function getDayName(dateStr) {
                const date = new Date(dateStr);
                return date.toLocaleString('en-US', { weekday: 'long' });
            }

            // Fetch sensor data from API
            async function fetchSensorData() {
                try {
                    const response = await axios.get('/api/sensors/aqi');
                    console.log('Fetched sensor data:', response.data); // Debug log
                    return response.data;
                } catch (error) {
                    console.error('Failed to fetch sensor data:', error);
                    return [];
                }
            }

            // Load sensors and initialize markers
            fetchSensorData().then(sensorData => {
                if (sensorData.length === 0) {
                    console.error('No sensor data available');
                    alert('No sensor data available. Please try again later.');
                    return;
                }

                sensorData.forEach((area, index) => {
                    // Ensure latitude and longitude are valid numbers
                    const lat = parseFloat(area.latitude);
                    const lng = parseFloat(area.longitude);

                    if (isNaN(lat) || isNaN(lng)) {
                        console.error(`Invalid coordinates for sensor ${area.name}: latitude=${area.latitude}, longitude=${area.longitude}`);
                        return; // Skip this sensor
                    }

                    // Construct realTimeData object to match expected format
                    const realTimeData = {
                        aqi: area.realtime_aqi || 0,
                        date: area.realtime_date || new Date().toISOString(),
                        source: 'Database',
                        name: area.sensor_name || 'N/A',
                        lat: lat,
                        lng: lng,
                    };

                    const marker = L.circleMarker([lat, lng], {
                        radius: 8,
                        color: getAQIColor(realTimeData.aqi),
                        fillColor: getAQIColor(realTimeData.aqi),
                        fillOpacity: 2,
                        weight: 1.5,
                    }).addTo(map);

                    // Initial popup with placeholder
                    marker.bindPopup(`
                        <div class="popup-content">
                            <b>${area.name}</b><br>
                            <p>${area.description}</p>
                            <p>Loading data...</p>
                        </div>
                    `);

                    marker.on('popupopen', () => {
                        loadAQIMarkers(area, marker);
                    });

                    sensorMarkers.push(marker);
                });
            });

            async function loadAQIMarkers(area, marker) {
                const realTimeData = {
                    aqi: area.realtime_aqi || 0,
                    date: area.realtime_date || new Date().toISOString(),
                    source: 'Database',
                    name: area.sensor_name || 'N/A',
                    lat: parseFloat(area.latitude),
                    lng: parseFloat(area.longitude),
                };
                const historicalData = area.historicalData || [];

                // Combine historical data with real-time data for the weekly trend
                const allData = [
                    ...historicalData.map(h => ({ date: h.date, aqi: h.aqi })),
                    { date: realTimeData.date, aqi: realTimeData.aqi }
                ].reverse(); // Reverse to show most recent first

                // Update popup content
                const dateStr = new Date(realTimeData.date).toLocaleString('en-US', {
                    year: 'numeric', month: 'long', day: 'numeric',
                    hour: 'numeric', minute: 'numeric', timeZone: 'Asia/Colombo'
                }) + ' GMT+0530';
                let popupContent = `
                    <div class="popup-content">
                        <p><b>${area.name}</b></p>
                        <p>${area.description}</p>
                        <p>${dateStr}</p>
                       
                        <span class="aqi-value">${realTimeData.aqi}</span>
                        <p class="aqi-category">${realTimeData.aqi} - ${getAQICategory(realTimeData.aqi)}</p>
                        <div class="weekly-trend">
                            ${allData.map(item => `
                                <div class="day-entry">
                                    <span class="day-name">${getDayName(item.date)}</span>
                                    <span class="day-value" style="color:${getAQIColor(item.aqi)}">${item.aqi}</span>
                                </div>
                            `).join('')}
                        </div>
                        <br>
                        Sensor: ${realTimeData.name}<br>
                        ${realTimeData.lat.toFixed(2)}° lat / ${realTimeData.lng.toFixed(2)}° long</p>
                        
                `;
                marker.setPopupContent(popupContent);
            }
        });
    </script>
</body>
</html>