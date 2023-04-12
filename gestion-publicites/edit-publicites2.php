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
 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') :
    http_response_code(405);
    echo json_encode([
        'success' => 0,
        'message' => 'Bad Request detected! Only POST method is allowed',
    ]);
    exit;
endif;

require '../db_connect.php';
$database = new Operations();
$conn = $database->dbConnection();

$idPub = $_POST['idPub'];
$libellePub = str_replace("\"", "`", str_replace("\'", "\'\'",$_POST['libellePub'])) ;
$ligne1 = str_replace("\"", "`", str_replace("\'", "\'\'",$_POST['ligne1']));
$ligne2 = str_replace("\"", "`", str_replace("\'", "\'\'",$_POST['ligne2']));
$ligne3 = str_replace("\"", "`", str_replace("\'", "\'\'",$_POST['ligne3']));
$lien = str_replace("\"", "`", str_replace("\'", "\'\'",$_POST['lien']));

if(!isset($idPub) || !isset($lien)) {
    echo json_encode([
        'success' => 0,
        'message' => 'Enter all values.'
    ]);
}


try {
    
    //Update SQL
    $sql = 'UPDATE publicites2 SET
    libellePub = "'.$libellePub.'",
    ligne1 = "'.$ligne1.'",
    ligne2 = "'.$ligne2.'",
    ligne3 = "'.$ligne3.'",
    lien = "'.$lien.'"
    WHERE idPub = "'.$idPub.'"';
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode([
        'success' => 200, 
        'data' => "Ajout effectué avec succès."
    ]);
    exit;
} else {

}

} catch (Exception $e) {
    http_response_code(404);
    echo json_encode([
        'success' => 0,
        'data' => $e->getMessage()
    ]);
    exit;
}

?>