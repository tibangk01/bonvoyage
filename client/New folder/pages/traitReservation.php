<?php 
	session_start();

	$dsn = 'mysql:dbname=bonvoyage;host=localhost';
    $user = 'root';
    $pswd = '';
    try { 
        $db = new PDO($dsn, $user, $pswd);
       echo "Connection realise avec succes!!!!";
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

    # reformatage de informations en proveance du formulaire :
    echo 'test'; exit();