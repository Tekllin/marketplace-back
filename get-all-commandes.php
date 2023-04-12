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

try {
    $data = null;
    
    $sql = "SELECT numFacture, message, dateFacture, nomUser, prenomUser, libelleEtatCommande, libelleAdresse, cpAdresse, villeAdresse, paysAdresse  FROM commandeFacture
    INNER JOIN user ON idUser = idUserFacture
    INNER JOIN livraisons ON idAdresse = idLivraisonFacture
    
    INNER JOIN etatcommande ON idEtatCommandeFacture = idEtatCommande

 


    ORDER BY dateFacture DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => 200,
        'data' => $data
    ]);

    
} catch (Exception $e) {
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
}

?>