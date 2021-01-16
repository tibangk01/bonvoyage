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


    $req2 = $db -> prepare("SELECT * from trajets where statutTrajet = 1 order by libVilleDepTrajet");
    $req2 -> execute(); 
?>
<!doctype html>
<html>
 
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="../../assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/libs/css/style.css">
    <link rel="stylesheet" href="../../assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" href="../../assets/vendor/charts/chartist-bundle/chartist.css">
    <link rel="stylesheet" href="../../assets/vendor/charts/morris-bundle/morris.css">
    <link rel="stylesheet" href="../../assets/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../assets/vendor/charts/c3charts/c3.css">
    <link rel="stylesheet" href="../../assets/vendor/fonts/flag-icon-css/flag-icon.min.css">
    <title>Admin | A propos </title>
</head>

<body>
    <!-- ============================================================== -->
    <!-- main wrapper -->
    <!-- ============================================================== -->
    <div class="dashboard-main-wrapper">
        <!-- ============================================================== -->
        <!-- navbar -->
        <!-- ============================================================== -->
        <div class="dashboard-header">
            <nav class="navbar navbar-expand-lg bg-white fixed-top " >
                <a class="navbar-brand" href="private.php">Bon Voyage</a>
                <div class="collapse navbar-collapse " id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto navbar-right-top">
                        <li class="nav-item dropdown nav-user"> 
                            <a class="dropdown-item" href="deconnexionAdmin.php"><i class="fas fa-user mr-2"></i>Déconnexion</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- ============================================================== -->
        <!-- end navbar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- left sidebar -->
        <!-- ============================================================== -->
        <div class="nav-left-sidebar sidebar-dark">
            <div class="menu-list">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav flex-column">
                             <li class="nav-divider">
                                Menu
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="gestionBus.php"  aria-expanded="false"><i class="fa fa-fw fa-home"></i>Accueil</a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link" href="gestionBus.php"  aria-expanded="false"><i class="fa fa-fw fas fa-bus"></i>Gestion Des Bus</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="gestionCaisiers.php"  aria-expanded="false"><i class="fa fa-fw fa-dollar-sign"></i>Gestion Des Caissiers</a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link" href="gestionTarifs.php"  aria-expanded="false"><i class="fa fa-fw fa-money-bill-alt"></i>Gestion Des Tarifs</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-1-2" aria-controls="submenu-1-2"><i class="fa fa-fw fa-road"></i>Gestion Des Trajets</a>
                                <div id="submenu-1-2" class="collapse submenu" style="">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="creationTrajets.php">Créer Un Trajet</a>
                                        </li>
                                        <?php while( $row2 = $req2 -> fetch()):?>
                                             <li class="nav-item">
                                                <a class="nav-link" href="listeTrajets.php?idTrajet= <?php echo$row2['numTrajet'];?>"><?php echo ucfirst(strtolower($row2['libVilleDepTrajet'])).' -> '.ucfirst(strtolower($row2['libVilleArrTrajet']));?></a> 
                                            </li>
                                        <?php endwhile;?>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="gestionVoyages.php"  aria-expanded="false"><i class="fa fa-fw fa-shipping-fast"></i>Gestion Des Voyages</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="listeAnnulation.php"  aria-expanded="false"><i class="fa fa-fw fa-list"></i>Liste Des Annulations</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"  aria-expanded="false"><i class="fa fa-fw fa-info"></i>A Propos</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

       
        <div class="dashboard-wrapper">
                <div class="container-fluid dashboard-content ">
                     <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="page-header">
                                <h2 class="pageheader-title">A Propos </h2>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-md-center text-center" style="margin-top: 15%;">
                        f@b | BTS <?php echo date('Y');?>
                    </div>
    
                </div>
            </div>
   
    <!-- Optional JavaScript -->
    <!-- jquery 3.3.1 -->
    <script src="../../assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <!-- bootstap bundle js -->
    <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- slimscroll js -->
    <script src="../../assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <!-- main js -->
    <script src="../../assets/libs/js/main-js.js"></script>
    <!-- chart chartist js -->
    <script src="../../assets/vendor/charts/chartist-bundle/chartist.min.js"></script>
    <!-- sparkline js -->
    <script src="../../assets/vendor/charts/sparkline/jquery.sparkline.js"></script>
    <!-- morris js -->
    <script src="../../assets/vendor/charts/morris-bundle/raphael.min.js"></script>
    <script src="../../assets/vendor/charts/morris-bundle/morris.js"></script>
    <!-- chart c3 js -->
    <script src="../../assets/vendor/charts/c3charts/c3.min.js"></script>
    <script src="../../assets/vendor/charts/c3charts/d3-5.4.0.min.js"></script>
    <script src="../../assets/vendor/charts/c3charts/C3chartjs.js"></script>
</body>
 
</html>