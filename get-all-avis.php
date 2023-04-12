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
    $data = null;
    if(isset($_GET['valid'])) {
        if($_GET['valid'] == 0) {
            $sql = "SELECT idAvis, libelleAvis, noteAvis, valid, idProduitAvis, idUserAvis, nomUser, prenomUser
            FROM avis
            INNER JOIN User ON idUserAvis = idUser
            WHERE valid = 0";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else if($_GET['valid'] == 1){
            $sql = "SELECT idAvis, libelleAvis, noteAvis, valid, idProduitAvis, idUserAvis, nomUser, prenomUser
            FROM avis
            INNER JOIN User ON idUserAvis = idUser
            WHERE valid = 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $sql = "SELECT idAvis, libelleAvis, noteAvis, valid, idProduitAvis, idUserAvis, nomUser, prenomUser
            FROM avis
            INNER JOIN User ON idUserAvis = idUser";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } else {
        $sql = "SELECT idAvis, libelleAvis, noteAvis, valid, idProduitAvis, idUserAvis, nomUser, prenomUser
        FROM avis
        INNER JOIN User ON idUserAvis = idUser";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    echo json_encode([
        'success' => 1,
        'data' => $data
    ]);

    
} catch (Exception $e) {
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
}

?>