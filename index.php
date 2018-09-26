<?php
require_once('config.php')
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <link rel="stylesheet" href="dist/style.min.css"/>
    <script src="dist/jstree.min.js"></script>
    <title></title>
    <script>
    </script>
</head>
<body>
<div style="margin-top: 100px;" class="container">
    <div class="row">
        <div class="col-6">
            <div id="strukturaDrzewa"></div>
            <button style="margin-top: 25px;" class="btn btn-sm" id="pokazWezly" onclick="rozwinDrzewo()">Rozwiń
                węzły
            </button>
            <hr>
            <form style="margin-top:25px " id="edycjaDrzewa" method="post">
                <div class="form-group">
                    <select id="idWezla" class="form-control" required>
                        <option value="" disabled selected>Wybierz wezel do edycji...</option>
                        <?php
                        $query = "Select * From struktura";
                        $results = queryDB($query);
                        foreach ($results as $result) {
                            ?>
                            <option value="<?php echo $result ['id'] ?>"><?php echo $result['text']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p> Przypmni do nowego węzła</p>
                        <div class="form-group">
                            <select id="idNowyWezl" class="form-control" required>
                                <option value="" disabled selected>Wybierz nowy wezel</option>
                                <?php
                                $query = "Select * From struktura";
                                $results = queryDB($query);
                                foreach ($results as $result) {
                                    ?>
                                    <option value="<?php echo $result ['id'] ?>"><?php echo $result['text']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="button" id="zamienWezlyButton" class="btn-sm btn-sm">Przymni do wybranego
                                węzła
                            </button>
                        </div>
                    </div>
                    <div class="col-6">
                        <p> Zmień nazwę wybranego dziecka</p>
                        <div class="form-group">
                            <input class="form-control" id="nowaNazwa" type="text" placeholder="Nazwa..." required>
                        </div>
                        <div class="form-group">

                            <button type="button" id="zmienNazweButton" class="btn-sm btn-secondary">Zmień nazwę
                                wybranego węzła
                            </button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <p> Usuń zaznaczone wezel</p>
                    <button type="button" id="usunWezelButton" class="btn-sm btn-danger">Usuń wybrany węzeł
                    </button>
                </div>


            </form>
        </div>
        <div class="col-6">
            <form id="formDodawanie" method="post">
                <h4>Dodawanie wezla</h4>
                <div class="form-group">
                    <label>Nazwa nowego wezla</label>
                    <input class="form-control" name="nazwaWezla" id="nazwaWezla" type="text" placeholder="Nazwa..."
                           required>
                </div>
                <div class="form-group">

                    <label>Wybierz nadrzedny wezel</label>
                    <select name="nadrzednyWezel" id="idNadrzenegoWezla" class="form-control" required>
                        <option value="" disabled selected>Wybierz rodzica</option>
                        <?php
                        $query = "Select * From struktura";
                        $results = queryDB($query);
                        foreach ($results as $result) {
                            ?>
                            <option value="<?php echo $result ['id'] ?>"><?php echo $result['text']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <button type="button" id="buttonAddWezel" class="btn-sm btn-dark">Dodaj dziecka</button>
                </div>
                <hr>
            </form>
        </div>
    </div>
</div>
</body>
</html>
<script type="text/javascript">

    //Załadowanie struktury drzewa

    $(document).ready(function () {
        //fill data to tree  with AJAX call
        $('#strukturaDrzewa').jstree({
            'plugins': ["wholerow"],
            'core': {
                'data': {
                    "url": "response.php",
                    "plugins": ["wholerow", "search", "sort"],
                    "dataType": "json" // needed only if you do not supply JSON headers
                }
            }
        })
    });

// Rozwinęcie/Zwinięcie wezłów
    function rozwinDrzewo() {
        var rozwiniete = false;
        if (rozwiniete === false) {
            rozwiniete = true;
            $("button#pokazWezly").text("Zwiń węzły");
            $("#strukturaDrzewa").jstree('open_all');
        } else {
            rozwiniete = false;
            $("#strukturaDrzewa").jstree('close_all');
            $("button#pokazWezly").text("Rozwiń węzły");
        }

    }
    // Dodanie nowych wezłow
    $(document).ready(function () {
        $('#buttonAddWezel').click(function () {
            var nazwaWezla = $('#idNadrzenegoWezla').val();
            var idRodzica = $('#idNadrzenegoWezla').val();
            if ((idRodzica !== null && idRodzica !== '')) {
                if ((nazwaWezla !== null && nazwaWezla !== '')) {
                    $.ajax({
                        type: "POST",
                        url: "dodajWezel.php",
                        data: $("#formDodawanie").serialize(),
                        success: function (data) {
                            alert(data);
                            window.location.reload();
                        }
                    });
                } else {
                    alert("Nie podano nazwy")
                }
            } else {
                alert("Nie wybrano rodzica")
            }
        });
    });
    $(document).ready(function () {
        $(document).on('click', '#usunWezelButton', function () {
            var id;
            if (confirm("Czy napewno chcesz usunąc rekord")) {
                id = $('#idWezla').val();
                if (id !== null && id !== '') {
                    $.ajax({
                        url: "usunWezel.php",
                        method: "Post",
                        data: {id: id},
                        success: function (data) {
                            alert(data);
                            location.reload();
                        }
                    });
                }
                else {
                    alert("Nie zaznaczono rekordu");
                }
            }
        });
    });

    $(document).ready(function () {
        $('#zmienNazweButton').click(function () {
            var nazwaWezla = $('#nowaNazwa').val();
            var idWezla = $('#idWezla').val();
            if ((idWezla !== null && idWezla !== '')) {
                if ((nazwaWezla !== null && nazwaWezla !== '')) {
                    $.ajax({
                        type: "POST",
                        url: "aktualizacjaNazwy.php",
                        data: {nazwaWezla: nazwaWezla, idWezla: idWezla},
                        success: function (data) {
                            alert(data);
                            //location.reload();
                            //$("#osoba").load("add_osoba.php");
                            window.location.reload();
                        }
                    });
                } else {
                    alert("Nie podano nowej nazwy")
                }
            } else {
                alert("Nie wybrano węzła do zmiany")
            }
        });
    });
    $(document).ready(function () {
        $('#zamienWezlyButton').click(function () {
            var idNowegoNadrzenegoWezla = $('#idNowyWezl').val();
            var idWezla = $('#idWezla').val();
            if ((idNowegoNadrzenegoWezla !== null && idNowegoNadrzenegoWezla !== '')) {
                if ((idWezla !== null && idWezla !== '')) {
                    $.ajax({
                        type: "POST",
                        url: "aktualizacjaWezel.php",
                        data: {idNowegoNadrzenegoWezla: idNowegoNadrzenegoWezla, idWezla: idWezla},
                        success: function (data) {
                            alert(data);
                            //location.reload();
                            //$("#osoba").load("add_osoba.php");
                            window.location.reload();
                        }
                    });
                } else {
                    alert("Nie podano nowej nazwy")
                }
            } else {
                alert("Nie wybrano węzła do zmiany")
            }
        });
    });
</script>
