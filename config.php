<?php
/**
 * Created by PhpStorm.
 * User: Yser
 * Date: 24.09.2018
 * Time: 16:57
 */


$tableName = 'struktura';

function connectDB()
{
    $serverName = 'localhost';
    $databaseName = 'struktura';
    $userName = 'root';
    $password = '';

    try {
        $db = new PDO('mysql:host=' . $serverName . ';dbname=' . $databaseName, $userName, $password);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $ex) {
        print "Błąd połączenia z bazą!: " . $ex->getMessage() . "<br/>";
        die();
    }

}

function closeConnect($db)
{
    $db = null;
}

function queryDB($query)
{
    $db = connectDB();
    try {
        $resluts = $db->query($query);
        return $resluts;
        closeConnect($db);
    } catch (PDOException $ex) {
        echo "Nie można wykonać zapytania";
    } catch (Exception $e) {
        echo "Błąd" . $e->getMessage();
    }
}

function insertDB($nazwaWezla, $idRodzica)
{
    $db = connectDB();
    try {
        $preparedStatement = $db->prepare("INSERT INTO `struktura` (`id`,`parent_id`,`text`) VALUES (NULL,:wezel,:rodzic)");
        $preparedStatement->bindValue(':wezel', $idRodzica, PDO::PARAM_STR);
        $preparedStatement->bindValue(':rodzic', $nazwaWezla, PDO::PARAM_STR);
        $preparedStatement->execute();
        // var_dump($preparedStatement->queryString);
        return true;
        closeConnect($db);
    } catch (PDOException $ex) {
        echo "Nie można wykonać zapytania ";
        return false;
    } catch (Exception $e) {
        echo "Błąd" . $e->getMessage();
        return false;
    }
}

;

function DeleteDB($id)
{
    $db = connectDB();
    try {
        $query = "SELECT * FROM struktura WHERE parent_id='$id'";
        $results = queryDB($query);
        while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
            DeleteDB($row['id']);
        }
        $query = "DELETE FROM struktura WHERE id='$id';";
        queryDB($query);
        return true;
        closeConnect($db);
    } catch (PDOException $ex) {
        echo "Nie można wykonać zapytania ";
        return false;
    } catch (Exception $e) {
        echo "Błąd" . $e->getMessage();
        return false;
    }
}

function updateNameDB($newName, $idWezla)
{
    $db = connectDB();
    try {
        $query = "Update struktura set text='$newName' where id='$idWezla'";
        $db->query($query);
        return true;
        closeConnect($db);
    } catch (PDOException $ex) {
        echo "Nie można wykonać zapytania ";
        return false;
    } catch (Exception $e) {
        echo "Błąd" . $e->getMessage();
        return false;
    }
}

function updateNewWezel($idWezla, $idNowegoWezla)
{
    if ($idWezla === 1) {
        echo "Nie można przenieś węzła nadrzędnego ";
    } else {
        $db = connectDB();
        $query = "SELECT id, parent_id FROM struktura WHERE id = '$idWezla';";
        $result = queryDB($query);
        $result->execute();
        $number_of_rows = $result->fetchAll();
        $number_of_rows=count($number_of_rows);
        if ($number_of_rows > 0) {
            $result = queryDB($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $idWezlaNadrzednego = $row['parent_id'];
                if ($idWezla === $idNowegoWezla) {
                    echo "Wybrano te same wezly";
                } elseif ($idWezlaNadrzednego === $idNowegoWezla) {
                    echo "Wezel nalezy już do tego wezla";
                } else {
                  $query = "SELECT GROUP_CONCAT(lv SEPARATOR ',') AS podrzedne FROM (SELECT @pv:=(SELECT GROUP_CONCAT(id SEPARATOR ',')" .
                        " FROM struktura WHERE parent_id IN (@pv)) AS lv FROM struktura" .
                        " JOIN (SELECT @pv:={$idWezla})tmp WHERE parent_id IN (@pv)) a;";
                    $result = queryDB($query);
                    $result->execute();
                    $number_of_rows = $result->fetchAll();
                  //r  var_dump($number_of_rows);
                    $number_of_rows=count($number_of_rows);
                   // echo $number_of_rows;
                    if ($number_of_rows> 0) {
                        $result = queryDB($query);
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            break;
                        }
                        $podrzedneWezly = $row['podrzedne'];
                        $podrzedneWezlyTablic = explode(",", $podrzedneWezly);
                        if (in_array($idNowegoWezla, $podrzedneWezlyTablic)) {
                            echo "Nie można przenieś wezła niżej w tej samej strukturze";
                        } else {
                            $query = "UPDATE struktura SET parent_id = '$idNowegoWezla' WHERE id = '$idWezla'";
                            $result = queryDB($query);
                            if ($result) {
                                echo "Węzeł został przeniesiony";
                            } else {
                                echo "Wystąpił błąd podczas przenoszenia węzła";
                            }
                        }
                    } else {
                        $query = "UPDATE struktura SET parent_id = '$idNowegoWezla' WHERE id = '$idWezla'";
                        $result = queryDB($query);
                        if ($result) {
                            echo "Węzeł został przeniesiony";
                        } else {
                            echo "Wystąpił błąd podczas przenoszenia węzła";
                        }
                    }
                //    $result = queryDB($query);
                }
                break;
            }
        } else {
             echo "Wystąpił błąd podczas przenoszenia węzła";
        }
    }
}




