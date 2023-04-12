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

$sql = "SELECT idAdresse, libelleAdresse, idUserAdresse, cpAdresse, villeAdresse, libellePays, etiquetteAdresse
        FROM livraisons
        INNER JOIN pays ON livraisons.codePaysAdresse = pays.codePays
        WHERE idUserAdresse = '" . $_GET['idUser'] . "'";
$stmt = $conn->prepare($sql);
$stmt->execute();
if($stmt->rowCount() > 0) {
    $sql = "UPDATE livraisons
            SET libelleAdresse = '" . $_GET['libelleAdresse'] . "',
                cpAdresse = '" . $_GET['cpAdresse'] . "',
                villeAdresse = '" . $_GET['villeAdresse'] . "',
                codePaysAdresse = '" . $_GET['codePaysAdresse'] . "',
                etiquetteAdresse = '" . $_GET['etiquetteAdresse'] . "'
            WHERE idUserAdresse = '" . $_GET['idUser'] . "'";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if($stmt->rowCount() > 0){
        echo json_encode([
            'success' => 1,
            'message' => 'Delivery address updated successfully.'
        ]);
    } else {
        echo json_encode([
            'success' => 0,
            'message' => 'Delivery address not updated.'
        ]);
    }
} else {
    $sql = "INSERT INTO livraisons (libelleAdresse, idUserAdresse, cpAdresse, villeAdresse, codePaysAdresse, etiquetteAdresse)
            VALUES ('" . $_GET['libelleAdresse'] . "', '" . $_GET['idUser'] . "', '" . $_GET['cpAdresse'] . "', '" . $_GET['villeAdresse'] . "', '" . $_GET['codePaysAdresse'] . "', '" . $_GET['etiquetteAdresse'] . "')";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if($stmt->rowCount() > 0){
        echo json_encode([
            'success' => 1,
            'message' => 'Delivery address added successfully.'
        ]);
    } else {
        echo json_encode([
            'success' => 0,
            'message' => 'Delivery address not added.'
        ]);
    }
}


?>