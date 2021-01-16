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
    <title>Admin | Cinkanssé - Lomé </title>
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
                                <a class="nav-link" href="gestionCaisiers.php"  aria-expanded="false"><i class="fa fa-fw fa-dollar-sign"></i>Gestion Des Caisiers</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="gestionVoyages.php"  aria-expanded="false"><i class="fa fa-fw fa-shipping-fast"></i>Gestion Des Voyages</a>
                            </li>
                           <li class="nav-item">
                                <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-1-2" aria-controls="submenu-1-2"><i class="fa fa-fw fa-road"></i>Gestion Des Trajets</a>
                                <div id="submenu-1-2" class="collapse submenu" style="">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="trajetUn.php">Cinkanssé - Lomé </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="trajetDeux.php">Lomé  - Cinkanssé</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="gestionTarifs.php"  aria-expanded="false"><i class="fa fa-fw fa-money-bill-alt"></i>Gestion Des Tarifs</a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link" href="listeAnnulation.php"  aria-expanded="false"><i class="fa fa-fw fa-list"></i>Liste Des Annulations</a>
                            </li
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
                                                  <tr>
                                                    <td>Cinkanssé - Lomé </td>
                                                    <td> 610</td>
                                                    <td>Cinkanssé</td> 
                                                    <td>Lomé</td>
                                                     
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
                                <div class="col-12">
                                   <div class="card">
                                    <h5 class="card-header" style="text-align: center;">Créer Une Nouvelle Ville Escale</h5>
                                       <div class="cord-body">
                                           <div class="row justify-content-md-center">
                                               <div class="col-10">
                                                   <form action="">
                                                        <div class="form-group row justify-content-md-center">
                                                          <div class="col-sm-12 ">
                                                            <input type="text" class="form-control" id="" placeholder="Entrer Une Ville : Mango, Lomé,...">
                                                          </div>
                                                        </div>
                                                        <div class="form-group row justify-content-md-center">
                                                          <div class="col-sm-12">
                                                            <input type="text" class="form-control" id="" placeholder="Entrer Une Distance (Km) : 250, 300,...">
                                                          </div>
                                                        </div>
                                                        <div class="form-group text-center">
                                                          <input  class="btn btn-outline-primary btn-sm " type="submit" value="Ajouter">
                                                          <input  class="btn btn-outline-primary btn-sm " type="reset" id="" value="Annuler"> 
                                                       </div>
                                                   </form>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="card">
                                <h5 class="card-header" style="text-align: center;">Liste Des Villes Escales Du Trajet</h5>
                                  <div class="card-body">
                                      <div class="table-responsive">
                                          <table class="table table-striped table-bordered first text-center">
                                              <thead>
                                                 <th>Nom De La Ville</th>
                                                <th>Distance A Partir De Cinkanssé</th>
                                                <th>Modifier</th>
                                                <th>Supprimer</th>
                                              </thead>
                                              <tbody>
                                                  <tr>
                                                     <td>Dapaong</td>
                                                     <td>35</td>
                                                     <td><a href="" class="btn btn-primary btn-sm">Modifier</a></td>
                                                     <td><a href=""class="btn btn-danger btn-sm">Supprimer</a></td>
                                                  </tr>
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
    <script src="../../assets/libs/js/dashboard-ecommerce.js"></script>
</body>
 
</html>