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
if(!isset($data->nom) || !isset($data->prenom) || !isset($data->email) || !isset($data->num) || !isset($data->genre) || !isset($data->pays)) {
    echo json_encode([
        'success' => 0,
        'message' => 'Enter all values.'
    ]);
}
try {
    $nom = htmlspecialchars(trim($data->nom));
    $prenom = htmlspecialchars(trim($data->prenom));
    $email = htmlspecialchars(trim($data->email));
    $num = htmlspecialchars(trim($data->num));
    $genre = $data->genre;
    $pays = $data->pays;
 
    $query = "INSERT INTO `student`(
    nom,
    prenom,
    email,
    num,
    genre,
    pays
    ) 
    VALUES(
    :nom,
    :prenom,
    :email,
    :num,
    :genre,
    :pays
    )";
 
    $stmt = $conn->prepare($query);
 
    $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
    $stmt->bindValue(':prenom', $prenom, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':num', $num, PDO::PARAM_STR);
    $stmt->bindValue(':genre', $genre, PDO::PARAM_STR);
    $stmt->bindValue(':pays', $pays, PDO::PARAM_STR);   

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