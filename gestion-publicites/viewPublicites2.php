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

if (isset($_GET['id'])) {
    $idPub = filter_var($_GET['id'], FILTER_VALIDATE_INT, [
        'options' => [
            'default' => 'all_pubs',
            'min_range' => 1
        ]
    ]);
}


try {
   
    //$sql = "SELECT idProduit, idAnnonceurProduit, libelleProduit, qtStock, descriptionProduit, prixUnitHT, idCategorieProduit, libelleNomAnnonceur FROM produits INNER JOIN annonceurs WHERE idProduit IN (SELECT idAnnonceurProduit FROM Annonce);"
    $sql = is_numeric($idPub) ? "SELECT * FROM publicites2 WHERE idPub = '$idPub'" : "SELECT * FROM publicites2";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
   
    if($stmt->rowCount() > 0) {

        if (is_numeric($idPub)) {
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
       
       
       
       
        $sql = "SELECT idImagePub, libelleImagePub, idPubliciteImage FROM imagepub2";
        $stmt = $conn->prepare($sql);
       
        $stmt->execute();
            $data['images'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        

        echo json_encode([
            'success' => 1,
            'data' => $data,
        ]);
    } else {
        echo json_encode([
            'success' => 0,
            'data' => 'No data.'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
}

?>