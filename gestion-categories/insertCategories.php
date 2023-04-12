<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$method = $_SERVER['REQUEST_METHOD'];

if ($method == "OPTIONS") {
    die();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') :
    http_response_code(405);
    echo json_encode([
        'success' => 0,
        'message' => 'Bad Request!.Only POST method is allowed',
    ]);
    exit;
endif;
 
require '../db_connect.php';
$database = new Operations();
$conn = $database->dbConnection();
 
$data = json_decode(file_get_contents("php://input"));

if(!isset($data->libelleCategorie) || !isset($data->idGamme)) {
    echo json_encode([
        'success' => 0,
        'message' => 'Enter all values.'
    ]);
}
 
try {

    
 
    $libelleCategorie = $data->libelleCategorie;
    $idGammeCategorie = $data->idGamme;
 
    $query = "INSERT INTO `categories`(
    libelleCategorie,
    idGammeCategorie
    ) 
    VALUES(
    :libelleCategorie,
    :idGammeCategorie
    )";

    $stmt = $conn->prepare($query);
   

    $stmt->bindValue(':libelleCategorie', $libelleCategorie, PDO::PARAM_STR);
    $stmt->bindValue(':idGammeCategorie', $idGammeCategorie, PDO::PARAM_INT);
    
    $fetch_count = "SELECT idGamme FROM gammes WHERE idGamme = :idGammeCategorie";
    $fetch_r = $conn->prepare($fetch_count);
    $fetch_r->bindValue(':idGammeCategorie', $idGammeCategorie, PDO::PARAM_INT);
    $fetch_r->execute();
    $verif = $fetch_r->fetch();

    if(!$verif){

        echo json_encode([
            
            'success' => 0,
            'data' => $verif
        ]);
        exit;
    }
    else{

        if ($stmt->execute()) {

            echo json_encode([
                'success' => 1,
                'data' => 'Data Inserted Successfully.'
            ]);
            exit;
            
        }
        echo json_encode([
            'success' => 0,
            'data' => 'There is some problem in data inserting'
        ]);
        exit;
    }

    
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => 0,
        'data' => $e->getMessage()
    ]);
    exit;
}
