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

    if (isset($_POST['numVoyage'])) {
        # création du ticket de voyage :
        $stmt ="INSERT INTO ticket(prenomPassager, nomPassager, telPassager,numVoyage, numVilleEscale)
                       VALUES(:a, :b, :c, :d, :e)";
        $req = $db -> prepare($stmt);        
        $req -> execute(array(
                    'a' => trim($_POST['prenomPassager']),
                    'b' => trim($_POST['nomPassager']),
                    'c' => trim($_POST['telPassager']),
                    'd' => $_POST['numVoyage'],
                    'e' => $_POST['villeEscale']
                     ));

        ## mis a jour du nombre de places dans la table voyages :
            # recueil le nombre de places disponibles pour le voyage :
            $stmt ="SELECT nbPlacesDispo FROM voyages where numVoyage =:a";
            $req = $db -> prepare($stmt);        
            $req -> execute(array(
                       'a' => $_POST['numVoyage']
                      ));
            $row = $req -> fetch();
            $nbPlacesDispo = $row['nbPlacesDispo'] - 1;

            // echo $nbPlacesDispo; exit();
        ## maj de la table voyages :
            $stmt ="UPDATE voyages set nbPlacesDispo = :a where numVoyage=:b";
            $req = $db -> prepare($stmt);        
            $req -> execute(array(
                       'a' => $nbPlacesDispo,
                       'b' => $_POST['numVoyage']
                      ));
        ## selection du numero du dernier ticket enregistrer :
            $stmt="SELECT max(numTicket) as a from ticket";
            $row = $db -> query($stmt)->fetch();
        ## enrégistrement de la date d' émission:
            $stmt ="INSERT INTO enregistrer(numCaiss, numTicket, dateEmission)
                       VALUES(:a, :b, :c)";
            $current_date_time = date("Y-m-d H:i:s");
            $req = $db -> prepare($stmt);        
            $req -> execute(array(
                        'a' => $_SESSION['pkCaiss'],
                        'b' => $row['a'],
                        'c' => $current_date_time = date("Y-m-d H:i:s")
                         ));
        ## création des variables de session pour la facturation :
            $_SESSION['numTrajetF'] = $_POST['numTrajet'];
            $_SESSION['numTicketF'] = $row['a'];
            $_SESSION['numVoyageF'] = $_POST['numVoyage'];
            $_SESSION['numVilleEscaleF'] = $_POST['villeEscale'];
            $_SESSION['billSaved'] = true;

            // echo $_SESSION['numVoyageF']; exit();
            header('location:../client.php');
    }else{
        echo' erreur 404'; exit();
    }