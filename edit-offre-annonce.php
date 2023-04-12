
<?php
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "OPTIONS") {
    die();
}
 

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
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

$idTypeOffre = $_POST['idTypeOffre'];
$libelleOffre = $_POST['libelleOffre'];
$descriptionOffre = $_POST['descriptionOffre'];
$prixOffre = $_POST['prixOffre'];
$dureeOffre = $_POST['dureeOffre'];
$nbProduit = $_POST['nbProduit'];

$libelleOffre = str_replace("\'", "\'\'", $libelleOffre);
$descriptionOffre = str_replace("\'", "\'\'", $descriptionOffre);

try {
    
    $sql = 'UPDATE typeOffre 
    SET libelleOffre = "'.$libelleOffre.'"
    , descriptionOffre = "'.$descriptionOffre.'"
    , prixOffre = "'.$prixOffre.'"
    , dureeOffre = "'.$dureeOffre.'"
    , nbProduit = "'.$nbProduit.'"
    WHERE idTypeOffre = "'.$idTypeOffre.'"
    ';
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    echo json_encode([
        'success' => 200,
        'message' => "Ajout effectué avec succès."
    ]);
    exit;

} catch (Exception $e) {
    http_response_code(404);
    echo json_encode([
        'success' => 500,
        'message' => $e->getMessage()
    ]);
    exit;
}

?>