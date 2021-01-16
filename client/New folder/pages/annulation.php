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

    // requete du  nombre total de messages sur le site web : 
    $req = $db -> prepare("SELECT * FROM caissiers WHERE numCaiss = :a AND statutCaiss = :b");
    $req -> execute(array(
            'a'=> $_SESSION['pkCaiss'],
            'b'=> 1
        ));
    $row = $req -> fetch();
    $prenomTemp= ucwords(strtolower($row['prenomCaiss']));
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<fab></fab>
	<link rel="stylesheet" href="../css/simplegrid.css">
	<link rel="stylesheet" href="../css/style.css">
	<title>Caissier | reçu d'annulation</title>
	
</head>
<body>
	<div class="grid">
		<div class="col-1-1 pad-20">
			<div class="col-4-12 text-center dmcaissier">
				<div class="col-1-1 m-col-1-child">Bienvenue  <?php echo$prenomTemp ?></div>
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
			<div class="col-1-1 pad-20 mt3">
				<table>
				<tr>
					<th>Numéro Du Ticket</th>
					<th>Date De Réservation</th>
					<th>Téléphone Du Passager</th>
					<th>Identité Du Caissier</th>
					<th>Annuler</th>
				</tr>
				<tr>
					<td>12</td>
					<td>12-12-2012</td>
					<td>90 90 90 90</td>
					<td> John Doe</td>
					<td><a href="factureAnnulation.php">Annuler</a></td>
				</tr>
			</table>
			</div>
		</div>
		
		<div class="col-1-1 mt-5 pad-20 text-center footer">
			<span>Fab | BTS 2019 </span>
		</div>
	</div>
</body>
</html>