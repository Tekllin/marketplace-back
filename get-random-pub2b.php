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
   
    //$sql = "SELECT idProduit, idAnnonceurProduit, libelleProduit, qtStock, descriptionProduit, prixUnitHT, idCategorieProduit, libelleNomAnnonceur FROM produits INNER JOIN annonceurs WHERE idProduit IN (SELECT idAnnonceurProduit FROM Annonce);"
    $sql = "SELECT * FROM publicites  WHERE bloc='bloc2'   ORDER BY RAND() limit 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

   
    if($stmt->rowCount() > 0) {

        
            $dataR = $stmt->fetch(PDO::FETCH_ASSOC);
  


        $id = $dataR['idPub'];
        
       
        $sql = "SELECT idImagePub, libelleImagePub, idPubliciteImage FROM imagepub WHERE idPubliciteImage = $id";
        $stmt = $conn->prepare($sql);
       
        $stmt->execute();
            $dataR['imagesRand'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        

        echo json_encode([
            'success' => 1,
            'dataR' => $dataR,
        ]);
    } else {
        echo json_encode([
            'success' => 0,
            'data' => 'no'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
}

?>