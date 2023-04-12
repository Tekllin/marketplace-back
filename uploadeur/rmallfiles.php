<?php
    $chemin = "../../".$_POST['chemin'];

    $fichiers = glob($chemin.'/*');

    foreach($fichiers as $unFichier) 
    {
        if(is_file($unFichier)) 
        {
            unlink($unFichier);
        } 
    }
?>