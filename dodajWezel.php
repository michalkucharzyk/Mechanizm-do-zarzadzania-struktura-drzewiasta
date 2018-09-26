<?php
/**
 * Created by PhpStorm.
 * User: Yser
 * Date: 25.09.2018
 * Time: 14:06
 */
require_once('config.php');

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
if (!isset($_POST['nadrzednyWezel']) === true) {
    $flaga = false;
    echo "Nie wybrano rodzica ";
} else {
    $idWezlaNadrzednego =filter_var($_POST['nadrzednyWezel'], FILTER_SANITIZE_STRING);

}
if ($flaga === true) {
    $add_results = insertDB($nazwaWezla, $idWezlaNadrzednego);
    if ($add_results === true) {
        echo "Dodan do bazy";
    } else {
        echo "Nie poprawne dane \n\r";
    }
}








