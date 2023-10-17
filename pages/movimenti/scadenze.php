<?php
	session_start();
	include_once '../../database.php';
	$database = new Database();
	$db = $database->getConnection();


	if (!isset($_SESSION['user_vest'])) {
		header("location: ../login/login.php");
		exit;
	}	

	$id_user=$_SESSION['id_user_vest'];
	$id_rep_user=$_SESSION['vest_id_rep'];
	$id_sr_user=$_SESSION['vest_id_sr'];
	$is_admin=$_SESSION['vest_access'];
	if ($is_admin!=1) header("location: ../../index.php");
	
	include_once '../../MVC/Models/M_scadenze.php';
	include_once '../../MVC/Controllers/C_scadenze.php';
	
	
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Scadenze</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../../index.php" class="nav-link">Home</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>



     
	 <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

	<?php
	
	$path="../../";	
	include ("../../side_menu.php");
	?>	

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">SCADENZE</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item">Pages</li>
			  <li class="breadcrumb-item active">scadenze</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->




    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">



		<div class="row">  
          <!-- /.col-md-6 -->
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="m-0">Elenco</h5>
              </div>
              <div class="card-body">
				<form action="scadenze.php" method="post" id='frm_scad1' name='frm_scad1'>  		  
					<div class="row">  

						<div class="col-lg-3 col-md-3 form-group" >
							<div class="form-group">
								<label for="min_scad">Visualizza scadenze inferiori a</label>
								<input type='date' name='min_scad' id='min_scad' value="<?php echo $min_scad; ?>" class="form-control" required >
							</div>
						</div>	
						<div class="col-lg-2 col-md-2 form-group" >
							<label for="btn_avvia"> </label><br>
							<button class="btn btn-primary" type="submit" id='btn_filtro' name='btn_filtro' >Filtra</button>
							<?php
								if (isset($_POST['btn_filtro'])) {
									echo "<button class='btn btn-secondary' type='submit' id='btn_reset' name='btn_reset'>Reset</option>"; 
								}
							?>
						</div>	
					</div>
				</form>
				

				<form action="scadenze.php" method="post" id='frm_scad2' name='frm_scad2'>  		  
					<table id="example1" class="table table-bordered table-striped">
					  <thead>
					  <tr>
						<th>Scadenza: Prossima/Scaduto</th>
						<th>Dipendente</th>
						<th>Prodotto</th>
						<th>Taglia</th>
						<th>Consegnato il</th>
						<th style='text-align:center'>
							<button class="btn btn-primary mb-3" type="submit" id='btn_avvia' name='btn_avvia' >
								Annulla scadenze evidenziate
							</button>
						</th>
					  </tr>
					  </thead>
					  <tbody>
							<?php
								$today=date("Y-m-d");
								for ($sca=0;$sca<=count($elenco)-1;$sca++) {
									$id_ref=$elenco[$sca]['id_ref'];
									$taglia=$elenco[$sca]['taglia'];
									$dipendente=stripslashes($elenco[$sca]['dipendente']);
									$descrizione=stripslashes($elenco[$sca]['descrizione']);
									$data_scadenza=$elenco[$sca]['data_scadenza'];
									$tipo_scad="<font color='orange'>Prossima</font>";
									if ($data_scadenza<=$today) $tipo_scad="<font color='red'>Scaduto</font>";
									$data_scadenza=date("d-m-Y",strtotime($data_scadenza));

									echo "<tr>";
										echo "<td>$tipo_scad</td>";
										echo "<td>$dipendente</td>";
										echo "<td>$descrizione</td>";
										echo "<td>$taglia</td>";
										echo "<td>$data_scadenza</td>";
										echo "<td style='text-align:center'>";
											echo "<input class='form-check-input' type='checkbox' value='$id_ref' id='scad$sca' name='scad[]'>";
										echo "</td>";
									echo "</tr>";
									
								}
							?>		
					  </tbody>
					  <tfoot>

					  </tfoot>
					</table>
				</form>

                
              </div>
            </div>

          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Opzioni</h5>
      <p>Operazioni aggiuntive...</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      JCsnc
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; <?php echo date("Y");?> <a href="https://www.liofilchem.com">Liofilchem</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../plugins/jszip/jszip.min.js"></script>
<script src="../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<script src="../../dist/js/adminlte.min.js"></script>

<?php if (1==2) {?>
	<!-- AdminLTE App -->
	<!-- AdminLTE for demo purposes -->
	<script src="../../dist/js/demo.js"></script>
<?php } ?>

<script>
  $(function () {
    $("#example1").DataTable({
		"responsive": true, "lengthChange": false, "autoWidth": false,
		//, "colvis"
		"buttons": ["copy", "excel", "pdf"],
		"language": {
			"lengthMenu": "Mostra _MENU_ prodotti per pagina &nbsp&nbsp",
			"zeroRecords": "Nessun prodotto trovato",
			"info": "Pagina mostrata _PAGE_ di _PAGES_ di _TOTAL_ prodotti",
			"infoEmpty": "Non ci sono prodotti",
			"infoFiltered": "(filtrate da _MAX_ record totali)",
			"search":         "Cerca:",
			"paginate": {
				"first":      "Prima",
				"last":       "Ultima",
				"next":       "Successiva",
				"previous":   "Precedente"
			}
		
		}	  
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>

</body>
</html>
