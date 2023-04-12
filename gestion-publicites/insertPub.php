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

require_once '../db_connect.php';
$database = new Operations();
$conn = $database->dbConnection();


if (empty($_FILES['imagePub0'])) {
    http_response_code(400);
    echo json_encode([
        'success' => 0,
        'message' => 'Vous devez envoyer au moins une image.'
    ]);
    exit;
}


$libellePub = str_replace("\"", "`", str_replace("\'", "\'\'",$_POST['libellePub'])) ;
$ligne1 = str_replace("\"", "`", str_replace("\'", "\'\'",$_POST['ligne1']));
$ligne2 = str_replace("\"", "`", str_replace("\'", "\'\'",$_POST['ligne2']));
$ligne3 = str_replace("\"", "`", str_replace("\'", "\'\'",$_POST['ligne3']));
$lien = str_replace("\"", "`", str_replace("\'", "\'\'",$_POST['lien']));
$bloc = str_replace("\"", "`", str_replace("\'", "\'\'",$_POST['bloc']));

$imagePub = array();

if(isset($_FILES['imagePub0'])) {
    array_push($imagePub, $_FILES['imagePub0']);
}
if(isset($_FILES['imagePub1'])) {
    array_push($imagePub, $_FILES['imagePub1']);
}
if(isset($_FILES['imagePub2'])) {
    array_push($imagePub, $_FILES['imagePub2']);
}

if(!isset($libellePub) || !isset($lien)) {
    echo json_encode([
        'success' => 0,
        'message' => 'Enter all values.'
    ]);
}


    try {
        
        $req = $conn->prepare(
            'INSERT INTO publicites(
                libellePub,
                ligne1,
                ligne2,
                ligne3,
                lien,
                bloc
  
            ) VALUES (
                "'.$libellePub.'",
                "'.$ligne1.'",
                "'.$ligne2.'",
                "'.$ligne3.'",
                "'.$lien.'",
                "'.$bloc.'"
     
            )'
        );
        $req->execute();

        try {
            $req = $conn->prepare("SELECT max(idPub) as nbPub FROM publicites");
            $req->execute();
            $data = $req->fetch();
    
            $nb = $data['nbPub'];
    
            for ($i = 0; $i < count($imagePub); $i++) {
                if ($imagePub[$i]['error'] == UPLOAD_ERR_OK) {
                    $tmp_name = $imagePub[$i]['tmp_name'];
                    $name = $imagePub[$i]['name'];
    
                    $file_ext = explode('.', $name);
                    $file_ext = strtolower(end($file_ext));
    
                    $name = $nb . '-' . $i . '.' .$file_ext;
    
                    move_uploaded_file($tmp_name, "../photos/pubs/$name");
                    
                    $req = $conn->prepare("INSERT INTO imagepub(libelleImagePub, idPubliciteImage) VALUES ('$name','$nb')");
                    $req->execute();
                }
            }
        } catch (Exception $e) {
            http_response_code(405);
            echo json_encode([
                'success' => 0,
                'data' => $e->getMessage()
            ]);
            exit;
        }

        

        http_response_code(200);
        echo json_encode([
            'success' => 0,
            'data' => "Ajout effectué avec succès."
        ]);
        exit;

    } catch (Exception $e) {
        http_response_code(404);
        echo json_encode([
            'success' => 0,
            'data' => $e->getMessage()
        ]);
        exit;
    }
?>