
<?php
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "OPTIONS") {
    die();
}
 

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

$idProduit = $_GET['idProduit'];
$refFacture = $_GET['refFacture'];
$value = $_GET['value'];


try {

    $sql = "SELECT count(idAnnonceProduit) as produitInsert, idAnnonceFacture, nbProduit 
    FROM annonceFacture  
    INNER JOIN concerner ON idAnnonceFactureConcerne = idAnnonceFacture 
    INNER JOIN typeOffre ON idTypeOffreConcerne = idTypeOffre 
    LEFT JOIN annonce ON idAnnonceFacture = idAnnonceAnnoFacture
    WHERE refFacture = '$refFacture'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $idAnnonce = $data['idAnnonceFacture'];
    $nbProduit = $data['nbProduit'];
    $produitInsert = $data['produitInsert'];

    if($value == 0) {
        $sql = "DELETE FROM annonce
        WHERE idAnnonceAnnoFacture = '$idAnnonce'
        AND idAnnonceProduit = '$idProduit'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        echo json_encode([
            'success' => 0,
            'data' => "Produit retiré."
        ]);
        exit;
    } else {
        if($produitInsert >= $nbProduit) {
            echo json_encode([
                'success' => 0,
                'data' => "Vous avez atteint le nombre maximum de produit pour cette facture."
            ]);
            exit;
            
        } else {

            $sql = "SELECT idAnnonceAnnoFacture FROM annonce WHERE idAnnonceProduit = '$idProduit'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            if($stmt->rowCount() > 0) {
                echo json_encode([
                    'success' => 500,
                    'data' => "Ce produit est déjà dans une facture."
                ]);
                exit;
            } else {
                $sql = "INSERT INTO Annonce (idAnnonceAnnoFacture, idAnnonceProduit, idEtatAnnonce) VALUES ('$idAnnonce', '$idProduit','1')";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
        
                http_response_code(200);
                echo json_encode([
                    'success' => 200,
                    'data' => "Ajout effectué avec succès. Il sera vérifié par un administrateur dans les 24h."
                ]);
                exit;
            }
        }
        
    }

} catch (Exception $e) {
    http_response_code(404);
    echo json_encode([
        'success' => 500,
        'data' => $e->getMessage()
    ]);
    exit;
}

?>