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

$sql = "SELECT idContinent
        FROM continent
        INNER JOIN pays ON continent.idContinent = pays.idContinentPays
        WHERE codePays = '" . $_GET['codePays'] . "'";
$stmt = $conn->prepare($sql);
$stmt->execute();
if($stmt->rowCount() > 0) {
    $idContinent = $stmt->fetch(PDO::FETCH_ASSOC)['idContinent'];
} else {
    echo json_encode([
        'success' => 0,
        'message' => "Aucun tarif n'a été trouvé pour votre adresse."
    ]);
    exit;
}

$idTransporteur = $_GET['idTransporteur'];
$poidsTotal = $_GET['poidsTotal'];

$tarifTotal = 0;

while ($poidsTotal > 30){
    $poids = 30;
    $sql = "SELECT tarif
        FROM fraisdeport
        WHERE idTransporteurFdp = '" . $idTransporteur . "' AND idContinentFdp = '" . $idContinent . "' AND poids = '" . $poids . "'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    if($stmt->rowCount() > 0) {
        $data = null;
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        $tarifTotal += $data['tarif'];
        $poidsTotal -= $poids;
    } else {
        echo json_encode([
            'success' => 0,
            'message' => "Aucun tarif n'a été trouvé pour votre adresse."
        ]);
        exit;
    }
}

if ($poidsTotal <= 1) {
    $poids = 1;
} elseif ($poidsTotal <= 2){
    $poids = 2;
} elseif ($poidsTotal <= 5){
    $poids = 5;
} elseif ($poidsTotal <= 10){
    $poids = 10;
} elseif ($poidsTotal <= 15){
    $poids = 15;
} elseif ($poidsTotal <= 20){
    $poids = 20;
} elseif ($poidsTotal <= 25){
    $poids = 25;
} elseif ($poidsTotal <= 30){
    $poids = 30;
}

$sql = "SELECT tarif
        FROM fraisdeport
        WHERE idTransporteurFdp = '" . $idTransporteur . "' AND idContinentFdp = '" . $idContinent . "' AND poids = '" . $poids . "'";

$stmt = $conn->prepare($sql);
$stmt->execute();

if($stmt->rowCount() > 0) {
    $data = null;
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    $tarifTotal += $data['tarif'];
}

if ($tarifTotal == 0) {
    echo json_encode([
        'success' => 0,
        'message' => "Aucun tarif n'a été trouvé pour votre adresse."
    ]);
    exit;
} else {
    echo json_encode([
        'success' => 1,
        'data' => $tarifTotal
    ]);
    exit;
}


?>