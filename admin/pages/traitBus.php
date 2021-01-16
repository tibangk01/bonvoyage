<?php
    session_start(); 

    $dsn = 'mysql:dbname=bonvoyage;host=localhost'; 
    $user = 'root';
    $pswd = '';
    try {
        $db = new PDO($dsn, $user, $pswd);
       // echo "Connection realise avec succes!!!!";
    } catch (PDOException $e) {
        die(' Erreur : '.$e -> getMessage());
    }

    function skip_accents( $str, $charset='utf-8' ) { // fonction pour enléver les accents d'une chaine en php;

        $str = htmlentities( $str, ENT_NOQUOTES, $charset ); 

        $str = preg_replace( '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str );

        $str = preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $str );

        $str = preg_replace( '#&[^;]+;#', '', $str );
        return $str;
    }

    # création des bus de voyages : 
    /**
        # a => marque saisie
        # b => nb places saisie
    */
    // echo $_POST['marqueBus'];

     if (isset($_POST['marqueBus']) && isset($_POST['nbPlacesBus']) && ($_POST['modificationB'] == 0)) {
        # reformatage des éléments du formulaire :
         $a = strtoupper(trim(skip_accents($_POST['marqueBus'],'utf-8')));
         $b = trim($_POST['nbPlacesBus']);

         # vérifie si a existe dans la base : 
        $req = $db -> prepare("SELECT * FROM bus where marquebus = :a");
        $req -> execute(array('a'=> $a));
        $marqueExiste = $req -> rowCount();
            # si oui on verifie le nombre de places sinon on insere :
            if ($marqueExiste >= 1) {
                # verification de l'existance de a et b ensemble :
                $req = $db -> prepare("SELECT * FROM bus where marquebus = :a and nbPlacesBus = :b");
                $req -> execute(array('a'=> $a, 'b' => $b));
                $marqueEtNbplacesExiste = $req -> rowCount();
                # si a et b existent ensemble alors message d'erreur sinon on insere :
                if ($marqueEtNbplacesExiste >= 1) {
                    # message ce bus est déjas enrégistré :
                     $_SESSION['duplicatedBFound'] = true;
                     header('location:gestionBus.php');
                } else {
                    # insertion :
                    $current_date_time = date("Y-m-d H:i:s");
                    $req = $db -> prepare("INSERT INTO bus(marqueBus, nbPlacesBus, dateModifBus) values(:a, :b, :c)");
                    $req -> execute(array('a'=> $a, 'b' => $b, 'c' => $current_date_time));
                    # message de création reussi :
                    $_SESSION['BCreated'] = true;
                    header('location:gestionBus.php');
                }
                
            } else {
                # insertion :
                $current_date_time = date("Y-m-d H:i:s");
                $req = $db -> prepare("INSERT INTO bus(marqueBus, nbPlacesBus, dateModifBus) values(:a, :b, :c)");
                $req -> execute(array('a'=> $a, 'b' => $b, 'c' => $current_date_time));
                # message de création reussi :
                $_SESSION['BCreated'] = true;
                header('location:gestionBus.php');
            }
            
     }
/**
    ## modification des bus 
*/
    /**
     # première étape : on reçoit le numéro de la ville on selectionne les elements qu'on renvoi vers lapage principale avec des variables de session 
    */
    if (isset($_GET['idUpdateB'])) {
            $req = $db -> prepare("SELECT * from bus where statutBus = :a and numBus = :b ");
            $req -> execute(array(
                                    'a' => 1,
                                    'b' => $_GET['idUpdateB']
                                 ));
            $row = $req -> fetch();

            $_SESSION['UnumB'] = $row['numBus'];
            $_SESSION['UmarqueB'] = $row['marqueBus'];
            $_SESSION['UnbPlacesB'] =$row['nbPlacesBus'];
           // echo $_SESSION['UnumTA'].' - '.$_SESSION['UvaleurTA']; exit();    
            header('location:gestionBus.php');
        }

    /**
     # première étape : on reçoit le numéro de la ville on selectionne les elements qu'on renvoi vers lapage principale avec des variables de session 
    */

    /**
     # deusième épate : modification des informations : 
    */
    if (isset($_POST['marqueBus']) && isset($_POST['nbPlacesBus']) && ($_POST['modificationB'] == 1) && isset($_POST['idModificationB'])) {
         /**
            # a => marque saisie
            # b => nb places saisie
            # c => marque de la bd
            # d => nb place de la bd
        */
          // echo 'hello moba ! '; exit();
        # reformatage des informations saisies par l' admin : 
        $a = strtoupper(trim(skip_accents($_POST['marqueBus'],'utf-8')));
        $b = trim($_POST['nbPlacesBus']);
        # selection des informations de la bd pour le numéro du bus :
        $req = $db -> prepare("SELECT * FROM bus where numBus = :a");
        $req -> execute(array('a'=>$_POST['idModificationB']));
        # stockage des informations de la db dans des variables c et d ;
        $row = $req -> fetch();
        $c = $row['marquebus'];
        $d = $row['nbPlacesBus'];

        # on verifie si a = c et b = d. si oui alors message erreur sinon on fait la verification suivante :
        if ($a === $c && $b === $d) {
            # message bus existe :
            $_SESSION['duplicatedBFound'] = true;
            header('location:gestionBus.php');
        } else {
            # on verifie si $a = $c et $b <> $d. si oui  message duplication. sinon on fait la verif suivante :
            if ($a === $c && $b !== $d) {
                # on verifie si a et b existe dans la base ens :
                 $req = $db -> prepare("SELECT * FROM bus where statutBus = :a and marquebus =:b and nbPlacesBus = :c");
                 $req -> execute(array('a'=>1, 'b'=> $a, 'c' => $b));

                 $marqueEtNbplacesExiste = $req -> rowCount();
                 # si oui messsage duplication sinon insertion : 
                 if ($marqueEtNbplacesExiste >= 1) {
                    # message duplication
                    $_SESSION['duplicatedBFound'] = true;
                    header('location:gestionBus.php');
                 } else {
                    # maj
                    $current_date_time = date("Y-m-d H:i:s");
                    $req = $db -> prepare("UPDATE bus set marquebus =:a, nbPlacesBus =:b, dateModifBus =:c where numBus =:d");
                    $req -> execute(array('a'=> $a, 'b' => $b, 'c' => $current_date_time, 'd' => $_POST['idModificationB']));
                    # message de création reussi :
                    $_SESSION['updateBSuccess'] = true;
                    header('location:gestionBus.php');
                 }
            } else {
                # on verifie si a existe dans la db.
                $req = $db -> prepare("SELECT * FROM bus where statutBus = :a and marquebus =:b");
                $req -> execute(array('a'=>1, 'b'=> $a));
                $marqueExiste = $req -> rowCount();

                if ($marqueExiste >= 1) {
                    # on verifie si a et b exitent ens.
                    $req = $db -> prepare("SELECT * FROM bus where statutBus = :a and marquebus =:b and nbPlacesBus = :c");
                    $req -> execute(array('a'=>1, 'b'=> $a, 'c' => $b));
                    $marqueEtNbplacesExiste = $req -> rowCount();

                    if ($marqueEtNbplacesExiste >=1) {
                        # message boublons :
                        $_SESSION['duplicatedBFound'] = true;
                        header('location:gestionBus.php');
                    } else {
                        # insertion:
                        $current_date_time = date("Y-m-d H:i:s");
                        $req = $db -> prepare("UPDATE bus set marquebus =:a, nbPlacesBus =:b, dateModifBus =:c where numBus =:d");
                        $req -> execute(array('a'=> $a, 'b' => $b, 'c' => $current_date_time, 'd' => $_POST['idModificationB']));
                        # message de création reussi :
                        $_SESSION['updateBSuccess'] = true;
                        header('location:gestionBus.php');
                    }

                } else {
                    # on insere :
                    $current_date_time = date("Y-m-d H:i:s");
                    $req = $db -> prepare("UPDATE bus set marquebus =:a, nbPlacesBus =:b, dateModifBus =:c where numBus =:d");
                    $req -> execute(array('a'=> $a, 'b' => $b, 'c' => $current_date_time, 'd' => $_POST['idModificationB']));
                    # message de création reussi :
                    $_SESSION['updateBSuccess'] = true;
                    header('location:gestionBus.php');
                }  
            } 
        }    
     }
     /**
     # deuxième épate : modification des informations : 
    */

/**
    ## modification des bus 
*/



/**
   ## suppression des bus :
*/
 if (isset($_GET['idSupprimerB'])) {
            $req = $db -> prepare("UPDATE bus SET statutBus = 0 WHERE numBus = :a");
            $req -> execute(array(
                            'a' => $_GET['idSupprimerB']
                           ));
            $_SESSION['BDeleted'] = true ;
             header('location:gestionBus.php');
             $req -> closeCursor();
        }
/**
   ## suppression des bus :
*/