<?php
/**
 * Created by PhpStorm.
 * User: Yser
 * Date: 25.09.2018
 * Time: 22:16
 */
require_once('config.php');
$idNowegoNadrzenegoWezla = $_POST['idNowegoNadrzenegoWezla'];
$idWeza = $_POST['idWezla'];

updateNewWezel($idWeza, $idNowegoNadrzenegoWezla);