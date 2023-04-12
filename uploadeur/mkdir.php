<?php
    $chemin = "../../".$_POST['chemin']."/";
    $repertoire = $_POST['nomRep'];

    mkdir($chemin.$repertoire);
?>