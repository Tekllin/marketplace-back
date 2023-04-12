
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
/*$token = $database->checkToken($_POST['token']);

if($token == false) {
    echo json_encode([
        'success' => 401,
        'message' => 'Unauthorized',
    ]);
    exit;
}
*/
$conn = $database->dbConnection();
$idProduit = $_POST['idProduit'];
$libelleProduit = $_POST['libelleProduit'];
$qtStock = $_POST['qtStock'];
$descriptionProduit = $_POST['descriptionProduit'];
$prixUnitHT = $_POST['prixUnitHT'];
$poidsProduit = $_POST['poidsProduit'];
$idCategorieProduit = $_POST['idCategorieProduit'];
$idAnnonceurProduit = $_POST['idAnnonceurProduit'];
$idTvaProduit = $_POST['idTvaProduit'];


$libelleProduit = str_replace("\'", "\'\'", $libelleProduit);
$descriptionProduit = str_replace("\'", "\'\'", $descriptionProduit);

$images = array();

try {
    
    $sql = "UPDATE produits SET libelleProduit = '$libelleProduit', qtStock = '$qtStock', descriptionProduit = '$descriptionProduit', prixUnitHT = '$prixUnitHT', poidsProduit = '$poidsProduit', idCategorieProduit = '$idCategorieProduit', idAnnonceurProduit = '$idAnnonceurProduit', idTvaProduit = '$idTvaProduit' WHERE idProduit = '$idProduit'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $sql = "DELETE FROM annonce WHERE idAnnonceProduit = '$idProduit'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    echo json_encode([
        'success' => 200,
        'message' => 'Produit modifié avec succès',
    ]);
    exit;

} catch (Exception $e) {
    echo json_encode([
        'success' => 500,
        'message' => $e->getMessage()
    ]);
    exit;
}

?>