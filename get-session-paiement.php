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
$uid = $_GET['user'];
$session = $_GET['session'];
try {
   
    $sql = "SELECT * FROM session_paiement WHERE idUserSession =".$uid;
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {

        $sql = "UPDATE session_paiement SET
        hashSession = '".$session."',
        idUserSession = '".$uid."'
        WHERE idUserSession =".$uid;
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        echo json_encode([
            'success' => 200,
            'data' => 'Session updated.'
        ]);

    } else {
        $sql = "INSERT INTO session_paiement (hashSession, idUserSession) VALUES ('".$session."', '".$uid."')";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        echo json_encode([
            'success' => 200,
            'data' => 'Session created.'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => 500,
        'message' => $e->getMessage(),
        'uid' => $uid,
        'session' => $session
    ]);
}

?>