<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


require 'db_connect.php';
$database = new Operations();
$conn = $database->dbConnection();

$data = json_decode(file_get_contents("php://input"));


if (!isset($data->id)) {
    echo json_encode(['success' => 0, 'message' => 'Please enter correct Students id.']);
    exit;
}

try {
    $fetch_post = "SELECT * FROM `student` WHERE id=:id";
    $fetch_stmt = $conn->prepare($fetch_post);
    $fetch_stmt->bindValue(':id', $data->id, PDO::PARAM_INT);
    $fetch_stmt->execute();

    if ($fetch_stmt->rowCount() > 0) :
        $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
        $nom = isset($data->nom) ? $data->nom : $row['nom'];
        $prenom = isset($data->prenom) ? $data->prenom : $row['prenom'];
        $email = isset($data->email) ? $data->email : $row['email'];
        $num = isset($data->num) ? $data->num : $row['num'];
        $genre = isset($data->genre) ? $data->genre : $row['genre'];
        $pays = isset($data->pays) ? $data->pays : $row['pays'];

       $update_query = "UPDATE `student` SET nom = :nom, prenom = :prenom, email = :email, num = :num, genre = :genre, pays = :pays 
       WHERE id = :id";

        $update_stmt = $conn->prepare($update_query);

        $update_stmt->bindValue(':nom', htmlspecialchars(strip_tags($nom)), PDO::PARAM_STR);
        $update_stmt->bindValue(':prenom', htmlspecialchars(strip_tags($prenom)), PDO::PARAM_STR);
        $update_stmt->bindValue(':email', htmlspecialchars(strip_tags($email)), PDO::PARAM_STR);
        $update_stmt->bindValue(':num', htmlspecialchars(strip_tags($num)), PDO::PARAM_STR);
        $update_stmt->bindValue(':genre', htmlspecialchars(strip_tags($genre)), PDO::PARAM_STR);
        $update_stmt->bindValue(':pays', htmlspecialchars(strip_tags($pays)), PDO::PARAM_STR);
        $update_stmt->bindValue(':id', $data->id, PDO::PARAM_INT);

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