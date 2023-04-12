<?php
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header('Access-Control-Allow-Credentials', true);
header('Access-Control-Allow-Methods: GET');
header("Content-Type: application/json; charset=UTF-8");

if($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'success' => 0,
        'message' => 'Bad Request Detected.'
    ]);
    exit;
}

require_once 'db_connect.php';
$database = new Operations();
$conn = $database->dbConnection();

if(isset($_GET['id'])) {
    $studentid = filter_var($_GET['id'], FILTER_VALIDATE_INT, [
        'options' => [
            'default' => 'all_students',
            'min_range' => 1
        ]
    ]);
}

try {
    $sql = is_numeric($studentid) ? "SELECT * FROM student WHERE id = $studentid" : "SELECT * FROM student";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0) {
        $data = null;

        if(is_numeric($studentid)) {
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        echo json_encode([
            'success' => 1,
            'data' => $data
        ]);
    } else {
        echo json_encode([
            'success' => 0,
            'data' => 'No data.'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
}

?>