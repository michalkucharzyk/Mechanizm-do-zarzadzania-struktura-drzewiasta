<?php
/**
 * Created by PhpStorm.
 * User: Yser
 * Date: 25.09.2018
 * Time: 19:55
 */
$flaga = true;
require_once('config.php');
if (isset($_POST['id'])) {
    $id = $_POST['id'];
}


$id = (int)$id;

$query = "Select parent_id from struktura where id='$id'";
$result = queryDB($query);
$result->execute();
$row = $result->fetch();
if ($row['parent_id'] === 0) {
    $flaga = false;
    echo "Nie można usnąc głównego węzła ";
}
if (!is_int($id)) {
    $flaga = false;
}

if ($flaga === true) {
    $delete = DeleteDB($id);
    echo "Usunięto pomyślnie";
}

