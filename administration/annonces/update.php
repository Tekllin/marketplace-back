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

    if (!isset($data->idAnnonceProduit) || !isset($data->idAnnonceAnnoFacture) || !isset($data->idEtat))
    {
        echo json_encode([
            'success' => 0,
            'message' => 'Field cannot be empty. Please fill all the fields.',
        ]);
        exit;
    }
    else if (empty(trim($data->idAnnonceProduit)) || empty(trim($data->idAnnonceAnnoFacture)) || empty(trim($data->idEtat)))
    {
        echo json_encode([
            'success' => 0,
            'message' => 'Please enter compulsory fileds | idAnnonceProduit, idAnnonceAnnoFacture and idEtat',
        ]);
        exit;
    }

    try 
    {
        $fetch_post = "SELECT idAnnonceProduit, idAnnonceAnnoFacture, idEtat FROM annonce WHERE idAnnonceProduit = :idAnnonceProduit AND idAnnonceAnnoFacture = :idAnnonceAnnoFacture AND idEtat = :idEtat";
        $fetch_stmt = $cnx->prepare($fetch_post);
        $fetch_stmt->bindValue(':idAnnonceProduit', $data->idAnnonceProduit, PDO::PARAM_INT);
        $fetch_stmt->bindValue(':idAnnonceAnnoFacture', $data->idAnnonceAnnoFacture, PDO::PARAM_INT);
        $fetch_stmt->bindValue(':idEtat', $data->idEtat, PDO::PARAM_INT);
        $fetch_stmt->execute();

        if ($fetch_stmt->rowCount() > 0) :
            $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
            $idEtatModif = isset($data->idEtatModif) ? $data->idEtatModif : $row['idEtat'];

            $update_query = "UPDATE `annonce` 
            SET idEtat = :idEtatModif
            WHERE idAnnonceProduit = :idAnnonceProduit 
            AND idAnnonceAnnoFacture = :idAnnonceAnnoFacture
            AND idEtat = :idEtat";

            $update_stmt = $cnx->prepare($update_query);

            $update_stmt->bindValue(':idEtatModif', htmlspecialchars(strip_tags($idEtatModif)), PDO::PARAM_INT);

            $update_stmt->bindValue(':idAnnonceProduit', $data->idAnnonceProduit, PDO::PARAM_INT);
            $update_stmt->bindValue(':idAnnonceAnnoFacture', $data->idAnnonceAnnoFacture, PDO::PARAM_INT);
            $update_stmt->bindValue(':idEtat', $data->idEtat, PDO::PARAM_INT);

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