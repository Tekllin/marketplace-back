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

$libelleOffre = $_POST['libelleOffre'];
$descriptionOffre = $_POST['descriptionOffre'];
$dureeOffre = $_POST['dureeOffre'];
$prixOffre = $_POST['prixOffre'];
$nbProduit = $_POST['nbProduit'];

$libelleOffre = str_replace("\'", "\'\'", $libelleOffre);
$descriptionOffre = str_replace("\'", "\'\'", $descriptionOffre);

try {
    
    $req = $conn->prepare(
        'INSERT INTO typeOffre(libelleOffre, descriptionOffre, dureeOffre, prixOffre, nbProduit)
        VALUES("'.$libelleOffre.'","'.$descriptionOffre.'","'.$dureeOffre.'","'.$prixOffre.'","'.$nbProduit.'")'

        
    );
    $req->execute();
    echo json_encode([
        'success' => 200,
        'data' => "Offre ajouté."
    ]);

} catch (Exception $e) {
    http_response_code(404);
    echo json_encode([
        'success' => 500,
        'data' => $e->getMessage()
    ]);
    exit;
}
?>