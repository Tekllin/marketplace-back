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

    if (!isset($data->numFacture)) 
    {
        echo json_encode(['success' => 0, 'message' => 'Please enter correct numFacture.']);
        exit;
    }

    try 
    {
        $fetch_post = "SELECT numFacture, dateFacture, idLivraisonFacture, idFacturationFacture, idUserFacture, idPaiementFacture, idEtatCommandeFacture, CommandeFacturecol FROM commandefacture WHERE numFacture = :numFacture";
        $fetch_stmt = $cnx->prepare($fetch_post);
        $fetch_stmt->bindValue(':numFacture', $data->numFacture, PDO::PARAM_INT);
        $fetch_stmt->execute();

        if ($fetch_stmt->rowCount() > 0) :
            $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
            $dateFacture = isset($data->dateFacture) ? $data->dateFacture : $row['dateFacture'];
            $idLivraisonFacture = isset($data->idLivraisonFacture) ? $data->idLivraisonFacture : $row['idLivraisonFacture'];
            $idFacturationFacture = isset($data->idFacturationFacture) ? $data->idFacturationFacture : $row['idFacturationFacture'];
            $idUserFacture = isset($data->idUserFacture) ? $data->idUserFacture : $row['idUserFacture'];
            $idPairementFacture = isset($data->idPairementFacture) ? $data->idPairementFacture : $row['idPaiementFacture'];
            $idEtatCommandeFacture = isset($data->idEtatCommandeFacture) ? $data->idEtatCommandeFacture : $row['idEtatCommandeFacture'];
            $CommandeFacturecol = isset($data->CommandeFacturecol) ? $data->CommandeFacturecol : $row['CommandeFacturecol'];

            $update_query = "UPDATE `commandefacture` 
            SET dateFacture = :dateFacture, 
            idLivraisonFacture = :idLivraisonFacture,
            idFacturationFacture = :idFacturationFacture,
            idUserFacture = :idUserFacture,
            idPaiementFacture = :idPairementFacture,
            idEtatCommandeFacture = :idEtatCommandeFacture,
            CommandeFacturecol = :CommandeFacturecol
            WHERE numFacture = :numFacture";

            $update_stmt = $cnx->prepare($update_query);

            $update_stmt->bindValue(':dateFacture', htmlspecialchars(strip_tags($dateFacture)), PDO::PARAM_STR);
            $update_stmt->bindValue(':idLivraisonFacture', htmlspecialchars(strip_tags($idLivraisonFacture)), PDO::PARAM_INT);
            $update_stmt->bindValue(':idFacturationFacture', htmlspecialchars(strip_tags($idFacturationFacture)), PDO::PARAM_INT);
            $update_stmt->bindValue(':idUserFacture', htmlspecialchars(strip_tags($idUserFacture)), PDO::PARAM_INT);
            $update_stmt->bindValue(':idPairementFacture', htmlspecialchars(strip_tags($idPairementFacture)), PDO::PARAM_INT);
            $update_stmt->bindValue(':idEtatCommandeFacture', htmlspecialchars(strip_tags($idEtatCommandeFacture)), PDO::PARAM_INT);
            $update_stmt->bindValue(':CommandeFacturecol', htmlspecialchars(strip_tags($CommandeFacturecol)), PDO::PARAM_STR);

            $update_stmt->bindValue(':numFacture', $data->numFacture, PDO::PARAM_INT);


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