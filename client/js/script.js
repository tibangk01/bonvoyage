// alert('test');
$(function () {

	$("#messageErreurGeneralReservation").hide();

	$("#villeEscale, #nomPassager, #premonPassager, telPassager").focusin(function() {
		$("#messageErreurGeneralReservation").hide();
	});


	var erreurVilleEscale = false;
	var erreurNomPassager = false;
	var erreurPrenomPassager = false;
	var erreurTelPassager = false;

	$("#villeEscale").focusout(function() {
		 check_villeEscale();
	});


	$("#nomPassager").focusout(function() {
		check_nomPassager();
		//alert("test");
	});

	$("#premonPassager").focusout(function() {
		check_prenomPassager();
	});


	$("telPassager").focusout(function() {
		check_telPassager();
	});


	$("#idTypeLogementAnn").focusout(function() {
		check_typeLogementAnn();
	});


	$("#idImageAnn").focusout(function() {
		check_imageAnn();
	}); 

	function check_villeEscale() {
		var libelleVille = $("#villeEscale").val();
		if (libelleVille ==0) {
			erreurVilleEscale = true;
			// alert('c est mauvais. ');
		} else {
			erreurVilleEscale = false;
			// alert('c est bon. ');
		}
	}
	function check_nomPassager() {
		var pattern = /^([-a-z\sàâçèéêîôû'])+$/i;
		var nom = $("#nomPassager").val();
		if (pattern.test(nom)) {
			erreurNomPassager= false;
		}else{
			erreurNomPassager = true;
		}
	} 

	function check_prenomPassager() {
		var pattern = /^([-a-z\sàâçèéêîôû'])+$/i;
		var prenom = $("#prenomPassager").val();
		if (pattern.test(prenom)) {
			erreurPrenomPassager= false;
		}else{
			erreurPrenomPassager = true;
		}	
	} 

	function check_telPassager() {
		var pattern =/^\+?(\d\s?)+$/; // regex pour les numéros de type afrique de l'ouest.
		var telephone = $("#telPassager").val();
		//var longeur_telephone = $("#telCaiss").val().length;
		if (pattern.test(telephone)) {
			erreurTelPassager = false;
		}else {
			erreurTelPassager = true;
		}	
	}


	/*
	* <========== blocage et deblocage du formulaire ==========>
	*/

	$("#formReservation").submit(function() { 
		erreurVilleEscale = false;
		erreurNomPassager = false;
		erreurPrenomPassager = false;
		erreurTelPassager = false;
		check_villeEscale();
		check_nomPassager();
		check_prenomPassager();
		check_telPassager();

		 if (erreurVilleEscale == false && erreurNomPassager == false && erreurPrenomPassager == false && erreurTelPassager == false) {
		 	$("#messageErreurGeneralReservation").hide();
		 	var c = confirm(" êtes-vous certains des informations entrées? ");
		 	return c;
		 } else if(erreurVilleEscale == true && erreurNomPassager == false && erreurPrenomPassager == false && erreurTelPassager == false) { 
		 	$("#messageErreurGeneralReservation").removeClass('d-none');
	 	    $("#messageErreurGeneralReservation").html(" Choisissez une ville de destination SVP.")
	     	$("#messageErreurGeneralReservation").show();
		 	return false; 
		 }else if(erreurVilleEscale == false && erreurNomPassager == true && erreurPrenomPassager == false && erreurTelPassager == false){
		 	$("#messageErreurGeneralReservation").removeClass('d-none');
	 	    $("#messageErreurGeneralReservation").html(" Saisissez un nom valide.")
	     	$("#messageErreurGeneralReservation").show();
	     	return false; 
		 }else if(erreurVilleEscale == false && erreurNomPassager == false && erreurPrenomPassager == true && erreurTelPassager == false){
		 	$("#messageErreurGeneralReservation").removeClass('d-none');
	 	    $("#messageErreurGeneralReservation").html(" Saisissez un prenom valide.")
	     	$("#messageErreurGeneralReservation").show();
	     	return false; 
		 }else if(erreurVilleEscale == false && erreurNomPassager == false && erreurPrenomPassager == false && erreurTelPassager == true){
		 	$("#messageErreurGeneralReservation").removeClass('d-none');
	 	    $("#messageErreurGeneralReservation").html(" Saisissez un numéro de téléphone valide.")
	     	$("#messageErreurGeneralReservation").show();
	     	return false; 
		 }else{
		 	$("#messageErreurGeneralReservation").removeClass('d-none');
	 	    $("#messageErreurGeneralReservation").html("Remplissez Correctement tous les champs SVP.")
	     	$("#messageErreurGeneralReservation").show();
	     	return false; 
		 }	
	});

	/*
	* <========== blocage et deblocage du formulaire ==========>
	*/

	/*
	* <========== purge du formulaire ==========>
	*/
	$("#resetformReservation").click(function () {
		$("#messageErreurGeneralReservation").hide();
	});
	/*
	* <========== purge du formulaire ==========>
	*/
});

