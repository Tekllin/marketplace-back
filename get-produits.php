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

try {
    if(isset($_GET['idAnno']) && !isset($_GET['idProduit'])) {
        $sql = "SELECT idProduit, idAnnonceurProduit, libelleProduit, qtStock, poidsProduit, descriptionProduit, prixUnitHT, idCategorieProduit, idTvaProduit, count(idProduitView) as NbVues, MAX(Image.libelleImage) as image 
        FROM produits 
        LEFT JOIN views ON idProduitView = idProduit
        LEFT JOIN Image ON Produits.idProduit = Image.idProduitImage 
        WHERE idAnnonceurProduit = ". $_GET['idAnno'] ."
        GROUP BY idProduit";
    
    } else if(isset($_GET['idProduit']) && isset($_GET['idAnno'])) {
        $sql = "SELECT idProduit, idAnnonceurProduit, libelleProduit, qtStock, poidsProduit, descriptionProduit, prixUnitHT, idCategorieProduit, idTvaProduit, count(idProduitView) as NbVues, MAX(Image.libelleImage) as image FROM produits LEFT JOIN views ON idProduitView = idProduit LEFT JOIN Image ON Produits.idProduit = Image.idProduitImage  WHERE idProduit = " . $_GET['idProduit'] . " AND idAnnonceurProduit = " . $_GET['idAnno']. " GROUP BY idProduit";
    
    } else if(!isset($_GET['idAnno']) && !isset($_GET['idProduit'])) {
        $sql = "SELECT idProduit, idAnnonceurProduit, libelleProduit, qtStock, poidsProduit, descriptionProduit, prixUnitHT, idCategorieProduit, idTvaProduit, count(idProduitView) as NbVues, MAX(Image.libelleImage) as image FROM produits LEFT JOIN views ON idProduitView = idProduit LEFT JOIN Image ON Produits.idProduit = Image.idProduitImage  GROUP BY idProduit";
    
    } else if(isset($_GET['idProduit']) && !isset($_GET['idAnno'])) {
        $sql = "SELECT idProduit, idAnnonceurProduit, libelleProduit, qtStock, poidsProduit, descriptionProduit, prixUnitHT, idCategorieProduit, idTvaProduit, count(idProduitView) as NbVues, MAX(Image.libelleImage) as image  FROM produits LEFT JOIN views ON idProduitView = idProduit LEFT JOIN Image ON Produits.idProduit = Image.idProduitImage  WHERE idProduit = " . $_GET['idProduit'] . " GROUP BY idProduit";
    
    }
        
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0) {

        if(!isset($_GET['idProduit'])) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        if($_GET['getimages'] == 0) {
            echo json_encode([
                'success' => 200,
                'data' => $data
            ]);
            exit;
        } else if ($_GET['getimages'] == 1 && isset($_GET['idProduit'])) {
            $sql = "SELECT idImage, libelleImage, idProduitImage FROM image WHERE idProduitImage = " . $_GET['idProduit'];
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $data['images'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } else if($_GET['getimages'] == 1) {
            $sql = "SELECT idImage, libelleImage, idProduitImage FROM image";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $data['images'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        echo json_encode([
            'success' => 200,
            'data' => $data
        ]);
    } else {
        echo json_encode([
            'success' => 500,
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