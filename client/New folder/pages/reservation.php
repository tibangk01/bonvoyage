<?php
    session_start(); 
    echo 'test';exit();
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
	<fab></fab>
	<link rel="stylesheet" href="../css/simplegrid.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="stylesheet" href="../../assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
</head>
<body>
	<div class="grid">
		<div class="col-1-1 pad-20">
			<div class="col-4-12 text-center dmcaissier">
				<div class="col-1-1 m-col-1-child">Bienvenue <?php echo$prenomTemp ?></div>
				<div class="col-1-1 m-col-1-child"><button onclick="location.href='deconnexionCaiss.php'">Déconnexion</button></div>
			</div>
			<div class="col-8-12 text-center dac">
				<div class="col-1-1">
				   <br>
				   BIENVENUE SUR LA PLATEFORME DE GESTION DE LA BILLETERIE
				</div>
			</div>
		</div>
		<div class="col-1-1 pad-20 mt3">
			<div class="col-1-1">
				<span>MENU | </span><a href="../client.php" class="menu">Page D' Accueil</a>
			</div>
			<div class="col-1-1 mt5">
					<form action="factureReservation.php" method="POST">
						<table class="w25 ma">
							
							<tr>
								<th style="font-size: 15px;">Enrégistrement D'un Passasser</th>
							</tr>
							<tr>
								<td>
									<select name="numVilleEscale" id="numVilleEscale" class="w90 m5 text-center">
										<option value="0" class="d-none">===== Choisissez Un Trajet =====</option>
										<?php while($row = $req -> fetch()) :?>
										<option value="<?php $row['b'];?>"><?php $row['a'];?></option>
										<?php endwhile;?>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<select name="" id="" class="w90 m5 text-center">
									<option value="">=== Choisissez Une Destination ===</option>
									<option value="">tes2</option>
								</select>
								</td>
							</tr>
							<tr>
								<td>
									<input type="text" class=" w90 m5" placeholder="Nom Du Passager">
								</td>
							</tr>
							<tr>
								<td>
									<input type="text" class="w90 m5" placeholder="Prénom Du Passager">
								</td>
							</tr>
							<tr>
								<td>
									<input type="text" class="w90 m5" placeholder="Téléphone Du Passager">
								</td>
							</tr>
							
							<tr>
								<td class="text-center"><input type="submit" value="Enrégistrer" class="m10">
							<input type="reset" value="Annuler" class="m10"></td>
							</tr>
						</table>
					</form>
			</div>
		</div>
		<div class="col-1-1 mt-5 pad-20 text-center footer">
			<span>Fab | BTS 2019 </span>
		</div>
	</div>
</body>
</html>