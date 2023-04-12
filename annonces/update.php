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

    if (!isset($data->idAnnonceProduit) || !isset($data->idAnnonceAnnoFacture)) 
    {
        echo json_encode(['success' => 0, 'message' => 'Please enter correct produit and facture id.']);
        exit;
    }

    try 
    {
        $fetch_post = "SELECT idAnnonceProduit, idAnnonceAnnoFacture, idEtatAnnonce FROM annonce WHERE idAnnonceProduit = :idAnnonceProduit AND idAnnonceAnnoFacture = :idAnnonceAnnoFacture";
        $fetch_stmt = $cnx->prepare($fetch_post);
        $fetch_stmt->bindValue(':idAnnonceProduit', $data->idAnnonceProduit, PDO::PARAM_INT);
        $fetch_stmt->bindValue(':idAnnonceAnnoFacture', $data->idAnnonceAnnoFacture, PDO::PARAM_STR);
        $fetch_stmt->execute();

        if ($fetch_stmt->rowCount() > 0) :
            $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
            $idAnnonceProduit = $data->idAnnonceProduit;
            $idAnnonceAnnoFacture = $data->idAnnonceAnnoFacture;
            $idEtat = isset($data->idEtat) && !empty($data->idEtat) ? $data->idEtat : $row['idEtatAnnonce'];

            $update_query = "UPDATE `annonce` 
            SET idEtatAnnonce = :idEtat
            WHERE idAnnonceProduit = :idAnnonceProduit 
            AND idAnnonceAnnoFacture = :idAnnonceAnnoFacture";

            $update_stmt = $cnx->prepare($update_query);

            $update_stmt->bindValue(':idEtat', htmlspecialchars(strip_tags($idEtat)), PDO::PARAM_INT);

            $update_stmt->bindValue(':idAnnonceProduit', $idAnnonceProduit, PDO::PARAM_INT);
            $update_stmt->bindValue(':idAnnonceAnnoFacture', $idAnnonceAnnoFacture, PDO::PARAM_STR);

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