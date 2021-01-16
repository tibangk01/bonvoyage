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

    // systeme de pagination : 
    $nb_element_par_page = 4 ;
    $req = $db -> prepare("SELECT * from tarifs where statutTarif = 1");
    $req -> execute();
    $nombre_resultat_renvoye = $req -> rowCount();
    $nombre_page = ceil($nombre_resultat_renvoye / $nb_element_par_page);
    
    if( isset($_GET['page']) ){
       $page = $_GET['page'];
    }else{
      $page = 1;   
    }

    $premier_resultat_sur_la_page=($page - 1)*$nb_element_par_page;

    $req = $db -> prepare("SELECT * from tarifs where statutTarif = 1 order by dateModifTarif desc limit $premier_resultat_sur_la_page, $nb_element_par_page");
    $req -> execute();

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
    <title>Admin | Tarifs</title>
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
                                <a class="nav-link" href="#"  aria-expanded="false"><i class="fa fa-fw fa-money-bill-alt"></i>Gestion Des Tarifs</a>
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

       
        <div class="dashboard-wrapper">
                <div class="container-fluid dashboard-content ">
                     <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="page-header">
                                <h2 class="pageheader-title">Gestion Des Tarifs</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-md-center" style="margin: 2%;">
                        <div class="col-12 text-center">
                          <?php if(isset($_SESSION['TACreated'])):?>
                                  <button class="d-none" id="btnTACreated">Bouton</button>
                                  <span id="TACreated" class="alert alert-success "></span>
                          <?php unset($_SESSION['TACreated']); ?>
                         <?php endif;?>
                         <?php if(isset($_SESSION['updateTASuccess']) && $_SESSION['updateTASuccess'] == true ):?>
                                    <button class="d-none" id="btnUpdateTASuccess">Bouton</button>
                                    <span id="updateTASuccess" class="alert alert-info d-done"></span>
                            <?php unset($_SESSION['updateTASuccess']); ?>
                         <?php endif;?>
                           <?php if(isset($_SESSION['duplicatedTAFound']) && $_SESSION['duplicatedTAFound'] == true ):?>
                                    <button class="d-none" id="btnDuplicatedTAFound">Bouton</button>
                                    <span id="duplicatedTAFound" class="alert alert-danger d-done"></span>
                            <?php unset($_SESSION['duplicatedTAFound']); ?>
                         <?php endif;?>

                        </div>
                     </div>   
                     <div class="row justify-content-md-center">
                         <div class="col-4" >
                              <form action="traitCreationTarifs.php" method="POST" id="formCreationTarif">
                                <?php if(isset($_SESSION['UnumTA'])) :?>
                                     <input type="hidden" name="modificationTA" value="1"> 
                                     <input type="hidden" name="idModificationTA" value="<?php echo $_SESSION['UnumTA'];?>"> 
                                <?php else:?>
                                     <input type="hidden" name="modificationTA" value="0"> 
                                <?php endif;?>
                                <div class="form-group">
                                  <label for="montantTarif">Ajouter un Tarif/Km : 125.50, 300,....</label>
                                  <input class="form-control" type="text" name="montantTarif" id="montantTarif" autofocus="" required="" value="<?php if(isset($_SESSION['UvaleurTA'])){ echo $_SESSION['UvaleurTA'];}  unset($_SESSION['UvaleurTA']);?>"> 
                                </div> 
                                
                                <div class="form-group text-center">
                                    <input  class="btn btn-outline-primary btn-sm" type="submit" value="<?php if(isset($_SESSION['UnumTA'])){echo"Modifier";}else{echo"Ajouter";}unset($_SESSION['UnumTA']);?>">
                                    <input  class="btn btn-outline-primary btn-sm" type="reset" id="formCreationValeurTAReset" value="Annuler"> 
                                </div>
                              </form>
                         </div>
                     </div>
                     <div class="row justify-content-md-center" style="margin:2%;">
                        <div class="col-12 text-center">
                            <span id="messageErreurValeurTarif" class="alert alert-danger d-none"></span>
                            <?php if(isset($_SESSION['TADeleted']) && $_SESSION['TADeleted'] == true ):?>
                                        <button class="d-none" id="btnTADeleted">Bouton</button>
                                        <span id="TADeleted" class="alert alert-danger d-done"></span>
                                <?php unset($_SESSION['TADeleted']); ?>
                             <?php endif;?>
                        </div>
                      </div>
                      <div class="row  justify-content-md-center">
                          <div class="col-10">
                              <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered first">
                                                <thead>
                                                    <tr>
                                                        <th>N°</th>
                                                        <th>Dates de création</th>
                                                        <th>Tarifs (Prix/km)</th>
                                                        <th>Modifier</th>
                                                        <th>Supprimer</th>
                                                    </tr>
                                                </thead>
                                                <tbody><?php $i =$premier_resultat_sur_la_page;?>
                                                  <?php while($row = $req -> fetch()) :?>
                                                    <tr>
                                                      <td><?php $i+=1;echo $i;?></td>
                                                       <td><?php echo $row['dateModifTarif']?></td> 
                                                      <td><?php echo $row['valeurTarif']?></td> 
                                                      <td class="text-center"><a href="traitCreationTarifs.php?idUpdateTA=<?php echo $row['numTarif'];?>" class="btn btn-primary btn-sm text">Modifier</a></td>
                                                      <td class="text-center"><a href="traitCreationTarifs.php?idSupprimerTA=<?php echo $row['numTarif'];?>"class="btn btn-danger btn-sm">Supprimer</a></td>
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
                                                  <li class="page-item"><a class="page-link" href="gestionTarifs.php?page=<?php echo$page;?>"><?php echo $page?></a></li>
                                               <?php endfor;?> 
                                          </ul>
                                      </nav>
                                    </div>
                                </div>
                          </div>
                      </div>
                    </div>
                </div>
            </div>

            <?php $req -> closeCursor();?>
   
    <!-- Optional JavaScript -->
    <!-- jquery 3.3.1 -->
     <script src="../../assets/vendor/jquery/jquery-3.3.1.min.js"></script>
     <script type="text/javascript" src="../js/scriptAdmin.js"></script>
     <script type="text/javascript">
       $(document).ready(function () {  
           $("#btnTACreated").trigger('click');
           $("#btnTADeleted").trigger('click');
           $("#btnUpdateTASuccess").trigger('click');
           $("#btnDuplicatedTAFound").trigger('click');
       });
      </script>
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
   
    <!-- chart c3 js -->
    <script src="../../assets/vendor/charts/c3charts/c3.min.js"></script>
    <script src="../../assets/vendor/charts/c3charts/d3-5.4.0.min.js"></script>
    <script src="../../assets/vendor/charts/c3charts/C3chartjs.js"></script>
</body>
 
</html>