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

    # selection du numéro de la ville de destination :

    $stmt ="SELECT numVilleEscale from ticket where numTicket = :a";
    $req = $db -> prepare($stmt);
    $req -> execute(array('a' => $_GET['idTicket']));
    $row = $req -> fetch();
    $numVilleEscale = $row['numVilleEscale'];
    // echo $numVilleEscale; exit();

    # selection de la distance du trajet :
    $stmt ="SELECT avoir.distVilleEscaleVilleDepartTrajet as a 
            FROM avoir
            INNER JOIN villes_escales
            ON avoir.numVilleEscale = villes_escales.numVilleEscale
            INNER JOIN trajets
            ON avoir.numTrajet = trajets.numTrajet
            WHERE villes_escales.numVilleEscale =:a
              AND trajets.numTrajet=:b";
    $req = $db -> prepare($stmt);
    $req -> execute(array('a' =>$numVilleEscale, 'b' => $_GET['idTrajet']));
    $row = $req -> fetch();
    $distance = $row['a'];
    // echo $distance; exit();

    # selection du tarif de voyage :
    $stmt ="SELECT tarifs.valeurTarif as a 
            FROM voyages
            INNER JOIN tarifs
            ON voyages.numTarif = tarifs.numTarif
            WHERE voyages.numVoyage=:a;";
    $req = $db -> prepare($stmt);
    $req -> execute(array('a' => $_GET['idVoyage']));
    $row = $req -> fetch();
    $tarif = $row['a'];
    // echo $tarif; exit();

    # formatage du code :
    $code = date("Y").'-TA-'.$_GET['idTicket'];
    # calcul du montant de la réduction : 
    $montantTicket =$distance*$tarif;
    $montant = $distance*$tarif*0.15;
    // echo ceil($montant); exit();
    // # calcul des arrondis :
    // $unite = ceil($montant) % 10;
    // // echo $unite; exit();
    // $montant = intdiv(ceil($montant), 10);
    // $montant = $montant*10;
    // if ($unite ==0) {
    //     $montantTotal+=0;
    //  } elseif($unite <= 5) {
    //     $montantTotal+=5;
    //  }else{
    //     $montantTotal+=10;
    //  }
    // echo $montant; exit();
    # annulation du ticket de voyage :
    $stmt ="INSERT INTO annuler(numcaiss, numTicket, dateAnnTicket, montantPenalite,codeAnnTicket)
                   VALUES(:a,:b,:c,:d, :e)";
    $current_date_time = date("Y-m-d H:i:s");
    $req = $db -> prepare($stmt);
    $req -> execute(array(
                'a' => $_SESSION['pkCaiss'],
                'b' => $_GET['idTicket'],
                'c' => $current_date_time,
                'd' => $montant,
                'e' => $code
            ));
    # desactivation du ticket :
    $stmt ="UPDATE ticket set statutTicket = 0 where numTicket =:a";
    $req = $db -> prepare($stmt);
    $req -> execute(array('a' => $_GET['idTicket']));
    # incrementation du voyage :
    $stmt ="UPDATE voyages set nbPlacesDispo = nbPlacesDispo + 1 where numVoyage =:a";
    $req = $db -> prepare($stmt);
    $req -> execute(array('a' => $_GET['idVoyage']));
    # création des variables de session :
    $_SESSION['idTicket'] = $_GET['idTicket'];
    $_SESSION['idVoyage'] = $_GET['idVoyage'];
    $_SESSION['idTrajet'] = $_GET['idTrajet'];
    $_SESSION['billCanceled'] = true;
    header('location:annulation.php');




