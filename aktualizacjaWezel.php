<?php
/**
 * Created by PhpStorm.
 * User: Yser
 * Date: 25.09.2018
 * Time: 22:16
 */
require_once('config.php');
$flaga = true;
if (!isset($_POST['idNowegoNadrzenegoWezla'])) {
    $flaga = false;
}
if (!isset($_POST['idWezla'])) {
    $flaga = false;
}
if ($flaga === true) {
    $idNowegoNadrzenegoWezla = $_POST['idNowegoNadrzenegoWezla'];
    $idWeza = $_POST['idWezla'];
    updateNewWezel($idWeza, $idNowegoNadrzenegoWezla);
}