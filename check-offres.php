
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

try {
   
    $sql = "SELECT idAnnonceFacture, dateDebutAnnonce, dureeOffre FROM annoncefacture 
    INNER JOIN concerner ON idAnnonceFacture = idAnnonceFactureConcerne
    INNER JOIN typeoffre ON idTypeOffreConcerne = idTypeOffre
    WHERE (dateDebutAnnonce IS NOT NULL OR dateDebutAnnonce != '1990-1-1')";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0) {

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //Check if dateDebutAnnonce + (30 * dureeOffre) > today date
        $today = date("Y-m-d");

        foreach ($data as $key => $value) {
            $idAnnonceFacture = $value['idAnnonceFacture'];
            $dateDebutAnnonce = $value['dateDebutAnnonce'];
            $dureeOffre = $value['dureeOffre'];
            $dateFinAnnonce = date('Y-m-d', strtotime($dateDebutAnnonce. ' + '.(30 * $dureeOffre).' days'));
            if($dateFinAnnonce < $today) {
                try {
                    $sql = "UPDATE annoncefacture SET dateDebutAnnonce = '1990-1-1' WHERE idAnnonceFacture = '".$idAnnonceFacture."'";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();

                    $sql = "DELETE FROM annonce WHERE idAnnonceAnnoFacture = '".$idAnnonceFacture."'";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();

                } catch (Exception $e) {

                }
                
            }
        }

        echo json_encode([
            'success' => 200,
            'message' => 'Data updated.'
        ]);

        
        
    } else {
        echo json_encode([
            'success' => 500,
            'data' => 'No data.'
        ]);
    }

} catch (Exception $e) {
    http_response_code(404);
    echo json_encode([
        'success' => 500,
        'message' => $e->getMessage()
    ]);
    exit;
}

?>