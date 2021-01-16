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
    # desactivation automatique des voyages :
      ## formatage de la date :
      $current_date_time =date("Y-m-d H:i:s");
      $date = date('Y-m-d', strtotime($current_date_time)); # casting de la datetime.
      ## on selectionne tous les voyages actifs:
      $reqX = $db -> prepare("SELECT * from voyages where statutVoyage = 1");
      $reqX -> execute();
      // $test = $reqX -> fetch();
      // echo $test['dateVoyage']; exit();

      while ($rowX = $reqX -> fetch()) { 
        # formatage des informations en provenace de la base de données:
          // echo $rowX['numVoyage']; exit();
        if($rowX['dateVoyage'] == $date || $rowX['nbPlacesDispo'] == 0){ 
          # on désactive ce voyage :
          $stmtX ="UPDATE voyages set statutVoyage =0 where numVoyage =:a";
          $reqY = $db -> prepare($stmtX);
          $reqY -> execute(array('a' => $rowX['numVoyage']));
        }
      }
    // requete du  nombre total de messages sur le site web : 
    $req = $db -> prepare("SELECT * FROM caissiers WHERE numCaiss = :a AND statutCaiss = :b");
    $req -> execute(array(
            'a'=> $_SESSION['pkCaiss'],
            'b'=> 1
        ));
    $row = $req -> fetch();
    $prenomTemp= $row['prenomCaiss'];
    $nomTemp= ucwords(strtolower($row['nomCaiss']));

    $stmt ="SELECT voyages.numVoyage as a, voyages.dateVoyage as b, voyages.nbPlacesDispo as c, trajets.libVilleDepTrajet as d_1, trajets.libVilleArrTrajet as d_2, trajets.numTrajet as e from voyages INNER JOIN trajets on voyages.numTrajet = trajets.numTrajet WHERE voyages.statutVoyage = 1 and voyages.nbPlacesDispo > 0";
    $req2 = $db -> prepare($stmt);
    $req2 -> execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Accueil | Caissiers</title>
	<link rel="stylesheet" href="css/all.css">
	<link rel="stylesheet" type="text/css" href="css/bt.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	   <div class="container-fluid">
	   	 <!-- banière de la sociéte -->
	   	 <div class="row designBaniere justify-content-md-center">
	   	 	INTERFACE DE GESTION DE LA BILLETERIE
	   	 </div>
	   	 <div class="row justify-content-md-center">
	   	 	<div class="col-11">
	   	 		<div class="row justify-content-md-center designMenu">
	   	 			<div class="col-8">
	   	 			<span class="font-weight-bold">MENU | </span><a href="#" class="btn btn-sm bgColor">Accueil</a>
	   	 			</div>
	   	 			<div class="col-4">
	   	 				<div class="col-12 text-center font-weight-bold" style="font-size: 20px;">Bienvenue <?php echo $prenomTemp.' '.$nomTemp;?></div>
	   	 				<div class="col-12 text-center" style="margin-top: 5px;">
	   	 					<a href="pages/deconnexionCaiss.php" class="btn btn-sm btn-primary bgColor">Déconnexion</a>
	   	 				</div>
	   	 			</div>
	   	 		</div>
	   	 	</div>
	   	 </div>
	   	 	<?php if(isset($_SESSION['billSaved'])):?>
	        	<div class="row justify-content-md-center">
		          	<div class="col-8">
		          		 <table class="table table-borderless" style="margin-top: 20px;">
		          	 	<tr>
		          	 		<td class="text-center" style="padding: 30px;"><span class="alert alert-success text-center font-weight-bold">La réservation a été bien effectuée.</span></td>
		          	 	</tr>
		          	 	<tr>
		          	 		<td class="text-center">
		          	 			<a href="client.php" onclick=" return confirm(' Êtes-vous sûr de vouloir quitter cette page? ')" class="btn btn-sm bgColor">Retour</a>
		          	 		    <a href="pages/factureReservation.php" target="_blank"  class="btn btn-sm bgColor">Imprimer le Ticket</a>
		          	 		</td>
		          	 	</tr>
		          	 </table>
		          	</div>
			   	</div>
	        <?php else:?>
		   	 <div class="row justify-content-md-center">
		   	 	<div class="col-9" style="margin-top: 30px;">
		   	 		<div class="designTableau">
		   	 			<table class="table-bordered">
						  <thead>
						  	<th>N° du Voyage</th>
						  	<th>Date De Voyage</th>
						  	<th>Places Disponibles</th>
						  	<th>Trajet</th>
						  	<th>Réserver Un Ticket</th>
						  	<th>Annuler Un Ticket</th>
						  </thead>
						  <tbody>
						  	<?php while( $row2 = $req2 -> fetch()) :?>
							  	<tr>
							  		<td style="font-size: 15px;"><?php echo $row2['a'];?></td>
							  		<td style="font-size: 15px;"><?php echo $row2['b'];?></td>
							  		<td style="font-size: 15px;"><?php echo $row2['c'];?></td>
							  		<td style="font-size: 15px;"><?php echo ucfirst(strtolower($row2['d_1'])).' -> '.ucfirst(strtolower($row2['d_2']));?></td>
							  		<td style="font-size: 15px;"><a href="pages/reservation.php?idTrajet=<?php echo $row2['e'];?>&idVoyage=<?php echo $row2['a'];?>" class="btn btn-sm bgColor">Réserver</a></td>
							  		<td style="font-size: 15px;"><a href="pages/annulation.php?idTrajet=<?php echo $row2['e'];?>&idVoyage=<?php echo $row2['a'];?>" class="btn btn-sm btn-danger">Annuler</a></td>
							  	</tr>
						  	<?php endwhile;?>
						  </tbody>
						</table>
		   	 		</div>
		   	 	</div>
		   	 </div>
	       	<?php endif;unset($_SESSION['billSaved']);?>
	   	 <div class="row font-weight-bold justify-content-md-center" style="margin: 30px;">
			<span>Fab | BTS  <?php echo date('Y');?> </span>
		</div>
	   </div>
	<script type="text/javascript" src="js/jquery-3.3.1.slim.min.js"></script>
	<script type="text/javascript" src="js/popper.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>	
</body>
</html>