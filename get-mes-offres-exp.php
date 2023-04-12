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
    $id = $_GET['id'];
    $sql = "SELECT refFacture, libelleOffre, dureeOffre, prixOffre, nbProduit, dateAnnoFacture, dateDebutAnnonce
    FROM annonceFacture
    INNER JOIN concerner ON idAnnonceFacture = idAnnonceFactureConcerne
    INNER JOIN typeOffre ON idTypeOffreConcerne = idTypeOffre
    WHERE (dateDebutAnnonce IS NOT NULL OR dateDebutAnnonce = '1990-1-1')
    AND idAnnonceurAnnoFacture = ".$id;
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0) {

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

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