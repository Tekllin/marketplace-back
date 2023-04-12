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

    if (!isset($data->numFactureCommande) && !isset($data->idProduitCommande)) 
    {
        echo json_encode(['success' => 0, 'message' => 'Please enter correct numFactureCommande and idProduitCommande.']);
        exit;
    }

    try 
    {
        $fetch_post = "SELECT numFactureCommande, idProduitCommande, qtProduitCommande FROM commander WHERE numFactureCommande = :numFactureCommande AND idProduitCommande = :idProduitCommande";
        $fetch_stmt = $cnx->prepare($fetch_post);
        $fetch_stmt->bindValue(':numFactureCommande', $data->numFactureCommande, PDO::PARAM_INT);
        $fetch_stmt->bindValue(':idProduitCommande', $data->idProduitCommande, PDO::PARAM_INT);
        $fetch_stmt->execute();

        if ($fetch_stmt->rowCount() > 0) :
            $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
            $numFactureCommande = $row['numFactureCommande'];
            $idProduitCommande = $row['idProduitCommande'];
            $numFactureCommandeModif = isset($data->numFactureCommandeModif) ? $data->numFactureCommandeModif : $row['numFactureCommande'];
            $idProduitCommandeModif = isset($data->idProduitCommandeModif) ? $data->idProduitCommandeModif : $row['idProduitCommande'];
            $qtProduitCommande = isset($data->qtProduitCommande) ? $data->qtProduitCommande : $row['qtProduitCommande'];

            $update_query = "UPDATE `commander` 
            SET numFactureCommande = :numFactureCommandeModif, 
            idProduitCommande = :idProduitCommandeModif,
            qtProduitCommande = :qtProduitCommande
            WHERE  numFactureCommande = :numFactureCommande
            AND idProduitCommande = :idProduitCommande";

            $update_stmt = $cnx->prepare($update_query);

            $update_stmt->bindValue(':numFactureCommandeModif', htmlspecialchars(strip_tags($numFactureCommandeModif)), PDO::PARAM_INT);
            $update_stmt->bindValue(':idProduitCommandeModif', htmlspecialchars(strip_tags($idProduitCommandeModif)), PDO::PARAM_INT);
            $update_stmt->bindValue(':qtProduitCommande', htmlspecialchars(strip_tags($qtProduitCommande)), PDO::PARAM_INT);

            $update_stmt->bindValue(':numFactureCommande', htmlspecialchars(strip_tags($numFactureCommande)), PDO::PARAM_INT);
            $update_stmt->bindValue(':idProduitCommande', htmlspecialchars(strip_tags($idProduitCommande)), PDO::PARAM_INT);

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