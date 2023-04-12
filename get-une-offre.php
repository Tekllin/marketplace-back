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
    
    $ref = $_GET['refFacture'];
    $sql = "SELECT idAnnonceFacture, refFacture, libelleOffre, dureeOffre, prixOffre, nbProduit, dateAnnoFacture, dureeOffre, dateDebutAnnonce
    FROM annonceFacture
    INNER JOIN concerner ON idAnnonceFacture = idAnnonceFactureConcerne
    INNER JOIN typeOffre ON idTypeOffreConcerne = idTypeOffre
    WHERE refFacture = '$ref'"; 
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0) {

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($data['dateDebutAnnonce'] === null) {
            echo json_encode([
                'success' => 500,
                'message' => 'Cette offre n\'a pas encore été validée.'
            ]);
        } else {
            echo json_encode([
                'success' => 200,
                'data' => $data
            ]);
        }
    } else {
        echo json_encode([
            'success' => 404,
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