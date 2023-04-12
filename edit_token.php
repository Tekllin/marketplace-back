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


if (!isset($data->emailUser)) {
    echo json_encode(['success' => 0, 'message' => 'Please enter correct Students id.']);
    exit;
}

try {
    $fetch_post = "SELECT * FROM `user` WHERE emailUser=:emailUser";
    $fetch_stmt = $conn->prepare($fetch_post);
    $fetch_stmt->bindValue(':emailUser', $data->emailUser, PDO::PARAM_STR);
    $fetch_stmt->execute();
        $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
        $emailUser = isset($data->emailUser) ? $data->emailUser : $row['emailUser'];
        $tokenInsc = isset($data->tokenInsc) ? $data->tokenInsc : $row['tokenInsc'];

       $update_query = "UPDATE `user` SET tokenInsc = :tokenInsc WHERE emailUser = :emailUser";

        $update_stmt = $conn->prepare($update_query);

        $update_stmt->bindValue(':tokenInsc', htmlspecialchars(strip_tags($tokenInsc)), PDO::PARAM_STR);
        $update_stmt->bindValue(':emailUser', $emailUser, PDO::PARAM_STR);

        if ($update_stmt->execute()) {
            echo json_encode([
                'success' => 1,
                'message' => $tokenInsc
            ]);
            exit;
        }

        echo json_encode([
            'success' => 0,
            'message' => 'Did not udpate. Something went  wrong.'
        ]);
        exit;
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
    exit;
}
?>