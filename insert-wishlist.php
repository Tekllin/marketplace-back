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

$data = json_decode(file_get_contents("php://input"));
if(!isset($data->idUserWishList) || !isset($data->idProduitWishList)) {
    echo json_encode([
        'success' => 0,
        'message' => 'Please enter compulsory fields.'
    ]);
}
try {
    $idUserWishList = htmlspecialchars(trim($data->idUserWishList));
    $idProduitWishList = htmlspecialchars(trim($data->idProduitWishList));
 
    $query = "INSERT INTO `wishlist`(
    idUserWishList,
    idProduitWishList
    ) 
    VALUES(
    :idUserWishList,
    :idProduitWishList
    )";
 
    $stmt = $conn->prepare($query);
 
    $stmt->bindValue(':idUserWishList', $idUserWishList, PDO::PARAM_INT);
    $stmt->bindValue(':idProduitWishList', $idProduitWishList, PDO::PARAM_INT);

/** if idUserWishList, idProduitWishlist is empty */
    if (empty($idUserWishList) || empty($idProduitWishList)) {
        echo json_encode([
            'success' => 0,
            'message' => 'Enter all values.'
        ]);
        exit;
    }

    if ($stmt->execute()) {
 
        http_response_code(201);
        echo json_encode([
            'success' => 1,
            'message' => 'Data Inserted Successfully.'
        ]);
        exit;
    }

    echo json_encode([
        'success' => 0,
        'message' => 'There is some problem in data inserting'
    ]);
    exit;
    
} catch (Exception $e) {
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
}

?>