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

    $nb_element_par_page = 4 ;
    $req = $db -> prepare("SELECT * from bus where statutBus = 1");
    $req -> execute();
    $nombre_resultat_renvoye = $req -> rowCount();
    $nombre_page = ceil($nombre_resultat_renvoye / $nb_element_par_page);
    
    if( isset($_GET['page']) ){
       $page = $_GET['page'];
    }else{
      $page = 1;   
    }

    $premier_resultat_sur_la_page=($page - 1)*$nb_element_par_page;

    $req = $db -> prepare("SELECT * from bus where statutBus = 1 order by dateModifBus desc limit $premier_resultat_sur_la_page, $nb_element_par_page");
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
    <title>Admin | Gestion Des Bus </title>
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
                            <a class="dropdown-item" href="#"><i class="fas fa-user mr-2"></i>Déconnexion</a>
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
                                <a class="nav-link" href="#"  aria-expanded="false"><i class="fa fa-fw fas fa-bus"></i>Gestion Des Bus</a>
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

        <div class="dashboard-wrapper">
                <div class="container-fluid dashboard-content ">
                     <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="page-header">
                                <h2 class="pageheader-title">Gestion Des Bus De Voyage</h2>
                            </div>
                        </div>
                    </div>
                     <div class="row justify-content-md-center" style="margin: 3%;">
                      <div class="col-12 text-center">
                          <?php if(isset($_SESSION['BCreated'])):?>
                                  <button class="d-none" id="btnBCreated">Bouton</button>
                                  <span id="BCreated" class="alert alert-success "></span>
                          <?php unset($_SESSION['BCreated']); ?>
                         <?php endif;?>
                         <?php if(isset($_SESSION['updateBSuccess']) && $_SESSION['updateBSuccess'] == true ):?>
                                    <button class="d-none" id="btnUpdateBSuccess">Bouton</button>
                                    <span id="updateBSuccess" class="alert alert-info d-done"></span>
                            <?php unset($_SESSION['updateBSuccess']); ?>
                         <?php endif;?>
                           <?php if(isset($_SESSION['duplicatedBFound']) && $_SESSION['duplicatedBFound'] == true ):?>
                                    <button class="d-none" id="btnDuplicatedBFound">Bouton</button>
                                    <span id="duplicatedBFound" class="alert alert-danger d-done"></span>
                            <?php unset($_SESSION['duplicatedBFound']); ?>
                         <?php endif;?>
                      </div>
                    </div>
                    <div class="row justify-content-md-center" >
                        <div class="col-5 text-center">
                           <form action="traitBus.php" method="POST" id="formBus">
                                <?php if(isset($_SESSION['UnumB'])) :?>
                                     <input type="hidden" name="modificationB" value="1"> 
                                     <input type="hidden" name="idModificationB" value="<?php echo $_SESSION['UnumB'];?>"> 
                                <?php else:?>
                                     <input type="hidden" name="modificationB" value="0"> 
                                <?php endif;?>
                               <div class="form-group">
                                   <input type="text" class="form-control" name="marqueBus" id="marqueBus" required="" autofocus="" placeholder=" Entrez la marque du bus (Ex: Mazda, King-lung, ...)" value="<?php if(isset($_SESSION['UmarqueB'])){ echo $_SESSION['UmarqueB'];}  unset($_SESSION['UmarqueB']);?>">
                               </div>
                                <div class="form-group">
                                  <input type="text" class="form-control" name="nbPlacesBus" id="nbPlacesBus" required="" placeholder=" Entrez le nombre de places du bus (Ex : 54, 15, ...)" value="<?php if(isset($_SESSION['UnbPlacesB'])){ echo $_SESSION['UnbPlacesB'];}  unset($_SESSION['UnbPlacesB']);?>">
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary btn-sm"><?php if(isset($_SESSION['UnumB'])){echo"Modifier";}else{echo"Créer";}unset($_SESSION['UnumB']);?></button>
                                    <button type="reset" id="formBusReset" class="btn btn-primary btn-sm">Annuler</button>
                                </div>
                            </form>
                        </div>
                      </div>
                      <div class="row justify-content-md-center" style="margin:2%;">
                        <div class="col-12 text-center">
                            <span id="messageErreurBus" class="alert alert-danger d-none"></span>
                             <?php if(isset($_SESSION['BDeleted']) && $_SESSION['BDeleted'] == true ):?>
                                        <button class="d-none" id="btnBDeleted">Bouton</button>
                                        <span id="BDeleted" class="alert alert-danger d-done"></span>
                                <?php unset($_SESSION['BDeleted']); ?>
                             <?php endif;?>
                        </div>
                      </div>
                      <div class="row justify-content-md-center">
                        <div class="col-9">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered first">
                                            <thead>
                                                <tr>
                                                    <td>N°</td>
                                                    <th>Marque</th>
                                                    <th>Nombre De Places</th>
                                                    <th>Modifier</th>
                                                    <th>Supprimer</th>
                                                </tr>
                                            </thead>
                                            <tbody><?php $i =$premier_resultat_sur_la_page;?>
                                            <?php while($row = $req -> fetch()) :?>
                                                <tr>
                                                  <td><?php $i+=1;echo $i;?></td>
                                                  <td><?php echo $row['marqueBus']?></td>
                                                  <td class="text-center"><?php echo $row['nbPlacesBus']?></td> 
                                                  <td class="text-center"><a href="traitBus.php?idUpdateB=<?php echo $row['numBus'];?>" class="btn btn-primary btn-sm">Modifier</a></td>
                                                  <td class="text-center"><a href="traitBus.php?idSupprimerB=<?php echo $row['numBus'];?>"class="btn btn-danger btn-sm">Supprimer</a></td>
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
                                              <li class="page-item"><a class="page-link" href="gestionBus.php?page=<?php echo $page;?>"><?php echo $page?></a></li>
                                           <?php endfor;?> 
                                      </ul>
                                  </nav>
                                </div>
                            </div>
                        </div>
                      </div>
            </div>
   
    <!-- Optional JavaScript -->
    <!-- jquery 3.3.1 -->
    <script src="../../assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="../js/scriptAdmin.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {  
           $("#btnBCreated").trigger('click');
           $("#btnBDeleted").trigger('click');
           $("#btnUpdateBSuccess").trigger('click');
           $("#btnDuplicatedBFound").trigger('click');
       });   
    </script>
    <!-- bootstap bundle js -->
    <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- slimscroll js -->
    <script src="../../assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <!-- main js -->
    <script src="../../assets/libs/js/main-js.js"></script>
    <!-- chart chartist js -->
   
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