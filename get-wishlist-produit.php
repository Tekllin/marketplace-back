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

if(isset($_GET['idUserWishList'])) {
    $idUserWishList = filter_var($_GET['idUserWishList'], FILTER_VALIDATE_INT, [
        'options' => [
            'default' => 'all_wishlist',
            'min_range' => 1
        ]
    ]);
}

try {
    $sql = is_numeric($idUserWishList) ? "SELECT w.idUserWishList, w.idProduitWishList, p.idProduit, p.libelleProduit, p.descriptionProduit, p.prixUnitHT 
            FROM wishlist w 
            INNER JOIN produits p ON w.idProduitWishList = p.idProduit 
            WHERE w.idUserWishList = $idUserWishList" : "SELECT w.idUserWishList, w.idProduitWishList, p.idProduit, p.libelleProduit, p.descriptionProduit, p.prixUnitHT 
            FROM wishlist w 
            INNER JOIN produits p ON w.idProduitWishList = p.idProduit";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0) {
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode([
            'success' => 1,
            'data' => $data
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