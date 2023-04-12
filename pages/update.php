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

    if (!isset($data->idPage)) 
    {
        echo json_encode(['success' => 0, 'message' => 'Please enter correct tutoriel or actualite id.']);
        exit;
    }

    try 
    {
        $fetch_post = "SELECT idPage, titrePage, videoPage, contenuPage, resumePage, datePublication, dateModification, imgPreview, boolPage FROM pages WHERE idPage = :idPage";
        $fetch_stmt = $cnx->prepare($fetch_post);
        $fetch_stmt->bindValue(':idPage', $data->idPage, PDO::PARAM_INT);
        $fetch_stmt->execute();



        if ($fetch_stmt->rowCount() > 0) :
            $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);

            $titrePage = isset($data->titrePage) && !empty($data->titrePage) ? $data->titrePage : $row['titrePage'];

            if (isset($data->contenuPage) && !empty($data->contenuPage))
            {
                $contenuPage = htmlentities(htmlspecialchars(trim($data->contenuPage)));
            }

           $videoPage = isset($data->videoPage) && !empty($data->videoPage) ? $data->videoPage : $row['videoPage'];

            $resumePage = isset($data->resumePage) && !empty($data->resumePage) ? $data->resumePage : $row['resumePage'];
            $datePublication = isset($data->datePublication) && !empty($data->datePublication) ? $data->datePublication : $row['datePublication'];
            $dateModification = isset($data->dateModification) && !empty($data->dateModification) ? $data->dateModification : $row['dateModification'];
            $imgPreview = isset($data->imgPreview) && !empty($data->imgPreview) ? $data->imgPreview : $row['imgPreview'];
            $boolPage = isset($data->boolPage) && !empty($data->boolPage) ? $data->boolPage : $row['boolPage'];

            $update_query = "UPDATE `pages` 
            SET titrePage = :titrePage,\r\n";
                
                if (isset($contenuPage))
                {
                    $update_query = $update_query."contenuPage = :contenuPage,\r\n";
                }

            $update_query = $update_query."resumePage = :resumePage,  
            datePublication = :datePublication, 
            dateModification = :dateModification, 
	    videoPage = :videoPage, 
            imgPreview = :imgPreview, 
            boolPage = :boolPage
            WHERE idPage = :idPage";

            $update_stmt = $cnx->prepare($update_query);

            $update_stmt->bindValue(':titrePage', htmlspecialchars(strip_tags($titrePage)), PDO::PARAM_STR);
            
            if(isset($contenuPage))
            {
                $update_stmt->bindValue(':contenuPage', $contenuPage, PDO::PARAM_STR);
            }

            $update_stmt->bindValue(':resumePage', htmlspecialchars(strip_tags($resumePage)), PDO::PARAM_STR);
            $update_stmt->bindValue(':datePublication', htmlspecialchars(strip_tags($datePublication)), PDO::PARAM_STR);
            $update_stmt->bindValue(':dateModification', htmlspecialchars(strip_tags($dateModification)), PDO::PARAM_STR);
            $update_stmt->bindValue(':videoPage', htmlspecialchars(strip_tags($videoPage)), PDO::PARAM_STR);
            $update_stmt->bindValue(':imgPreview', htmlspecialchars(strip_tags($imgPreview)), PDO::PARAM_STR);
            $update_stmt->bindValue(':boolPage', htmlspecialchars(strip_tags($boolPage)), PDO::PARAM_INT);
    
            $update_stmt->bindValue(':idPage', $data->idPage, PDO::PARAM_INT);

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