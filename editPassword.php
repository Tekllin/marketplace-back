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

$sql = "SELECT passwordUser FROM user WHERE idUser = '" . $_GET['id'] . "'";
$stmt = $conn->prepare($sql);
$stmt->execute();

$oldPassword = $stmt->fetch(PDO::FETCH_ASSOC)['passwordUser'];

if ($oldPassword == $_GET['newPassword']){
    echo json_encode([
        'success' => 0,
        'sameMdp' => 1,
        'message' => 'Old password and new password are the same'
    ]);
    exit;
} else {
    $sql = "UPDATE user SET passwordUser = '" . $_GET['newPassword'] . "' WHERE idUser = '" . $_GET['id'] . "'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    echo json_encode([
        'success' => 1,
        'sameMdp' => 0,
        'message' => 'Password changed',
        'oldPassword' => $oldPassword,
        'newPassword' => $_GET['newPassword']
    ]);
}

?>