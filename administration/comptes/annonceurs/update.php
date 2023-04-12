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

    if (!isset($data->idAnnonceur)) 
    {
        echo json_encode(['success' => 0, 'message' => 'Please enter correct user id.']);
        exit;
    }

    try 
    {
        $fetch_post = "SELECT idAnnonceur, libelleNomAnnonceur, logoAnnonceur FROM annonceurs WHERE idAnnonceur = :idAnnonceur";
        $fetch_stmt = $cnx->prepare($fetch_post);
        $fetch_stmt->bindValue(':idAnnonceur', $data->idAnnonceur, PDO::PARAM_INT);
        $fetch_stmt->execute();

        if ($fetch_stmt->rowCount() > 0) :
            $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
            $idAnnonceur = $data->idAnnonceur;
            $idAnnonceurModif = isset($data->idAnnonceurModif) && !empty($data->idAnnonceurModif) ? $data->idAnnonceurModif : $row['idAnnonceur'];
            $libelleNomAnnonceur = isset($data->libelleNomAnnonceur) && !empty($data->libelleNomAnnonceur) ? $data->libelleNomAnnonceur : $row['libelleNomAnnonceur'];
            $logoAnnonceur = isset($data->logoAnnonceur) && !empty($data->logoAnnonceur) ? $data->logoAnnonceur : $row['logoAnnonceur'];

            $update_query = "UPDATE `annonceurs` 
            SET idAnnonceur = :idAnnonceurModif, 
            libelleNomAnnonceur = :libelleNomAnnonceur,
            logoAnnonceur = :logoAnnonceur
            WHERE idAnnonceur = :idAnnonceur";

            $update_stmt = $cnx->prepare($update_query);

            $update_stmt->bindValue(':idAnnonceurModif', htmlspecialchars(strip_tags($idAnnonceurModif)), PDO::PARAM_INT);
            $update_stmt->bindValue(':libelleNomAnnonceur', htmlspecialchars(strip_tags($libelleNomAnnonceur)), PDO::PARAM_STR);
            $update_stmt->bindValue(':logoAnnonceur', htmlspecialchars(strip_tags($logoAnnonceur)), PDO::PARAM_STR);

            $update_stmt->bindValue(':idAnnonceur', $data->idAnnonceur, PDO::PARAM_INT);


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