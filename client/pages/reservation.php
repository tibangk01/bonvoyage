<?php
    session_start(); 
    // echo 'test';exit();
    $dsn = 'mysql:dbname=bonvoyage;host=localhost';
    $user = 'root';
    $pswd = '';
    try {
        $db = new PDO($dsn, $user, $pswd);
      //  echo "Connection realise avec succes!!!!";
    } catch (PDOException $e) {
        die(' Erreur : '.$e -> getMessage());
    }

   # sélection et affichage des informations dans le formulaire:
    $stmt ="SELECT villes_escales.libVilleEscale as a, avoir.numVilleEscale as b from avoir INNER join villes_escales on avoir.numVilleEscale = villes_escales.numVilleEscale INNER JOIN trajets on avoir.numTrajet = trajets.numTrajet WHERE trajets.numTrajet =:a AND villes_escales.statutVilleEscale =1";
    $req = $db -> prepare($stmt);
    $req -> execute(array(
            'a'=> $_GET['idTrajet']
        ));
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Accueil | Caissiers</title>
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
	   
		   	 <div class="row justify-content-md-center">
	            <span id="messageErreurGeneralReservation" class="alert alert-danger d-none"></span> 
		   	 </div>
		   	 <div class="row justify-content-md-center">
		   	 	<div class="col-4" style="margin-top: 5px;">
		   	 		
					  	<form action="traitReservation.php" method="POST" id="formReservation">
					  		 <?php if(isset($_GET['idTrajet']) && isset($_GET['idVoyage'])) :?>
	                             <input type="hidden" name="numTrajet" value="<?php echo $_GET['idTrajet']; unset($_GET['idTrajet']);?>"> 
	                             <input type="hidden" name="numVoyage" value="<?php echo $_GET['idVoyage']; unset($_GET['idTrajet']);?>">
	                        <?php endif;?>
					  		<div class="card">
					            <div class="card-body">
					   	 			<div class="form-group">
					   	 				<select name="villeEscale" id="villeEscale" class="form-control">
										  <option value="0" class="d-none">== Choisissez La Destination ==</option>
											<?php while($row = $req -> fetch()) :?>
											<option value="<?php echo $row['b'];?>"><?php echo ucfirst(strtolower($row['a']));?></option>
											<?php endwhile;?>
										</select>
					   	 			</div>
									<div class="form-group">
										<input class="form-control" type="text" name="nomPassager" id="nomPassager" required="" placeholder="Nom Du Passager">
									</div>
									<div class="form-group">
										<input class="form-control" type="text" name="prenomPassager" id="prenomPassager" required="" placeholder="Prenom Du Passager">
									</div>
									<div class="form-group">
										<input class="form-control" type="text" name="telPassager" id="telPassager" required="" placeholder="Téléphone Du Passager">
									</div>
									 <div class="form-group text-center">
				                        <input  class="btn bgColor" type="submit" value="Réserver">
				                        <input  class="btn bgColor" type="reset" id="resetformReservation" value="Effacer"> 
				                    </div>

						       </div>
						    </div>
			   	 		</form>		 
		   	 	</div>
		   	 </div>
	   
	   	 <div class="row font-weight-bold justify-content-md-center" style="margin: 30px;">
			<span>Fab | BTS  <?php echo date('Y');?> </span>
		</div>
	   </div>
	<script type="text/javascript" src="../js/jquery-3.3.1.slim.min.js"></script>
	<script type="text/javascript" src="../js/script.js"></script>
	<script type="text/javascript" src="../js/popper.min.js"></script>
	<script type="text/javascript" src="../js/bootstrap.min.js"></script>	
</body>
</html>