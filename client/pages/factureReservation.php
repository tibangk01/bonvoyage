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

    // echo $_SESSION['numTicketF']; exit();
    # requete de selection du numero du ticket et de la date d'émission :
    $stmt ="SELECT caissiers.nomCaiss as a,
				   caissiers.prenomCaiss as b,
			       enregistrer.numTicket as c,
			       enregistrer.dateEmission as d,
                   ticket.nomPassager as e,
                   ticket.prenomPassager as f
			from enregistrer
			inner join caissiers
			on enregistrer.numcaiss = caissiers.numCaiss
            INNER join ticket
            on enregistrer.numTicket = ticket.numTicket
			WHERE enregistrer.numTicket =:a";
    $req = $db -> prepare($stmt);
    $req -> execute(array(
                            'a' => $_SESSION['numTicketF']
                        ));
    $row = $req -> fetch();

    # requete de selection date voyage, 
    $stmt ="SELECT voyages.dateVoyage as a,
				   trajets.libVilleDepTrajet as b_1,
			       trajets.libVilleArrTrajet as b_2,
			       tarifs.valeurTarif as c
			FROM voyages
			INNER JOIN trajets
			ON voyages.numTrajet = trajets.numTrajet
			INNER JOIN tarifs
			ON voyages.numTarif = tarifs.numTarif
			where voyages.numVoyage =:a
				and voyages.statutVoyage =1";
    $req2 = $db -> prepare($stmt);
    $req2 -> execute(array(
                            'a' => $_SESSION['numVoyageF']
                        ));
    $row2 = $req2 -> fetch();
    ## stockage de la valeur du tarif : 
    $valeurTarif = $row2['c'];

    # requete de selection de la destination et du kilometrage :
    $stmt ="SELECT avoir.distVilleEscaleVilleDepartTrajet as a,
				   villes_escales.libVilleEscale as b 
			FROM avoir
			INNER JOIN villes_escales
			ON avoir.numVilleEscale = villes_escales.numVilleEscale
			INNER JOIN trajets
			ON avoir.numTrajet = trajets.numTrajet
			WHERE trajets.numTrajet = :a
				AND villes_escales.numVilleEscale =:b";
    $req3 = $db -> prepare($stmt);
    $req3 -> execute(array(
                            'a' => $_SESSION['numTrajetF'],
                            'b' => $_SESSION['numVilleEscaleF']
                        ));
    $row3 = $req3 -> fetch();
    # reformatage du montant du ticket:
    $montantTotal = $row3['a']*$valeurTarif;
    // echo $montantTotal; exit();
    $unite = ceil($montantTotal)%10;
    $montantTotal = intdiv(ceil($montantTotal), 10);
    $montantTotal *=10;
    if ($unite ==0) {
     	$montantTotal+=0;
     } elseif($unite <= 5) {
     	$montantTotal+=5;
     }else{
     	$montantTotal+=10;
     }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="../css/simplegrid.css">
	<link rel="stylesheet" href="../css/styles.css">
	<title>Caissiers | Facture Réservation</title>
</head>
<style>
	body{background: none;}
	table{
		background: transparent;
		font-weight: bold;
	}

	th,tr{
		background: lightgray;
	}

	td{
		padding: 5px;
	}
</style>
<body>
	<div class="grid" style="margin-top : 10%;">
		<div class="col-1-1 ">
			<div class="printable">
				<div class="w80 ma">
				<table style="width: 80%;" class="ma">
					<tr>
						<td colspan="2" style="font-size: 18px; text-align: center; "><span style="text-decoration: underline;">Ticket De Voyage N°:</span> <?php echo $row['c'];?>  - Date d'émission : <?php echo $row['d'] ?></td>
					</tr>
					<tr>
						<td>Date Du Voyage : </td>
						<td><?php echo $row2['a'];?></td>
					</tr>
					<tr>
						<td>Convocation : </td>
						<td> 6h00 GMT</td>
					</tr>
					<tr>
						<td>Passager : </td>
						<td><?php echo ucfirst(strtolower($row['f'])).' '.strtoupper($row['e']);?></td>
					</tr>
					<tr>
						<td>Trajet :</td>
						<td><?php echo $row2['b_1'].' - '.$row2['b_2'];?></td>
					</tr>
					<tr>
						<td>Destination :</td>
						<td><?php echo $row3['b'] ?></td>
					</tr>
					<tr>
						<td>Montant Du Ticket :</td>
						<td><?php echo $montantTotal.' Fr CFA';?></td>
					</tr>
					<tr>
						<td>Caissier : </td>
						<td> <?php echo ucfirst(strtolower($row['a'])).' '.$row['b'];?></td>
					</tr>
					<tr>
						<td colspan="2" style="text-align: center;">
							<i>BON VOYAGE, N°1 dans votre coeur...</i>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align: center;">
							<small style="background: red;">NB :Ce billet peut être annuler jusqu'a la veille du voyage avec 15% de panalité sur le montant du ticket </small>
						</td>	
					</tr>	
				</table>
			</div>
			</div>
			<div class="col-1-1">
				<div class="w60 ma text-center pad-20">
						<td colspan="2"><button id="print">Imprimer</button></td>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="../js/jquery-3.3.1.slim.min.js"></script>
	<script type="text/javascript" src="../js/divjs.js"></script>
	<script type="text/javascript">
		$('#print').click(function () {
			$('.printable').printElement();
		})
		$('.printable').printElement({
			title:' Ticket De Voyage'
		});
		$('.printable').printElement({
			css:'link'
		});
		$('.printable').printElement({
			ecss:'background : red;'
		});
		$('.printable').printElement({
			lcss:['../css/simplegrid.css','../css/styles.css']
		});
	</script>
</body>
</html>