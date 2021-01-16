//alert('test');
// verification de la bonne saisie des donnees du tarif : 

$(function() {
	$("#messageErreurValeurTarif").hide();
	var erreurValeurTarif = false;

	$("#montantTarif").focusin(function() {
		$("#messageErreurValeurTarif").hide();
	    //alert('jes suis dedans');
	});

	$("#montantTarif").focusout(function() {
		check_valeurTarif();
	    //alert('test');
	});


	function check_valeurTarif(){
		var pattern = /^\s*([0-9])+\.?([0-9])*\s*$/; // interdire aussi les caractères spéciaux.
		var valeurSaisie = $("#montantTarif").val();
		var valeurTSaisie = Math.trunc($("#montantTarif").val());
		var longeurTarif = valeurTSaisie.toString().length;
		if (pattern.test(valeurTSaisie) && longeurTarif <= 3 && valeurTSaisie >= 1 ) {
			erreurValeurTarif = false;
		} else {
			erreurValeurTarif = true;
		}
		
	}

	$("#formCreationTarif").submit(function() {
		 erreurValeurTarif = false;
		 check_valeurTarif();

		 if (erreurValeurTarif == false) {
		 	return true;	
		 } else { 
		 	$("#messageErreurValeurTarif").removeClass('d-none');
		 	$("#messageErreurValeurTarif").html('La valeur doit être un réel entre 1 et 999.');
			$("#messageErreurValeurTarif").show();
		 	return false;
		 }
	});

	$("#formCreationTarif").click(function () {
		$("#messageErreurValeurTarif").hide();
	});
});

// animation des messages : 


$(function () { 

	$("#btnTACreated").click(function () {
		$("#TACreated").removeClass('d-none');
		$("#TACreated").html("Tarif ajouté.");
		setTimeout(function () {
			$("#TACreated").fadeOut('slow');
		}, 4000);
	});

	$("#btnDuplicatedTAFound").click(function () {
		$("#duplicatedTAFound").removeClass('d-none');
		$("#duplicatedTAFound").html("Ce tarif a déja été enrégisté.");
		setTimeout(function () {
			$("#duplicatedTAFound").fadeOut('slow');
		}, 4000);
	});
	$("#btnUpdateTASuccess").click(function () {
		$("#updateTASuccess").removeClass('d-none');
		$("#updateTASuccess").html("Mis à jour du tarif effectué.");
		setTimeout(function () {
			$("#updateTASuccess").fadeOut('slow');
		}, 4000);
	});
	$("#btnTADeleted").click(function () {
		$("#TADeleted").removeClass('d-none');
		$("#TADeleted").html(" Suppression du tarif effectué.");
		setTimeout(function () {
			$("#TADeleted").fadeOut('slow');
		}, 4000);
	});
/*
	$("#btnVilleDeleted").click(function () {
		$("#villeDeleted").removeClass('d-none');
		$("#villeDeleted").html(' Ville Supprimée avec succès');
		setTimeout(function () {
			$("#villeDeleted").fadeOut('slow');
		}, 2000);
	});

	$("#btnDuplicatedFound").click(function () {
		$("#duplicatedFound").removeClass('d-none');
		$("#duplicatedFound").html(' Cette ville existe déja');
		setTimeout(function () {
			$("#duplicatedFound").fadeOut('slow');
		}, 2500);
	}); */

});

// gestion des caissi
// controle des saisies :
$(function() {
	$("#messageErreurGeneralCaiss").hide();
	var erreurNomCaiss = false;
	var erreurPrenomCaiss = false;
	var erreurTelCaiss = false;
	var erreurIdenfifiantCaiss = false;
	var erreurMdpCaiss = false;

	$("#nomCaiss").focusout(function() {
		check_nomCaiss();
	});


	$("#prenomCaiss").focusout(function() {
		check_prenomCaiss();

	});

	$("#telCaiss").focusout(function() {
		check_telephoneCaiss();
		
	});

	$("#identifiantCaiss").focusout(function() {
		check_IdentifiantCaiss();
		
	});

	$("#mdpCaiss").focusout(function() {
		check_motDePasseCaiss();
		
	});



	function check_nomCaiss(){
		var pattern = /^([-a-z\sàâçèéêîôû'])+$/i; // interdire aussi les caractères spéciaux.
		var nom = $("#nomCaiss").val();
		if (pattern.test(nom)) {
			erreurNomCaiss = false;
		}else{
			erreurNomCaiss = true;
		}
	}

	function check_prenomCaiss(){
		var pattern = /^([-a-z\sàâçèéêîôû'])+$/i;
		var prenom = $("#prenomCaiss").val();
		if (pattern.test(prenom)) {
			$("#messageErreurPrenom").hide();
		}else{
			erreurPrenomCaiss = true;
		}
	}

	function check_telephoneCaiss(){ 
		var pattern =/^\+?(\d\s?)+$/; // regex pour les numéros de type afrique de l'ouest.
		var telephone = $("#telCaiss").val();
		//var longeur_telephone = $("#telCaiss").val().length;
		if (pattern.test(telephone)) {
			
		}else {
			erreurTelCaiss = true;
		}
		
	}

	function check_IdentifiantCaiss(){
		var pattern = /^.{6,}$/;
		var Identifiant = $("#identifiantCaiss").val();
		if (pattern.test(Identifiant)) {
		}else{
			erreurIdentifiantCaiss = true;
		}
	}

	function check_motDePasseCaiss(){ 
		var mdp = $("#mdpCaiss").val().length;
		if ( mdp > 7 ) {
			erreurMdpCaiss = false;
		}else{ 
			erreurMdpCaiss = true;
		}
	}

	
	$("#formCaiss").submit(function() {
	 erreurNomCaiss = false;
	 erreurPrenomCaiss = false;
	 erreurTelCaiss = false;
	 erreurIdentifiantCaiss = false;
	 erreurMdpCaiss= false;
	
	 check_nomCaiss();
	 check_prenomCaiss();
	 check_telephoneCaiss();
	 check_IdentifiantCaiss();
	 check_motDePasseCaiss();

	 if (erreurNomCaiss == false && erreurPrenomCaiss == false && erreurTelCaiss == false && erreurIdentifiantCaiss == false && erreurMdpCaiss == false ) {
	 	return true;
	 } else { 
	 	if (erreurNomCaiss == false && erreurPrenomCaiss == false && erreurTelCaiss == true && erreurIdentifiantCaiss == false && erreurMdpCaiss == false ) {
	 		$("#messageErreurGeneralCaiss").removeClass('d-none');
	 	    $("#messageErreurGeneralCaiss").html(" Saisissez un numéro de téléphone valide.")
	     	$("#messageErreurGeneralCaiss").show();
	 	} else if(erreurNomCaiss == false && erreurPrenomCaiss == false && erreurTelCaiss == false && erreurIdentifiantCaiss == true && erreurMdpCaiss == false) {
	 		$("#messageErreurGeneralCaiss").removeClass('d-none');
	     	$("#messageErreurGeneralCaiss").html(" Minimum 6 caractères pour l'identifiant.")
		    $("#messageErreurGeneralCaiss").show();
	 	}else if(erreurNomCaiss == false && erreurPrenomCaiss == false && erreurTelCaiss == false && erreurIdentifiantCaiss == false && erreurMdpCaiss == true){
	 		$("#messageErreurGeneralCaiss").removeClass('d-none');
	     	$("#messageErreurGeneralCaiss").html(" Minimum 8 caractères pour le mot de passe.")
		    $("#messageErreurGeneralCaiss").show();
	 	}else{
	 		$("#messageErreurGeneralCaiss").removeClass('d-none');
	 	    $("#messageErreurGeneralCaiss").html(" Remplissez Correctement tous les champs SVP.")
		    $("#messageErreurGeneralCaiss").show();
	 	}
	 	
	 	return false;
	 }

	});

	$("#formCaissReset").click(function () {
		
		$("#messageErreurGeneralCaiss").hide();
	});
});


// gestion des messages sur la page des caissiers :
$(function () { 

	$("#btnCCreated").click(function () {
		$("#CCreated").removeClass('d-none');
		$("#CCreated").html("Ajout du caissier effectuée.");
		setTimeout(function () {
			$("#CCreated").fadeOut('slow');
		}, 4000);
	});
	$("#btnDuplicatedCaissFound").click(function () {
		$("#duplicatedCaissFound").removeClass('d-none');
		$("#duplicatedCaissFound").html("Ce numéro de téléphone e est déja utilisé.");
		setTimeout(function () {
			$("#duplicatedCaissFound").fadeOut('slow');
		}, 5000);
	});
	$("#btnDuplicatedCaissFound2").click(function () {
		$("#duplicatedCaissFound2").removeClass('d-none');
		$("#duplicatedCaissFound2").html("Ce numéro et");
		setTimeout(function () {
			$("#duplicatedCaissFound2").fadeOut('slow');
		}, 5000);
	});
	$("#btnPseudoUsed").click(function () {
		$("#pseudoUsed").removeClass('d-none');
		$("#pseudoUsed").html("Ce pseudo est déja utilisé.");
		setTimeout(function () {
			$("#pseudoUsed").fadeOut('slow');
		}, 5000);
	});
	$("#btnNothingDone").click(function () {
		$("#nothingDone").removeClass('d-none');
		$("#nothingDone").html("Aucune modification n'a été affectuée.");
		setTimeout(function () {
			$("#nothingDone").fadeOut('slow');
		}, 5000);
	});
	$("#btnUpdateCSuccess").click(function () {
		$("#updateCSuccess").removeClass('d-none');
		$("#updateCSuccess").html("Mis à jour des informations du caissier effectuée.");
		setTimeout(function () {
			$("#updateCSuccess").fadeOut('slow');
		}, 4000);
	});
	$("#btnCDeleted").click(function () {
		$("#CDeleted").removeClass('d-none');
		$("#CDeleted").html(" Suppression du caissier effectué.");
		setTimeout(function () {
			$("#CDeleted").fadeOut('slow');
		}, 4000);
	});
	$("#btnDuplicatedPassFound").click(function () {
		$("#duplicatedPassFound").removeClass('d-none');
		$("#duplicatedPassFound").html("Mots de passe différents.");
		setTimeout(function () {
			$("#duplicatedPassFound").fadeOut('slow');
		}, 4000);
	});

	

/*	$("#btnDuplicatedVTFound").click(function () {
		$("#duplicatedVTFound").removeClass('d-none');
		$("#duplicatedVTFound").html(' Ce tarif a déja été enrégistré.');
		setTimeout(function () {
			$("#duplicatedVTFound").fadeOut('slow');
		}, 1500);
	});

	$("#btnVilleDeleted").click(function () {
		$("#villeDeleted").removeClass('d-none');
		$("#villeDeleted").html(' Ville Supprimée avec succès');
		setTimeout(function () {
			$("#villeDeleted").fadeOut('slow');
		}, 2000);
	});

	$("#btnDuplicatedFound").click(function () {
		$("#duplicatedFound").removeClass('d-none');
		$("#duplicatedFound").html(' Cette ville existe déja');
		setTimeout(function () {
			$("#duplicatedFound").fadeOut('slow');
		}, 2500);
	}); */

});



//alert('test');
// verification de la bonne saisie des donnees du tarif : 

$(function() {
	$("#messageErreurTrajet").hide();
	
	var erreurLibVilleDepTrajet = false;
	var erreurLibVilleArrTrajet = false;
	var erreurConfTrajet = false;
	var erreurDistTotalTrajet = false;

	$("#libVilleDepTrajet").focusin(function() {
		$("#messageErreurTrajet").hide();
	    //alert('jes suis dedans');
	});

	$("#libVilleDepTrajet").focusout(function() {
		check_libVilleDepTrajet();
	    //alert('test');
	});

	$("#libVilleArrTrajet").focusin(function() {
		$("#messageErreurTrajet").hide();
	    //alert('jes suis dedans');
	});

	$("#libVilleArrTrajet").focusout(function() {
		check_libVilleArrTrajet();
	    //alert('test');
	});

	function check_libVilleDepTrajet(){
		// var pattern = /^\s*([0-9])+\.?([0-9])*\s*$/; // interdir:e aussi les caractères spéciaux.
		var valeurSaisie = $("#libVilleDepTrajet").val().length;
		if ( valeurSaisie <=20  ) {
			erreurLibVilleDepTrajet = false;
		} else {
			erreurLibVilleDepTrajet = true;
		}
		
	}
	function check_libVilleArrTrajet(){
		// var pattern = /^\s*([0-9])+\.?([0-9])*\s*$/; // interdir:e aussi les caractères spéciaux.
		var valeurSaisie = $("#libVilleArrTrajet").val().length;
		if ( valeurSaisie <=20 ) {
			erreurLibVilleArrTrajet = false;
		} else {
			erreurLibVilleArrTrajet = true;
		}	
	}
	function check_conformiteTrajet() {
		var villeDep = $("#libVilleDepTrajet").val();
		var villeArr = $("#libVilleArrTrajet").val();

		if (villeDep !== villeArr) {
			erreurConfTrajet = false;
		} else {
			erreurConfTrajet = true;
		}
	}

	function check_distTotalTrajet() {
		var pattern = /^\s*([0-9])+([0-9])*\s*$/;
		var valeurSaisie = $("#distTotalTrajet").val();
		var LvaleurSaisie = valeurSaisie.length;
		//alert(valeurSaisie);
		if (pattern.test(valeurSaisie) && LvaleurSaisie <= 4 ) {
			erreurDistTotalTrajet = false;
		} else {
			erreurDistTotalTrajet = true;
		}
	}

	$("#formTrajet").submit(function() {
		 erreurLibVilleDepTrajet = false;
		 erreurLibVilleArrTrajet = false;
      	 erreurConfTrajet = false;
	     erreurDistTotalTrajet = false;

		 check_libVilleDepTrajet();
		 check_libVilleArrTrajet();
		 check_conformiteTrajet();
		 check_distTotalTrajet();


		 if (erreurLibVilleDepTrajet == false && erreurLibVilleArrTrajet == false && erreurConfTrajet == false && erreurDistTotalTrajet == false) {
		 	return true;	
		 } else { 
		 	if (erreurLibVilleDepTrajet == true && erreurLibVilleArrTrajet == false && erreurConfTrajet == false && erreurDistTotalTrajet == false) {
		 		$("#messageErreurTrajet").removeClass('d-none');
			 	$("#messageErreurTrajet").html('Maximum 20 caractères pour les noms de villes.');
				$("#messageErreurTrajet").show();
		 	} else if(erreurLibVilleDepTrajet == false && erreurLibVilleArrTrajet == true && erreurConfTrajet == false && erreurDistTotalTrajet == false) {
		 		$("#messageErreurTrajet").removeClass('d-none');
			 	$("#messageErreurTrajet").html('Maximum 20 caractères pour les noms de villes.');
				$("#messageErreurTrajet").show();
		 	}else if(erreurLibVilleDepTrajet == false && erreurLibVilleArrTrajet == false && erreurConfTrajet == true && erreurDistTotalTrajet == false){
		 		$("#messageErreurTrajet").removeClass('d-none');
			 	$("#messageErreurTrajet").html('Les deux noms de villes ne peuvent pas être iddentique.');
				$("#messageErreurTrajet").show();
		 	}else if(erreurLibVilleDepTrajet == false && erreurLibVilleArrTrajet == false && erreurConfTrajet == false && erreurDistTotalTrajet == true){
		 		$("#messageErreurTrajet").removeClass('d-none');
			 	$("#messageErreurTrajet").html('La distance doit être un entier entre 1 et 9999.');
				$("#messageErreurTrajet").show();
		 	}else{
		 		$("#messageErreurTrajet").removeClass('d-none');
			 	$("#messageErreurTrajet").html('Remplissez correctement tous les champs SVP.');
				$("#messageErreurTrajet").show();
		 	}
		 	return false;
		 }
	});

	$("#formTrajet").click(function () {
		$("#messageErreurTrajet").hide();
	});
});


// gestion des message trajets : 

$(function () { 

	$("#btnTRCreated").click(function () {
		$("#TRCreated").removeClass('d-none');
		$("#TRCreated").html("Le trajet a été ajouté.");
		setTimeout(function () {
			$("#TRCreated").fadeOut('slow');
		}, 4000);
	});

	$("#btnDuplicatedTRFound").click(function () {
		$("#duplicatedTRFound").removeClass('d-none');
		$("#duplicatedTRFound").html("Ce trajet a déja été enrégistré.");
		setTimeout(function () {
			$("#duplicatedTRFound").fadeOut('slow');
		}, 5000);
	});
	$("#btnUpdateTRSuccess").click(function () {
		$("#updateTRSuccess").removeClass('d-none');
		$("#updateTRSuccess").html("Mis à jour du trajet effectué.");
		setTimeout(function () {
			$("#updateTRSuccess").fadeOut('slow');
		}, 4000);
	});
	$("#btnTRDeleted").click(function () {
		$("#TRDeleted").removeClass('d-none');
		$("#TRDeleted").html(" Suppression Du Trajet Effectué.");
		setTimeout(function () {
			$("#TRDeleted").fadeOut('slow');
		}, 4000);
	});
	// $("#btnDuplicatedPassFound").click(function () {
	// 	$("#duplicatedPassFound").removeClass('d-none');
	// 	$("#duplicatedPassFound").html("Mots de passe différents.");
	// 	setTimeout(function () {
	// 		$("#duplicatedPassFound").fadeOut('slow');
	// 	}, 4000);
	// });

});


// liste des controles pour la creation des villes escales des trajets : 

$(function() {
	$("#messageErreurVilleEscale").hide();
	
	var erreurLibVilleEscale = false;
	var erreurDistVEVAT = false;

	$("#libVilleEscale").focusin(function() {
		$("#messageErreurVilleEscale").hide();
	    //alert('jes suis dedans');
	});
	$("#distVEVAT").focusin(function() {
		$("#messageErreurVilleEscale").hide();
	    //alert('jes suis dedans');
	});

	$("#libVilleEscale").focusout(function() {
		check_libVilleEscale();
	    //alert('test');
	});
	$("#distVEVAT").focusout(function() {
		check_distVEVAT();
	    //alert('test');
	});

	function check_libVilleEscale(){
		// var pattern = /^\s*([0-9])+\.?([0-9])*\s*$/; // interdir:e aussi les caractères spéciaux.
		var valeurSaisie = $("#libVilleEscale").val().length;
		if ( valeurSaisie <= 20  ) {
			erreurLibVilleEscale = false;
		} else {
			erreurLibVilleEscale = true;
		}
		
	}

	function check_distVEVAT() {
		var pattern = /^\s*([0-9])+([0-9])*\s*$/;
		var valeurSaisie = $("#distVEVAT").val();
		var LvaleurSaisie = valeurSaisie.length;
		//alert(valeurSaisie);
		if (pattern.test(valeurSaisie) && LvaleurSaisie <= 4 ) {
			erreurDistVEVAT = false;
		} else {
			erreurDistVEVAT = true;
		}
	}

	$("#formVilleEscale").submit(function() {
		 erreurLibVilleEscale = false;
	     erreurDistVEVAT = false;

		 check_libVilleEscale();
		 check_distVEVAT();

		 if (erreurLibVilleEscale == false && erreurDistVEVAT == false) {
		 	// alert('test');
		 	return true;	
		 } else { 
		 	if (erreurLibVilleEscale == true && erreurDistVEVAT == false) {
		 		$("#messageVilleEscale").removeClass('d-none');
		 	    $("#messageVilleEscale").html('Maximum 20 caractères pour le nom de la ville.');
			   $("#messageVilleEscale").show();
		 	} else if(erreurLibVilleEscale == false && erreurDistVEVAT == true) {
	 			$("#messageVilleEscale").removeClass('d-none');
			 	$("#messageVilleEscale").html('La distance doit être un entier entre 1 et 9999.');
				$("#messageVilleEscale").show();
		 	}else{
		 		$("#messageVilleEscale").removeClass('d-none');
			 	$("#messageVilleEscale").html('Remplissez correctement tous les champs SVP.');
				$("#messageVilleEscale").show();
		 	}
		 	return false;
		 }
	});

	$("#formVilleEscaleReset").click(function () {
		$("#messageVilleEscale").hide();
	});
});

// liste des annulations pour l'affichage des messages d'erreur  : 

$(function () { 

	$("#btnLVTRCreated").click(function () {
		$("#LVTRCreated").removeClass('d-none');
		$("#LVTRCreated").html("La ville a été bien enrégistrée.");
		setTimeout(function () {
			$("#LVTRCreated").fadeOut('slow');
		}, 4000);
	});
	$("#btnDuplicatedLVTRFound").click(function () { 
		$("#duplicatedLVTRFound").removeClass('d-none');
		$("#duplicatedLVTRFound").html("Cette ville existe déja pour ce trajet.");
		setTimeout(function () {
			$("#duplicatedLVTRFound").fadeOut('slow');
		}, 5000);
	});
	$("#btnDuplicatedDistVEFound").click(function () { 
		$("#duplicatedDistVEFound").removeClass('d-none');
		$("#duplicatedDistVEFound").html("La distance saisie est déja utilisée pour une autre ville.");
		setTimeout(function () {
			$("#duplicatedDistVEFound").fadeOut('slow');
		}, 5000);
	});


	$("#btnLongDistVEFound").click(function () { 
		$("#longDistVEFound").removeClass('d-none');
		$("#longDistVEFound").html("La distance saisie est incorrect.");
		setTimeout(function () {
			$("#longDistVEFound").fadeOut('slow');
		}, 5000);
	});
	$("#btnUpdateVESuccess").click(function () {
		$("#updateVESuccess").removeClass('d-none');
		$("#updateVESuccess").html("Mis à jour du trajet effectué.");
		setTimeout(function () {
			$("#updateVESuccess").fadeOut('slow');
		}, 4000);
	});
	$("#btnVEDeleted").click(function () {
		$("#VEDeleted").removeClass('d-none');
		$("#VEDeleted").html(" La ville a été correctement supprimée.");
		setTimeout(function () {
			$("#VEDeleted").fadeOut('slow');
		}, 4000);
	});
	$("#btnErrorVE").click(function () {
		$("#errorVE").removeClass('d-none');
		$("#errorVE").html(" Cette ville est déja enrégistrée.");
		setTimeout(function () {
			$("#errorVE").fadeOut('slow');
		}, 4000);errorVAE
	});
	$("#btnErrorVDE").click(function () {
		$("#errorVDE").removeClass('d-none');
		$("#errorVDE").html(" Entrez une ville escale valide.");
		setTimeout(function () {
			$("#errorVDE").fadeOut('slow');
		}, 4000);
	});
	// $("#btnDuplicatedPassFound").click(function () {
	// 	$("#duplicatedPassFound").removeClass('d-none');
	// 	$("#duplicatedPassFound").html("Mots de passe différents.");
	// 	setTimeout(function () {
	// 		$("#duplicatedPassFound").fadeOut('slow');
	// 	}, 4000);
	// });

});


/**
  ## controle du formulaire de la page de création des bus de voyage :  
*/

$(function() {
	// alert('hello ! ');
	$("#messageErreurBus").hide();
	
	var erreurMarqueBus = false;
	var erreurNbPlacesBus = false;

	$("#marqueBus").focusin(function() {
		$("#messageErreurBus").hide();
	    //alert('jes suis dedans');
	});
	$("#nbPlacesBus").focusin(function() {
		$("#messageErreurBus").hide();
	    //alert('jes suis dedans');
	});

	$("#marqueBus").focusout(function() {
		check_MarqueBus();
	    //alert('test');
	});
	$("#nbPlacesBus").focusout(function() {
		check_NbPlacesBus();
	    //alert('test');
	});

	function check_MarqueBus(){
		// var pattern = /^\s*([0-9])+\.?([0-9])*\s*$/; // interdir:e aussi les caractères spéciaux.
		var valeurSaisie = $("#marqueBus").val().length;
		if ( valeurSaisie <= 20  ) {
			erreurMarqueBus = false;
		} else {
			erreurMarqueBus = true;
		}
		
	}

	function check_NbPlacesBus() {
		var pattern = /^\s*([0-9])+([0-9])*\s*$/;
		var valeurSaisie = $("#nbPlacesBus").val();
		// var LvaleurSaisie = valeurSaisie.length;
		//alert(valeurSaisie);
		if (pattern.test(valeurSaisie) && (valeurSaisie >= 15 && valeurSaisie <=65 )) {
			erreurNbPlacesBus = false;
		} else {
			erreurNbPlacesBus = true;
		}
	}

	$("#formBus").submit(function() {
		 erreurMarqueBus = false;
	     erreurNbPlacesBus = false;

		 check_MarqueBus();
		 check_NbPlacesBus();

		 if (erreurMarqueBus == false && erreurNbPlacesBus == false) {
		 	// alert('test');
		 	return true;	
		 } else { 
		 	if (erreurMarqueBus == true && erreurNbPlacesBus == false) {
		 		$("#messageErreurBus").removeClass('d-none');
		 	    $("#messageErreurBus").html('Maximum 30 caractères pour la marque du bus.');
			   $("#messageErreurBus").show();
		 	} else if(erreurMarqueBus == false && erreurNbPlacesBus == true) {
	 			$("#messageErreurBus").removeClass('d-none');
			 	$("#messageErreurBus").html('Le nombre de places doit être un entier compris entre 15 et 65.');
				$("#messageErreurBus").show();
		 	}else{
		 		$("#messageErreurBus").removeClass('d-none');
			 	$("#messageErreurBus").html('Remplissez correctement tous les champs SVP.');
				$("#messageErreurBus").show();
		 	}
		 	return false;
		 }
	});

	$("#formBusReset").click(function () {
		$("#messageErreurBus").hide();
	});
});

/**
  ## controle du formulaire de la page de création des bus de voyage :  
*/

/**
	## animation des messsages d erreur de creation des bus :
*/

$(function () { 

	$("#btnBCreated").click(function () {
		$("#BCreated").removeClass('d-none');
		$("#BCreated").html("Ajout du bus effectué.");
		setTimeout(function () {
			$("#BCreated").fadeOut('slow');
		}, 4000);
	});
	$("#btnDuplicatedBFound").click(function () {
		$("#duplicatedBFound").removeClass('d-none');
		$("#duplicatedBFound").html(" Ce bus est déja enrégistré.");
		setTimeout(function () {
			$("#duplicatedBFound").fadeOut('slow');
		}, 5000);
	 });
	$("#btnUpdateBSuccess").click(function () {
		$("#updateBSuccess").removeClass('d-none');
		$("#updateBSuccess").html("Mis à jour des informations du bus effectuée.");
		setTimeout(function () {
			$("#updateBSuccess").fadeOut('slow');
		}, 4000);
	});
	$("#btnBDeleted").click(function () {
		$("#BDeleted").removeClass('d-none');
		$("#BDeleted").html(" Suppression du bus effectué.");
		setTimeout(function () {
			$("#BDeleted").fadeOut('slow');
		}, 4000);
	});
});

/**
	## animation des messsages d erreur de creation des bus :
*/

/** 
   ## controle du formulaire de création du voyage :
*/
$(function () {
	$("#messageErreurVoyage").hide(); 
	var erreurTRV = false;
	var erreurTA = false;
	var erreurBV = false;

	$("#valeurTRV").focusout(function() {
		check_TRV();
	});
	$("#valeurTA").focusout(function() {
		check_TA();
	});
	$("#valeurBV").focusout(function() {
		check_BV();
	});

	function check_TRV() {
		var libelleVille = $("#valeurTRV").val();
		if (libelleVille ==0) {
			erreurTRV = true;
		//	$("#messageErreurville").html("&nbsp;*Choisissez une ville SVP")
		} else {
			erreurTRV = false;
		}
	}
	function check_TA() {
		var libelleVille = $("#valeurTA").val();
		if (libelleVille ==0) {
			erreurTA = true;
		//	$("#messageErreurville").html("&nbsp;*Choisissez une ville SVP")
		} else {
			erreurTA = false;
		}
	}
	function check_BV() {
		var libelleVille = $("#valeurBV").val();
		if (libelleVille ==0) {
			erreurBV = true;
		//	$("#messageErreurville").html("&nbsp;*Choisissez une ville SVP")
		} else {
			erreurBV = false;
		}
	}

	/*
	* <========== blocage et deblocage du formulaire ==========>
	*/

	$("#formVoyage").submit(function() { 
		erreurTRV = false;
		erreurTA = false;
		erreurBV = false;
		check_TRV();
		check_TA();
		check_BV();
		
		 if (erreurTRV == false && erreurTA == false && erreurBV == false) {
		 	$("#messageErreurVoyage").hide();
		 	return true;
		 } else { 
		 	if (erreurTRV == true && erreurTA == false && erreurBV == false) {
			 	$("#messageErreurVoyage").removeClass('d-none');
				$("#messageErreurVoyage").html("Choisissez un trajet.");	
				$("#messageErreurVoyage").show();
		 	} else if(erreurTRV == false && erreurTA == true && erreurBV == false) {
		 		$("#messageErreurVoyage").removeClass('d-none');
				$("#messageErreurVoyage").html("Choisissez un tarif au km.");	
				$("#messageErreurVoyage").show();
		 	}else if(erreurTRV == false && erreurTA == false && erreurBV == true){
		 		$("#messageErreurVoyage").removeClass('d-none');
				$("#messageErreurVoyage").html("Choisissez un type de bus.");	
				$("#messageErreurVoyage").show();
		 	}else{
		 		$("#messageErreurVoyage").removeClass('d-none');
				$("#messageErreurVoyage").html("Remplissez correctement tout le formulaire SVP.");	
				$("#messageErreurVoyage").show();
		 	}
		 	return false; 
		 }	
	});

	$("#formVoyageReset").click(function () {
		$("#messageErreurVoyage").hide();
	});
	
});

/**
   ## controle du formulaire de création du voyage :
*/

/**
	## animation des messsages d erreur de creation des voyages :
*/

$(function () { 

	$("#btnDVDepassee").click(function () {
		$("#DVDepassee").removeClass('d-none');
		$("#DVDepassee").html(" La date choisie est invalide.");
		setTimeout(function () {
			$("#DVDepassee").fadeOut('slow');
		}, 4000);
	});
	$("#btnVoyageCreated").click(function () {
		$("#voyageCreated").removeClass('d-none');
		$("#voyageCreated").html(" le voyage a bien été bien créé.");
		setTimeout(function () {
			$("#voyageCreated").fadeOut('slow');
		}, 4000);
	});
	$("#btnDuplicatedVoyageFound").click(function () {
		$("#duplicatedVoyageFound").removeClass('d-none');
		$("#duplicatedVoyageFound").html(" Un voyage est déja enrégistré pour ce trajet à cette date.");
		setTimeout(function () {
			$("#duplicatedVoyageFound").fadeOut('slow');
		}, 5000);
	 });
	$("#btnUpdateVoyageSuccess").click(function () {
		$("#updateVoyageSuccess").removeClass('d-none');
		$("#updateVoyageSuccess").html("Mis à jour des informations du voyage effectuée.");
		setTimeout(function () {
			$("#updateVoyageSuccess").fadeOut('slow');
		}, 4000);
	});
	$("#btnVoyageDeleted").click(function () {
		$("#voyageDeleted").removeClass('d-none');
		$("#voyageDeleted").html(" Suppression du voyage effectué.");
		setTimeout(function () {
			$("#voyageDeleted").fadeOut('slow');
		}, 4000);
	});
});

/**
	## animation des messsages d erreur de creation des voyages :
*/
