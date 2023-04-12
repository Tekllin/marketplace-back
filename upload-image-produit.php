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


try {
    if(isset($_FILES['image'])) {
        $images = $_FILES['image'];
        $idProduit = $_POST['idProduit'];

        $sql = "SELECT count(libelleImage) as nbImage from image WHERE idProduitImage = '$idProduit'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $nbImage = $stmt->fetch(PDO::FETCH_ASSOC);
        $nbImage = $nbImage['nbImage'];

        if($nbImage == 3) {
            echo json_encode([
                'success' => 200,
                'message' => "Vous avez atteint le nombre maximum d'image"
            ]);
            exit;
        } 

        $tmp_name = $images['tmp_name'];
        $name = $images['name'];

        $file_ext = explode('.', $name);
        $file_ext = strtolower(end($file_ext));

        $name = $idProduit . '-' . $nbImage . '.' .$file_ext;

        move_uploaded_file($tmp_name, "photos/produits/$name");
        
        $req = $conn->prepare("INSERT INTO image(libelleImage, idProduitImage) VALUES ('$name','$idProduit')");
        $req->execute();

        $req = $conn->prepare("DELETE FROM annonce WHERE idAnnonceProduit = '$idProduit'");
        $req->execute();

        echo json_encode([
            'success' => 200,
            'message' => "Image envoyée."
        ]);
        exit;

    } else {
        echo json_encode([
            'success' => 500,
            'message' => "Vous n'avez pas rentrez d'image"
        ]);
        exit;
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => 405,
        'data' => $e->getMessage()
    ]);
    exit;
}
?>