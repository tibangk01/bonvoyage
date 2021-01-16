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

    $req = $db -> prepare("SELECT * from trajets where statutTrajet = 1 order by libVilleDepTrajet");
    $req -> execute(); 

## liste des annulations par date :
  # selection des differentes date dans la table annuler :
  $req2 = $db -> prepare("SELECT distinct substr(dateAnnTicket, 1,10) as a from annuler;");
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
    <title>Admin | Listes Des Annulations </title>
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
                                <a class="nav-link active" href="private.php"  aria-expanded="false"><i class="fa fa-fw fa-home"></i>Accueil</a>
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
                                        <?php while( $row = $req -> fetch()):?>
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
                                <a class="nav-link" href="#"  aria-expanded="false"><i class="fa fa-fw fa-list"></i>Liste Des Annulations</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="apropos.php"  aria-expanded="false"><i class="fa fa-fw fa-info"></i>A Propos</a>
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
                                <h2 class="pageheader-title">Liste Des Annulations De Tickets Par Date</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <?php while($row2 = $req2 -> fetch()):?>
                              <div class="card">
                                <h5 class="card-header">Date : <?php echo $row2['a'];?></h5>
                                  <?php
                                    # selection des annulations concernat chacunes des dates :
                                    $chaine = $row2['a'].'%';
                                    $stmt ="SELECT ticket.numVoyage as a,
                                                 caissiers.nomCaiss as b,
                                                   caissiers.prenomCaiss as c,
                                                   ticket.numTicket as d,
                                                   annuler.dateAnnTicket as e,
                                                   annuler.montantPenalite as f 
                                            from annuler
                                            INNER JOIN ticket
                                            ON annuler.numTicket = ticket.numTicket
                                            INNER JOIN caissiers
                                            ON annuler.numcaiss = caissiers.numCaiss
                                            where annuler.dateAnnTicket like :a";
                                    $req3 = $db -> prepare($stmt);
                                    $req3 -> execute(array('a' => $chaine)); 
                                  ?>
                                  <div class="card-body">
                                      <div class="table-responsive">
                                          <table class="table table-striped table-bordered first text-center">
                                              <thead>
                                                  <tr>
                                                  <th>N° Du Voyage</th>
                                                  <th>N° Du Ticket De Voyage</th>
                                                  <th>Montant Pénalité</th>
                                                  <th>Caissier</th>
                                                  </tr>
                                              </thead>
                                              <tbody>
                                              <?php while($row3 = $req3 -> fetch()):?>
                                                  <tr>
                                                    <td><?php echo $row3['a'];?></td>
                                                    <td><?php echo $row3['d'];?></td>
                                                    <td><?php echo $row3['f'];?></td> 
                                                    <td><?php echo $row3['b'].' '.$row3['c'];?></td>
                                                  </tr>
                                              <?php endwhile;?>
                                              </tbody>
                                          </table>
                                      </div>
                                  </div>
                              </div>
                      <?php endwhile;?>
                      </div>
                        </div>
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