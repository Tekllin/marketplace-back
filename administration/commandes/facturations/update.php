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

    require '../../db_connect.php';
    $db = new Operations();
    $cnx = $db->dbConnection();

    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->idAdresse)) 
    {
        echo json_encode(['success' => 0, 'message' => 'Please enter correct addresse id.']);
        exit;
    }

    try 
    {
        $fetch_post = "SELECT idAdresse, idUserAdresse, libelleAdresse, cpAdresse, villeAdresse, paysAdresse, etiquetteAdresse FROM facturations WHERE idAdresse = :idAdresse";
        $fetch_stmt = $cnx->prepare($fetch_post);
        $fetch_stmt->bindValue(':idAdresse', $data->idAdresse, PDO::PARAM_INT);
        $fetch_stmt->execute();

        if ($fetch_stmt->rowCount() > 0) :
            $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
            $idUserAdresse = isset($data->idUserAdresse) && !empty($data->idUserAdresse) ? $data->idUserAdresse : $row['idUserAdresse'];
            $libelleAdresse = isset($data->libelleAdresse) && !empty($data->libelleAdresse) ? $data->libelleAdresse : $row['libelleAdresse'];
            $cpAdresse = isset($data->cpAdresse) && !empty($data->cpAdresse) ? $data->cpAdresse : $row['cpAdresse'];
            $villeAdresse = isset($data->villeAdresse) && !empty($data->villeAdresse) ? $data->villeAdresse : $row['villeAdresse'];
            $paysAdresse = isset($data->paysAdresse) && !empty($data->paysAdresse) ? $data->paysAdresse : $row['paysAdresse'];
            $etiquetteAdresse = isset($data->etiquetteAdresse) && !empty($data->etiquetteAdresse) ? $data->etiquetteAdresse : $row['etiquetteAdresse'];

            $update_query = "UPDATE `facturations` 
            SET idUserAdresse = :idUserAdresse, 
            libelleAdresse = :libelleAdresse,
            cpAdresse = :cpAdresse,
            villeAdresse = :villeAdresse,
            paysAdresse = :paysAdresse,
            etiquetteAdresse = :etiquetteAdresse
            WHERE idAdresse = :idAdresse";

            $update_stmt = $cnx->prepare($update_query);

            $update_stmt->bindValue(':idUserAdresse', htmlspecialchars(strip_tags($idUserAdresse)), PDO::PARAM_INT);
            $update_stmt->bindValue(':libelleAdresse', htmlspecialchars(strip_tags($libelleAdresse)), PDO::PARAM_STR);
            $update_stmt->bindValue(':cpAdresse', htmlspecialchars(strip_tags($cpAdresse)), PDO::PARAM_STR);
            $update_stmt->bindValue(':villeAdresse', htmlspecialchars(strip_tags($villeAdresse)), PDO::PARAM_STR);
            $update_stmt->bindValue(':paysAdresse', htmlspecialchars(strip_tags($paysAdresse)), PDO::PARAM_STR);
            $update_stmt->bindValue(':etiquetteAdresse', htmlspecialchars(strip_tags($etiquetteAdresse)), PDO::PARAM_STR);

            $update_stmt->bindValue(':idAdresse', $data->idAdresse, PDO::PARAM_INT);


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