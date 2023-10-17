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
	
	include_once '../../MVC/Models/M_schema.php';
	include_once '../../MVC/Controllers/C_schema.php';
	
	
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
  <title>Schema</title>

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
            <h1 class="m-0">SCHEMA VESTIONE REPARTI</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item">Pages</li>
			  <li class="breadcrumb-item active">Schema Vestizione Reparti</li>
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


		<?php
		
			if (strlen($id_edit)!=0 || $btn_new=="1") {
				$disp_edit="block";
				$disp_elenco="none";
			} else {
				$disp_edit="none";
				$disp_elenco="block";
			}
		?>

          <div class="col-lg-12" style='display:<?php echo $disp_edit; ?>' >
			<form action="schema.php" method="post" id='frm_edit' name='frm_edit'>  		  
				<input type='hidden' name='reparto_ref' id='reparto_ref' value="<?php echo $reparto_sel;?>" >
				<input type='hidden' name='tipo_prod_ref' id='tipo_prod_red' value="<?php echo $tipo_prod_sel;?>" >
				
				<input type='hidden' name='save_new' id='save_new' value="<?php echo $btn_new; ?>">
				<div class="card">
				  <div class="card-header">
					<?php 
						$value="Nuovo Prodotto nello schema";
						if ($btn_new!="1") $value="Modifica Prodotto presente nello schema";
					?>	
					<h5 class="m-0"><?php echo $value;?></h5>
				  </div>
				  <div class="card-body">
						
						<div class="row">
							<div class="col-lg-12 col-md-12 form-group" >
								<div class="form-group">
									<label for="prodotto_schema">Scelta tra i prodotti disponibili da mostrare nello schema associato al reparto</label>						
									<?php
									
										if ($btn_new!="1") 
											echo "<select type='text' name='prodotto_schema' id='prodotto_schema' class='form-control' required>";
										else 
											echo "<select type='text' name='prodotto_schema[]' id='prodotto_schema' class='form-control' required multiple style='height:300px'>";
									?>
									

										<?php 
											
											//echo "<option value=''>Select...</option>";
											for ($sca=0;$sca<=count($elenco_prodotti)-1;$sca++) {
												
												$codice_fornitore=$elenco_prodotti[$sca]['codice_fornitore'];
												$descrizione=$elenco_prodotti[$sca]['descrizione'];
												$descrizione=stripslashes($elenco_prodotti[$sca]['descrizione']);
												$fornitore=$elenco_prodotti[$sca]['fornitore'];
												//$value= "$descrizione - $codice_fornitore ($fornitore)";
												$value= "$descrizione - $codice_fornitore";
												echo "<option value='".$codice_fornitore."' ";
												if ($btn_new!="1") {
													if ($info_articolo_schema==$codice_fornitore) echo "
													selected ";
												}	
												echo ">".$value."</option>";
											}
										?>
										
									</select>
								</div>
							</div>


						
						</div>	

					<br><br>
					
					<input type='hidden' name='id_save' id='id_save' value='<?php echo $id_edit; ?>'>
					
					<?php 
						$value="Salva"; 
						if ($btn_new!="1") $value="Salva Modifiche";
					?>
					
					
					<input class="btn btn-primary" type="submit" value="<?php echo $value; ?>" name='btn_save'>
					   
					

					
					<a href="javascript:void(0)" onclick='torna()' class="btn btn-secondary">Torna ad allestimento completo</a>
				  </div>
				</div>
			</form>
          </div>		
		


		
		
          <!-- /.col-md-6 -->
           <div class="col-lg-12" style='display:<?php echo $disp_elenco; ?>' >

			<?php
				if (strlen($save)!=0) {
					echo "<div class='alert alert-warning' role='alert'>";
					  echo "<b>Operazione non eseguita:</b> $save";
					echo "</div>";
				}
			?>		   
		   
			<form action="schema.php" method="post" id='frm_view' name='frm_view'>
				
				<div class="card">
				  <div class="card-header">
					<h5 class="m-0">Associazione schema vestizione ai reparti</h5>
				  </div>
				  <div class="card-body">

					<div class="row">

						<div class="col-lg-3 col-md-3 form-group" >
							<div class="form-group">
								<label for="tipo_prod">Scelta tipo richiesta</label>						
								<select type='text' name='tipo_prod' id='tipo_prod' class="form-control" onchange="$('#frm_view').submit();" required >
									<?php 
										echo "<option value=''>Select...</option>";
										echo "<option value='abb' ";
										if ($tipo_prod=="abb") echo " selected ";
										echo ">Abbigliamento</option>";
										echo "<option value='dpi' ";
										if ($tipo_prod=="dpi") echo " selected ";
										echo ">DPI</option>";
									?>
								</select>
							</div>
						</div>					
					
						<div class="col-lg-9 col-md-9 form-group" >
							<div class="form-group">
								<label for="reparto">Scelta Reparto</label>						
								<select type='text' name='reparto' id='reparto' class="form-control" onchange="$('#frm_view').submit();" required >
									<?php 
										echo "<option value=''>Select...</option>";
										
										for ($sca=0;$sca<=count($reparti)-1;$sca++) {
											$id_reparto=$reparti[$sca]['id'];
											$descr_reparto=$reparti[$sca]['reparto'];
											
											$id_sr=$reparti[$sca]['id_sr'];
											$descr_sotto_reparto=$reparti[$sca]['sotto_reparto'];
											$id_ref=$id_reparto."|".$id_sr;
											echo "<option value='".$id_ref."' ";
											$ref_sr="";
											if (strlen($descr_sotto_reparto)!=0) $ref_sr=" ($descr_sotto_reparto)";
											if ($reparto==$id_ref) echo " selected ";
											echo ">".$descr_reparto.$ref_sr."</option>";
										}									
									?>
								</select>
							</div>
						</div>



					</div>
					
					<?php

						if (strlen($reparto)!=0 && strlen($tipo_prod)!=0) {
							
								echo "<hr>";
								
								?>
								<table id="example1" class="table table-bordered table-striped">
									  <thead>
									  <tr>
										<th>Codice</th>
										<th>Descrizione</th>
										<th>Fornitore</th>
										<th>Modifica</th>
										<th>Elimina</th>
									  </tr>
									  
									  </thead>
										  <tbody>

											<?php
											
												for ($sca=0;$sca<=count($schema)-1;$sca++) {
													$descrizione=$schema[$sca]['descrizione'];
													$descrizione=stripslashes($descrizione);
													$codice_fornitore=$schema[$sca]['codice_fornitore'];
													$fornitore=$schema[$sca]['fornitore'];
													$fornitore=stripslashes($fornitore);
													echo "<tr>";
														echo "<td>";
															echo "<i class='fas fa-barcode'></i> ";
															echo $codice_fornitore;
														echo "</td>";

														echo "<td>";
															echo $descrizione;
														echo "</td>";

														echo "<td>";
															echo $fornitore;
														echo "</td>";


														echo "<td style='text-align:center'>";
															echo "<a href='javascript:void(0)' onclick=\"modifica(".$schema[$sca]['id'].")\">";
																echo "<i class='fas fa-edit'></i>";
															echo "</a>";
														echo "</td>";
														echo "<td style='text-align:center'>";
															echo "<a href='javascript:void(0)'  onclick=\"elimina(".$schema[$sca]['id'].")\">";
																echo "<font color='red'>";
																	echo "<i class='fas fa-trash-alt'></i>";
																echo "</font>";
															echo "</a>";
														echo "</td>";									
													echo "</tr>";
												}
											?>



										  </tbody>
										<tfoot>
						
										</tfoot>
								</table>
								<input type='hidden' name='id_edit' id='id_edit' >
								<input type='hidden' name='id_delete' id='id_delete' >
								
								<div class='mt-2'>
									<a href='javascript:void(0)' onclick="$('#div_opz').toggle()">Opzioni</a>
									<div class="row mt-2" id='div_opz' style='display:none'>
										<div class="col-lg-4 col-md-4 form-group" >
											<div class="form-group">
												<label for="reparto">Copia struttura nel reparto </label>						
												<select type='text' name='reparto_copia' id='reparto_copia' class="form-control">
													<?php 
														echo "<option value=''>Select...</option>";
														
														for ($sca=0;$sca<=count($reparti)-1;$sca++) {
															$id_reparto=$reparti[$sca]['id'];
															$descr_reparto=$reparti[$sca]['reparto'];
															
															$id_sr=$reparti[$sca]['id_sr'];
															$descr_sotto_reparto=$reparti[$sca]['sotto_reparto'];
															$id_ref=$id_reparto."|".$id_sr;
															echo "<option value='".$id_ref."' ";
															$ref_sr="";
															if (strlen($descr_sotto_reparto)!=0) $ref_sr=" ($descr_sotto_reparto)";
															echo ">".$descr_reparto.$ref_sr."</option>";
														}									
													?>
												</select>
											</div>
											<input type='hidden' name='save_copia' id='save_copia'>
											<input class="btn btn-primary" type="button" onclick='copia()' value="Copia">
										</div>
										
									</div>
									
								</div>
								
								<?php	
								if ($save_copia=="1") {
									echo "<div class='alert alert-success' role='alert'>";
										echo "Clonazione allestimento effettuata!";
									echo "</div>";
								}
								
								echo "<br>";
								
								echo "<button class='btn btn-navbar' type='button' onclick='new_prod()'>";
								  echo "<input type='hidden' id='btn_new' name='btn_new'>";
								  echo "<font color='blue'>";
									echo "<i class='fas fa-plus-square'></i>";
								  echo "</font> Aggiungi Prodotto";
								echo "</button>";
							
						}
					?>	
					
				  </div>
				</div>
				<input type='hidden' name='reparto_sel' id='reparto_sel' value="<?php echo $reparto_sel;?>" >
				<input type='hidden' name='tipo_prod_sel' id='tipo_prod_sel' value="<?php echo $tipo_prod_sel;?>" >
					
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
		"ordering": true,
		"buttons": ["copy", "csv", "excel", "pdf"],
		"language": {
			"lengthMenu": "Mostra _MENU_ articoli per pagina &nbsp&nbsp",
			"zeroRecords": "Nessun articolo abbinato a questo reparto",
			"info": "Pagina mostrata _PAGE_ di _PAGES_ di _TOTAL_ articoli nel reparto",
			"infoEmpty": "Non ci sono dati",
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


  function new_prod() {
	$("#btn_new").val("1");
	reparto=$("#reparto").val();
	$("#reparto_sel").val(reparto)
	tipo_prod=$("#tipo_prod").val();
	$("#tipo_prod_sel").val(tipo_prod)

	$("#frm_view").submit();
  }
  function modifica(id) {
	$("#id_edit").val(id);
	reparto=$("#reparto").val();
	$("#reparto_sel").val(reparto)
	tipo_prod=$("#tipo_prod").val();
	$("#tipo_prod_sel").val(tipo_prod)
	$("#frm_view").submit();
  }

  function torna(id) {
	reparto_sel=$("#reparto_sel").val();
	$("#reparto").val(reparto_sel)

	tipo_prod_sel=$("#tipo_prod_sel").val();
	$("#tipo_prod").val(tipo_prod_sel)
	
	$("#frm_view").submit();
  }

  
  function elimina(id) {
	if (!confirm("Sicuro di eliminare il prodotto?")) return false
	$("#id_delete").val(id);
	$("#frm_view").submit();
  }  
  
  function copia() {
	  origine=$("#reparto").val()
	  destinazione=$("#reparto_copia").val()
	  if (origine==destinazione) {
		  alert("Il reparto di destinazione deve essere diverso dal reparto di orgine")
		  return false;
	  }
	  $("#save_copia").val("1");
	  $("#frm_view").submit();
  }
  
</script>

</body>
</html>
