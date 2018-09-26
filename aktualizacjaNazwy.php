<?php
/**
 * Created by PhpStorm.
 * User: Yser
 * Date: 25.09.2018
 * Time: 21:32
 */
require_once ('config.php');
$flaga = true;

if (!isset($_POST['nazwaWezla']) == true) {
    $flaga = false;
    echo "Nazwa węzła nie może pyć puste \n\r ";
} else {
    $nazwaWezla =filter_var($_POST['nazwaWezla'], FILTER_SANITIZE_STRING);
}
if (empty($nazwaWezla) === true) {
    $flaga = false;
    echo " Pole nazwa wezla nie może być puste \n\r";
}

if (!isset($_POST['idWezla']) === true) {
    $flaga = false;
    echo "Nie wybrano rodzica ";
} else {
    $idRodzica =filter_var($_POST['idWezla'], FILTER_SANITIZE_STRING);

}

if ($flaga === true) {
    $add_results = updateNameDB($nazwaWezla, $idRodzica);
    if ($add_results === true) {
        echo "Nazwa została zaktualizowana ";
    } else {
        echo "Nie poprawne dane \n\r";
    }
}
