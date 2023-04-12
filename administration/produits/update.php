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

    if (!isset($data->idProduit)) 
    {
        echo json_encode(['success' => 0, 'message' => 'Please enter correct produit id.']);
        exit;
    }

    try 
    {
        $fetch_post = "SELECT idProduit, idAnnonceurProduit, libelleProduit, qtStock, descriptionProduit, idCategorieProduit, prixUnitHT FROM produits WHERE idProduit = :idProduit";
        $fetch_stmt = $cnx->prepare($fetch_post);
        $fetch_stmt->bindValue(':idProduit', $data->idProduit, PDO::PARAM_INT);
        $fetch_stmt->execute();

        if ($fetch_stmt->rowCount() > 0) :
            $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
            $idAnnonceurProduit = isset($data->idAnnonceurProduit) && !empty($data->idAnnonceurProduit)? $data->idAnnonceurProduit : $row['idAnnonceurProduit'];
            $libelleProduit = isset($data->libelleProduit) && !empty($data->libelleProduit) ? $data->libelleProduit : $row['libelleProduit'];
            $qtStock = isset($data->qtStock) && !empty($data->qtStock) ? $data->qtStock : $row['qtStock'];
            $descriptionProduit = isset($data->descriptionProduit) && !empty($data->descriptionProduit) ? $data->descriptionProduit : $row['descriptionProduit'];
            $idCategorieProduit = isset($data->idCategorieProduit) && !empty($data->idCategorieProduit) ? $data->idCategorieProduit : $row['idCategorieProduit'];
            $prixUnitHT = isset($data->prixUnitHT) && !empty($data->prixUnitHT) ? $data->prixUnitHT : $row['prixUnitHT'];

            $update_query = "UPDATE `produits` 
            SET idAnnonceurProduit = :idAnnonceurProduit, 
            libelleProduit = :libelleProduit,
            qtStock = :qtStock,
            descriptionProduit = :descriptionProduit,
            idCategorieProduit = :idCategorieProduit,
            prixUnitHT = :prixUnitHT
            WHERE idProduit = :idProduit";

            $update_stmt = $cnx->prepare($update_query);

            $update_stmt->bindValue(':idAnnonceurProduit', htmlspecialchars(strip_tags($idAnnonceurProduit)), PDO::PARAM_INT);
            $update_stmt->bindValue(':libelleProduit', htmlspecialchars(strip_tags($libelleProduit)), PDO::PARAM_STR);
            $update_stmt->bindValue(':qtStock', htmlspecialchars(strip_tags($qtStock)), PDO::PARAM_INT);
            $update_stmt->bindValue(':descriptionProduit', htmlspecialchars(strip_tags($descriptionProduit)), PDO::PARAM_STR);
            $update_stmt->bindValue(':idCategorieProduit', htmlspecialchars(strip_tags($idCategorieProduit)), PDO::PARAM_INT);
            $update_stmt->bindValue(':prixUnitHT', htmlspecialchars(strip_tags($prixUnitHT)), PDO::PARAM_STR);

            $update_stmt->bindValue(':idProduit', $data->idProduit, PDO::PARAM_INT);


            if ($update_stmt->execute()) 
            {
                echo json_encode([
                    'success' => 1,
                    'message' => 'Record updated successfully'
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