/**
 * AQI Simulation Client-side Script
 * This file handles the AJAX requests and real-time updates for the AQI simulation
 */

document.addEventListener('DOMContentLoaded', function() {
    const isSimulationRunning = document.querySelector('[name="is_running"]')?.value === '1';
    let simulationInterval = null;
    
    // Start auto-refresh if simulation is running on page load
    if (isSimulationRunning) {
        startAutoRefresh();
    }
    
    // Manual refresh button
    const refreshButton = document.getElementById('manual-refresh');
    if (refreshButton) {
        refreshButton.addEventListener('click', function() {
            fetchLatestReadings();
        });
    }
    
    // Fetch latest readings from the server
    function fetchLatestReadings() {
        fetch('/admin/readings/latest')
            .then(response => response.json())
            .then(data => {
                updateReadingsDisplay(data);
                updateAlertsBadge();
            })
            .catch(error => console.error('Error fetching readings:', error));
    }
    
    // Update the UI with new readings
    function updateReadingsDisplay(readings) {
        readings.forEach(reading => {
            const readingElement = document.querySelector(`[data-sensor-id="${reading.sensor_location_id}"]`);
            if (readingElement) {
                const valueElement = readingElement.querySelector('.aqi-value');
                const statusElement = readingElement.querySelector('.aqi-status');
                const updatedElement = readingElement.querySelector('.updated-time');
                
                if (valueElement) valueElement.textContent = parseFloat(reading.value).toFixed(1);
                if (statusElement) statusElement.textContent = reading.status || 'Unknown';
                if (updatedElement) updatedElement.textContent = 'Just now';
                
                // Update gauge color
                const gauge = readingElement.closest('.aqi-gauge');
                if (gauge) {
                    gauge.style.borderColor = getAqiColor(reading.value);
                }
            }
        });
    }
    
    // Start auto-refresh timer
    function startAutoRefresh() {
        const frequencySeconds = parseInt(document.querySelector('[name="frequency_seconds"]')?.value || '300');
        
        simulationInterval = setInterval(() => {
            fetchLatestReadings();
        }, frequencySeconds * 1000);
    }
    
    // Stop auto-refresh timer
    function stopAutoRefresh() {
        if (simulationInterval) {
            clearInterval(simulationInterval);
            simulationInterval = null;
        }
    }
    
    // Get color based on AQI value
    function getAqiColor(value) {
        value = parseFloat(value);
        
        if (value <= 50) return '#00E400'; // Good
        else if (value <= 100) return '#FFFF00'; // Moderate
        else if (value <= 150) return '#FF7E00'; // Unhealthy for Sensitive Groups
        else if (value <= 200) return '#FF0000'; // Unhealthy
        else if (value <= 300) return '#99004C'; // Very Unhealthy
        else return '#7E0023'; // Hazardous
    }
});