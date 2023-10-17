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

	include_once '../../MVC/Models/M_prodotti.php';
	include_once '../../MVC/Controllers/C_prodotti.php';
	
	
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
  <title>Prodotti</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  
  
  <!-- per upload -->
  <link href="../../dist/css/jquery.dm-uploader.min.css" rel="stylesheet">
  <!-- per upload -->

  
   <link href="styles.css?ver=1.3" rel="stylesheet">
  
  
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
            <h1 class="m-0">PRODOTTI</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item">Pages</li>
			  <li class="breadcrumb-item active">Prodotti</li>
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
		  

		<?php
		
			if (strlen($id_edit)!=0 || isset($_POST['btn_new'])) {
				$disp_edit="block";
				$disp_elenco="none";
			} else {
				$disp_edit="none";
				$disp_elenco="block";
			}
		?>

          <div class="col-lg-12" style='display:<?php echo $disp_edit; ?>' >
			<form action="prodotti.php" method="post" id='frm_edit' name='frm_edit'>  		  
				<div class="card">
				  <div class="card-header">
					<?php 
						$value="Nuovo Prodotto";
						if (!isset($_POST['btn_new'])) $value="Modifica Prodotto";
					?>	
					<h5 class="m-0"><?php echo $value;?></h5>
				  </div>
				  
				  <div class="card-body">
				  
				  
						<div class="row">  

							<div class="col-lg-2 col-md-2 form-group" >
								<div class="form-group">
									<label for="fornitore">Tipo</label>
									<select type='text' name='tipo_prod' id='tipo_prod' class="form-control" required >
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
							
							<div class="col-lg-4 col-md-4 form-group" >
								<div class="form-group">
									<label for="codice_fornitore">Codice</label>						
									<input type='text' name='codice_fornitore' id='codice_fornitore' class="form-control" placeholder='Codice' value='<?php echo $codice_fornitore; ?>' required maxlength=40 >
									<div id='avviso' class='mt-1'></div>
								</div>
							</div>  

							<div class="col-lg-4 col-md-4 form-group" >
								<div class="form-group">
									<label for="descrizione">Descrizione</label>						
									<input type='text' name='descrizione' id='descrizione' class="form-control" placeholder='Descrizione'  value='<?php echo $descrizione; ?>' required maxlength=100 >
								</div>
							</div>  

							<div class="col-lg-2 col-md-2 form-group" >
								<div class="form-group">
									<label for="taglia">Taglia</label>						
									<input type='text' name='taglia' id='taglia' class="form-control" placeholder='Taglia' value='<?php echo $taglia; ?>' required maxlength=20>
								</div>
							</div>  

						</div>	
						
						<div class="row">
							<div class="col-lg-3 col-md-3 form-group" >
								<div class="form-group">
									<label for="fornitore">Fornitore</label>
									<select type='text' name='fornitore' id='fornitore' class="form-control" required >
										<?php 
											echo "<option value=''>Select...</option>";
											for ($sca=0;$sca<=count($fornitori)-1;$sca++) {
												echo "<option value='".$fornitori[$sca]['id']."' ";
												if ($id_fornitore==$fornitori[$sca]['id']) echo " selected ";
												echo ">".$fornitori[$sca]['fornitore']."</option>";
											}
										?>
									</select>
								</div>
							</div>

							
							<div class="col-lg-3 col-md-3 form-group" >
								<div class="form-group">
									<label for="giacenza">Giacenza</label>						
									<input type='number' name='giacenza' id='giacenza' class="form-control" placeholder='Giacenza'  value='<?php echo $giacenza; ?>' required >
								</div>
							</div>  

							<div class="col-lg-2 col-md-2 form-group" >
								<div class="form-group">
									<label for="sottoscorta">Sottoscorta</label>						
									<input type='number' name='sottoscorta' id='sottoscorta' class="form-control" placeholder='Sottoscorta' value='<?php echo $sottoscorta; ?>' required maxlength=20>
								</div>
							</div>  
							

							<div class="col-lg-2 col-md-2 form-group" >
								<div class="form-group">
									<label for="scadenza">Scadenza alla consegna</label>
									<select type='text' name='scadenza' id='scadenza' class="form-control" required >
										<?php 
											echo "<option value='0'>Nessuna scadenza</option>";
											
											echo "<option value='30' ";
											if ($scadenza=="30") echo " selected ";
											echo ">1 mese</option>";
											echo "<option value='60' ";
											if ($scadenza=="60") echo " selected ";
											echo ">2 mesi</option>";
											echo "<option value='90' ";
											if ($scadenza=="90") echo " selected ";
											echo ">3 mesi</option>";
											echo "<option value='120' ";
											if ($scadenza=="120") echo " selected ";
											echo ">4 mesi</option>";
											echo "<option value='180' ";
											if ($scadenza=="180") echo " selected ";
											echo ">6 mesi</option>";
											echo "<option value='365' ";
											if ($scadenza=="365") echo " selected ";
											echo ">1 anno</option>";
											echo "<option value='738' ";
											if ($scadenza=="738") echo " selected ";
											echo ">2 anni</option>";
											echo "<option value='1099' ";
											if ($scadenza=="1099") echo " selected ";
											echo ">3 anni</option>";
											echo "<option value='1460' ";
											if ($scadenza=="1460") echo " selected ";
											echo ">4 anni</option>";
											echo "<option value='1825' ";
											if ($scadenza=="1825") echo " selected ";
											echo ">5 anni</option>";
											
										?>
									</select>
								</div>
							</div>		
							<div class="col-lg-2 col-md-2 form-group" >
								<div class="form-group">
									<a href='#' data-toggle='modal' data-target='#modal_story'  onclick="view_story(<?php echo $id_edit; ?>);">
										<label for="prezzo">Prezzo</label>						
									</a>	
									<input type='number' name='prezzo' id='prezzo' class="form-control" step='any' placeholder='Prezzo'  value='<?php echo $prezzo; ?>' required >
								</div>
							</div>  

							
						
						</div>	
						<!-- Verifica presenza allegati -->
						
						<?php
							$img_pres=0;
							if (strlen($id_edit)!=0) {
								echo "<div class='text-center mt-2 mb-2' id='div_img'>";
									for ($sca=1;$sca<=4;$sca++) {
										$ext="";
										if ($sca==1) $ext="jpg";
										if ($sca==2) $ext="jpeg";
										if ($sca==3) $ext="png";
										if ($sca==4) $ext="gif";
										$fx="files/".$id_edit.".$ext";
										if (file_exists($fx)) {
										  	
										  echo "<img src='$fx' class='rounded' alt='Immagine Articolo'>";
										  echo "<hr>";
										  echo "<a href='javascript:void(0)' onclick='dele_foto()'>";
											echo "<font color='red'>";
												echo "<i class='fas fa-trash-alt'></i>";
											echo "</font> Elimina foto";
										  echo "</a>";
										  $img_pres=1;

										  break;
										} 
									}
								echo "</div>";
							}
						?>
						
			<?php if ($img_pres==1) {
				
				?>
				<hr>
					<!-- duplicazione prodotto !-->
					<label for="xx">Copia l'immagine del prodotto per gli articoli...</label>
					<div class="row mb-3 mt-2">
						<div class="col-md-12 col-sm-12">
							<?php
								echo "<select type='text' name='clone_foto[]' id='clone_foto' class='form-control' multiple style='height:200px'>";
									for ($sca=0;$sca<=count($elenco)-1;$sca++) {
										$id_prod=$elenco[$sca]['id'];
										if ($id_prod==$id_edit) continue;
										$taglia_c=$elenco[$sca]['taglia'];
										$codice_fornitore_c=$elenco[$sca]['codice_fornitore'];
										$fornitore_c=$elenco[$sca]['fornitore'];
										if ($codice_fornitore!=$codice_fornitore_c) continue;

										echo "<option value='".$elenco[$sca]['id']."' ";
										
										echo ">".$elenco[$sca]['descrizione']." - $codice_fornitore_c ($taglia_c) - $fornitore_c</option>";
									}
								echo "</select>";

							?>
						</div>
					</div>
					<button type="submit" name='btn_clone' value='1' id='btn_clone' class="btn btn-secondary btn-lg btn-block">Avvia Copia immagini
			<?php } ?>			

						
						
						<!-- ALLEGATI -->
						
						<!-- ref https://github.com/danielm/uploader -->
						
						<?php if (!isset($_POST['btn_new'])) {?>
							
							<button type="button" class="mt-4 btn btn-info btn-lg btn-block" onclick="$('#sez_allegati').toggle()">Apri/Chiudi sezione aggiunta allegati</button>
						<?php } ?>
						
						
						<div id='sez_allegati' style="display:none" class='mt-2'>
							<div class="row">
							<div class="col-md-6 col-sm-12">
							  
							  <!-- Our markup, the important part here! -->
							  <div id="drag-and-drop-zone" class="dm-uploader p-5">
								<h3 class="mb-5 mt-5 text-muted">Trascina il file quì</h3>

								<div class="btn btn-primary btn-block mb-5">
									<span>...altrimenti sfoglia</span>
									<input type="file" title='Click to add Files' />
								</div>
							  </div><!-- /uploader -->

							</div>
							<div class="col-md-6 col-sm-12">
							  <div class="card h-100">
								<div class="card-header">
								  File Inviati
								</div>

								<ul class="list-unstyled p-2 d-flex flex-column col" id="files">
								  <li class="text-muted text-center empty">Nessun File inviato.</li>
								</ul>
							  </div>
							</div>
							</div><!-- /file list -->				  



							<div class="row">
							<div class="col-12">
							   <div class="card h-100">
								<div class="card-header">
								  Messaggi di debug
								</div>

								<ul class="list-group list-group-flush" id="debug">
								  <li class="list-group-item text-muted empty">Loading plugin....</li>
								</ul>
							  </div>
							</div>
							</div> <!-- /debug -->
						</div>


						<!-- File item template -->
						<script type="text/html" id="files-template">
						  <li class="media">
							<div class="media-body mb-1">
							  <p class="mb-2">
								<strong>%%filename%%</strong> - Status: <span class="text-muted">Waiting</span>
							  </p>
							  <div class="progress mb-2">
								<div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
								  role="progressbar"
								  style="width: 0%" 
								  aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
								</div>
							  </div>
							  <hr class="mt-1 mb-1" />
							</div>
						  </li>
						</script>

						<!-- Debug item template -->
						<script type="text/html" id="debug-template">
						  <li class="list-group-item text-%%color%%"><strong>%%date%%</strong>: %%message%%</li>
						</script>
						
						
						

					<br><br>
					
					<input type='hidden' name='id_save' id='id_save' value='<?php echo $id_edit; ?>'>
					<?php 
						$value="Salva"; 
						if (!isset($_POST['btn_new'])) $value="Salva Modifiche";
					?>
					
					
					<input class="btn btn-primary" type="submit" value="<?php echo $value; ?>" name='btn_save'>
					   
					

					
					<a href="prodotti.php" class="btn btn-secondary">Torna ad elenco</a>
				  </div>
				</div>
			</form>
			
          </div>		  
		  
		  
          <div class="col-lg-12" style='display:<?php echo $disp_elenco; ?>'>
			<form action="prodotti.php" method="post" id='frm_view' name='frm_view'>  
			
				<div class="card">
				  <div class="card-header">
					<h5 class="m-0">Elenco</h5>
				  </div>
				  <div class="card-body">

					<table id="example1" class="table table-bordered table-striped">
					  <thead>
					  <tr>
					    <th>Foto</th>
						<th>Tipo</th>
						<th>Codice</th>
						<th>Descrizione</th>
						<th>Taglia</th>
						<th>Fornitore</th>
						<th>Giacenza</th>
						<th>Sottoscorta</th>
						<th>Modifica</th>
						<th>Elimina</th>
					  </tr>
					  
					  </thead>
						  <tbody>

							<?php
								for ($sca=0;$sca<=count($elenco)-1;$sca++) {
									$id_prod=$elenco[$sca]['id'];
									$tipo_prod=$elenco[$sca]['tipo_prod'];
									$descrizione=$elenco[$sca]['descrizione'];
									$descrizione=stripslashes($descrizione);
									$codice_fornitore=$elenco[$sca]['codice_fornitore'];
									$taglia=$elenco[$sca]['taglia'];
									$fornitore=$elenco[$sca]['fornitore'];
									$fornitore=stripslashes($fornitore);
									$giacenza=$elenco[$sca]['giacenza'];
									$sottoscorta=$elenco[$sca]['sottoscorta'];
							
									echo "<tr>";
										echo "<td style='text-align:center'>";
											for ($sc1=1;$sc1<=4;$sc1++) {
												$ext="";
												if ($sc1==1) $ext="jpg";
												if ($sc1==2) $ext="jpeg";
												if ($sc1==3) $ext="png";
												if ($sc1==4) $ext="gif";
												$fx="files/".$id_prod.".$ext";
												if (file_exists($fx)) {
													echo "<i class='fas fa-camera'></i>";
													break;
												} 
											}	
										echo "</td>";
										echo "<td>";
											echo $tipo_prod;
										echo "</td>";
										echo "<td>";
											echo "<i class='fas fa-barcode'></i> ";
											echo $codice_fornitore;
										echo "</td>";

										echo "<td>";
											echo $descrizione;
										echo "</td>";
										
										echo "<td>$taglia</td>";
										echo "<td>$fornitore</td>";
										echo "<td>$giacenza</td>";
										echo "<td>$sottoscorta</td>";

										echo "<td style='text-align:center'>";
											echo "<a href='javascript:void(0)' onclick=\"modifica(".$elenco[$sca]['id'].")\">";
												echo "<i class='fas fa-edit'></i>";
											echo "</a>";
										echo "</td>";
										echo "<td style='text-align:center'>";
											echo "<a href='javascript:void(0)'  onclick=\"elimina(".$elenco[$sca]['id'].")\">";
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
					<br><br>
					<button class="btn btn-navbar" type="submit" id='btn_new' name='btn_new'>
					  <font color='blue'>
						<i class="fas fa-plus-square"></i>
					  </font> Nuovo Prodotto
					</button>
					
				  </div>
				</div>
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


<div class="modal fade bd-example-modal-lg" id="modal_story" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Storicizzazione prezzi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id='body_msg_story' style='overflow:scroll'>
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
      </div>
    </div>
  </div>
</div> 
  
  
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

<!-- per upload -->
<script src="../../dist/js/jquery.dm-uploader.min.js"></script>
<script src="demo-ui.js?ver=1.8"></script>
<script src="demo-config.js?ver=1.5"></script>
<!-- fine upload -->

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
			"lengthMenu": "Mostra _MENU_ prodotti per pagina &nbsp&nbsp",
			"zeroRecords": "Nessun utente trovato",
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
	
	
	$('#codice_fornitore').on('keydown', function(e) {
	  if (e.keyCode === 9 || e.keyCode === 13) {
		e.preventDefault();
		e.stopImmediatePropagation();
		
		new_obj();
	  } else $("#avviso").empty();
	});
	
	
  });
  
function new_obj() {
	html="";
	html+="<div class='spinner-grow spinner-grow-sm' role='status'>";
		html+="<span class='sr-only'>Loading...</span>";
	html+="</div>";

	$("#avviso").html(html)
	
	codice_prodotto=$("#codice_fornitore").val()
	fetch('ajax.php', {
		method: 'post',
		//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
		headers: {
		  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		body: 'operazione=check_prodotto&codice_prodotto='+codice_prodotto
	})
	.then(response => {
		if (response.ok) {
		   return response.json();
		}
	})
	.then(resp=>{
		html="<font color='green'>Codice inesistente!</font>"
		if (!resp || resp.length==0 || resp[0]=="0") {
			$("#avviso").html(html)
			return;
		} else {
			html="<font color='orange'>Codice Esistente!</font>"
			$("#avviso").html(html)
			return;
		}	
		
	})
	.catch(status, err => {
		return console.log(status, err);
	})	
}

  

  function modifica(id) {
	$("#id_edit").val(id);
	$("#frm_view").submit();
  }
  
  function elimina(id) {
	if (!confirm("Sicuro di eliminare il prodotto?")) return false
	$("#id_delete").val(id);
	$("#frm_view").submit();
  }
  function save() {
	  
	  $("#frm_edit").submit();
  }
  
  function dele_foto() {
		product_id=$("#id_save").val();
		if (!confirm("Sicuri di eliminare la foto associata al prodotto?")) return false;

		fetch('ajax.php', {
			method: 'post',
			//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
			headers: {
			  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
			},
			body: 'operazione=dele_foto&product_id='+product_id
		})
		.then(response => {
			if (response.ok) {
			   return response.json();
			}
		})
		.then(resp=>{
			if (resp.status=="OK") {
				$("#div_img").empty();
				alert("Foto eliminata");
			} else alert ("Problemi occorsi durante l'eliminazione!");
				
		})
		.catch(status, err => {
			return console.log(status, err);
		})	  
	}

	function view_story(id_edit) {

		fetch('ajax.php', {
			method: 'post',
			//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
			headers: {
			  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
			},
			body: 'operazione=info_prezzo&product_id='+id_edit
		})
		.then(response => {
			if (response.ok) {
			   return response.json();
			}
		})
		.then(resp=>{
			html="Storia prezzo prodotto"
			$("#exampleModalLabel").html(html)
			html="";
			html+="<table id='example4' class='table table-bordered table-striped'>";
				html+="<thead>";
					html+="<tr>";
						html+="<th>Prezzo</th>";
						html+="<th>Data</th>";
						html+="<th>Elimina</th>";
					html+="</tr>";
				html+="</thead>";
				html+="<tbody>";
					for (sca=0;sca<=resp.length-1;sca++) {
						id_prezzo=resp[sca].id
						html+="<tr id='tr_prezzi"+id_prezzo+"'>";
							html+="<td>"+resp[sca].prezzo+"€</td>";
							html+="<td>"+resp[sca].data+"</td>";
							html+="<td style='text-align:center'><a href='#' onclick='delete_prezzo("+id_prezzo+")'>";
								html+="<i class='fas fa-trash-alt'></i>";
							html+="</a></td>";	
							
						html+="</tr>";

					}
				html+"</tbody>";
			html+="</table>";	
			$("#body_msg_story").html(html);
			

			$("#example4").DataTable({
				"responsive": true, "lengthChange": false, "autoWidth": false,
				//, "colvis"
				"buttons": ["copy", "excel", "pdf"],
				"language": {
					"lengthMenu": "Mostra _MENU_ prezzi per pagina &nbsp&nbsp",
					"zeroRecords": "Nessun prezzo trovato",
					"info": "Pagina mostrata _PAGE_ di _PAGES_ di _TOTAL_ prezzi",
					"infoEmpty": "Non ci sono prezzi associati",
					"infoFiltered": "(filtrate da _MAX_ record totali)",
					"search":         "Cerca:",
					"paginate": {
						"first":      "Prima",
						"last":       "Ultima",
						"next":       "Successiva",
						"previous":   "Precedente"
					}
				
				}	  
			}).buttons().container().appendTo('#example4_wrapper .col-md-6:eq(0)');					
			
		})
		.catch(status, err => {
			return console.log(status, err);
		})	  
	}

	function delete_prezzo(id_delete) {
		if (!confirm("Sicuri di cancellare il prezzo dell'articolo dalla storia?")) return false;
		fetch('ajax.php', {
			method: 'post',
			//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
			headers: {
			  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
			},
			body: 'operazione=delete_prezzo&id_delete='+id_delete
		})
		.then(response => {
			if (response.ok) {
			   return response.json();
			}
		})
		.then(resp=>{
			if (resp.status=="OK") {
				$("#tr_prezzi"+id_delete).remove();
				alert("Prezzo rimosso\nSe il prezzo è riferito a quello indicato attualmente nella scheda prodotto è necessario uscire e rientrare!");
				
			}
			
		})
		.catch(status, err => {
			return console.log(status, err);
		})	
	}

</script>

</body>
</html>
