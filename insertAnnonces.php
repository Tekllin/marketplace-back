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
 
require 'db_connect.php';
$database = new Operations();
$conn = $database->dbConnection();
 
$data = json_decode(file_get_contents("php://input"));

if(!isset($data->idAnnonceProduit) || !isset($data->idAnnonceAnnoProduit)) {
    echo json_encode([
        'success' => 0,
        'message' => 'Enter all values.'
    ]);
}
 
try {

    
 
    $idAnnonceProduit = $data->idAnnonceProduit;
    $idAnnonceAnnoProduit = $data->idAnnonceAnnoProduit;
 
    $query = "INSERT INTO `annonce`(
    idAnnonceProduit,
    idAnnonceAnnoProduit
    ) 
    VALUES(
    :idAnnonceProduit,
    :idAnnonceAnnoProduit
    )";

    $stmt = $conn->prepare($query);
   

    $stmt->bindValue(':idAnnonceProduit', $idAnnonceProduit, PDO::PARAM_INT);
    $stmt->bindValue(':idAnnonceAnnoProduit', $idAnnonceAnnoProduit, PDO::PARAM_INT);
    
    $fetch_prd = "SELECT idProduit FROM produits WHERE idProduit = :idAnnonceProduit";
    $fetch_r = $conn->prepare($fetch_prd);
    $fetch_r->bindValue(':idAnnonceProduit', $idAnnonceProduit, PDO::PARAM_INT);
    $fetch_r->execute();
    $verifProduit = $fetch_r->fetch();

    $fetch_fct = "SELECT idAnnonceFacture FROM annoncefacture WHERE idAnnonceFacture = :idAnnonceAnnoProduit";
    $fetch_s = $conn->prepare($fetch_fct);
    $fetch_s->bindValue(':idAnnonceAnnoProduit', $idAnnonceAnnoProduit, PDO::PARAM_INT);
    $fetch_s->execute();
    $verifFacture = $fetch_s->fetch();

    if(!$verifProduit || !$verifFacture){

        echo json_encode([
            
            'success' => 0,
            'dataPrd' => $verifProduit,
            'dataFct' => $verifFacture
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
