<?php
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "OPTIONS") {
    die();
}
 

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
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

$data = json_decode(file_get_contents("php://input"));
if(!isset($data->nomUser) || !isset($data->prenomUser) || !isset($data->emailUser) || !isset($data->passwordUser) || !isset($data->telUser) || !isset($data->tokenInsc) || !isset($data->idDroitUser)) {
    echo json_encode([
        'success' => 0,
        'message' => 'Please enter compulsory fields.'
    ]);
}
try {
    $nomUser = htmlspecialchars(trim($data->nomUser));
    $prenomUser = htmlspecialchars(trim($data->prenomUser));
    $emailUser = htmlspecialchars(trim($data->emailUser));
    $passwordUser = htmlspecialchars(trim($data->passwordUser));
    $telUser = htmlspecialchars(trim($data->telUser));
    $tokenInsc = htmlspecialchars(trim($data->tokenInsc));
    $idDroitUser = htmlspecialchars(trim($data->idDroitUser));
    $idCoefPointsCredit = 1;
 
    $query = "INSERT INTO `user`(
    nomUser,
    prenomUser,
    emailUser,
    passwordUser,
    telUser,
    tokenInsc,
    idDroitUser,
    idCoefPointsCredit
    ) 
    VALUES(
    :nomUser,
    :prenomUser,
    :emailUser,
    :passwordUser,
    :telUser,
    :tokenInsc,
    :idDroitUser,
    :idCoefPointsCredit
    )";
 
    $stmt = $conn->prepare($query);
 
    $stmt->bindValue(':nomUser', $nomUser, PDO::PARAM_STR);
    $stmt->bindValue(':prenomUser', $prenomUser, PDO::PARAM_STR);
    $stmt->bindValue(':emailUser', $emailUser, PDO::PARAM_STR);
    $stmt->bindValue(':passwordUser', $passwordUser, PDO::PARAM_STR);
    $stmt->bindValue(':telUser', $telUser, PDO::PARAM_STR);
    $stmt->bindValue(':tokenInsc', $tokenInsc, PDO::PARAM_STR);  
    $stmt->bindValue(':idDroitUser', $idDroitUser, PDO::PARAM_INT); 
    $stmt->bindValue(':idCoefPointsCredit', $idCoefPointsCredit, PDO::PARAM_INT);

/** if nomUser, prenomUser , emailUser, passwordUser, telUser is empty */
    if (empty($nomUser) || empty($prenomUser) || empty($emailUser) || empty($passwordUser) || empty($telUser)) {
        echo json_encode([
            'success' => 0,
            'message' => 'Enter all values.'
        ]);
        exit;
    }

    if ($stmt->execute()) {
 
        http_response_code(201);
        echo json_encode([
            'success' => 1,
            'message' => 'Data Inserted Successfully.'
        ]);
        exit;
    }

    echo json_encode([
        'success' => 0,
        'message' => 'There is some problem in data inserting'
    ]);
    exit;
    
} catch (Exception $e) {
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
}

?>