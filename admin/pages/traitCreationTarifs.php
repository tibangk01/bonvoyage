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

    ##  création des tarifs:
        /**
              # a => tarifs
        */
        if (isset($_POST['montantTarif']) && isset($_POST['modificationTA']) && ($_POST['modificationTA'] == 0)) {
                # stockage des informations dans des variables :
                $a = strtoupper(trim(skip_accents($_POST['montantTarif'],'utf-8')));
                # verifie si le tarif existe :
                $stmt ="SELECT * from tarifs where valeurTarif = :a and statutTarif = :b";
                $req = $db -> prepare($stmt);
                $req -> execute(array(
                                        'a' =>$a,
                                        'b' =>1
                                    ));
                $tarifExiste = $req -> rowCount();
                # si oui alors doublon sinon alors insertion :
                if ($tarifExiste>=1) {
                    # msg doublon :
                    $_SESSION['duplicatedTAFound'] = true;
                    header('location:gestionTarifs.php');
                } else {
                    # insertion :
                    $stmt = "INSERT INTO tarifs(valeurTarif, numRes,dateCreationTarif, dateModifTarif )
                                    VALUES(:a,:b,:c, :d)";
                    $current_date_time = date("Y-m-d H:i:s");
                    $req = $db -> prepare($stmt);
                    $req -> execute(array(
                               'a'=> floatval($a),
                               'b'=> $_SESSION['pkRes'],
                               'c'=> $current_date_time,
                               'd'=> $current_date_time
                            ));
                    $_SESSION['TACreated'] = true;
                    header('location:gestionTarifs.php');
                } 
        }
    ##  création des tarifs.

    ## modification des tarifs ( étape un ) :
        if (isset($_GET['idUpdateTA'])) {
            $req = $db -> prepare("SELECT * from tarifs where statutTarif = :a and numTarif = :b ");
            $req -> execute(array(
                                    'a' => 1,
                                    'b' => $_GET['idUpdateTA']
                                 ));
            $row = $req -> fetch();
            $_SESSION['UnumTA'] = $row['numTarif'];
            $_SESSION['UvaleurTA'] = $row['valeurTarif'];
           // echo $_SESSION['UnumTA'].' - '.$_SESSION['UvaleurTA']; exit();    
            header('location:gestionTarifs.php');
        }
    ## modification des tarifs.

    ## modification des tarifs (étape deux):
         if (isset($_POST['montantTarif']) && isset($_POST['modificationTA'])  && ($_POST['modificationTA'] == 1) && isset($_POST['idModificationTA']) ){
            /**
                # a => tarif
                # b => tarif bd
            */
            # sélection des éléments de la db :
            $req = $db -> prepare("SELECT * FROM tarifs WHERE numTarif =:a and statutTarif =:b");
            $req -> execute(array('a' =>$_POST['idModificationTA'], 'b'=>1 ));
            $row = $req -> fetch();
            # insertion des informations dans la db :
            $a = $_POST['montantTarif'];
            $b = $row['valeurTarif'];
            // echo $b.' - '.$a; exit();

            # on verifie si a = b. si oui doublon sinon on continue la verif :
            if ($a == $b) {
                # msg doublon :
                $_SESSION['duplicatedTAFound'] = true;
                header('location:gestionTarifs.php'); 
            } else {
                # verif si a existe dans la db active :
                $stmt ="SELECT * from tarifs where valeurTarif = :a  and statutTarif = :b";
                $req = $db -> prepare($stmt);
                $req -> execute(array(
                                        'a' =>$a,
                                        'b' =>1
                                    ));
                $tarifExiste = $req -> rowCount();
                # si oui  msg doublon, sinon maj :
                if ($tarifExiste>= 1) {
                    # msg doublon :
                    $_SESSION['duplicatedTAFound'] = true;
                    header('location:gestionTarifs.php');
                } else {
                    # maj :
                    $current_date_time = date("Y-m-d H:i:s");
                    $req = $db -> prepare("UPDATE tarifs SET
                                                            valeurTarif = :a,
                                                            dateModifTarif = :d
                                                        WHERE numTarif = :b AND statutTarif = :c");
                    $req -> execute(array(
                                            'a' => floatval($a),
                                            'b' => $row['numTarif'],
                                            'c' => 1,
                                            'd' =>  $current_date_time
                                         ));
                    // var_dump(floatval($a)); exit();
                    $_SESSION['updateTASuccess'] = true ;
                    header('location:gestionTarifs.php');
                }
                
            }
         }
    ## modification des tarifs.

    ## suppression des tarifs:
         if (isset($_GET['idSupprimerTA'])) {
            $req = $db -> prepare("UPDATE tarifs SET statutTarif = 0 WHERE numTarif = :a");
            $req -> execute(array(
                            'a' => $_GET['idSupprimerTA']
                           ));
            $_SESSION['TADeleted'] = true ;
            header('location: gestionTarifs.php');
             $req -> closeCursor();
        }
    ## suppression des tarifs. 

    

         


 