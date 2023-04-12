<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header('Access-Control-Allow-Credentials', true);
header('Access-Control-Allow-Methods: GET');
header("Content-Type: application/json; charset=UTF-8");

require_once './vendor/autoload.php';
use \Firebase\JWT\JWT;

    $secretKey = "maclesecret"; // Remplacez par votre propre clé secrète
    $issuedAt = time();
    $expirationTime = $issuedAt + (60 * 60); // 1 heure
    $payload = array(
        "iat" => $issuedAt,
        "exp" => $expirationTime,
        "data" => array(
            "email" => $_GET['email'],
        )
    );

$jwt = JWT::encode($payload, $secretKey, 'HS256');

// Envoie du token
echo json_encode([
    'success' => 0,
    'message' => $jwt
]); 
?>