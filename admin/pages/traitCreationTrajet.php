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

    ## création de trajet : 
    /**
          # a => libVilleDep
          # b => libVilleArr
          # c => dist tajet
    */
     if (isset($_POST['libVilleDepTrajet']) && isset($_POST['modificationTR']) && ($_POST['modificationTR'] == 0)) {
            # stockage des informations dans des variables :
            $a = strtoupper(trim(skip_accents($_POST['libVilleDepTrajet'],'utf-8')));
            $b = strtoupper(trim(skip_accents($_POST['libVilleArrTrajet'],'utf-8')));
            $c = trim(floatval($_POST['distTotalTrajet']));

            # vérifie si a et b existent ensmeble actives : 
            $stmt ="SELECT * from trajets where libVilleDepTrajet = :a and libVilleArrTrajet = :b and statutTrajet = :c";
            $req = $db -> prepare($stmt);
            $req -> execute(array(
                                    'a' =>$a,
                                    'b' =>$b,
                                    'c' => 1
                                ));
            $depArrExiste = $req -> rowCount();
            // echo $depArrExiste; exit();
            # si oui msg doublon sinon insertion:
            if ($depArrExiste >= 1) {
                # msg doublon
                $_SESSION['duplicatedTRFound'] = true;
                header('location:creationTrajets.php');
            } else {
                # insertion :
                $current_date_time = date("Y-m-d H:i:s");
                $req = $db -> prepare("INSERT INTO trajets(libVilleDepTrajet,libVilleArrTrajet, distTotalTrajet, dateModifTrajet, numRes)
                                            values(:a,:b,:c,:d,:e)"
                                      );
                $req -> execute(array(
                                      'a' => $a,
                                      'b' => $b,
                                      'c' => $c,
                                      'd' => $current_date_time,
                                      'e' => $_SESSION['pkRes']
                                    ));
                $_SESSION['TRCreated'] = true;
                header('location:creationTrajets.php');
            }
     }
    ## création de trajet.

    ## modification des trajets(étape un):
    if (isset($_GET['idUpdateTR'])) {
        $req = $db -> prepare("SELECT * from trajets where statutTrajet =:a and numTrajet =:b");
        $req -> execute(array(
                                'a' => 1,
                                'b' => $_GET['idUpdateTR']
                             ));
        $row = $req -> fetch();

        $_SESSION['UnumTR'] = $row['numTrajet'];
        $_SESSION['UlibVilleDepTR'] = $row['libVilleDepTrajet'];
        $_SESSION['UlibVilleArrTR'] = $row['libVilleArrTrajet'];
        $_SESSION['UdistTotalTR'] = $row['distTotalTrajet'];
        // echo 'test'; exit();
        // echo $_SESSION['UnumTA'].' - '.$_SESSION['UvaleurTA']; exit();    
        header('location:creationTrajets.php');
        }
    ## modification des trajets.

    ## modification des trajets(étape deux):
        if (isset($_POST['libVilleDepTrajet']) && isset($_POST['modificationTR'])  && ($_POST['modificationTR'] == 1) && isset($_POST['idModificationTR']) ){
            /**
                # a => villeDep 
                # b => villeArr
                # c => dist
                # d => villeDep db
                # e => villeArr db
                # f => dist db
            */
            # sélection des informations de la db :
            $req = $db -> prepare("SELECT * FROM trajets WHERE numTrajet =:a and statutTrajet =:b");
            $req -> execute(array('a' =>$_POST['idModificationTR'], 'b'=>1 ));
            $row = $req -> fetch();

            $a = strtoupper(trim(skip_accents($_POST['libVilleDepTrajet'],'utf-8')));
            $b = strtoupper(trim(skip_accents($_POST['libVilleArrTrajet'],'utf-8')));
            $c = trim(floatval($_POST['distTotalTrajet']));

            $d = $row['libVilleDepTrajet'];
            $e = $row['libVilleArrTrajet'];
            $f = $row['distTotalTrajet'];
            if ($a == $d && $b == $e && $c == $f) {
                # Message doublon :
                $_SESSION['duplicatedTRFound'] = true;
                header('location:creationTrajets.php'); 
            } else {
                if ($a == $d && $b == $e && $c != $f) {
                    # maj:
                    $current_date_time = date("Y-m-d H:i:s");
                    $req = $db -> prepare("UPDATE trajets SET 
                                                  distTotalTrajet = :a,
                                                  dateModifTrajet = :b 
                                           WHERE numTrajet = :c");
                    $req -> execute(array(
                                    'a' => $c,
                                    'b' => $current_date_time,
                                    'c' => $_POST['idModificationTR']
                                   ));
                    $_SESSION['updateTRSuccess'] = true ;
                    header('location:creationTrajets.php');
                } else {
                    if ($a == $d && $b != $e) {
                        # on verifie si a et b actives dans db existent :
                        $stmt ="SELECT * from trajets where libVilleDepTrajet = :a and libVilleArrTrajet = :b and statutTrajet = :c";
                        $req = $db -> prepare($stmt);
                        $req -> execute(array(
                                                'a' =>$a,
                                                'b' =>$b,
                                                'c' => 1
                                            ));
                        $depArrExiste = $req -> rowCount();
                        // echo $depArrExiste; exit();
                        # si oui alors message doublon  sinon  maj :
                        if ($depArrExiste >= 1) {
                            # msg doublon:
                            $_SESSION['duplicatedTRFound'] = true;
                            header('location:creationTrajets.php'); 
                        } else {
                            # maj:
                            $current_date_time = date("Y-m-d H:i:s");
                            $req = $db -> prepare("UPDATE trajets SET 
                                                          distTotalTrajet = :a,
                                                          dateModifTrajet = :b,
                                                          libVilleDepTrajet =:d,
                                                          libVilleArrTrajet =:e 
                                                   WHERE numTrajet = :c");
                            $req -> execute(array(
                                            'a' => $c,
                                            'b' => $current_date_time,
                                            'c' => $_POST['idModificationTR'],
                                            'd' => $a,
                                            'e' => $b
                                           ));
                            $_SESSION['updateTRSuccess'] = true ;
                            header('location:creationTrajets.php');
                        }
                    }else{
                        if ($a != $d) {
                                # on verifie sie a existe dans db:
                                $stmt ="SELECT * from trajets where libVilleDepTrajet = :a and statutTrajet = :b";
                                $req = $db -> prepare($stmt);
                                $req -> execute(array(
                                                        'a' =>$a,
                                                        'b' =>1
                                                    ));
                                $villeDepExiste = $req -> rowCount();
                                // echo $villeDepExiste; exit();
                                if ($villeDepExiste >= 1) {
                                    # on verifie si a et b existent ens db :
                                    $stmt ="SELECT * from trajets where libVilleDepTrajet = :a and libVilleArrTrajet = :b and statutTrajet = :c";
                                    $req = $db -> prepare($stmt);
                                    $req -> execute(array(
                                                            'a' =>$a,
                                                            'b' =>$b,
                                                            'c' => 1
                                                        ));
                                    $depArrExiste = $req -> rowCount();
                                    # si oui msg doublon sinon maj :

                                    if ($depArrExiste>= 1) {
                                        # msg doublon :
                                        $_SESSION['duplicatedTRFound'] = true;
                                        header('location:creationTrajets.php'); 
                                    } else {
                                        # maj:
                                        $current_date_time = date("Y-m-d H:i:s");
                                        $req = $db -> prepare("UPDATE trajets SET 
                                                                      distTotalTrajet = :a,
                                                                      dateModifTrajet = :b,
                                                                      libVilleDepTrajet =:d,
                                                                      libVilleArrTrajet =:e  
                                                               WHERE numTrajet = :c");
                                        $req -> execute(array(
                                                        'a' => $c,
                                                        'b' => $current_date_time,
                                                        'c' => $_POST['idModificationTR'],
                                                       ));
                                        $_SESSION['updateTRSuccess'] = true ;
                                        header('location:creationTrajets.php');
                                    }
                                } else {
                                    # maj :
                                    // echo 'test'; exit();
                                    $current_date_time = date("Y-m-d H:i:s");
                                    $req = $db -> prepare("UPDATE trajets SET 
                                                                  distTotalTrajet = :a,
                                                                  dateModifTrajet = :b, 
                                                                  libVilleDepTrajet =:d,
                                                                  libVilleArrTrajet =:e 
                                                           WHERE numTrajet = :c");
                                    $req -> execute(array(
                                                    'a' => $c,
                                                    'b' => $current_date_time,
                                                    'c' => $_POST['idModificationTR'],
                                                    'd' => $a,
                                                    'e' => $b
                                                   ));
                                    $_SESSION['updateTRSuccess'] = true ;
                                    header('location:creationTrajets.php');
                                }
                            }

                    }
                }
            }
        }
    ## modification des trajets.

    ## suppression des trajets :
        if (isset($_GET['idSupprimerTR'])) {
            $req = $db -> prepare("UPDATE trajets SET statutTrajet = 0 WHERE numTrajet = :a");
            $req -> execute(array(
                            'a' => $_GET['idSupprimerTR']
                           ));
            $_SESSION['TRDeleted'] = true ;
            header('location:creationTrajets.php');
             $req -> closeCursor();
        }
    ## suppression des trajets.
