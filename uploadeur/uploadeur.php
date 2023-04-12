<?php 
    if ($_POST['nvNomFichier'] != "")
    {
        $filename = $_POST['nvNomFichier'];
    }
    else
    {
        $filename = $_FILES['fichier']['name'];
    }

    $location = "../".$_POST['chemin']."/".$filename;

    if (move_uploaded_file($_FILES['fichier']['tmp_name'], $location))
    { 
        echo 'Success'; 
    } 
    else
    { 
        echo 'Failure'; 
    }
?>