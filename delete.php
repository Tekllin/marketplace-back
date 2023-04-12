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

if(isset($_GET['id'])) {
    $studentid = filter_var($_GET['id'], FILTER_VALIDATE_INT, [
        'options' => [
            'default' => 'all_students',
            'min_range' => 1
        ]
    ]);
}

try {
    $id = $_GET['idProduit'];

    $sql = "SELECT libelleImage FROM image WHERE idProduitImage = $id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll();

    foreach($data as $row) {
        $image = "photos/produits/".$row['libelleImage'];
        unlink($image);
    }
    //Delete image

    $sql ="DELETE FROM image WHERE idProduitImage = $id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();


    //Delete produit
    $sql = "DELETE FROM produits WHERE idProduit = $id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    echo json_encode([
        'success' => 1,
        'data' => "Produit deleted",
    ]);

    
} catch (Exception $e) {
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
}

?>