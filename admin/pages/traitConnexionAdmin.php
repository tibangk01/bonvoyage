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
    $req = $db -> prepare("SELECT * FROM responsable WHERE identifiantRes = :a AND mdpRes = :b");
    $req -> execute(array(
            'a'=> $_POST['idConnRes'],
            'b'=> $_POST['mdpConnRes']
        ));
    $compt = $req -> rowCount();

    $row = $req-> fetch();

    if ($compt == 1) { 
        $_SESSION['pkRes'] = $row['numRes'];
        header('location:private.php');
    } else { 
        $_SESSION['connUserError'] = true;
      header('location:../index.php');
    } 

  $req -> closeCursor();
?>