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

$sql = "SELECT isCreditable
        FROM commandefacture
        WHERE numFacture = '" . $_GET['numFacture'] . "'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$isCreditable = $stmt->fetch(PDO::FETCH_ASSOC)['isCreditable'];

if($isCreditable == 0) {
    echo json_encode([
        'success' => 0,
        'message' => 'Cette commande n\'est pas créditable.'
    ]);

} else {
    try {
        $sql = "SELECT coefApplicable
                FROM coefpointscredit C
                INNER JOIN user U ON U.idCoefPointsCredit = C.idCoef
                WHERE U.idUser = '" . $_GET['idUser'] . "'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $coefApplicable = $stmt->fetch(PDO::FETCH_ASSOC)['coefApplicable'];
    
        $prixTotal = $_GET['prixTotal'];
    
        $creditObtenu = $prixTotal * $coefApplicable;
    
        $sql = "UPDATE user
                SET soldePointsCredit = soldePointsCredit + " . $creditObtenu . "
                WHERE idUser = '" . $_GET['idUser'] . "'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    
        if($stmt->rowCount() > 0) {
            echo json_encode([
                'success' => 1,
                'message' => 'Solde de points crédit mis à jour.'
            ]);

            $sql = "UPDATE commandefacture
                    SET isCreditable = 0
                    WHERE numFacture = '" . $_GET['numFacture'] . "'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

        } else {
            echo json_encode([
                'success' => 0,
                'message' => 'Aucune mise à jour effectuée.'
            ]);
        }
    
    } catch (Exception $e) {
        echo json_encode([
            'success' => 0,
            'message' => $e->getMessage()
        ]);
    }
}




?>