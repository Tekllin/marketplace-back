
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
 

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
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


$id = $_POST['id'];
$libelleAdresse = $_POST['libelleAdresse'];
$villeAdresse = $_POST['villeAdresse'];
$cpAdresse = $_POST['cpAdresse'];
$paysAdresse = $_POST['paysAdresse'];


try {

    $sql = "SELECT idUserAdresse FROM facturations WHERE idUserAdresse =".$id;
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $sql = "UPDATE facturations 
        SET libelleAdresse = '$libelleAdresse', 
        villeAdresse = '$villeAdresse', 
        cpAdresse = '$cpAdresse', 
        paysAdresse = '$paysAdresse' 
        WHERE idUserAdresse =".$id;
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $sql = "SELECT idAdresse FROM facturations WHERE idUserAdresse =".$id;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => 200,
            'data' => $result,
            'message' => "Adresse modifiée."
        ]);
    } else {
        
        $sql = "INSERT INTO facturations (idUserAdresse, libelleAdresse, villeAdresse, cpAdresse, paysAdresse) 
        VALUES ('$id', '$libelleAdresse', '$villeAdresse', '$cpAdresse', '$paysAdresse')";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $sql = "SELECT idAdresse FROM facturations WHERE idUserAdresse =".$id;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => 1,
            'data' => $result,
            'message' => "Adresse ajoutée."
        ]);
    }


} catch (Exception $e) {
    http_response_code(404);
    echo json_encode([
        'success' => 500,
        'data' => $e->getMessage()
    ]);
    exit;
}

?>