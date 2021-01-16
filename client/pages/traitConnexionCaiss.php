<?php
    session_start(); 
    $dsn = 'mysql:dbname=bonvoyage;host=localhost';
    $user = 'root';
    $pswd = '';
    try {
        $db = new PDO($dsn, $user, $pswd);
      //  echo "Connection realise avec succes!!!!";
    } catch (PDOException $e) {
        die(' Erreur : '.$e -> getMessage());
    }

    // requete du  nombre total de messages sur le site web : 
    $req = $db -> prepare("SELECT * FROM caissiers WHERE identifiantCaiss = :a AND mdpCaiss = :b AND statutCaiss = :c");
    $req -> execute(array(
            'a'=> $_POST['idConnCaiss'],
            'b'=> $_POST['mdpConnCaiss'],
            'c'=> 1
        ));
    $compt = $req -> rowCount();

    $row = $req-> fetch();

    if ($compt >= 1) { 
        $_SESSION['pkCaiss'] = $row['numCaiss'];
        header('location:../client.php');
    } else { 
        $_SESSION['connCaissError'] = true;
        header('location:../../index.php');
    } 

  $req -> closeCursor();
?>