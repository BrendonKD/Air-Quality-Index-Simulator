<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

$host = '127.0.0.1';
$dbname = 'aqi_data';
$username = 'root';
$password = 'pass';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        echo json_encode(['error' => 'Invalid JSON data']);
        exit();
    }

    $area_name = $data['area_name'] ?? '';
    $latitude = $data['latitude'] ?? null;
    $longitude = $data['longitude'] ?? null;
    $aqi = $data['aqi'] ?? null;
    $pm25 = $data['pm25'] ?? null;
    $sensor_name = $data['sensor_name'] ?? '';
    $record_date = $data['record_date'] ?? null;
    $record_time = $data['record_time'] ?? null;
    $source = $data['source'] ?? '';

    try {
        $stmt = $pdo->prepare("
            INSERT INTO aqi_records (area_name, latitude, longitude, aqi, pm25, sensor_name, record_date, record_time, source)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$area_name, $latitude, $longitude, $aqi, $pm25, $sensor_name, $record_date, $record_time, $source]);
        echo json_encode(['status' => 'success', 'message' => 'Data stored successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to store data: ' . $e->getMessage()]);
    }
} elseif ($method === 'GET') {
    $area_name = $_GET['area_name'] ?? '';
    $start_date = $_GET['start_date'] ?? '';
    $end_date = $_GET['end_date'] ?? '';

    $query = "SELECT * FROM aqi_records WHERE area_name = ? AND record_date BETWEEN ? AND ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$area_name, $start_date, $end_date]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>