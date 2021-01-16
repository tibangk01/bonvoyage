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

# selection des infos dans la table annuler :
   $stmt ="SELECT annuler.codeAnnTicket as a,
				   annuler.montantPenalite as b,
			       annuler.dateAnnTicket as c,
			       annuler.numTicket as d,
			       caissiers.nomCaiss as e,
			       caissiers.prenomCaiss as f 
			FROM annuler
			INNER JOIN caissiers
			ON annuler.numcaiss = caissiers.numCaiss
			WHERE annuler.numTicket =:a";
    $req = $db -> prepare($stmt);
    $req -> execute(array('a' => $_SESSION['idTicket']));
    $row = $req -> fetch();
    	// echo $row['b']; exit();
    # calcul des montant :
	    #==> montant total de la facture : 
	    $montantTotal =($row['b']*100)/15;
	    // echo $montantTotal; exit();

	    # arondi de la reduction :
	    # calcul des arrondis :
	    $reduc = $row['b'];
	    $unite = ceil($reduc)%10;
	    $reduc = intdiv($reduc, 10);
	    $reduc *=10;
	    if ($unite ==0) {
	        $reduc+=0;
	     } elseif($unite <= 5) {
	        $reduc+=5;
	     }else{
	        $reduc+=10;
	     }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<fab></fab>
	<link rel="stylesheet" href="../css/simplegrid.css">
	<link rel="stylesheet" href="../css/styles.css">
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
				<div class="w60 ma">
					<table >
						<tr style="text-align: center;">
							<td colspan="2" ><span style="text-decoration: underline; font-size: 18px;">Reçu D'annulation De Tickets N°:</span> <?php echo $row['a'];?></td>
						</tr>
						<tr>
							<td>Date D'annulation :</td>
							<td><?php echo $row['c'];?></td>
						</tr>
						<tr>
							<td>Numéro Du Ticket D'annulation:</td>
							<td><?php echo $row['d'];?></td>
						</tr>
						
						<tr>
							<td>Montant Du Ticket De Voyage :</td>
							<td><?php echo $montantTotal.' Fr CFA';?></td>
						</tr>
						<tr>
							<td>Pénalités D'annulation :</td>
							<td><?php echo $reduc.' Fr CFA';?></td>
						</tr>
						<tr>
							<td>Montant Rendu :</td>
							<td><?php echo $montantTotal - $reduc.' Fr CFA';?></td>
						</tr>
						<tr>
							<td>Caissier :</td>
							<td><?php echo ucfirst(strtolower($row['e'])).' '.$row['f'];?></td>
						</tr>
						<tr>
							<td colspan="2" class="text-center">
								<i>BON VOYAGE, N°1 dans votre coeur...</i>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class="col-1-1">
			<div class="w60 ma text-center pad-20">
					<td colspan="2"><button id="print">Imprimer</button></td>
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