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

    if (!isset($data->idAvis)) 
    {
        echo json_encode(['success' => 0, 'message' => 'Please enter correct Avis id.']);
        exit;
    }

    try 
    {
        $fetch_post = "SELECT idAvis, libelleAvis, noteAvis, valid, idProduitAvis, idUserAvis FROM avis WHERE idAvis = :idAvis";
        $fetch_stmt = $cnx->prepare($fetch_post);
        $fetch_stmt->bindValue(':idAvis', $data->idAvis, PDO::PARAM_INT);
        $fetch_stmt->execute();

        if ($fetch_stmt->rowCount() > 0) :
            $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
            $idAvis = $row['idAvis'];
            $libelleAvis = isset($data->libelleAvis) && !empty($data->libelleAvis) ? $data->libelleAvis : $row['libelleAvis'];
            $noteAvis = isset($data->noteAvis) && !empty($data->noteAvis) ? $data->noteAvis : $row['noteAvis'];
            $verifie = isset($data->verifie) && !empty($data->verifie) ? $data->verifie : $row['verifie'];
            $idProduitAvis = isset($data->idProduitAvis) && !empty($data->idProduitAvis) ? $data->verifie : $row['idProduitAvis'];
            $idUserAvis = isset($data->idUserAvis) && !empty($data->idUserAvis) ? $data->verifie : $row['idUserAvis'];

            $update_query = "UPDATE `avis` 
            SET libelleAvis = :libelleAvis, 
            noteAvis = :noteAvis,
            valid = :verifie,
            idProduitAvis = :idProduitAvis,
            idUserAvis = :idUserAvis
            WHERE idAvis = :idAvis";

            $update_stmt = $cnx->prepare($update_query);

            $update_stmt->bindValue(':libelleAvis', htmlspecialchars(strip_tags($libelleAvis)), PDO::PARAM_STR);
            $update_stmt->bindValue(':noteAvis', htmlspecialchars(strip_tags($noteAvis)), PDO::PARAM_INT);
            $update_stmt->bindValue(':verifie', htmlspecialchars(strip_tags($verifie)), PDO::PARAM_INT);
            $update_stmt->bindValue(':idProduitAvis', htmlspecialchars(strip_tags($idProduitAvis)), PDO::PARAM_INT);
            $update_stmt->bindValue(':idUserAvis', htmlspecialchars(strip_tags($idUserAvis)), PDO::PARAM_INT);

            $update_stmt->bindValue(':idAvis', htmlspecialchars(strip_tags($idAvis)), PDO::PARAM_INT);

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
                'message' => 'Did not udpate. Something went wrong.'
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