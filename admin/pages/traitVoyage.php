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

    function skip_accents( $str, $charset='utf-8' ) { // fonction pour enléver les accents d'une chaine en php;

        $str = htmlentities( $str, ENT_NOQUOTES, $charset ); 

        $str = preg_replace( '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str );

        $str = preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $str );

        $str = preg_replace( '#&[^;]+;#', '', $str );
        return $str;
    }

    # création des voyages : 
        /**
          # a => trajets
          # b => tarifs
          # c => bus
          # d => date
          # e => trajets db
          # f => tarifs db
          # g => bus db
        */
    # requere de selection du nombre de places du bus :
        $stmt ="SELECT nbPlacesBus from bus where numBus = :a";
        $req = $db -> prepare($stmt);
        $req -> execute(array(
                                'a' => $_POST['valeurBV']
                            ));
        $row = $req -> fetch();
        $bus = $row['nbPlacesBus']; 
        // echo $bus ; exit();

     if (isset($_POST['valeurTRV']) && isset($_POST['modificationV']) && ($_POST['modificationV'] == 0)) {
            # stockage des informations dans des variables :
            $a = $_POST['valeurTRV'];
            $b = $_POST['valeurTA'];
            $c = $_POST['valeurBV'];
            $d = $_POST['valeurDV'];
            $current_date = date("Y-m-d");

            # si la d < current_date alors : message erreur date
            if ($d <= $current_date) {
                # message date dépassée :
                $_SESSION['DVDepassee'] = true;
                header('location:gestionVoyages.php');
            } else {
                # verif si date et trajet existe ens actices:
                $stmt ="SELECT * from voyages where dateVoyage = :a and numTrajet =:b and statutVoyage = :c";
                $current_date = date("Y-m-d");
                $req = $db -> prepare($stmt);
                $req -> execute(array(
                                        'a' =>$d,
                                        'b' =>$a,
                                        'c' =>1
                                    ));
                $dateTrajetExiste = $req -> rowCount();
                # si oui alors msg voyage existe sinon insertion :
                if ($dateTrajetExiste >= 1) {
                    # msg doublon:
                    $_SESSION['duplicatedVoyageFound'] = true;
                    header('location:gestionVoyages.php');
                } else {
                    # insertion:
                    $stmt ="INSERT INTO voyages(dateVoyage, dateModifVoyage, numRes, numTrajet, numBus, numTarif,dateCreation, nbPlacesDispo)
                            VALUES(:a,:b,:c,:d,:e,:f,:g,:h)";
                    $current_date_time = date("Y-m-d H:i:s");
                    $req = $db -> prepare($stmt);
                    $req -> execute(array(
                                            'a' =>$d,
                                            'b' =>$current_date_time,
                                            'c' =>$_SESSION['pkRes'],
                                            'd' =>$a,
                                            'e' =>$c,
                                            'f' =>$b,
                                            'g' =>$current_date_time,
                                            'h' =>$bus
                                        ));
                    $_SESSION['voyageCreated'] = true;
                    header('location:gestionVoyages.php');
                } 
            }    
    }
    # création des voyages : 

    # modification voyages(étape un):
     if (isset($_GET['idUpdateVoyage'])) {
            $req = $db -> prepare("SELECT * from voyages where numVoyage = :a ");
            $req -> execute(array(
                                    'a' => $_GET['idUpdateVoyage']
                                 ));
            $row = $req -> fetch();
            $_SESSION['UnumVoyage'] = $row['numVoyage'];
            $_SESSION['UnumTrajet'] = $row['numTrajet'];
            $_SESSION['UnumTarif'] = $row['numTarif'];
            $_SESSION['UnumBus'] =$row['numBus'];
            $_SESSION['UnumDate'] =$row['dateVoyage'];
           // echo $_SESSION['UnumTrajet'].' - '.$_SESSION['UnumTarif'].' '.$_SESSION['UnumDate']; exit();    
            header('location:gestionVoyages.php');
        }
    # modification voyages.

    # modification voyages(étape deux):
    if (isset($_POST['valeurTRV']) && ($_POST['modificationV'] == 1) && isset($_POST['idModificationV'])) {
         /**
            # a => trajet
            # b => tarif
            # c => bus
            # d => date
            # e => trajet db
            # f => tarif db
            # g => bus db
            # h => date creation db
            # i => date modif db
            # j => idVoyage
        */
        ## requete de selection des éléments de la base de données :
            $req = $db -> prepare("SELECT * from voyages where statutVoyage = :a and numVoyage = :b");
            $req -> execute(array('a'=>1, 'b'=> $_POST['idModificationV']));
            $row = $req -> fetch();
        ## insertion des elements dans les variables :
            $a = $_POST['valeurTRV'];
            $b = $_POST['valeurTA'];
            $c = $_POST['valeurBV'];
            $d = $_POST['valeurDV'];
            $j = $_POST['idModificationV'];
            $e = $row['numTrajet'];
            $f = $row['numTarif'];
            $g = $row['numBus'];
            $h = $row['dateVoyage'];
            $i = date('Y-m-d', strtotime($row['dateCreation'])); # casting de la datetime.

            if ($a == $e && $b == $f && $c == $g && $d == $h) {
                # msg doublons voyages :
                $_SESSION['duplicatedVoyageFound'] = true;
                header('location:gestionVoyages.php');
            } else {
                if ($a == $e && $b == $f && $c != $g && $d == $h) {
                    # maj:
                    $req = $db -> prepare("UPDATE voyages SET numBus = :a WHERE numVoyage = :b");
                    $req -> execute(array(
                                    'a' => $c,
                                    'b' => $j
                                   ));
                    # message de mis à jour :
                    $_SESSION['updateVoyageSuccess'] = true;
                    header('location:gestionVoyages.php');
                } else {
                    if ($a == $e && $b != $f && $d == $h) {
                        # maj:
                        $req = $db -> prepare("UPDATE voyages SET numTarif=:a, numBus = :b WHERE numVoyage = :c");
                        $req -> execute(array(
                                        'a' =>$b,
                                        'b' =>$c,
                                        'c' =>$j
                                       ));
                        # message de mis à jour :
                        $_SESSION['updateVoyageSuccess'] = true;
                        header('location:gestionVoyages.php');
                    } else {
                        if($a != $e && $d == $h) {
                            # verif si trajet et date saisie existent ensemble :
                            $stmt ="SELECT * from voyages where dateVoyage = :a and numTrajet =:b and statutVoyage = :c";
                            $current_date = date("Y-m-d");
                            $req = $db -> prepare($stmt);
                            $req -> execute(array(
                                                    'a' =>$d,
                                                    'b' =>$a,
                                                    'c' =>1
                                                ));
                            $dateTrajetExiste = $req -> rowCount();
                            // echo $dateTrajetExiste; exit();
                            if ($dateTrajetExiste >= 1) {
                                #  msg doublon:
                                $_SESSION['duplicatedVoyageFound'] = true;
                                header('location:gestionVoyages.php');
                            } else {
                                # maj :
                                $req = $db -> prepare("UPDATE voyages SET numTrajet=:a, numTarif=:b, numBus = :c WHERE numVoyage = :d");
                                $req -> execute(array(
                                                'a' =>$a,
                                                'b' =>$b,
                                                'c' =>$c,
                                                'd' =>$j
                                               ));
                                # message de mis à jour :
                                $_SESSION['updateVoyageSuccess'] = true;
                                header('location:gestionVoyages.php');
                            }
                        } else {
                            if ($d != $h) {
                                if ( $d <= $i) {
                                    # msg date erreur:
                                    $_SESSION['DVDepassee'] = true;
                                    header('location:gestionVoyages.php');
                                } else {
                                    # on verifie si la date saisie est active pour un voyage :
                                    $stmt ="SELECT * from voyages where dateVoyage = :a and statutVoyage = :b";
                                    $req = $db -> prepare($stmt);
                                    $req -> execute(array(
                                                            'a' =>$d,
                                                            'b' =>1
                                                        ));
                                    $dateExiste = $req -> rowCount();
                                    // echo $dateExiste; exit();
                                    if ($dateExiste >= 1) {
                                        # verif si trajet et date saisie existent ensemble :
                                        $stmt ="SELECT * from voyages where dateVoyage = :a and numTrajet =:b and statutVoyage = :c";
                                        $current_date = date("Y-m-d");
                                        $req = $db -> prepare($stmt);
                                        $req -> execute(array(
                                                                'a' =>$d,
                                                                'b' =>$a,
                                                                'c' =>1
                                                            ));
                                        $dateTrajetExiste = $req -> rowCount();
                                        // echo $dateTrajetExiste; exit();
                                        if ($dateTrajetExiste >= 1) {
                                            #  msg doublon:
                                            $_SESSION['DVDepassee'] = true;
                                            header('location:gestionVoyages.php');
                                        } else {
                                            # maj :
                                             $current_date_time = date("Y-m-d H:i:s");
                                            $req = $db -> prepare("UPDATE voyages SET numTrajet=:a, numTarif=:b, numBus = :c, dateVoyage =:d WHERE numVoyage = :f");
                                            $req -> execute(array(
                                                            'a' =>$a,
                                                            'b' =>$b,
                                                            'c' =>$c,
                                                            'd' =>$d,
                                                            'f' =>$j
                                                           ));
                                            # message de mis à jour :
                                            $_SESSION['updateVoyageSuccess'] = true;
                                            header('location:gestionVoyages.php');
                                        }
                                    } else {
                                        # maj:
                                        $req = $db -> prepare("UPDATE voyages SET dateVoyage=:a, numTrajet=:b, numTarif=:c, numBus = :d WHERE numVoyage = :e");
                                        $req -> execute(array(
                                                        'a' =>$d,
                                                        'b' =>$a,
                                                        'c' =>$b,
                                                        'd' =>$c,
                                                        'e' =>$j
                                                       ));
                                        # message de mis à jour :
                                        $_SESSION['updateVoyageSuccess'] = true;
                                        header('location:gestionVoyages.php');
                                    }
                                    
                                }       

                            }
                        }#4
                        
                    }#3
                    
                }#2
                
            } #1
    }
    # modification voyages(étape deux).


/**
   ## suppression des bus :
*/
 if (isset($_GET['idSupprimerVoyage'])) {
            // echo ' je suis dedans'; exit();
            $req = $db -> prepare("UPDATE voyages SET statutVoyage = 0 WHERE numVoyage = :a");
            $req -> execute(array(
                            'a' => $_GET['idSupprimerVoyage']
                           ));
            $_SESSION['voyageDeleted'] = true ;
             header('location:gestionVoyages.php');
             $req -> closeCursor();
        }
/**
   ## suppression des bus :
*/