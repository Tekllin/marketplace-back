<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header('Access-Control-Allow-Credentials', true);
header('Access-Control-Allow-Methods: GET');
header("Content-Type: application/json; charset=UTF-8");

require_once "vendor/autoload.php";
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

try {
    $secret = "mysecretkey";
    $token = $_GET['token'];

    $decoded = JWT::decode($token, new Key($secret, 'HS256'));
    $expirationTime = $decoded->exp;
    $currentTime = time();
    if($currentTime > $expirationTime) {
        echo json_encode([
            'error' => 0,
            'hasExpired' => 1,
            'message' => "Token expired"
        ]);
    } else {
        echo json_encode([
            'error' => 0,
            'hasExpired' => 0,
            'message' => "Token valid"
        ]);
    }
} catch (\Exception $e) {
    echo json_encode([
        'error' => 1,
        'hasExpired' => 0,
        'message' => "Invalid token: " . $e->getMessage(),
    ]);
    
}

?>