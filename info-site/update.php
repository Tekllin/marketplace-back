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

    try 
    {
        $fetch_post = "SELECT logoSite, logoEnteteSite, descriptionSite, emailSite, adresseSite, cpSite, villeSite, paysSite, numeroSite, copyright, qsnSite, facebookSite, instagramSite, linkdinSite FROM infosite";
        $fetch_stmt = $cnx->prepare($fetch_post);
        $fetch_stmt->execute();

        if ($fetch_stmt->rowCount() > 0) :
            $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);

            $logoSite = isset($data->logoSite) && !empty($data->logoSite) ? $data->logoSite : $row['logoSite'];
            $logoEnteteSite = isset($data->logoEnteteSite) && !empty($data->logoEnteteSite) ? $data->logoEnteteSite : $row['logoEnteteSite'];
            $descriptionSite = isset($data->descriptionSite) && !empty($data->descriptionSite) ? $data->descriptionSite : $row['descriptionSite'];
            $emailSite = isset($data->emailSite) && !empty($data->emailSite) ? $data->emailSite : $row['emailSite'];
            $adresseSite = isset($data->adresseSite) && !empty($data->adresseSite) ? $data->adresseSite : $row['adresseSite'];
            $cpSite = isset($data->cpSite) && !empty($data->cpSite) ? $data->cpSite : $row['cpSite'];
            $villeSite = isset($data->villeSite) && !empty($data->villeSite) ? $data->villeSite : $row['villeSite'];
            $paysSite = isset($data->paysSite) && !empty($data->paysSite) ? $data->paysSite : $row['paysSite'];
            $numeroSite = isset($data->numeroSite) && !empty($data->numeroSite) ? $data->numeroSite : $row['numeroSite'];
            $copyright = isset($data->copyright) && !empty($data->copyright) ? $data->copyright : $row['copyright'];
            $facebookSite = isset($data->facebookSite) && !empty($data->facebookSite) ? $data->facebookSite : $row['facebookSite'];
            $instagramSite = isset($data->instagramSite) && !empty($data->instagramSite) ? $data->instagramSite : $row['instagramSite'];
            $linkdinSite = isset($data->linkdinSite) && !empty($data->linkdinSite) ? $data->linkdinSite : $row['linkdinSite'];
            
           /* if (isset($data->qsnSite) && !empty($data->qsnSite) )
            {
                $qsnSite = htmlentities(htmlspecialchars(trim($data->qsnSite)));
            }*/

            $update_query = "UPDATE `infosite` 
            SET logoSite = :logoSite, 
            logoEnteteSite = :logoEnteteSite,
            descriptionSite = :descriptionSite,
            emailSite = :emailSite,
            adresseSite = :adresseSite,
            cpSite = :cpSite,
            villeSite = :villeSite,
            paysSite = :paysSite,
            numeroSite = :numeroSite,
            copyright = :copyright";

           /* if (isset($qsnSite))
            {
                $update_query = $update_query.",\r\n qsnSite = :qsnSite";
            }*/

            $update_query = $update_query.",\r\n facebookSite = :facebookSite,
            instagramSite = :instagramSite,
            linkdinSite = :linkdinSite";

            $update_stmt = $cnx->prepare($update_query);

            $update_stmt->bindValue(':logoSite', htmlspecialchars(strip_tags($logoSite)), PDO::PARAM_STR);
            $update_stmt->bindValue(':logoEnteteSite', htmlspecialchars(strip_tags($logoEnteteSite)), PDO::PARAM_STR);
            $update_stmt->bindValue(':descriptionSite', htmlspecialchars(strip_tags($descriptionSite)), PDO::PARAM_STR);
            $update_stmt->bindValue(':emailSite', htmlspecialchars(strip_tags($emailSite)), PDO::PARAM_STR);
            $update_stmt->bindValue(':adresseSite', htmlspecialchars(strip_tags($adresseSite)), PDO::PARAM_STR);
            $update_stmt->bindValue(':cpSite', htmlspecialchars(strip_tags($cpSite)), PDO::PARAM_STR);
            $update_stmt->bindValue(':villeSite', htmlspecialchars(strip_tags($villeSite)), PDO::PARAM_STR);
            $update_stmt->bindValue(':paysSite', htmlspecialchars(strip_tags($paysSite)), PDO::PARAM_STR);
            $update_stmt->bindValue(':numeroSite', htmlspecialchars(strip_tags($numeroSite)), PDO::PARAM_STR);
            $update_stmt->bindValue(':copyright', htmlspecialchars(strip_tags($copyright)), PDO::PARAM_STR);
            
            if (isset($qsnSite))
            {
                $update_stmt->bindValue(':qsnSite', $qsnSite, PDO::PARAM_STR);
            }

            $update_stmt->bindValue(':facebookSite', htmlspecialchars(strip_tags($facebookSite)), PDO::PARAM_STR);
            $update_stmt->bindValue(':instagramSite', htmlspecialchars(strip_tags($instagramSite)), PDO::PARAM_STR);
            $update_stmt->bindValue(':linkdinSite', htmlspecialchars(strip_tags($linkdinSite)), PDO::PARAM_STR);
            
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