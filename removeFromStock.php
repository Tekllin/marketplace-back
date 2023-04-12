<?php

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
    $sql = "SELECT isCreditable FROM commandeFacture WHERE numFacture = '$_GET[hash]'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $isCreditable = $stmt->fetch(PDO::FETCH_ASSOC)['isCreditable'];

    if ($isCreditable == 0) {
        echo json_encode([
            'success' => 0,
            'message' => "Le stock a déjà été mis à jour."
        ]);
        
    } else {
        $sql = "SELECT idProduitComm, qtProduitComm
                FROM commander
                WHERE numFactComm = '$_GET[hash]'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $data = null;
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($data as $key => $value) {
                $sql = "UPDATE produits
                        SET qtStock = qtStock - $value[qtProduitComm]
                        WHERE idProduit = $value[idProduitComm]";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
            }

            echo json_encode([
                'success' => 1,
                'message' => "Le stock a été mis à jour."
            ]);

        } else {
            echo json_encode([
                'success' => 0,
                'message' => "La commande n'existe pas."
            ]);
        }
    }

    
} catch (Exception $e) {
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
}


?>