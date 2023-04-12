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

    if (!isset($data->idActuTuto)) 
    {
        echo json_encode(['success' => 0, 'message' => 'Please enter correct tutoriel or actualite id.']);
        exit;
    }

    try 
    {
        $fetch_post = "SELECT idActuTuto, titreActuTuto, videoActuTuto, contenuActuTuto, resumeActuTuto, datePublication, dateModification, imgPreview, boolActuTuto, idCatActuTuto FROM tutoactu WHERE idActuTuto = :idActuTuto";
        $fetch_stmt = $cnx->prepare($fetch_post);
        $fetch_stmt->bindValue(':idActuTuto', $data->idActuTuto, PDO::PARAM_INT);
        $fetch_stmt->execute();



        if ($fetch_stmt->rowCount() > 0) :
            $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);

            $titreActuTuto = isset($data->titreActuTuto) && !empty($data->titreActuTuto) ? $data->titreActuTuto : $row['titreActuTuto'];

            if (isset($data->contenuActuTuto) && !empty($data->contenuActuTuto))
            {
                $contenuActuTuto = htmlentities(htmlspecialchars(trim($data->contenuActuTuto)));
            }

           $videoActuTuto = isset($data->videoActuTuto) && !empty($data->videoActuTuto) ? $data->videoActuTuto : $row['videoActuTuto'];

            $resumeActuTuto = isset($data->resumeActuTuto) && !empty($data->resumeActuTuto) ? $data->resumeActuTuto : $row['resumeActuTuto'];
            $datePublication = isset($data->datePublication) && !empty($data->datePublication) ? $data->datePublication : $row['datePublication'];
            $dateModification = isset($data->dateModification) && !empty($data->dateModification) ? $data->dateModification : $row['dateModification'];
            $imgPreview = isset($data->imgPreview) && !empty($data->imgPreview) ? $data->imgPreview : $row['imgPreview'];
            $boolActuTuto = isset($data->boolActuTuto) && !empty($data->boolActuTuto) ? $data->boolActuTuto : $row['boolActuTuto'];
            $idCatActuTuto = isset($data->idCatActuTuto) && !empty($data->idCatActuTuto) ? $data->idCatActuTuto : $row['idCatActuTuto'];

            $update_query = "UPDATE `tutoactu` 
            SET titreActuTuto = :titreActuTuto,\r\n";
                
                if (isset($contenuActuTuto))
                {
                    $update_query = $update_query."contenuActuTuto = :contenuActuTuto,\r\n";
                }

            $update_query = $update_query."resumeActuTuto = :resumeActuTuto,  
            datePublication = :datePublication, 
            dateModification = :dateModification, 
	    videoActuTuto = :videoActuTuto, 
            imgPreview = :imgPreview, 
            boolActuTuto = :boolActuTuto,
            idCatActuTuto = :idCatActuTuto
            WHERE idActuTuto = :idActuTuto";

            $update_stmt = $cnx->prepare($update_query);

            $update_stmt->bindValue(':titreActuTuto', htmlspecialchars(strip_tags($titreActuTuto)), PDO::PARAM_STR);
            
            if(isset($contenuActuTuto))
            {
                $update_stmt->bindValue(':contenuActuTuto', $contenuActuTuto, PDO::PARAM_STR);
            }

            $update_stmt->bindValue(':resumeActuTuto', htmlspecialchars(strip_tags($resumeActuTuto)), PDO::PARAM_STR);
            $update_stmt->bindValue(':datePublication', htmlspecialchars(strip_tags($datePublication)), PDO::PARAM_STR);
            $update_stmt->bindValue(':dateModification', htmlspecialchars(strip_tags($dateModification)), PDO::PARAM_STR);
            $update_stmt->bindValue(':videoActuTuto', htmlspecialchars(strip_tags($videoActuTuto)), PDO::PARAM_STR);
            $update_stmt->bindValue(':imgPreview', htmlspecialchars(strip_tags($imgPreview)), PDO::PARAM_STR);
            $update_stmt->bindValue(':boolActuTuto', htmlspecialchars(strip_tags($boolActuTuto)), PDO::PARAM_INT);
            $update_stmt->bindValue(':idCatActuTuto', htmlspecialchars(strip_tags($idCatActuTuto)), PDO::PARAM_INT);
    
            $update_stmt->bindValue(':idActuTuto', $data->idActuTuto, PDO::PARAM_INT);

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