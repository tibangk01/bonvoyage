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
	<link rel="stylesheet" href="css/simplegrid.css">
	<link rel="stylesheet" href="css/style.css">
	<title>Caissiers | Accueil</title>
</head>
<body>
	<div class="grid">
		<div class="col-1-1 pad-20">
			<div class="col-4-12 text-center dmcaissier">
				<div class="col-1-1 m-col-1-child">Bienvenue <?php echo$prenomTemp ?>  </div>
				<div class="col-1-1 m-col-1-child"><button onclick="location.href='pages/deconnexionCaiss.php'">Déconnexion</button></div>
			</div>
			<div class="col-8-12 text-center dac">
				<div class="col-1-1">
				   <br>
				   BIENVENUE SUR LA PLATEFORME DE GESTION DE LA BILLETERIE
				</div>
			</div>
		</div>
		<div class="col-1-1 mt3 pad-20">
			<table>
				<tr>
					<th>Numéro Du Voyage</th>
					<th>Date Du Voyage</th>
					<th>Nombre De Places Disponible</th>
					<th>Trajet</th>
					<th>Faire Une Réservation</th>
					<th>Annuler Une Réservation</th>
				</tr>
				<tr>
					<td>12</td>
					<td>12-12-2012</td>
					<td>12</td>
					<td>Lomé-Cinkanssé</td>
					<td><a href="pages/reservation.php">Réserver</a></td>
					<td><a href="pages/annulation.php">Annuler</a></td>
				</tr>
				<tr>
					<td>12</td>
					<td>12-12-2012</td>
					<td>12</td>
					<td>Lomé-Cinkanssé</td>
					<td><a href="pages/reservation.php">Réserver</a></td>
					<td><a href="pages/annulation.php">Annuler</a></td>
				</tr>
				
			</table>
		</div>
		<div class="col-1-1 mt-5 pad-30 text-center footer">
			<span>Fab | BTS 2019 </span>
		</div>
	</div>
</body>
</html>