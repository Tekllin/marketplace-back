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

$idProduit = $_POST['idProduit'];
$libelleAvis = $_POST['libelleAvis'];
$noteAvis = $_POST['noteAvis'];
$idClient = $_POST['idClient'];

$libelleAvis = str_replace("\'", "\'\'", $libelleAvis);

try {
    $sql = 'INSERT INTO avis(idProduitAvis, libelleAvis, valid, noteAvis, idUserAvis) VALUES ("'.$idProduit.'","'.$libelleAvis.'","0","'.$noteAvis.'","'.$idClient.'")';
    $req = $conn->prepare($sql);
    $req->execute();
    
    echo json_encode([
        'success' => 200,
        'message' => "Commentaire envoyé, il sera traité par les administrateurs."
    ]);
    exit;
} catch (Exception $e) {
    echo json_encode([
        'success' => 500,
        'message' => 'Erreur lors de l\'envoi du commentaire.'
    ]);
    exit;
}
?>