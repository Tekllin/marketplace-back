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


    $id = $_GET['idProduit'];

    try {
        $sql = "SELECT libelleImage FROM image WHERE idProduitImage = $id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
    
        foreach($data as $row) {
            $image = "photos/produits/".$row['libelleImage'];
            unlink($image);
        }

    } catch (Exception $e) {
        echo json_encode([
            'success' => 500,
            'message' => "Erreur lors de la suppression d'image"
        ]);
    }

    try {
        $sql ="DELETE FROM avis WHERE idProduitAvis = $id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

    }catch (Exception $e) {
        echo json_encode([
            'success' => 500,
            'message' => "Erreur lors de la suppression des avis"
        ]);
    }

    try {
        $sql ="DELETE FROM image WHERE idProduitImage = $id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

    }catch (Exception $e) {
        echo json_encode([
            'success' => 500,
            'message' => "Erreur lors de la suppression d'image"
        ]);
    }

    try {
        $sql ="DELETE FROM views WHERE idProduitView = $id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

    }catch (Exception $e) {
        echo json_encode([
            'success' => 500,
            'message' => "Erreur lors de la suppression."
        ]);
    }

    try {
        $sql = "DELETE FROM produits WHERE idProduit = $id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }catch (Exception $e) {
        echo json_encode([
            'success' => 500,
            'message' => "Erreur lors de la suppression du produit."
        ]);
    }

   
    
    echo json_encode([
        'success' => 200,
        'message' => "Produit supprimé avec succès."
    ]);
    


?>