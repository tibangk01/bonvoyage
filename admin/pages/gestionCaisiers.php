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
    $req = $db -> prepare("SELECT * from caissiers where statutCaiss = 1");
    $req -> execute();
    $nombre_resultat_renvoye = $req -> rowCount();
    $nombre_page = ceil($nombre_resultat_renvoye / $nb_element_par_page);
    
    if( isset($_GET['page']) ){
       $page = $_GET['page'];
    }else{
      $page = 1;   
    }

    $premier_resultat_sur_la_page=($page - 1)*$nb_element_par_page;
   // echo $premier_resultat_sur_la_page; exit();

    $req = $db -> prepare("SELECT * from caissiers where statutCaiss = 1 order by dateCreationCaiss desc limit $premier_resultat_sur_la_page, $nb_element_par_page");
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
    <title>Admin | Caissiers </title>
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
                                <a class="nav-link" href="#"  aria-expanded="false"><i class="fa fa-fw fa-dollar-sign"></i>Gestion Des Caissiers</a>
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
                                <h2 class="pageheader-title">Gestion Des Caissiers </h2>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-md-center">
                        <div class="col-12 text-center">
                             <?php if(isset($_SESSION['CCreated'])):?>
                                        <button class="d-none" id="btnCCreated">Bouton</button>
                                        <span id="CCreated" class="alert alert-success "></span>
                                <?php unset($_SESSION['CCreated']); ?>
                             <?php endif;?>
                              <?php if(isset($_SESSION['pseudoUsed'])):?>
                                        <button class="d-none" id="btnPseudoUsed">Bouton</button>
                                        <span id="pseudoUsed" class="alert alert-danger "></span>
                                <?php unset($_SESSION['pseudoUsed']); ?>
                             <?php endif;?>
                             <?php if(isset($_SESSION['nothingDone'])):?>
                                        <button class="d-none" id="btnNothingDone">Bouton</button>
                                        <span id="nothingDone" class="alert alert-info "></span>
                                <?php unset($_SESSION['nothingDone']); ?>
                             <?php endif;?>
                              <?php if(isset($_SESSION['updateCSuccess']) && $_SESSION['updateCSuccess'] == true ):?>
                                        <button class="d-none" id="btnUpdateCSuccess">Bouton</button>
                                        <span id="updateCSuccess" class="alert alert-info d-done"></span>
                                <?php unset($_SESSION['updateCSuccess']); ?>
                             <?php endif;?>
                              <?php if(isset($_SESSION['duplicatedCaissFound']) && $_SESSION['duplicatedCaissFound'] == true ):?>
                                        <button class="d-none" id="btnDuplicatedCaissFound">Bouton</button>
                                        <span id="duplicatedCaissFound" class="alert alert-danger d-done"></span>
                                <?php unset($_SESSION['duplicatedCaissFound']); ?>
                             <?php endif;?>
                             <?php if(isset($_SESSION['duplicatedPassFound']) && $_SESSION['duplicatedPassFound'] == true ):?>
                                        <button class="d-none" id="btnDuplicatedPassFound">Bouton</button>
                                        <span id="duplicatedPassFound" class="alert alert-danger d-done"></span>
                                <?php unset($_SESSION['duplicatedPassFound']); ?>
                             <?php endif;?>

                             
                        </div>
                    </div>
                    <div class="row justify-content-md-center" style="margin-top: 2%;">
                             <div class="col-5">
                                <form action="traitCaiss.php" method="POST" id="formCaiss">
                                        <?php if(isset($_SESSION['UnumCaiss'])) :?>
                                             <input type="hidden" name="modificationC" value="1"> 
                                             <input type="hidden" name="idModificationC" value="<?php echo $_SESSION['UnumCaiss'];?>"> 
                                        <?php else:?>
                                             <input type="hidden" name="modificationC" value="0"> 
                                        <?php endif;?>
                                      <div class="form-row">
                                        <div class="form-group col-md-6">
                                          <input type="text" class="form-control" name="nomCaiss"  id="nomCaiss" placeholder="Nom" required="" autofocus=""
                                           value="<?php if(isset($_SESSION['UnomCaiss'])){ echo $_SESSION['UnomCaiss'];}  unset($_SESSION['UnomCaiss']);?>" >
                                        </div>
                                        <div class="form-group col-md-6">
                                          <input type="text" class="form-control" name="prenomCaiss" id="prenomCaiss" placeholder="Prénom" required=""
                                          value="<?php if(isset($_SESSION['UprenomCaiss'])){ echo $_SESSION['UprenomCaiss'];}  unset($_SESSION['UprenomCaiss']);?>">
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <input type="tel" class="form-control" name="telCaiss" id="telCaiss" placeholder="Téléphone ( Ex : +228..., 90..., ... )" required=""
                                        value="<?php if(isset($_SESSION['UtelCaiss'])){ echo $_SESSION['UtelCaiss'];}  unset($_SESSION['UtelCaiss']);?>">
                                      </div>
                                      <div class="form-group">
                                        <input type="text" class="form-control" name="identifiantCaiss" id="identifiantCaiss" placeholder="Identifiant ( au moins 6 caractères )" required="" value="<?php if(isset($_SESSION['UidentifiantCaiss'])){ echo $_SESSION['UidentifiantCaiss'];}  unset($_SESSION['UidentifiantCaiss']);?>">
                                      </div>
                                      <div class="form-group">
                                        
                                        <input type="<?php if( isset( $_SESSION['UnumCaiss'] ) ){ echo 'text'; }else{echo 'password'; } ?>" class="form-control" name="mdpCaiss" id="mdpCaiss" placeholder="<?php if(isset( $_SESSION['UnumCaiss'] ) ){ echo "Nouveau Mot De Passe (8 caractères au moins) "; }else{ echo"Mot De Passe ( au moins 8 caractères )"; } ?>" required=""> 
                                      </div>
                                       <?php if(isset($_SESSION['UnumCaiss'])) :?>
                                        <div class="form-group">
                                          <input type="password" class="form-control" name="confirmMdpCaiss" id="confirmMdpCaiss" placeholder="Confirmation De Mot De Passe" required="">
                                        </div>
                                      <?php endif;?>
                                      <div class="form-group text-center">
                                          <button type="submit" class="btn btn-primary btn-sm"><?php if(isset($_SESSION['UnumCaiss'])){echo"Modifier";}else{echo"Ajouter";}unset($_SESSION['UnumCaiss']);?></button>
                                          <button type="reset" id="formCaissReset" class="btn btn-primary btn-sm">Effacer</button>
                                      </div>
                                </form>
                            </div>
                    </div>
                    <div class="row justify-content-md-center" style="margin-top: 8px;">
                        <div class="col-12 text-center">
                            <span id="messageErreurGeneralCaiss" class="alert alert-danger d-none"></span>
                             <?php if(isset($_SESSION['CDeleted']) && $_SESSION['CDeleted'] == true ):?>
                                        <button class="d-none" id="btnCDeleted">Bouton</button>
                                        <span id="CDeleted" class="alert alert-danger d-done"></span>
                                <?php unset($_SESSION['CDeleted']); ?>
                             <?php endif;?>
                        </div>

                    </div>
                    <div class="row justify-content-md-center" style="margin-top: 2%;">
                         <div class="col-11">
                             <div class="card text-center">
                                <h5 class="card-header" style="text-align: center;">Liste Caissiers Enrégistrés</h5>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered first">
                                            <thead>
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Nom</th>
                                                    <th>Prénom</th>
                                                    <th>Téléphone</th>
                                                    <th>Date D'ajout</th>  
                                                    <th>Modifier</th>
                                                    <th>Supprimer</th>
                                                </tr>
                                            </thead>
                                            <tbody><?php $i =$premier_resultat_sur_la_page;?>
                                                 <?php while($row = $req -> fetch()) :?>
                                                    <tr>
                                                        <td><?php $i+=1;echo $i;?></td>
                                                        <td><?php echo $row['nomCaiss'];?></td>
                                                        <td><?php echo $row['prenomCaiss'];?></td>
                                                        <td><?php echo $row['telCaiss'];?></td>
                                                        <td><?php echo $row['dateCreationCaiss'];?></td>  
                                                         <td><a href="traitCaiss.php?idUpdateC=<?php echo $row['numCaiss'];?>" class="btn btn-primary btn-sm">Modifier</a></td>
                                                         <td><a href="traitCaiss.php?idSupprimerC=<?php echo $row['numCaiss'];?>"class="btn btn-danger btn-sm">Supprimer</a></td>
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
                        <li class="page-item"><a class="page-link" href="gestionCaisiers.php?page=<?php echo$page;?>"><?php echo $page?></a></li>
                    <?php endfor;?>
                                          
                                        </ul>
                                    </nav>
                                  </div>
                            </div>
                         </div>
                    </div>
    
                </div>
            </div>
            <!-- liste des button triggers-->
            
   <?php $req -> closeCursor(); ?>
    <!-- Optional JavaScript -->
    <!-- jquery 3.3.1 -->
    <script src="../../assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="../js/scriptAdmin.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
           $("#btnCCreated").trigger('click');
            $("#btnNothingDone").trigger('click');
            $("#btnPseudoUsed").trigger('click');
           $("#btnCDeleted").trigger('click');
           $("#btnUpdateCSuccess").trigger('click');
           $("#btnDuplicatedCaissFound").trigger('click');
           $("#btnDuplicatedPassFound").trigger('click');
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