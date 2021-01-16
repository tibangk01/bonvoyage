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

    ## création des caissiers :
    	/**
    		# a => nom
    		# b => prenom
    		# c => tel
    		# d => identifiant
    		# e => mdp
    	*/
    	 if (isset($_POST['nomCaiss']) && isset($_POST['modificationC']) && ($_POST['modificationC'] == 0)) {
    	 	# stockage des informations dans les variables :
    	 	$a = strtoupper(trim(skip_accents($_POST['nomCaiss'],'utf-8')));
    	 	$b = strtoupper(trim(skip_accents($_POST['prenomCaiss'],'utf-8')));
    	 	$c = preg_replace('/\+/','', $_POST['telCaiss']);
    	 	$c = trim(preg_replace('/\s+/','',$c));
    	 	$d = trim($_POST['identifiantCaiss']);
    	 	$e = $_POST['mdpCaiss'];

    	 	# on verifie si c  de actives existent dans la db : 
    	 	$stmt ="SELECT * from caissiers where telCaiss =:a  and statutCaiss =:c";
            $req = $db -> prepare($stmt);
            $req -> execute(array(
                                    'a' =>$c,
                                    'c' =>1
                                ));
            $telExiste = $req -> rowCount();
            # si oui alors doublons  sinon insertion : 
            // echo $telIdentifiantExiste; exit();
            if ($telExiste >= 1) {
            	# msg doublon :
            	$_SESSION['duplicatedCaissFound'] = true;
            	header('location:gestionCaisiers.php');
            } else { 
            	# on verife pour le pseudo :
            	$stmt ="SELECT * from caissiers where identifiantCaiss =:a  and statutCaiss =:c";
	            $req = $db -> prepare($stmt);
	            $req -> execute(array(
	                                    'a' =>$d,
	                                    'c' =>1
	                                ));
	            $identifiantExiste = $req -> rowCount();
	            if ($identifiantExiste>=1) {
	            	# msg pseudo existe:
	            	$_SESSION['pseudoUsed'] = true;
            	    header('location:gestionCaisiers.php');
	            } else {
	            	# insertion :
	            	$numTempo = trim(preg_replace('/\s+/','',$_POST['telCaiss'])); # purge des espaces du téléphone :
			    	$stmt = "INSERT INTO caissiers(nomCaiss, prenomCaiss, telCaiss, identifiantCaiss, mdpCaiss, dateCreationCaiss, numRes)
								 VALUES(:a,:b,:c,:d,:e,:f,:g);";
				 	$current_date_time = date("Y-m-d H:i:s");
			    	$req = $db -> prepare($stmt);
			        $req -> execute(array(
			         			'a'=> $a,
			         			'b'=> $b,
			         			'c'=> $numTempo,
			         			'd'=> $d,
			         			'e'=> $e,
			         			'f'=> $current_date_time,
			         			'g'=> $_SESSION['pkRes']
		         			));
			        // echo $numTempo; exit();
			        $_SESSION['CCreated'] = true;
			        header('location:gestionCaisiers.php');
	            }
	            

            	
            }
            
    	 }

    ## création des caissiers.

    ## modfication des caissiers ( étape un ) :
    	  if (isset($_GET['idUpdateC'])) {
	        $req = $db -> prepare("SELECT * from caissiers where statutCaiss = :a and numCaiss = :b ");
	        $req -> execute(array(
	                                'a' => 1,
	                                'b' => $_GET['idUpdateC']
	                             ));
	        $row = $req -> fetch();
	        $_SESSION['UnumCaiss'] = $row['numCaiss'];
	        $_SESSION['UnomCaiss'] = $row['nomCaiss'];
	        $_SESSION['UprenomCaiss'] = $row['prenomCaiss'];
	        $_SESSION['UtelCaiss'] = $row['telCaiss'];
	        $_SESSION['UidentifiantCaiss'] = $row['identifiantCaiss']; 
	        // echo $_SESSION['UnumCaiss']; exit();    
	        header('location:gestionCaisiers.php');
	    }
    ## modfication des caissiers.

    ## modification caissiers( étape deux):
	     if (isset($_POST['nomCaiss']) && isset($_POST['modificationC'])  && ($_POST['modificationC'] == 1) && isset($_POST['idModificationC']) ) {
	     	/**
	    		# a => nom
	    		# b => prenom
	    		# c => tel
	    		# d => identifiant
	    		# e => mdp
	    		# f => nom db
	    		# g => prenom db
	    		# h => tel db
	    		# i => identifiant db
	    		# j => mdp db
	    		# k => mdp confirm
	    	*/
	    	# sélection des éléments de la db :
            $req = $db -> prepare("SELECT * FROM caissiers WHERE numCaiss = :a and statutCaiss = :b");
            $req -> execute(array('a' =>$_POST['idModificationC'], 'b'=>1 ));
            $row = $req -> fetch();

            # stockage des informations dans les variables :
            $a = strtoupper(trim(skip_accents($_POST['nomCaiss'],'utf-8')));
    	 	$b = strtoupper(trim(skip_accents($_POST['prenomCaiss'],'utf-8')));
    	 	$c = preg_replace('/\+/','', $_POST['telCaiss']);
    	 	$c = trim(preg_replace('/\s+/','',$c));
    	 	$d = trim($_POST['identifiantCaiss']);
    	 	$e = $_POST['mdpCaiss'];
    	 	
    	 	$f =$row['nomCaiss'];
    	 	$g =$row['prenomCaiss'];
    	 	$h =preg_replace('/\+/','', $row['telCaiss']);
    	 	$i =$row['identifiantCaiss'];
    	 	$j =$row['mdpCaiss'];
    	 	$k =$_POST['confirmMdpCaiss'];
    	 	// echo $h; exit();

    	 	if ($a == $f && $b == $g && $c == $h && $d == $i && $e == $j) {
    	 		if ($e !== $k) {
    	 			# msg erreur mdp :
    	 			$_SESSION['duplicatedPassFound'] = true;
	     			header('location:gestionCaisiers.php');	
    	 		} else {
    	 			# msg doublon :
	    	 		// echo ' yeah'; exit();
	    	 		$_SESSION['nothingDone'] = true;
	     			header('location:gestionCaisiers.php');	
    	 		}
    	 	} else {
    	 		if ($a != $f && $b == $g && $c == $h && $d == $i && $e == $j) {
    	 			# maj :
    	 			$numTempo = trim(preg_replace('/\s+/','',$_POST['telCaiss']));
    	 			$req = $db -> prepare("UPDATE caissiers SET
	                										   nomCaiss = :a,
	                										   prenomCaiss = :b,
	                										   telCaiss = :c,
	                										   identifiantCaiss = :d,
	                										   mdpCaiss = :e 
										   					WHERE numCaiss = :f AND statutCaiss = :g");
	                $req -> execute(array(
	                                        'a' => $a,
	                                        'b' => $b,
	                                        'c' => $numTempo,
	                                        'd' => $d,
	                                        'e' => $e,
	                                        'f' => $_POST['idModificationC'],
	                                        'g' => 1
	                                     ));
	                $_SESSION['updateCSuccess'] = true ;
	                header('location:gestionCaisiers.php');
    	 		} else {
    	 			if ($b != $g && $c == $h && $d == $i && $e == $j) {
    	 				# maj :
    	 				$numTempo = trim(preg_replace('/\s+/','',$_POST['telCaiss']));
	    	 			$req = $db -> prepare("UPDATE caissiers SET
		                										   nomCaiss = :a,
		                										   prenomCaiss = :b,
		                										   telCaiss = :c,
		                										   identifiantCaiss = :d,
		                										   mdpCaiss = :e 
											   					WHERE numCaiss = :f AND statutCaiss = :g");
		                $req -> execute(array(
		                                        'a' => $a,
		                                        'b' => $b,
		                                        'c' => $numTempo,
		                                        'd' => $d,
		                                        'e' => $e,
		                                        'f' => $_POST['idModificationC'],
		                                        'g' => 1
		                                     ));
		                $_SESSION['updateCSuccess'] = true ;
		                header('location:gestionCaisiers.php');
    	 			} else {
    	 				if ($c == $h && $d == $i && $e != $j) {
    	 					# on verif si e = k. si oui maj sinon msg erreur mdp :
    	 					if ($e == $k) {
    	 						# maj :
	    	 					$numTempo = trim(preg_replace('/\s+/','',$_POST['telCaiss']));
			    	 			$req = $db -> prepare("UPDATE caissiers SET
				                										   nomCaiss = :a,
				                										   prenomCaiss = :b,
				                										   telCaiss = :c,
				                										   identifiantCaiss = :d,
				                										   mdpCaiss = :e 
													   					WHERE numCaiss = :f AND statutCaiss = :g");
				                $req -> execute(array(
				                                        'a' => $a,
				                                        'b' => $b,
				                                        'c' => $numTempo,
				                                        'd' => $d,
				                                        'e' => $e,
				                                        'f' => $_POST['idModificationC'],
				                                        'g' => 1
				                                     ));
				                $_SESSION['updateCSuccess'] = true ;
				                header('location:gestionCaisiers.php');
    	 					} else {
    	 						# msg erreur mdp : 
    	 						$_SESSION['duplicatedPassFound'] = true;
     			                header('location:gestionCaisiers.php');
    	 					}
    	 					
    	 				} else {
    	 					if ($c == $h && $d != $i) {
    	 						# on verifie si c et d de actives existent dans la db : 
					    	 	$stmt ="SELECT * from caissiers where telCaiss =:a and identifiantCaiss = :b and statutCaiss =:c";
					            $req = $db -> prepare($stmt);
					            $req -> execute(array(
					                                    'a' =>$c,
					                                    'b' =>$d,
					                                    'c' =>1
					                                ));
					            $telIdentifiantExiste = $req -> rowCount();
					            # si oui alors doublons  sinon insertion : 
					            // echo $telIdentifiantExiste; exit();
					            if ($telIdentifiantExiste >= 1) {
					            	# msg doublon :
					            	$_SESSION['pseudoUsed'] = true;
                                	header('location:gestionCaisiers.php');
					            } else {
					            	# maj :
			    	 				$numTempo = trim(preg_replace('/\s+/','',$_POST['telCaiss']));
				    	 			$req = $db -> prepare("UPDATE caissiers SET
					                										   nomCaiss = :a,
					                										   prenomCaiss = :b,
					                										   telCaiss = :c,
					                										   identifiantCaiss = :d,
					                										   mdpCaiss = :e 
														   					WHERE numCaiss = :f AND statutCaiss = :g");
					                $req -> execute(array(
					                                        'a' => $a,
					                                        'b' => $b,
					                                        'c' => $numTempo,
					                                        'd' => $d,
					                                        'e' => $e,
					                                        'f' => $_POST['idModificationC'],
					                                        'g' => 1
					                                     ));
					                $_SESSION['updateCSuccess'] = true ;
					                header('location:gestionCaisiers.php');
					            }
    	 					} else {
    	 						if ($c != $h) {
    	 							# on verifie si c  actives existe dans la db : 
						    	 	$stmt ="SELECT * from caissiers where telCaiss =:a  and statutCaiss =:b";
						            $req = $db -> prepare($stmt);
						            $req -> execute(array(
						                                    'a' =>$c,
						                                    'b' =>1
						                                ));
						            $telIdentifiantExiste = $req -> rowCount();
						            # si oui alors doublons  sinon insertion : 
						            // echo $telIdentifiantExiste; exit();
						            if ($telIdentifiantExiste >= 1) {
						            	# msg doublon :
						            	$_SESSION['duplicatedCaissFound'] = true;
	                                	header('location:gestionCaisiers.php');
						            } else {
						            	# maj :
				    	 				$numTempo = trim(preg_replace('/\s+/','',$_POST['telCaiss']));
					    	 			$req = $db -> prepare("UPDATE caissiers SET
						                										   nomCaiss = :a,
						                										   prenomCaiss = :b,
						                										   telCaiss = :c,
						                										   identifiantCaiss = :d,
						                										   mdpCaiss = :e 
															   					WHERE numCaiss = :f AND statutCaiss = :g");
						                $req -> execute(array(
						                                        'a' => $a,
						                                        'b' => $b,
						                                        'c' => $numTempo,
						                                        'd' => $d,
						                                        'e' => $e,
						                                        'f' => $_POST['idModificationC'],
						                                        'g' => 1
						                                     ));
						                $_SESSION['updateCSuccess'] = true ;
						                header('location:gestionCaisiers.php');
						            }

    	 						} else {
    	 							# maj :
				    	 				$numTempo = trim(preg_replace('/\s+/','',$_POST['telCaiss']));
					    	 			$req = $db -> prepare("UPDATE caissiers SET
						                										   nomCaiss = :a,
						                										   prenomCaiss = :b,
						                										   telCaiss = :c,
						                										   identifiantCaiss = :d,
						                										   mdpCaiss = :e 
															   					WHERE numCaiss = :f AND statutCaiss = :g");
						                $req -> execute(array(
						                                        'a' => $a,
						                                        'b' => $b,
						                                        'c' => $numTempo,
						                                        'd' => $d,
						                                        'e' => $e,
						                                        'f' => $_POST['idModificationC'],
						                                        'g' => 1
						                                     ));
						                $_SESSION['updateCSuccess'] = true ;
						                header('location:gestionCaisiers.php');
    	 						}
    	 						
    	 					}
    	 					
    	 				}
    	 				
    	 			}
    	 			
    	 		}
    	 		
    	 	}
    	 	
	     }
    ## modification caissiers.

      if (isset($_GET['idUpdateC'])) {
	        $req = $db -> prepare("SELECT * from caissiers where statutCaiss = :a and numCaiss = :b ");
	        $req -> execute(array(
	                                'a' => 1,
	                                'b' => $_GET['idUpdateC']
	                             ));
	        $row = $req -> fetch();
	        $_SESSION['UnumCaiss'] = $row['numCaiss'];
	        $_SESSION['UnomCaiss'] = $row['nomCaiss'];
	        $_SESSION['UprenomCaiss'] = $row['prenomCaiss'];
	        $_SESSION['UtelCaiss'] = $row['telCaiss'];
	        $_SESSION['UidentifiantCaiss'] = $row['identifiantCaiss']; 

	    //    echo $_SESSION['UnumCaiss']; exit();    
	        header('location:gestionCaisiers.php');
	    }


	     if (isset($_GET['idSupprimerC'])) {
	        $req = $db -> prepare("UPDATE caissiers SET statutCaiss = 0 WHERE numCaiss = :a");
	        $req -> execute(array(
	                        'a' => $_GET['idSupprimerC']
	                       ));
	        $_SESSION['CDeleted'] = true ;
	        header('location: gestionCaisiers.php');
	    }

 $req -> closeCursor();
    