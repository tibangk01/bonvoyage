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
# desactivation automatique des voyages :
      ## formatage de la date :
      $current_date_time =date("Y-m-d H:i:s");
      $date = date('Y-m-d', strtotime($current_date_time)); # casting de la datetime.
      ## on selectionne tous les voyages actifs:
      $reqX = $db -> prepare("SELECT * from voyages where statutVoyage = 1");
      $reqX -> execute();
      // $test = $reqX -> fetch();
      // echo $test['dateVoyage']; exit();

      while ($rowX = $reqX -> fetch()) { 
        # formatage des informations en provenace de la base de données:
          // echo $rowX['numVoyage']; exit();
        if($rowX['dateVoyage'] == $date || $rowX['nbPlacesDispo'] == 0 ){ 
          # on désactive ce voyage :
          $stmtX ="UPDATE voyages set statutVoyage =0 where numVoyage =:a";
          $reqY = $db -> prepare($stmtX);
          $reqY -> execute(array('a' => $rowX['numVoyage']));
        }
      }

# requete des tops 5 des vendeurs : 
    $stmt1="SELECT COUNT(caissiers.numCaiss) as a,
                   caissiers.nomCaiss as b,
                   caissiers.prenomCaiss as c
            FROM enregistrer
            INNER JOIN caissiers
            ON enregistrer.numcaiss = caissiers.numCaiss
            inner join ticket 
            on enregistrer.numTicket = ticket.numTicket
            where ticket.statutTicket =1
            GROUP BY caissiers.numCaiss
            order by a desc
            limit 5 OFFSET 0";
    $req = $db -> prepare($stmt1);
    $req -> execute();
# requete pour l'affuchage des tarifs :
    $req2 = $db -> prepare("SELECT * from trajets where statutTrajet = 1 order by libVilleDepTrajet");
    $req2 -> execute(); 
# requete pour affichage des ticket annuler:
    $stmt2 ="SELECT ticket.numVoyage as a,
                   ticket.numTicket as b,
                   annuler.dateAnnTicket as c,
                   annuler.montantPenalite as d
            FROM annuler
            INNER JOIN ticket
            ON annuler.numTicket = ticket.numTicket
            ORDER BY annuler.dateAnnTicket DESC
            LIMIT 5 OFFSET 0;";
    $req3 = $db -> prepare($stmt2);
    $req3 -> execute(); 

# requete du nombre de voyages crées :
    $req4 = $db -> prepare("SELECT count(*) as a from voyages where statutVoyage =1");
    $req4 -> execute(); 
    $row4 = $req4 -> fetch();

# requete du nombre de ticket vendues :
    $req5 = $db -> prepare("SELECT count(*) as a from ticket where statutTicket =1");
    $req5 -> execute(); 
    $row5 = $req5 -> fetch();
# requete nombre de caissiers actifs :
    $req6 = $db -> prepare("SELECT count(*) as a from  ticket where statutTicket =0");
    $req6 -> execute(); 
    $row6 = $req6 -> fetch();

# pagination :
    $nb_element_par_page = 4 ;
    $req8 = $db -> prepare("SELECT * from voyages where statutVoyage = 1");
    $req8 -> execute();
    $nombre_resultat_renvoye = $req8 -> rowCount();
    // echo $nombre_resultat_renvoye; exit();
    $nombre_page = ceil($nombre_resultat_renvoye / $nb_element_par_page);
    
    if( isset($_GET['page']) ){
       $page = $_GET['page'];
    }else{
      $page = 1;   
    }
    $premier_resultat_sur_la_page=($page - 1)*$nb_element_par_page;
# requete nombre de caissiers actifs :
    $req7 = $db -> prepare("SELECT numVoyage,dateVoyage from voyages where statutVoyage = 1 order by dateVoyage lIMIT $premier_resultat_sur_la_page, $nb_element_par_page");
    $req7 -> execute(); 
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
    <title>Admin | Accueil</title>
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
                <a class="navbar-brand" href="#">Bon Voyage</a>
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
        <div class="nav-left-sidebar sidebar-dark   ">
            <div class="menu-list">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav flex-column">
                            <li class="nav-divider">
                                Menu
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="#"  aria-expanded="false"><i class="fa fa-fw fa-home"></i>Accueil</a>
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
                                <a class="nav-link" href="apropos.php"  aria-expanded="false"><i class="fa fa-fw fa-info"></i>A Propos</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- end left sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->
        <div class="dashboard-wrapper">
            <div class="dashboard-ecommerce">
                <div class="container-fluid dashboard-content ">
                    <!-- ============================================================== -->
                    <!-- pageheader  -->
                    <!-- ============================================================== -->
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="page-header">
                                <h2 class="pageheader-title">Tableau De Bord </h2>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- end pageheader  -->
                    <!-- ============================================================== -->
                    <div class="ecommerce-widget">

                        <div class="row" style="text-align: center;">
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="text-muted">Nombre Total de Voyages En Cours</h5>
                                        <div class="metric-value d-inline-block">
                                            <h1 class="mb-1"><?php echo $row4['a'];?></h1>
                                        </div>
                                       
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="text-muted">Nombre Total de Tichets Vendus</h5>
                                        <div class="metric-value d-inline-block">
                                            <h1 class="mb-1"><?php echo $row5['a'];?></h1>
                                        </div>
                                       
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="text-muted">Nombre Total De Tickets Annulés</h5>
                                        <div class="metric-value d-inline-block">
                                            <h1 class="mb-1"><?php echo $row6['a'];?></h1>
                                        </div>
                                       
                                    </div>
                                </div>
                            </div>
                           
                           
                           
                        </div>
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                              <div class="card">
                                  <h5 class="card-header" style="text-align: center;">Statistiques Des Voyages En Cours</h5>
                                  <div class="card-body">
                                      <div class="table-responsive">
                                          <table class="table table-striped table-bordered first">
                                              <thead>
                                                  <tr>
                                                      <th>Numéro Voyage</th>
                                                      <th>Tickets Vendues</th>
                                                      <th>Tickets Annulés</th>
                                                      <th>Recette Total</th>
                                                      <th>Date Du Voyage</th>  
                                                  </tr>
                                              </thead>

                                              <tbody>
<?php while($row7 = $req7 -> fetch()):?>
     <tr>
        <td><?php echo $row7['numVoyage'];?></td>
<?php
    ## selection de num voyage et date voyage :
    $query1 = "SELECT *from voyages where statutVoyage = 1 and numVoyage =:a";
    $reqt1 = $db -> prepare($query1);
    $reqt1 -> execute(array('a' => $row7['numVoyage']));
    $ligne1= $reqt1->fetch();
    $numVoyage = $ligne1['numVoyage'];
    $dateVoyage = $ligne1['dateVoyage'];
    $numTrajetVoyage = $ligne1['numTrajet']; 
    $numTarifVoyage = $ligne1['numTarif'];

    ## selection du tarif :
    $query6 = "SELECT valeurTarif from tarifs where statutTarif = 1 and numTarif =:a";
    $reqt6 = $db -> prepare($query6);
    $reqt6 -> execute(array('a' => $numTarifVoyage));
    $ligne6= $reqt6->fetch();
    $valeurTarifVoayge = $ligne6['valeurTarif']; 

    ## on selectionne le nombre de tickets vendue du voyage :
    $query2 = "SELECT count(numTicket) as a from ticket where numVoyage =:a and statutTicket = 1";
    $reqt2 = $db -> prepare($query2);
    $reqt2 -> execute(array('a' => $numVoyage));
    $ligne2= $reqt2->fetch();
    $nbTicketVendu=$ligne2['a'];

    ## on selectionne le nombre de tickets annulees :
    $query3 = "SELECT count(numTicket) as a from ticket where numVoyage =:a and statutTicket = 0";
    $reqt3 = $db -> prepare($query3);
    $reqt3 -> execute(array('a' => $numVoyage));
    $ligne3= $reqt3->fetch();
    $nbTicketsAnnule = $ligne3['a'];

    ## chiffre d'affaire par voyage :
    $CAparTrajets = 0;
      # selectionner toutes les tickets vendues du trajet ainsi que leurs statuts :
      $query4 = "SELECT numVilleEscale, statutTicket from ticket where numVoyage =:a";
      $reqt4 = $db -> prepare($query4);
      $reqt4 -> execute(array('a' => $numVoyage));
      # boucle pour parcourir les éléments du tableau :
      while ($ligne4 = $reqt4 -> fetch()) { 
          # on selectionne la distance liée à cette ville et au trajet du voyage :
           $query5 = "SELECT distVilleEscaleVilleDepartTrajet as a from avoir where numVilleEscale=:a and numTrajet=:b";
           $reqt5 = $db -> prepare($query5);
           $reqt5 -> execute(array('a' => $ligne4['numVilleEscale'],
                                   'b' => $numTrajetVoyage
                            ));
           $ligne5 = $reqt5 -> fetch();
            if ($ligne4['statutTicket'] == 1) {
                  $CAparTrajets+= $valeurTarifVoayge* $ligne5['a'];
              } else {
                  $CAparTrajets+= $valeurTarifVoayge* $ligne5['a']*0.15;
              } 
      } 

      # arrondi du ca : 

        $unite = ceil($CAparTrajets)%10;
        $CAparTrajets = intdiv(ceil($CAparTrajets), 10);
        $CAparTrajets *=10;
        if ($unite ==0) {
            $CAparTrajets+=0;
         } elseif($unite <= 5) {
            $CAparTrajets+=5;
         }else{
            $CAparTrajets+=10;
         }
?> 

                                                    <td><?php echo $nbTicketVendu;?></td>
                                                    <td><?php echo $nbTicketsAnnule ; ?></td> 
                                                    <td><?php echo $CAparTrajets; if($CAparTrajets > 0){echo ' fr cfa';}?></td>
                                                    <td><?php echo $row7['dateVoyage'];?></td>  
                                                  </tr>
                                            <?php endwhile;?>
                                                 
                                              </tbody>
                                          </table>
                                      </div>
                                  </div>
                                  <div class="card-footer" >
                                    <nav class="d-flex">
                                        <ul class="pagination mx-auto">
                                           <?php for( $page=1; $page <= $nombre_page; $page++) :?>
                         <li class="page-item"><a class="page-link" href="private.php?page=<?php echo$page;?>"><?php echo $page?></a></li>
                                           <?php endfor;?>
                                        </ul>
                                    </nav>
                                  </div>
                              </div>
                          </div>
                     <div class="col-xl-7 col-lg-7 col-md-7 col-sm-7 col-7">
                        <div class="card">
                            <h5 class="card-header" style="text-align: center;">Liste Des 5 Derniers Tickets Annulés</h5>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered first">
                                        <thead>
                                            <tr>
                                                <th>N° Du Voyage</th>
                                                <th>N° Du Ticket</th>
                                                <th>Date D'annulation</th>
                                                <th>Montant Pénalité</th>  
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <?php while($row3 = $req3 -> fetch()) :?>
                                                <?php
                                                    $reduc = $row3['d'];
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
                                            <tr>
                                                <td><?php echo $row3['a'];?></td>
                                                <td><?php echo $row3['b'];?></td>
                                                <td><?php echo $row3['c'];?></td>
                                               <td><?php echo $reduc;?></td>  
                                            </tr>
                                             <?php endwhile;?>  
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-5">
                        <div class="card">
                            <h5 class="card-header" style="text-align: center;">Top 5 des Caissiers</h5>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered first">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Prénom</th>
                                                <th class="text-center">Total Tickets Vendus</th>  
                                            </tr>
                                        </thead>
                                        <tbody>
                                         <?php while($row = $req -> fetch()) :?>
                                            <tr>
                                                <td><?php echo $row['c'];?></td>
                                                <td><?php echo ucfirst(strtolower($row['b']));?></td>
                                                <td class="text-center"><?php echo $row['a'];?></td>  
                                            </tr>
                                           <?php endwhile;?>  
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                   </div>
                     
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
           
            <!-- ============================================================== -->
            <!-- end footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- end wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper  -->
    <!-- ============================================================== -->
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