<?php
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header('Access-Control-Allow-Credentials', true);
header('Access-Control-Allow-Methods: DELETE');
header("Content-Type: application/json; charset=UTF-8");


require_once 'db_connect.php';
$database = new Operations();
$conn = $database->dbConnection();

try {
    $idProduit = $_GET['idProduit'];
    $idUser = $_GET['idUser'];

    $sql = "DELETE FROM avis WHERE idProduitAvis = '".$idProduit."' AND idUserAvis = '".$idUser."'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    echo json_encode([
        'success' => 200,
        'message' => "Avis supprimé",
        'sql' => $sql
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => 500,
        'message' => "Erreur lors de la suppression de l'avis"
    ]);
}


    


?>