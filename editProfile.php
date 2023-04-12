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


$sql = "UPDATE user SET nomUser = '" . $_GET['newLastname'] . "', prenomUser = '" . $_GET['newFirstname'] . "', telUser = '" . $_GET['newPhone'] . "' 
        WHERE idUser = '" . $_GET['id'] . "'";
$stmt = $conn->prepare($sql);
$stmt->execute();

if($stmt->rowCount() > 0) {
    echo json_encode([
        'success' => 1,
        'message' => 'Profile updated successfully.'
    ]);
} else {
    echo json_encode([
        'success' => 0,
        'message' => 'Profile not updated.'
    ]);
}

?>