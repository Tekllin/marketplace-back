<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


 $method = $_SERVER['REQUEST_METHOD'];

if ($method == "OPTIONS") {
    die();
}


if ($_SERVER['REQUEST_METHOD'] !== 'PUT') :
    http_response_code(405);
    echo json_encode([
        'success' => 0,
        'message' => 'Bad Request detected! Only PUT method is allowed',
    ]);
    exit;
endif;

require '../db_connect.php';
$database = new Operations();
$conn = $database->dbConnection();

$data = json_decode(file_get_contents("php://input"));

//print_r($data);

//die();


if (!isset($data->id)) {
    echo json_encode(['success' => 0, 'message' => "L'id entré est incorrecte."]);
    exit;
}

try {

    $fetch_post = "SELECT * FROM `categories` WHERE idCategorie=:id";
    $fetch_stmt = $conn->prepare($fetch_post);
    $fetch_stmt->bindValue(':id', $data->id, PDO::PARAM_INT);
    $fetch_stmt->execute();

    if ($fetch_stmt->rowCount() > 0) :
     //echo 'AAA';
        $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
        $libelleCategorie = isset($data->libelleCategorie) ? $data->libelleCategorie : $row['libelleCategorie'];
        $idGammeCategorie = isset($data->idGamme) ? $data->idGamme : $row['idGamme'];
   
       $update_query = "UPDATE `categories` SET libelleCategorie = :libelleCategorie, idGammeCategorie = :idGammeCategorie
        WHERE idCategorie = :id";

        $update_stmt = $conn->prepare($update_query);

        $update_stmt->bindValue(':libelleCategorie', htmlspecialchars(strip_tags($libelleCategorie)), PDO::PARAM_STR);
        $update_stmt->bindValue(':idGammeCategorie', htmlspecialchars(strip_tags($idGammeCategorie)), PDO::PARAM_INT);
 


        $update_stmt->bindValue(':id', $data->id, PDO::PARAM_INT);


        $fetch_count = "SELECT idGamme FROM gammes  WHERE idGamme = :idGammeCategorie";
    $fetch_r = $conn->prepare($fetch_count);
    $fetch_r->bindValue(':idGammeCategorie', $idGammeCategorie, PDO::PARAM_INT);
    $fetch_r->execute();
    $verif = $fetch_r->fetch();

    if(!$verif){

        echo json_encode([
            
            'success' => 0,
            'data' => $verif
        ]);
        exit;
    }
    else{
        if ($update_stmt->execute()) {

            echo json_encode([
                'success' => 1,
                'message' => 'Record udated successfully'
            ]);
            exit;
        }

        echo json_encode([
            'success' => 0,
            'message' => 'Did not udpate. Something went  wrong.'
        ]);
        exit;
    }

    else :
        echo json_encode(['success' => 0, 'message' => 'Invalid ID. No record found by the ID.']);
        exit;
    endif;
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
    exit;
}