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
    <title>Admin | Liste Des Trajets</title>
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
                              <div class="card">
                                <h5 class="card-header" style="text-align: center;">Informations Sur Le Trajet</h5>
                                  <div class="card-body">
                                      <div class="table-responsive">
                                          <table class="table table-striped table-bordered first text-center">
                                              <thead>
                                                  <tr>
                                                      <th>Dénomination De Trajet</th>
                                                      <th>Distance Total (Km)</th>
                                                      <th>Ville de Départ</th>
                                                      <th>Ville D'arrivée</th>
                                                      
                                                  </tr>
                                              </thead>
                                              <tbody>
                                                  <?php

                                                      if (isset( $_GET['idTrajet'])) {
                                                        $id =  $_GET['idTrajet'];
                                                      } else {
                                                        $id =  $_SESSION['idTrajet'];
                                                      }
                                                      
                                                        $req = $db -> prepare("SELECT * from trajets where numTrajet = :a");
                                                        $req -> execute(array('a' => $id ));
                                                        $row = $req -> fetch();
                                                   ?>
                                                  <tr>
                                                    <td><?php echo $row['libVilleDepTrajet'].' -> '.$row['libVilleArrTrajet']; ?></td>
                                                    <td><?php echo $row['distTotalTrajet'];?></td>
                                                    <td><?php echo $row['libVilleDepTrajet'];?></td> 
                                                    <td><?php echo $row['libVilleArrTrajet'];?></td>   
                                                  </tr>
                                              </tbody>
                                          </table>
                                      </div>
                                  </div>
                              </div>
                          </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                             <div class="row justify-content-md-center">
                                   <?php if(isset($_SESSION['longDistVEFound'])):?>
                                            <button class="d-none" id="btnLongDistVEFound">Bouton</button>
                                            <span id="longDistVEFound" class="alert alert-danger"></span>
                                      <?php unset($_SESSION['longDistVEFound']); ?>
                                   <?php endif;?>
                                   <?php if(isset($_SESSION['LVTRCreated'])):?>
                                            <button class="d-none" id="btnLVTRCreated">Bouton</button>
                                            <span id="LVTRCreated" class="alert alert-success "></span>
                                    <?php unset($_SESSION['LVTRCreated']); ?>
                                   <?php endif;?>
                                   <?php if(isset($_SESSION['updateVESuccess']) && $_SESSION['updateVESuccess'] == true ):?>
                                              <button class="d-none" id="btnUpdateVESuccess">Bouton</button>
                                              <span id="updateVESuccess" class="alert alert-info d-done"></span>
                                      <?php unset($_SESSION['updateVESuccess']); ?>
                                   <?php endif;?>
                                     <?php if(isset($_SESSION['duplicatedLVTRFound']) && $_SESSION['duplicatedLVTRFound'] == true ):?>
                                              <button class="d-none" id="btnDuplicatedLVTRFound">Bouton</button>
                                              <span id="duplicatedLVTRFound" class="alert alert-danger d-done"></span>
                                      <?php unset($_SESSION['duplicatedLVTRFound']); ?>
                                   <?php endif;?>
                                    <?php if(isset($_SESSION['duplicatedDistVEFound']) && $_SESSION['duplicatedDistVEFound'] == true ):?>
                                              <button class="d-none" id="btnDuplicatedDistVEFound">Bouton</button>
                                              <span id="duplicatedDistVEFound" class="alert alert-danger d-done"></span>
                                      <?php unset($_SESSION['duplicatedDistVEFound']); ?>
                                   <?php endif;?>
                                   <?php if(isset($_SESSION['errorVE']) && $_SESSION['errorVE'] == true ):?>
                                              <button class="d-none" id="btnErrorVE">Bouton</button>
                                              <span id="errorVE" class="alert alert-danger d-done"></span>
                                      <?php unset($_SESSION['errorVE']); ?>
                                   <?php endif;?>
                                    <?php if(isset($_SESSION['errorVDE']) && $_SESSION['errorVDE'] == true ):?>
                                              <button class="d-none" id="btnErrorVDE">Bouton</button>
                                              <span id="errorVDE" class="alert alert-danger d-done"></span>
                                      <?php unset($_SESSION['errorVDE']); ?>
                                   <?php endif;?>
                             </div>
                            <div class="row justify-content-md-center">
                                
                                <div class="col-12">
                                   <div class="card">
                                    <h5 class="card-header" style="text-align: center;">Créer Une Nouvelle Ville Escale</h5>
                                       <div class="cord-body">
                                           <div class="row justify-content-md-center">
                                               <div class="col-10">
                                                    <?php
                                                      if (isset($_GET['idTrajet'])) {
                                                         $_SESSION['idTrajet'] = $_GET['idTrajet'];
                                                      } 
                                                    ?>
                                                    
                                                   <form action="traitLVTR.php" method="POST" id="formVilleEscale">
                                                        <?php if(isset($_SESSION['UnumVE'])) :?>
                                                         <input type="hidden" name="idTrajet" value="<?php echo $_SESSION['idTrajet'];?>"> 
                                                         <input type="hidden" name="modificationLVTR" value="1"> 
                                                         <input type="hidden" name="idModificationLVTR" value="<?php echo $_SESSION['UnumVE'];?>"> 
                                                    <?php else:?>
                                                         <input type="hidden" name="idTrajet" value="<?php echo $_SESSION['idTrajet'];?>">
                                                         <input type="hidden" name="modificationLVTR" value="0"> 
                                                    <?php endif;?>
                                                        <div class="form-group row justify-content-md-center">
                                                          <div class="col-sm-12 ">
                                                            <input type="text" class="form-control" name="libVilleEscale" id="libVilleEscale" placeholder="Entrer Une Ville : Mango, Lomé,..." autofocus="" required="" value="<?php if(isset($_SESSION['UlibVE'])){ echo $_SESSION['UlibVE'];}  unset($_SESSION['UlibVE']);?>" >
                                                          </div>
                                                        </div>
                                                        <div class="form-group row justify-content-md-center">
                                                          <div class="col-sm-12">
                                                            <input type="text" class="form-control" name="distVEVAT" id="distVEVAT" required="" placeholder="Entrer Une Distance (Km) : 250, 300,..." value="<?php if(isset($_SESSION['UdistVE'])){ echo $_SESSION['UdistVE'];} unset($_SESSION['UdistVE']);?>">
                                                          </div>
                                                        </div>
                                                        <div class="form-group text-center">
                                                          <input  class="btn btn-outline-primary btn-sm " type="submit" value="<?php if(isset($_SESSION['UnumVE'])){echo"Modifier";}else{echo"Ajouter";}unset($_SESSION['UnumVE']);?>">
                                                          <input  class="btn btn-outline-primary btn-sm " type="reset" id="formVilleEscaleReset" value="Annuler"> 
                                                       </div>
                                                   </form>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                                </div>
                                <div class="col-12 text-center">
                                  <!-- messages erreur formulaire et suppression -->
                                   <span id="messageVilleEscale" class="alert alert-danger d-none">
                                </div>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="row justify-content-md-center">
                                <?php if(isset($_SESSION['VEDeleted']) && $_SESSION['VEDeleted'] == true ):?>
                                          <button class="d-none" id="btnVEDeleted">Bouton</button>
                                          <span id="VEDeleted" class="alert alert-danger d-done"></span>
                                  <?php unset($_SESSION['VEDeleted']); ?>
                               <?php endif;?>
                            </div>
                            <div class="card">
                                <h5 class="card-header" style="text-align: center;">Liste Des Villes Escales Du Trajet</h5>
                                  <div class="card-body">
                                      <div class="table-responsive">
                                          <table class="table table-striped table-bordered first text-center">
                                              <thead>
                                                 <th>Nom De La Ville</th>
                                                <th>Distance A Partir De <?php echo ucfirst(strtolower($row['libVilleDepTrajet']));?> </th>
                                                <th>Modifier</th>
                                                <th>Supprimer</th>
                                              </thead>
                                              <tbody>
                                                   <?php

                                                        if (isset( $_GET['idTrajet'])) {
                                                          $id =  $_GET['idTrajet'];
                                                        } else {
                                                          $id =  $_SESSION['idTrajet'];
                                                        }

                                                        $stmt ="SELECT villes_escales.libVilleEscale as a,
                                                                       villes_escales.numVilleEscale as b,
                                                                       avoir.distVilleEscaleVilleDepartTrajet as c
                                                                FROM avoir
                                                                INNER JOIN villes_escales
                                                                ON avoir.numVilleEscale = villes_escales.numVilleEscale
                                                                INNER JOIN trajets
                                                                ON avoir.numTrajet = trajets.numTrajet
                                                                WHERE trajets.numTrajet = :a and villes_escales.statutVilleEscale =:b
                                                                ORDER BY villes_escales.numVilleEscale desc";
                                                        $req = $db -> prepare($stmt);
                                                        $req -> execute(array('a' => $id, 'b' => 1 ));
                                                   ?>
                                                    <?php while($ligneVE = $req -> fetch()) :?>
                                                      <tr>
                                                         <td><?php echo $ligneVE['a']; ?></td>
                                                         <td><?php echo $ligneVE['c']; ?></td>
                                                         <td><a href="traitLVTR.php?idUpdateVE=<?php echo $ligneVE['b'];?>" class="btn btn-primary btn-sm">Modifier</a></td>
                                                         <td><a href="traitLVTR.php?idSupprimerVE=<?php echo $ligneVE['b'];?>"class="btn btn-danger btn-sm">Supprimer</a></td>
                                                      </tr>
                                                   <?php endwhile;?>
                                              </tbody>
                                          </table>
                                      </div>
                                  </div>
                              </div>
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
           $("#btnErrorVE").trigger('click');
           $("#btnErrorVDE").trigger('click');
           $("#btnLVTRCreated").trigger('click');
           $("#btnVEDeleted").trigger('click');
           $("#btnDuplicatedDistVEFound").trigger('click');
           $("#btnUpdateVESuccess").trigger('click');
           $("#btnDuplicatedLVTRFound").trigger('click');
           $("#btnLongDistVEFound").trigger('click');
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
    <script src="../../assets/vendor/charts/morris-bundle/morris.js"></script>
    <!-- chart c3 js -->
    <script src="../../assets/vendor/charts/c3charts/c3.min.js"></script>
    <script src="../../assets/vendor/charts/c3charts/d3-5.4.0.min.js"></script>
    <script src="../../assets/vendor/charts/c3charts/C3chartjs.js"></script>
   
</body>
 
</html>