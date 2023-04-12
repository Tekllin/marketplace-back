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

    if (!isset($data->idCategorie)) 
    {
        echo json_encode(['success' => 0, 'message' => 'Please enter correct categorie id.']);
        exit;
    }

    try 
    {
        $fetch_post = "SELECT idCategorie, libelleCategorie, couleurCategorie FROM cattutoactu WHERE idCategorie = :idCategorie";
        $fetch_stmt = $cnx->prepare($fetch_post);
        $fetch_stmt->bindValue(':idCategorie', $data->idCategorie, PDO::PARAM_INT);
        $fetch_stmt->execute();

        if ($fetch_stmt->rowCount() > 0) :
            $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
            $libelleCategorie = isset($data->libelleCategorie) && !empty($data->libelleCategorie) ? $data->libelleCategorie : $row['libelleCategorie'];
            $couleurCategorie = isset($data->couleurCategorie) && !empty($data->couleurCategorie) ? $data->couleurCategorie : $row['couleurCategorie'];
            
            $update_query = "UPDATE `cattutoactu` 
            SET libelleCategorie = :libelleCategorie,
            couleurCategorie = :couleurCategorie
            WHERE idCategorie = :idCategorie";

            $update_stmt = $cnx->prepare($update_query);

            $update_stmt->bindValue(':libelleCategorie', htmlspecialchars(strip_tags($libelleCategorie)), PDO::PARAM_STR);
            $update_stmt->bindValue(':couleurCategorie', htmlspecialchars(strip_tags($couleurCategorie)), PDO::PARAM_STR);

            $update_stmt->bindValue(':idCategorie', $data->idCategorie, PDO::PARAM_INT);


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