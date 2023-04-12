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
    $idProduit = $_GET['idProduit'];
    $sql = "SELECT idProduit, idAnnonceurProduit, libelleProduit, qtStock, descriptionProduit, prixUnitHT, idCategorieProduit, libelleNomAnnonceur 
    FROM produits 
    INNER JOIN annonceurs ON idAnnonceur = idAnnonceurProduit
    LEFT JOIN annonce ON idProduit = idAnnonceProduit
    WHERE (idAnnonceProduit = $idProduit)
    OR adminProduit = 1 AND idProduit = $idProduit";  
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0) {

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $sql = "SELECT idImage, libelleImage, idProduitImage FROM image WHERE idProduitImage =". $_GET['idProduit'];
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data['images'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => 200,
            'data' => $data
        ]);
    } else {
        echo json_encode([
            'success' => 500,
            'data' => 'No data.'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => 500,
        'message' => $e->getMessage()
    ]);
}

?>