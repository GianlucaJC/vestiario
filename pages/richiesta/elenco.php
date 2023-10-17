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
	
	include_once '../../MVC/Models/M_main.php';
	include_once '../../MVC/Models/M_richieste.php';
	include_once '../../MVC/Controllers/C_richieste.php';
	

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
  <title>Elenco Richieste</title>

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
            <h1 class="m-0">RICHIESTE</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item">Pages</li>
			  <li class="breadcrumb-item active">Richieste</li>
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
			<form action="richiesta.php" method="post" id='frm_view' name='frm_view'>  
				<div class="card">
				  <div class="card-header">
					<h5 class="m-0">Elenco</h5>
				  </div>
				  <div class="card-body">

					<table id="example1" class="table table-bordered table-striped">
					  <thead>
					  <tr>
						<th>#</th>
						<th>Tipo Richiesta</th>
						<?php if ($is_admin==1) {?>
							<th>Reparto</th>
							<th>Richiedente</th>
						<?php } ?>
						<th>Dipendente</th>
						<th>Data</th>
						<th>Stato</th>
						<th>Modifica</th>
						<?php if ($is_admin==1) echo "<th>Impegno</th>"; ?>
						
						<th>Cancella</th>
						</tr>
					  </thead>
					  <tbody>
						<?php
						
							for ($sca=0;$sca<=count($elenco)-1;$sca++) {
								echo "<tr>";
									echo "<td style='text-align:center'><i>".($sca+1)."</i></td>";
									echo "<td>";
										echo $elenco[$sca]['tipo_richiesta'];
									echo "</td>";

									if ($is_admin==1) {
										echo "<td>";
											echo stripslashes($elenco[$sca]['reparto']);
										echo "</td>";
										echo "<td>";
											echo stripslashes($elenco[$sca]['richiedente']);
										echo "</td>";
									}

									echo "<td>";
										echo stripslashes($elenco[$sca]['dipendente']);
									echo "</td>";
									
									echo "<td>";
										echo date("d-m-Y",strtotime($elenco[$sca]['data_richiesta']));
									echo "</td>";
									echo "</td>";

									$stato=$elenco[$sca]['stato'];
									$status="New";
									if ($stato==0) $status="<font color='red'>New</font>";
									if ($stato==1) $status="<font color='orange'>View</font>";
									if ($stato==2) $status="<font color='yellow'>Rewiew</font<";
									if ($stato==3) $status="<font color='green'>Close</font>";
									echo "<td>";
										echo $status;
									echo "</td>";
									
									echo "<td style='text-align:center'>";
										if ($stato!=3) {
											if ($elenco[$sca]['id_richiedente']==$id_user || $is_admin==1) {
												echo "<a href='javascript:void(0)' onclick=\"modifica(".$elenco[$sca]['id'].")\">";
													echo "<font size=5><i class='fas fa-edit'></i></font>";
												echo "</a>";
											}
										}	
									echo "</td>";

									if  ($is_admin==1) {
										echo "<td style='text-align:center'>";
											echo "<a href='javascript:void(0)' onclick=\"impegno(".$elenco[$sca]['id'].")\">";
												echo "<font size=5  color='green'><i class='far fa-calendar-check'></i></font>";
											echo "</a>";
										echo "</td>";
									}
										
									
									echo "<td style='text-align:center'>";
										if ($stato!=3) {
											if ($elenco[$sca]['id_richiedente']==$id_user || $is_admin==1) {
												echo "<a href='javascript:void(0)'  onclick=\"elimina(".$elenco[$sca]['id'].")\">";
													echo "<font size=5 color='red'>";
														echo "<i class='fas fa-trash-alt'></i>";
													echo "</font>";
												echo "</a>";
											}
										}
									echo "</td>";
								echo "</tr>";
							}
						?>
					  </tbody>
					  <tfoot>

					  </tfoot>
					</table>
					<input type='hidden' name='id_edit' id='id_edit' >
					
					

				
					
				  </div>
				</div>

			</form>
			
			<form action="elenco.php" method="post" id='frm_delete' name='frm_delete'>  
				<input type='hidden' name='id_delete' id='id_delete' >
			</form>
			
			<form action="impegno.php" method="post" id='frm_impegno' name='frm_impegno'>  
				<input type='hidden' name='impegno' id='impegno' >
			</form>
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
		"buttons": ["copy", "csv", "excel", "pdf"],
		"language": {
			"lengthMenu": "Mostra _MENU_ richieste per pagina &nbsp&nbsp",
			"zeroRecords": "Nessuna richiesta trovata",
			"info": "Pagina mostrata _PAGE_ di _PAGES_ di _TOTAL_ richieste",
			"infoEmpty": "Non ci sono richieste",
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
  
  function modifica(id) {
	$("#id_edit").val(id);
	$("#frm_view").submit();
  }
  
  function impegno(id) {
	$("#impegno").val(id)
	$("#frm_impegno").submit();
  }

  function elimina(id) {
	if (!confirm("Sicuro di eliminare la richiesta?")) return false
	$("#id_delete").val(id);
	$("#frm_delete").submit();
  }
  function save() {
	  
	  $("#frm_edit").submit();
  }
</script>

</body>
</html>