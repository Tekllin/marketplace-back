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

if(isset($_GET['idUserWishList']) && isset($_GET['idProduitWishList'])) {
    $idUserWishList = $_GET['idUserWishList'];
    $idProduitWishList = $_GET['idProduitWishList'];
} else {
    echo json_encode([
        'success' => 0,
        'message' => 'Please enter compulsory fields.'
    ]);
    exit;
}

try {
    $sql = "DELETE FROM wishlist WHERE idUserWishList = $idUserWishList AND idProduitWishList = $idProduitWishList";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    echo json_encode([
        'success' => 1,
        'data' => "WishList deleted",
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
}

?>