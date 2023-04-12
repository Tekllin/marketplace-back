<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: PUT");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == "OPTIONS") 
    {
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
    $db = new Operations();
    $cnx = $db->dbConnection();

    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->idUser)) 
    {
        echo json_encode(['success' => 0, 'message' => 'Please enter correct user id.']);
        exit;
    }

    try 
    {
        $fetch_post = "SELECT idUser, nomUser, prenomUser, emailUser, passwordUser, telUser, tokenInsc, idDroitUser FROM user WHERE idUser = :idUser";
        $fetch_stmt = $cnx->prepare($fetch_post);
        $fetch_stmt->bindValue(':idUser', $data->idUser, PDO::PARAM_INT);
        $fetch_stmt->execute();

        if ($fetch_stmt->rowCount() > 0) :
            $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
            $nomUser = isset($data->nomUser) && !empty($data->nomUser) ? $data->nomUser : $row['nomUser'];
            $prenomUser = isset($data->prenomUser) && !empty($data->prenomUser) ? $data->prenomUser : $row['prenomUser'];
            $emailUser = isset($data->emailUser) && !empty($data->emailUser) ? $data->emailUser : $row['emailUser'];
            $passwordUser = isset($data->passwordUser) && !empty($data->passwordUser) ? $data->passwordUser : $row['passwordUser'];
            $telUser = isset($data->telUser) && !empty($data->telUser) ? $data->telUser : $row['telUser'];
            $tokenInsc = isset($data->tokenInsc) && !empty($data->tokenInsc) ? $data->tokenInsc : $row['tokenInsc'];
            $idDroitUser = isset($data->idDroitUser) && !empty($data->idDroitUser) ? $data->idDroitUser : $row['idDroitUser'];

            $update_query = "UPDATE `user` 
            SET nomUser = :nomUser, 
            prenomUser = :prenomUser,
            emailUser = :emailUser,
            passwordUser = :passwordUser,
            telUser = :telUser,
            tokenInsc = :tokenInsc,
            idDroitUser = :idDroitUser
            WHERE idUser = :idUser";

            $update_stmt = $cnx->prepare($update_query);

            $update_stmt->bindValue(':nomUser', htmlspecialchars(strip_tags($nomUser)), PDO::PARAM_STR);
            $update_stmt->bindValue(':prenomUser', htmlspecialchars(strip_tags($prenomUser)), PDO::PARAM_STR);
            $update_stmt->bindValue(':emailUser', htmlspecialchars(strip_tags($emailUser)), PDO::PARAM_STR);
            $update_stmt->bindValue(':passwordUser', htmlspecialchars(strip_tags($passwordUser)), PDO::PARAM_STR);
            $update_stmt->bindValue(':telUser', htmlspecialchars(strip_tags($telUser)), PDO::PARAM_STR);
            $update_stmt->bindValue(':tokenInsc', htmlspecialchars(strip_tags($tokenInsc)), PDO::PARAM_STR);
            $update_stmt->bindValue(':idDroitUser', htmlspecialchars(strip_tags($idDroitUser)), PDO::PARAM_STR);

            $update_stmt->bindValue(':idUser', $data->idUser, PDO::PARAM_INT);


            if ($update_stmt->execute()) 
            {
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
    } 
    catch (PDOException $e) 
    {
        http_response_code(500);
        echo json_encode([
            'success' => 0,
            'message' => $e->getMessage()
        ]);
        exit;
    }
?>