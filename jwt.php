<?php

require_once 'vendor/autoload.php';
use \Firebase\JWT\JWT;

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

// Encodage d'un token JWT
$secretKey = "mysecretkey"; // Remplacez par votre propre clé secrète
$issuedAt = time();
//$expirationTime = $issuedAt + (60); // 1 minute
//$expirationTime = $issuedAt + (60 * 30); // 30 minutes
//$expirationTime = $issuedAt + (60 * 60); // 1 heure
$expirationTime = $issuedAt + (60 * 60 * 2); // 2 heures
//$expirationTime = $issuedAt + (60 * 60 * 24); // 1 jour
//$expirationTime = $issuedAt + (60 * 60 * 24 * 7); // 1 semaine
//$expirationTime = $issuedAt + (60 * 60 * 24 * 7 * 4); // 1 mois

$payload = array(
    "iat" => $issuedAt,
    "exp" => $expirationTime,
    "data" => array(
        //"email" => hash('SHA256',$_GET['email']),
        "email" => $_GET['email'],
    )
);



// Token JWT en clair
$jwt = JWT::encode($payload, $secretKey, 'HS256');

// Crypter le token
$jwt = base64_encode($jwt);


// Envoie du token crypté en bdd
require_once 'db_connect.php';
$database = new Operations();
$conn = $database->dbConnection();

$sql = "UPDATE user SET tokenConn = '" . $jwt . "' WHERE emailUser = '" . $_GET['email'] . "'";
$stmt = $conn->prepare($sql);
$stmt->execute();

// Envoie du token
echo json_encode([
    'success' => 0,
    'message' => $jwt
]);


?>