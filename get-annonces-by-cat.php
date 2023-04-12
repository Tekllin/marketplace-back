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
    if(isset($_GET['idCat'])) {
        $idCat = $_GET['idCat'];
        $sql = "SELECT idProduit, idAnnonceurProduit, libelleProduit, qtStock, descriptionProduit, prixUnitHT, idCategorieProduit, libelleNomAnnonceur, 
        AVG(CASE WHEN Avis.valid = 1 THEN Avis.noteAvis ELSE NULL END) as averageNote, MIN(Image.libelleImage) as image
        FROM produits
        LEFT JOIN annonceurs ON idAnnonceur = idAnnonceurProduit 
        LEFT JOIN Avis ON Produits.idProduit = Avis.idProduitAvis
        LEFT JOIN Image ON Produits.idProduit = Image.idProduitImage
        WHERE (idProduit IN (SELECT idAnnonceProduit FROM Annonce WHERE idEtatAnnonce = 2) OR adminProduit = 1 )
        AND idCategorieProduit = '$idCat'
        
        GROUP BY idProduit";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
       
        if($stmt->rowCount() > 0) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode([
                'success' => 200,
                'data' => $data,
            ]);
        } else {
            echo json_encode([
                'success' => 500,
                'data' => 'No data.'
            ]);
        }
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
}

?>