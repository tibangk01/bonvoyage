<?php
    session_start(); 
?>
<!doctype html>
<html lang="en">
 
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Connexion Caissier</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/libs/css/style.css">
    <link rel="stylesheet" href="assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <style>
    html,
    body {
        height: 100%;
    }

    body {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
    }
    </style>
</head>

<body>
    <!-- ============================================================== -->
    <!-- login page  -->
    <!-- ============================================================== -->
    <div class="splash-container">
        <div class="card ">
            <div class="card-header text-center"><img class="logo-img" src="assets/images/logoCaissier.png" alt="logo"><span class="splash-description">Saisissez Vos Informations</span></div>
            <div class="card-body">
                <form action="client/pages/traitConnexionCaiss.php" method="POST">
                    <div class="form-group">

                        <input class="form-control form-control-lg" name="idConnCaiss" id="idConnCaiss" type="text" placeholder="Identifiant" autocomplete="off" autofocus="" required="">
                    </div>
                    <div class="form-group">
                        <input class="form-control form-control-lg" name="mdpConnCaiss" id="mdpConnCaiss" type="password" placeholder="Mot De Passe" required="">
                    </div>
                    <div class="form-group text-center">
                        <input  class="btn btn-outline-primary " type="submit" value="Connexion">
                        <input  class="btn btn-outline-primary " type="reset" id="" value="Annuler"> 
                    </div>
                </form>
            </div>
           
            <?php if(isset($_SESSION['connCaissError'])): ?>
                 <div class="card-footer text-danger text-center ">
                    Erreur de connexion
                </div>
           <?php endif; unset($_SESSION['connCaissError']) ?>
           
        </div>
    </div>
  
    <!-- ============================================================== -->
    <!-- end login page  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <script src="assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
</body>
 
</html>