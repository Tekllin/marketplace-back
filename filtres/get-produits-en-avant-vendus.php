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

require_once '../db_connect.php';
$database = new Operations();
$conn = $database->dbConnection();

try {

    $sql = "SELECT Produits.idProduit, descriptionProduit, prixUnitHT as prixProduit, Produits.libelleProduit, 
    AVG(CASE WHEN Avis.valid = 1 THEN Avis.noteAvis ELSE NULL END) as averageNote, 
    MAX(Image.libelleImage) as image, SUM(qtProduitCommande) as NbVendu FROM Produits 
    LEFT JOIN Avis ON Produits.idProduit = Avis.idProduitAvis 
    LEFT JOIN Image ON Produits.idProduit = Image.idProduitImage 
    LEFT JOIN commander ON idProduitCommande = idProduit
    WHERE (adminProduit = 1 AND miseEnAvant = 1) OR (miseEnAvant = 1 
    AND idProduit IN (SELECT idAnnonceProduit FROM Annonce WHERE idEtatAnnonce = 2)) 
    GROUP BY Produits.idProduit, Produits.libelleProduit 
    ORDER BY NbVendu DESC;";

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