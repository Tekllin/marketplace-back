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


if (empty($_FILES['image0'])) {
    http_response_code(400);
    echo json_encode([
        'success' => 0,
        'message' => 'Vous devez envoyer au moins une image.'
    ]);
    exit;
}



$libelleProduit = $_POST['libelleProduit'];
$qtStock = $_POST['qtStock'];
$descriptionProduit = $_POST['descriptionProduit'];
$prixUnitHT = $_POST['prixUnitHT'];
$poidsProduit = $_POST['poidsProduit'];
$idCategorieProduit = $_POST['idCategorieProduit'];
$idAnnonceurProduit = $_POST['idAnnonceurProduit'];
$idTvaProduit = $_POST['idTvaProduit'];
$adminProduit = $_POST['adminProduit'];

$libelleProduit = str_replace("\'", "\'\'", $libelleProduit);
$descriptionProduit = str_replace("\'", "\'\'", $descriptionProduit);

$images = array();

if(isset($_FILES['image0'])) {
    array_push($images, $_FILES['image0']);
}
if(isset($_FILES['image1'])) {
    array_push($images, $_FILES['image1']);
}
if(isset($_FILES['image2'])) {
    array_push($images, $_FILES['image2']);
}

    try {
        
        $req = $conn->prepare(
            'INSERT INTO produits(
                libelleProduit,
                qtStock,
                descriptionProduit,
                prixUnitHT,
                poidsProduit,
                adminProduit,
                idCategorieProduit,
                idAnnonceurProduit,
                idTvaProduit
            ) VALUES (
                "'.$libelleProduit.'",
                "'.$qtStock.'",
                "'.$descriptionProduit.'",
                "'.$prixUnitHT.'",
                "'.$poidsProduit.'",
                "'.$adminProduit.'",
                "'.$idCategorieProduit.'",
                "'.$idAnnonceurProduit.'",
                "'.$idTvaProduit.'"
            )'
        );
        $req->execute();

        try {
            $req = $conn->prepare("SELECT max(idProduit) as nbProduit FROM produits");
            $req->execute();
            $data = $req->fetch();
    
            $nb = $data['nbProduit'];
    
            for ($i = 0; $i < count($images); $i++) {
                if ($images[$i]['error'] == UPLOAD_ERR_OK) {
                    $tmp_name = $images[$i]['tmp_name'];
                    $name = $images[$i]['name'];
    
                    $file_ext = explode('.', $name);
                    $file_ext = strtolower(end($file_ext));
    
                    $name = $nb . '-' . $i . '.' .$file_ext;
    
                    move_uploaded_file($tmp_name, "photos/produits/$name");
                    
                    $req = $conn->prepare("INSERT INTO image(libelleImage, idProduitImage) VALUES ('$name','$nb')");
                    $req->execute();
                }
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => 500,
                'data' => $e->getMessage()
            ]);
            exit;
        }
        echo json_encode([
            'success' => 200,
            'message' => "Ajout effectué avec succès."
        ]);
        exit;

    } catch (Exception $e) {
        echo json_encode([
            'success' => 405,
            'data' => $e->getMessage()
        ]);
        exit;
    }
?>