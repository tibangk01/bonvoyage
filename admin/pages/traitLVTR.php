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

    # création des villes escales:
    /**
        # a => livVilleEscale
        # b => distvilleescale
    */
    if (isset($_POST['libVilleEscale']) && isset($_POST['modificationLVTR']) && $_POST['modificationLVTR'] == 0 && isset($_POST['idTrajet'])) {
        # stockage des informations dans des variables :
        $a = strtoupper(trim(skip_accents($_POST['libVilleEscale'],'utf-8')));
        $b = trim(floatval($_POST['distVEVAT']));

        $stmt ="SELECT distTotalTrajet as c,libVilleDepTrajet as d,libVilleArrTrajet as e from trajets where numTrajet =:a and statutTrajet = 1";
        $req = $db -> prepare($stmt);
        $req -> execute(array(
                                'a' => $_POST['idTrajet']
                             ));
        $row = $req -> fetch();
        $e = $row['c'];
        $d = $row['d'];
        $f = $row['e'];
        // echo $d; exit();

        if ($d == $a) {
            # msg error ville depart :
            $_SESSION['errorVDE'] = true;
            header('location:listeTrajets.php');
        } elseif($a == $f){
            # on verifie si la distance est la meme que celui du trajet :
            if ($b == $e) {
                # insertion :
                 ## insertion  de la ville :
                $stmt ="INSERT INTO villes_escales(libVilleEscale) values(:a)";
                $req = $db -> prepare($stmt);
                $req -> execute(array('a'=>$a));
                ## selection du dernier id :
                $stmt="SELECT max(numVilleEscale) as a from villes_escales";
                $row = $db -> query($stmt)->fetch();
                ##  insertion dans avoir :
                $stmt ="INSERT INTO avoir(distVilleEscaleVilleDepartTrajet, numVilleEscale, numTrajet) values(:a, :b, :c)";
                $req = $db -> prepare($stmt);
                $req -> execute(array('a'=>$b, 'b'=>$row['a'], 'c'=>$_POST['idTrajet']));
                $_SESSION['LVTRCreated'] = true;
                 header('location:listeTrajets.php');

            } else {
                # msg erreur ville:
                $_SESSION['longDistVEFound'] = true;
                header('location:listeTrajets.php');
            }
            
        }else {
            # on verifie si a existe active dans la db :
            $stmt ="SELECT villes_escales.libVilleEscale,
                    FROM avoir
                    INNER JOIN villes_escales
                    ON avoir.numVilleEscale = villes_escales.numVilleEscale
                    inner join trajets
                    on avoir.numTrajet = trajets.numTrajet
                    WHERE trajets.numTrajet = :a
                        and trajets.statutTrajet = 1
                        AND villes_escales.statutVilleEscale = 1
                        and villes_escales.libVilleEscale = :b";
            $req = $db -> prepare($stmt);
            $req -> execute(array(
                                    'a' => $_POST['idTrajet'],
                                    'b' => $a
                                ));
            $villeEscaleExiste = $req -> rowCount();
            // echo $villeEscaleExiste; exit();
            # si oui alors msg doublon, sinon insertion :
            if ($villeEscaleExiste >= 1 ) {
                # msg doublon :
                $_SESSION['errorVE'] = true;
                header('location:listeTrajets.php');
            } else {
                # on verifie si la distance est inférieur à celle du trajet. si oui on insere sinon message dist incorrect :
                if ($b > $e) {
                    # msg erruer dist :
                    $_SESSION['longDistVEFound'] = true;
                    header('location:listeTrajets.php');
                } elseif($b == $e) {
                    # on verifie si la ville saisie est la même chase que
                    if ($a != $f) {
                        # msg erreur ville escale :
                        $_SESSION['errorVDE'] = true;
                        header('location:listeTrajets.php');
                    } else {
                        ## insertion  de la ville :
                            $stmt ="INSERT INTO villes_escales(libVilleEscale) values(:a)";
                            $req = $db -> prepare($stmt);
                            $req -> execute(array('a'=>$a));
                            ## selection du dernier id :
                            $stmt="SELECT max(numVilleEscale) as a from villes_escales";
                            $row = $db -> query($stmt)->fetch();
                            ##  insertion dans avoir :
                            $stmt ="INSERT INTO avoir(distVilleEscaleVilleDepartTrajet, numVilleEscale, numTrajet) values(:a, :b, :c)";
                            $req = $db -> prepare($stmt);
                            $req -> execute(array('a'=>$b, 'b'=>$row['a'], 'c'=>$_POST['idTrajet']));
                        $_SESSION['LVTRCreated'] = true;
                        header('location:listeTrajets.php');
                    }
                    
                }else{
                    # on verifie si la distance n'est pas prise par une autre ville :
                    $stmt="SELECT avoir.distVilleEscaleVilleDepartTrajet
                           from avoir
                           inner join trajets
                           on avoir.numTrajet = trajets.numTrajet
                           where trajets.statutTrajet = 1 
                             and trajets.numTrajet = :a
                             and avoir.distVilleEscaleVilleDepartTrajet=:b";
                    $req = $db -> prepare($stmt);
                    $req -> execute(array('a'=>$_POST['idTrajet'], 'b' =>$b));
                    $doublonDist = $req -> rowCount();
                    // echo $doublonDist; exit();
                    if ($doublonDist >=1) {
                        # msg er dist:
                        $_SESSION['duplicatedDistVEFound'] = true;
                        header('location:listeTrajets.php');
                    } else {
                        # insertion:
                            ## insertion  de la ville :
                            $stmt ="INSERT INTO villes_escales(libVilleEscale) values(:a)";
                            $req = $db -> prepare($stmt);
                            $req -> execute(array('a'=>$a));
                            ## selection du dernier id :
                            $stmt="SELECT max(numVilleEscale) as a from villes_escales";
                            $row = $db -> query($stmt)->fetch();
                            ##  insertion dans avoir :
                            $stmt ="INSERT INTO avoir(distVilleEscaleVilleDepartTrajet, numVilleEscale, numTrajet) values(:a, :b, :c)";
                            $req = $db -> prepare($stmt);
                            $req -> execute(array('a'=>$b, 'b'=>$row['a'], 'c'=>$_POST['idTrajet']));
                        $_SESSION['LVTRCreated'] = true;
                        header('location:listeTrajets.php');
                    }
                }
            }
                
        }       
    }
    # création des villes escales. 

    # modification villes escale(étape un)
    if (isset($_GET['idUpdateVE'])) {
        $stmt ="SELECT avoir.distVilleEscaleVilleDepartTrajet as a,
                       villes_escales.libVilleEscale as b
                FROM avoir 
                INNER JOIN villes_escales 
                ON avoir.numVilleEscale = villes_escales.numVilleEscale 
                INNER JOIN trajets 
                ON avoir.numTrajet = trajets.numTrajet 
                WHERE trajets.numTrajet =:a 
                AND villes_escales.statutVilleEscale =:b
                AND avoir.numVilleEscale =:c";
        $req = $db -> prepare($stmt);
        $req -> execute(array(
                                'a' => $_SESSION['idTrajet'],
                                'b' => 1,
                                'c' => $_GET['idUpdateVE']
                             ));
        $row = $req -> fetch();

        $_SESSION['UnumVE'] = $_GET['idUpdateVE'];
        $_SESSION['UlibVE'] = $row['b'];
        $_SESSION['UdistVE'] = $row['a'];
        // echo 'test'; exit();
       // echo $_SESSION['UnumVE'].' - '.$_SESSION['UlibVE'].' - '.$_SESSION['UdistVE']; exit();    
        header('location:listeTrajets.php');
    }
    # modification villes escale.

    # modification villes escale (étape deux)
    if ( isset($_POST['libVilleEscale']) && isset($_POST['modificationLVTR']) && $_POST['modificationLVTR'] == 1 && isset($_POST['idTrajet']) && isset($_POST['idModificationLVTR'])) {
        /**
            # a => libville
            # b => distVille
            # c => libVille db
            # d => distVille db
            # e => dist total trajet db
            # f => lib ville dep trajet
            # g => lib ville arr trajet
        */
        # selection des éléments dans la db :
        $stmt ="SELECT avoir.distVilleEscaleVilleDepartTrajet as a,
                        villes_escales.libVilleEscale as b,
                        trajets.distTotalTrajet as c,
                        trajets.libVilleDepTrajet as d,
                        trajets.libVilleArrTrajet as e
                FROM avoir 
                INNER JOIN villes_escales 
                ON avoir.numVilleEscale = villes_escales.numVilleEscale 
                INNER JOIN trajets 
                ON avoir.numTrajet = trajets.numTrajet 
                WHERE trajets.numTrajet =:a
                AND avoir.numVilleEscale =:b";
        $req = $db -> prepare($stmt);
        $req -> execute(array('a' => $_POST['idTrajet'], 'b' => $_POST['idModificationLVTR']));
        $row = $req -> fetch();

        # insertion des valeurs dans les variables :
        $a = strtoupper(trim(skip_accents($_POST['libVilleEscale'],'utf-8')));
        $b = trim(floatval($_POST['distVEVAT']));

        $c = $row['b'];
        $d = $row['a'];
        $e = $row['c'];
        $f = $row['d'];
        $g = $row['e'];
        // echo $a.' - '.$b.' - '.$c.' - '.$d.' ~ '.$e; exit();
        if ($a == $c && $b == $d) {
            # msg doublon:
            $_SESSION['duplicatedLVTRFound'] = true;
            header('location:listeTrajets.php');
         } else {
            # on verif si  a= c et b <> d :
            if ($a == $c && $b != $d) {
                if ($b > $e) {
                    # msg erreur distance :
                     $_SESSION['longDistVEFound'] = true;
                    header('location:listeTrajets.php');
                } elseif($b == $e) {
                    # on verifie si la ville est celle de depart
                    if ($a == $f) {
                        # maj:
                        $stmt ="UPDATE avoir
                                INNER JOIN villes_escales
                                ON avoir.numVilleEscale = villes_escales.numVilleEscale
                                SET villes_escales.libVilleEscale =:a,
                                    avoir.distVilleEscaleVilleDepartTrajet = :b
                                WHERE villes_escales.numVilleEscale =:c
                                and avoir.numTrajet = :d";
                        $req = $db -> prepare($stmt);
                        $req -> execute(array(
                                                'a' => $a,
                                                'b' => $b,
                                                'c' => $_POST['idModificationLVTR'],
                                                'd' => $_POST['idTrajet']
                                            ));
                        # message de mise à jour
                        $_SESSION['updateVESuccess'] = true;
                        header('location:listeTrajets.php');
                    } else {
                        # msg erreur ville escale :
                        $_SESSION['errorVDE'] = true;
                        header('location:listeTrajets.php');
                    }
                }else{ 
                    $stmt="SELECT avoir.distVilleEscaleVilleDepartTrajet
                           from avoir
                           inner join trajets
                           on avoir.numTrajet = trajets.numTrajet
                           where trajets.statutTrajet = 1 
                             and trajets.numTrajet = :a
                             and avoir.distVilleEscaleVilleDepartTrajet=:b";
                    $req = $db -> prepare($stmt);
                    $req -> execute(array('a'=>$_POST['idTrajet'], 'b' =>$b));
                    $doublonDist = $req -> rowCount();
                    // echo $doublonDist; exit();
                    if ($doublonDist >=1) {
                        # msg er dist:
                        $_SESSION['duplicatedDistVEFound'] = true;
                        header('location:listeTrajets.php');
                    } else {
                        # maj:
                        $stmt ="UPDATE avoir
                                INNER JOIN villes_escales
                                ON avoir.numVilleEscale = villes_escales.numVilleEscale
                                SET villes_escales.libVilleEscale =:a,
                                    avoir.distVilleEscaleVilleDepartTrajet = :b
                                WHERE villes_escales.numVilleEscale =:c
                                and avoir.numTrajet = :d";
                        $req = $db -> prepare($stmt);
                        $req -> execute(array(
                                                'a' => $a,
                                                'b' => $b,
                                                'c' => $_POST['idModificationLVTR'],
                                                'd' => $_POST['idTrajet']
                                            ));
                        # message de mise à jour
                        $_SESSION['updateVESuccess'] = true;
                        header('location:listeTrajets.php');
                    }         
                } 
            } else {
                # on verifie si a <> c :
                if ($a != $c) { 
                    if ($a == $f) {
                        # msg erreur ville dep:
                        $_SESSION['errorVDE'] = true;
                        header('location:listeTrajets.php');
                    } else {
                        # on verifie si la ville saisie n'existe pas dans db:
                        $stmt ="SELECT villes_escales.libVilleEscale
                                FROM avoir
                                INNER JOIN villes_escales
                                on avoir.numVilleEscale = villes_escales.numVilleEscale
                                INNER JOIN trajets
                                ON avoir.numTrajet = trajets.numTrajet
                                where villes_escales.statutVilleEscale = 1
                                    and trajets.numTrajet =:a
                                    and trajets.statutTrajet =1
                                    and villes_escales.libVilleEscale =:b";
                        $req = $db -> prepare($stmt);
                        $req -> execute(array('a'=>$_POST['idTrajet'], 'b' =>$a));
                        $villeEscaleExiste = $req -> rowCount();
                        // echo $villeEscaleExiste; exit();
                        # si oui doublon. sinon alors on vérif la distance : 
                        if ($villeEscaleExiste >= 1) {
                            # doubon :
                            $_SESSION['errorVE'] = true;
                            header('location:listeTrajets.php');
                        } else {
                            # on verif si la distance nouvelle est inferieur a celle du trajet :
                            if ($b > $e) {
                                # msg er dist:
                                $_SESSION['longDistVEFound'] = true;
                                header('location:listeTrajets.php');
                            } elseif($b == $e) { 
                                # on vérifie si la ville est bien celle d'arrivee :
                                if ($a != $g) {
                                    # msg erreur :
                                    $_SESSION['errorVDE'] = true;
                                    header('location:listeTrajets.php');    
                                } else { 
                                    $stmt ="UPDATE avoir
                                            INNER JOIN villes_escales
                                            ON avoir.numVilleEscale = villes_escales.numVilleEscale
                                            SET villes_escales.libVilleEscale =:a,
                                                avoir.distVilleEscaleVilleDepartTrajet = :b
                                            WHERE villes_escales.numVilleEscale =:c
                                            and avoir.numTrajet = :d";
                                    $req = $db -> prepare($stmt);
                                    $req -> execute(array(
                                                            'a' => $a,
                                                            'b' => $b,
                                                            'c' => $_POST['idModificationLVTR'],
                                                            'd' => $_POST['idTrajet']
                                                        ));
                                    # message de mise à jour
                                    $_SESSION['updateVESuccess'] = true;
                                    header('location:listeTrajets.php');  
                                }
                            }else{ 
                               $stmt="SELECT avoir.distVilleEscaleVilleDepartTrajet
                                       from avoir
                                       inner join trajets
                                       on avoir.numTrajet = trajets.numTrajet
                                       where trajets.statutTrajet = 1 
                                         and trajets.numTrajet = :a
                                         and avoir.distVilleEscaleVilleDepartTrajet=:b";
                                $req = $db -> prepare($stmt);
                                $req -> execute(array('a'=>$_POST['idTrajet'], 'b' =>$b));
                                $doublonDist = $req -> rowCount();
                                // echo $doublonDist; exit();
                                if ($doublonDist >=1) {
                                    # msg er dist:
                                    $_SESSION['duplicatedDistVEFound'] = true;
                                    header('location:listeTrajets.php');
                                } else {
                                    # maj:
                                    $stmt ="UPDATE avoir
                                            INNER JOIN villes_escales
                                            ON avoir.numVilleEscale = villes_escales.numVilleEscale
                                            SET villes_escales.libVilleEscale =:a,
                                                avoir.distVilleEscaleVilleDepartTrajet = :b
                                            WHERE villes_escales.numVilleEscale =:c
                                            and avoir.numTrajet = :d";
                                    $req = $db -> prepare($stmt);
                                    $req -> execute(array(
                                                            'a' => $a,
                                                            'b' => $b,
                                                            'c' => $_POST['idModificationLVTR'],
                                                            'd' => $_POST['idTrajet']
                                                        ));
                                    # message de mise à jour
                                    $_SESSION['updateVESuccess'] = true;
                                    header('location:listeTrajets.php');
                                }            
                            }                 
                        } 
                    }
                }    
            } 
         }    
     }
    # modification villes escale.

    # suppression des villes escales :
     if (isset($_GET['idSupprimerVE'])) {
            $stmt ="UPDATE villes_escales
                    INNER JOIN avoir
                    ON villes_escales.numVilleEscale = avoir.numVilleEscale
                    SET villes_escales.statutVilleEscale = 0
                    WHERE avoir.numTrajet = :a and avoir.numVilleEscale = :b";
            $req = $db -> prepare($stmt);
            $req -> execute(array(
                            'a' => $_SESSION['idTrajet'],
                            'b' => $_GET['idSupprimerVE']
                           ));
            $_SESSION['VEDeleted'] = true ;
            header('location:listeTrajets.php');
             $req -> closeCursor();
        }
    # suppression des villes escales.