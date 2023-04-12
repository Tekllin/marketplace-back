<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



$method = $_SERVER['REQUEST_METHOD'];

if ($method == "OPTIONS") {
    die();
}


if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') :
    http_response_code(405);
    echo json_encode([
        'success' => 0,
        'message' => 'Bad Reqeust detected. HTTP method should be DELETE',
    ]);
    exit;
endif;

require '../db_connect.php';
$database = new Operations();
$conn = $database->dbConnection();

$data = json_decode(file_get_contents("php://input"));
//echo $data = file_get_contents("php://input");

$id =  $_GET['id'];



if (!isset($id)) {
    echo json_encode(['success' => 0, 'message' => 'Please provide the post ID.']);
    exit;
}

try {

    $fetch_post = "SELECT * FROM `categories` WHERE idCategorie = :id";
    $fetch_stmt = $conn->prepare($fetch_post);
    $fetch_stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $fetch_stmt->execute();

    if ($fetch_stmt->rowCount() > 0) :

        $fetch_count = "SELECT count(*) as count FROM produits where idCategorieProduit = :id AND idCategorieProduit in (SELECT idCategorie FROM categories)";
        $fetch_r = $conn->prepare($fetch_count);
        $fetch_r->bindValue(':id', $id, PDO::PARAM_INT);
        $fetch_r->execute();
        $dataP = $fetch_r->fetch();

        if($dataP['count'] != 0) {
            echo json_encode([
                'success' => 0,
                'data' => $dataP
            ]);
            exit;

        } else {

        
        $delete_post = "DELETE FROM `categories` WHERE idCategorie = :id";
        $delete_post_stmt = $conn->prepare($delete_post); 
        $delete_post_stmt->bindValue(':id', $id,PDO::PARAM_INT);

        if ($delete_post_stmt->execute()) {
            
            echo json_encode([
                'success' => 1,
                'message' => 'Suppression réalisé avec succès'
            ]);
            exit;
        }

        echo json_encode([
            'success' => 0,
            'message' => 'Suppression impossible.'
        ]);
        exit;
    }
    else :
        echo json_encode(['success' => 0, 'message' => 'ID invalide. Aucun message trouvé par ID']);
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