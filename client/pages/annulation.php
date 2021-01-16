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

    if (isset($_GET['idVoyage'])) {
    	$voyage  = $_GET['idVoyage'];
    	$trajet  = $_GET['idTrajet'];
    } else {
    	$voyage = $_SESSION['idVoyage'];
    	$trajet  = $_SESSION['idTrajet'];
    }
    
	# requete de selection du numero du ticket et de la date d'émission :
    $stmt ="SELECT ticket.numTicket as a,
			       enregistrer.dateEmission as b,
			       ticket.telPassager as c,
			       caissiers.nomCaiss as d,
			       caissiers.prenomCaiss as e
			from enregistrer
			INNER JOIN ticket
			ON enregistrer.numTicket = ticket.numTicket
			INNER JOIN caissiers
			ON enregistrer.numcaiss = caissiers.numCaiss
			WHERE ticket.numVoyage =:a
			and ticket.statutTicket = 1";
    $req = $db -> prepare($stmt);
    $req -> execute(array(
                            'a' => $voyage
                        ));
	      
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Annulation | Caissiers</title>
	<link rel="stylesheet" href="../css/all.css">
	<link rel="stylesheet" type="text/css" href="../css/bt.css">
	<link rel="stylesheet" type="text/css" href="../css/style.css">
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
	   	 			<span class="font-weight-bold">MENU | </span><a href="../client.php" class="btn btn-sm bgColor">Retour A La Page D'accueil</a>
	   	 			</div>
	   	 			<div class="col-4">
	   	 				<div class="col-12 text-center font-weight-bold" style="font-size: 20px;">Bienvenue John Doe</div>
	   	 				<div class="col-12 text-center">
	   	 					<a href="deconnexionCaiss.php" class="btn btn-sm btn-primary bgColor">Déconnexion</a>
	   	 				</div>
	   	 			</div>
	   	 		</div>
	   	 	</div>
	   	 </div>
	   	 <?php if(isset($_SESSION['billCanceled'])):?>
        	<div class="row justify-content-md-center">
	          	<div class="col-8">
	          		 <table class="table table-borderless" style="margin-top: 20px;">
	          	 	<tr>
	          	 		<td class="text-center" style="padding: 30px;"><span class="alert alert-info text-center font-weight-bold">La réservation a été bien annulée.</span></td>
	          	 	</tr>
	          	 	<tr>
	          	 		<td class="text-center">
	          	 			<a href="annulation.php" onclick=" return confirm(' Êtes-vous sûr de vouloir quitter cette page? ')" class="btn btn-sm bgColor">Retour</a>
	          	 		    <a href="factureAnnulation.php" target="_blank"  class="btn btn-sm bgColor">Imprimer le reçu.</a>
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
					  	<th>N° du Ticket</th>
					  	<th>Date D'émission</th>
					  	<th>Téléphone Du Passager</th>
					  	<th>Identité Du Caissier</th>
					  	<th>Annuler Un Ticket</th>
					  </thead>
					  <tbody>
					  	<?php while( $row = $req -> fetch()) :?>
					  	<tr>
					  		<td><?php echo $row['a'];?></td>
					  		<td><?php echo $row['b'];?></td>
					  		<td><?php echo $row['c'];?></td>
					  		<td><?php echo $row['d'].' '.ucfirst(strtolower($row['e'])); ?></td>
					  		<td><a href="traitAnnulation.php?idTicket=<?php echo $row['a'];?>&idTrajet=<?php echo $trajet;?>&idVoyage=<?php echo $voyage;?>" class="btn btn-sm btn-danger" onclick=" return confirm(' Êtes-vous sûr de vouloir annuler ce ticket?')" >Annuler</a></td>
					  	</tr>
					  	<?php endwhile;?>
					  </tbody>
					</table>
	   	 		</div>
	   	 	</div>
	   	 </div>
	   	<?php endif;unset($_SESSION['billCanceled']);?>
	   	 <div class="row font-weight-bold justify-content-md-center" style="margin: 30px;">
			<span>Fab | BTS  <?php echo date('Y');?> </span>
		</div>
	   </div>
	<script type="text/javascript" src="js/jquery-3.3.1.slim.min.js"></script>
	<script type="text/javascript" src="js/popper.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>	
</body>
</html>