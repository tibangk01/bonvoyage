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
    $req = $db -> prepare("SELECT * from voyages where statutVoyage = 1");
    $req -> execute();
    $nombre_resultat_renvoye = $req -> rowCount();
    $nombre_page = ceil($nombre_resultat_renvoye / $nb_element_par_page);
    
    if( isset($_GET['page']) ){
       $page = $_GET['page'];
    }else{
      $page = 1;   
    }

    $premier_resultat_sur_la_page=($page - 1)*$nb_element_par_page;

    # requete affichage des informations dans le tableau :
    $stmt ="SELECT voyages.numVoyage as a,
                   bus.nbPlacesBus as b,
                   trajets.libVilleDepTrajet as c_1,
                   trajets.libVilleArrTrajet as c_2,
                   tarifs.valeurTarif as d,
                   voyages.dateVoyage as e 
            FROM voyages
            INNER JOIN bus
            ON voyages.numBus = bus.numBus
            INNER JOIN trajets
            ON voyages.numTrajet = trajets.numTrajet
            INNER JOIN tarifs
            ON voyages.numTarif = tarifs.numTarif
            WHERE voyages.statutVoyage = 1
            ORDER BY voyages.dateCreation DESC 
            lIMIT $premier_resultat_sur_la_page, $nb_element_par_page"; 
    $req6 = $db -> prepare($stmt);
    $req6 -> execute();  

    $req2 = $db -> prepare("SELECT * from trajets where statutTrajet = 1 order by libVilleDepTrajet");
    $req2 -> execute(); 

    # requete pour les trajets :
    $req3 = $db -> prepare("SELECT * from trajets where statutTrajet = 1 order by libVilleDepTrajet");
    $req3 -> execute(); 

    # requete pour les tarifs :
    $req4 = $db -> prepare("SELECT * from tarifs where statutTarif = 1 order by valeurTarif");
    $req4 -> execute(); 

    # requete pour les bus : 
    $req5 = $db -> prepare("SELECT * from bus where statutBus = 1 order by marqueBus");
    $req5 -> execute();  
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
    <title>Admin | Voyages </title>
    <style>
        select{
            text-align-last: center;
        }
    </style>
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
                                        <?php while( $row2 = $req2 -> fetch()):?>
                                             <li class="nav-item">
                                                <a class="nav-link" href="listeTrajets.php?idTrajet= <?php echo$row2['numTrajet'];?>"><?php echo ucfirst(strtolower($row2['libVilleDepTrajet'])).' -> '.ucfirst(strtolower($row2['libVilleArrTrajet']));?></a> 
                                            </li>
                                        <?php endwhile;?>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"  aria-expanded="false"><i class="fa fa-fw fa-shipping-fast"></i>Gestion Des Voyages</a>
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
                                <h2 class="pageheader-title">Gestion Des Voyages</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-md-center" style="margin: 2%;">
                         <div class="col-12 text-center">
                          <?php if(isset($_SESSION['voyageCreated'])):?>
                                  <button class="d-none" id="btnVoyageCreated">Bouton</button>
                                  <span id="voyageCreated" class="alert alert-success "></span>
                          <?php unset($_SESSION['voyageCreated']); ?>
                         <?php endif;?>
                         <?php if(isset($_SESSION['updateVoyageSuccess']) && $_SESSION['updateVoyageSuccess'] == true ):?>
                                    <button class="d-none" id="btnUpdateVoyageSuccess">Bouton</button>
                                    <span id="updateVoyageSuccess" class="alert alert-info d-done"></span>
                            <?php unset($_SESSION['updateVoyageSuccess']); ?>
                         <?php endif;?>
                          <?php if(isset($_SESSION['DVDepassee']) && $_SESSION['DVDepassee'] == true ):?>
                                    <button class="d-none" id="btnDVDepassee">Bouton</button>
                                    <span id="DVDepassee" class="alert alert-danger d-done"></span>
                            <?php unset($_SESSION['DVDepassee']); ?>
                         <?php endif;?>
                           <?php if(isset($_SESSION['duplicatedVoyageFound']) && $_SESSION['duplicatedVoyageFound'] == true ):?>
                                    <button class="d-none" id="btnDuplicatedVoyageFound">Bouton</button>
                                    <span id="duplicatedVoyageFound" class="alert alert-danger d-done"></span>
                            <?php unset($_SESSION['duplicatedVoyageFound']); ?>
                         <?php endif;?>

                        </div>
                    </div>
                    <div class="row justify-content-md-center">
                        <div class="col-5">
                            <form action="traitVoyage.php" method="POST" id="formVoyage">
                                 <?php if(isset($_SESSION['UnumVoyage'])) :?>
                                     <input type="hidden" name="modificationV" value="1"> 
                                     <input type="hidden" name="idModificationV" value="<?php echo $_SESSION['UnumVoyage'];?>"> 
                                <?php else:?>
                                     <input type="hidden" name="modificationV" value="0"> 
                                <?php endif;?>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                         <select name="valeurTRV" id="valeurTRV" class="form-control">
                                            <option value="0" class="d-none" >==== Choisir Un Trajet ====</option>
                                            <?php while($row3 = $req3 -> fetch()) :?>
                                               <?php if(!isset($_SESSION['UnumTrajet'])):?>
                                                     <option value="<?php echo $row3['numTrajet'];?>"><?php echo ucfirst(strtolower($row3['libVilleDepTrajet'])).' ---> '.ucfirst(strtolower($row3['libVilleArrTrajet']));?></option>
                                               <?php else:?>
                                                    <?php if($_SESSION['UnumTrajet'] == $row3['numTrajet']):?>
                                                         <option selected="" value="<?php echo $row3['numTrajet'];?>"><?php echo ucfirst(strtolower($row3['libVilleDepTrajet'])).' ---> '.ucfirst(strtolower($row3['libVilleArrTrajet']));?></option>
                                                    <?php else :?>
                                                         <option value="<?php echo $row3['numTrajet'];?>"><?php echo ucfirst(strtolower($row3['libVilleDepTrajet'])).' ---> '.ucfirst(strtolower($row3['libVilleArrTrajet']));?></option>
                                                    <?php endif;?>
                                               <?php endif;?>
                                            <?php endwhile; unset($_SESSION['UnumTrajet']);?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                         <select name="valeurTA" id="valeurTA" class="form-control">
                                            <option value="0" class="d-none" >==== Choisir Un Tarif/Km ====</option>
                                            <?php while($row4 = $req4 -> fetch()):?>
                                                <?php if(!isset($_SESSION['UnumTarif'])):?>
                                                     <option value="<?php echo $row4['numTarif'];?>"><?php echo $row4['valeurTarif']?></option>
                                               <?php else:?>
                                                    <?php if($_SESSION['UnumTarif'] == $row4['numTarif']):?>
                                                         <option selected="" value="<?php echo $row4['numTarif']?>"><?php echo $row4['valeurTarif']?></option>
                                                    <?php else :?>
                                                         <option value="<?php echo $row4['numTarif']?>"><?php echo $row4['valeurTarif']?></option>
                                                    <?php endif;?>
                                               <?php endif;?>
                                            <?php endwhile; unset($_SESSION['UnumTarif']);?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                         <select name="valeurBV" id="valeurBV" class="form-control">
                                            <option value="0" class="d-none" >==== Choisir Un Type de bus ====</option>
                                             <?php while($row5 = $req5 -> fetch()) :?>
                                                <?php if(!isset($_SESSION['UnumBus'])):?>
                                                    <option value="<?php echo $row5['numBus']?>"><?php echo $row5['marqueBus'].' - '.$row5['nbPlacesBus'].' places';?></option>
                                               <?php else:?>
                                                    <?php if($_SESSION['UnumBus'] == $row5['numBus']):?>
                                                         <option selected="" value="<?php echo $row5['numBus']?>"><?php echo $row5['marqueBus'].' - '.$row5['nbPlacesBus'].' places';?></option>
                                                    <?php else :?>
                                                         <option value="<?php echo $row5['numBus']?>"><?php echo $row5['marqueBus'].' - '.$row5['nbPlacesBus'].' places';?></option>
                                                    <?php endif;?>
                                               <?php endif;?>
                                            <?php endwhile; unset($_SESSION['UnumBus']);?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                     <div class="col-sm-12">
                                        <input type="date" required="" id="valeurDV" name="valeurDV" class="form-control" data-toggle="tooltip" data-placement="right" title="Entrez ou choisissez La Date Du Voyage" value="<?php if(isset($_SESSION['UnumDate'])){echo $_SESSION['UnumDate'];unset($_SESSION['UnumDate']);}?>">
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                  <input  class="btn btn-primary btn-sm " type="submit" value="<?php if(isset($_SESSION['UnumVoyage'])){echo"Modifier";}else{echo"Ajouter";}unset($_SESSION['UnumVoyage']);?>">
                                  <input  class="btn btn-primary btn-sm " type="reset" id="formVoyageReset" value="Annuler"> 
                               </div>
                            </form>
                        </div>
                    </div>
                     <div class="row justify-content-md-center" style="margin:2%;">
                                <div class="col-12 text-center">
                                    <span id="messageErreurVoyage" class="alert alert-danger d-none"></span>
                                     <?php if(isset($_SESSION['voyageDeleted']) && $_SESSION['voyageDeleted'] == true ):?>
                                                <button class="d-none" id="btnVoyageDeleted">Bouton</button>
                                                <span id="voyageDeleted" class="alert alert-danger d-done"></span>
                                        <?php unset($_SESSION['voyageDeleted']); ?>
                                     <?php endif;?>
                                </div>
                            </div>
                    <div class="row justify-content-md-center">
                         <div class="col-12">
                             <div class="card text-center">
                                <h5 class="card-header" style="text-align: center;">Liste Des Voyages Enrégistrés</h5>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered first">
                                            <thead>
                                                <tr>
                                                    <th>Numéro Voyage</th>
                                                    <th>Nombre De Places</th>
                                                    <th>Trajet</th>
                                                    <th>Tarif au Km</th>  
                                                    <th>Date Du Voyage</th>
                                                    <th>Modifier</th>
                                                    <th>Supprimer</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                              <?php while( $row6 = $req6 -> fetch()):?>
                                                <tr>
                                                    <td><?php echo $row6['a'];?></td>
                                                    <td><?php echo $row6['b'];?></td>
                                                    <td><?php echo $row6['c_1'].' - '.$row6['c_2'];?></td>
                                                    <td><?php echo $row6['d'];?></td>  
                                                    <td><?php echo $row6['e'];?></td>
                                                     <td><a href="traitVoyage.php?idUpdateVoyage=<?php echo $row6['a'];?>" class="btn btn-primary btn-sm">Modifier</a></td>
                                                    <td><a href="traitVoyage.php?idSupprimerVoyage=<?php echo $row6['a'];?>" class="btn btn-danger btn-sm">Supprimer</a></td>
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
                         <li class="page-item"><a class="page-link" href="gestionVoyages.php?page=<?php echo$page;?>"><?php echo $page?></a></li>
                                           <?php endfor;?> 
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
   


    <script src="../../assets/vendor/jquery/jquery-3.3.1.min.js"></script>
     <script type="text/javascript" src="../js/scriptAdmin.js"></script>
     <script type="text/javascript">
       $(document).ready(function () {  
           $("#btnVoyageCreated").trigger('click');
           $("#btnTADeleted").trigger('click');
           $("#btnUpdateVoyageSuccess").trigger('click');
           $("#btnVoyageDeleted").trigger('click');
           $("#btnDVDepassee").trigger('click');
           $("#btnDuplicatedVoyageFound").trigger('click');
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