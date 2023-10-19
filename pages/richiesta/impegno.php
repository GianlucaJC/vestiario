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

	include_once '../../MVC/Models/M_main.php';
	include_once '../../MVC/Models/M_impegno.php';
	include_once '../../MVC/Controllers/C_impegno.php';
	

	
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Vestizione | Richiesta</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="../../plugins/daterangepicker/daterangepicker.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="../../plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="../../plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
  <!-- BS Stepper -->
  <link rel="stylesheet" href="../../plugins/bs-stepper/css/bs-stepper.min.css">
  <!-- dropzonejs -->
  <link rel="stylesheet" href="../../plugins/dropzone/min/dropzone.min.css">
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

  <!-- Main Sidebar Container -->
	<?php
	
		$path="../../";	
		include ("../../side_menu.php");
	?>	


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Gestione IMPEGNI</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
			    <li class="breadcrumb-item">Pages</li>
              <li class="breadcrumb-item active">Impegni</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
	  
	<?php if ($nofirma==1) {?>
		<div class="alert alert-danger" role="alert">
			<b>Attenzione</b>. Firma non presente!
		</div>	 
	<?php } ?>

	<?php if ($genera=="99") {?>
		<div class="alert alert-warning" role="alert">
			<b>Attenzione</b>. Per poter eseguire la consegna bisogna prima impegnare!
		</div>	 
	<?php } ?>
	<?php 
		$tipo_richiesta=$load_richiesta[0]['tipo_richiesta'];
		$id_dipendente=$load_richiesta[0]['id_dipendente'];
		if (strlen($file_pdf)!=0) {?>
		<div class="alert alert-info" role="alert">
			<b>File PDF creato ed archiviato (disponibile nella sezione dipendenti)</b><br>
			<a href="<?php echo '../dipendenti/info/'.$tipo_richiesta.'/'.$id_dipendente.'/'.$file_pdf;?>" target='_blank'>
				Clicca quì per aprirlo
			</a>
		</div>	
		
	<?php } ?>
	
	
	<?php if (strlen($send_richiesta)!=0) {?>

		<?php if ($save_impegni['check']=="1") {?>
			<div class="alert alert-success" role="alert">
			  <b>Operazione completata con successo</b>. Impegni aggiornati.
			</div>	 
		<?php } 
		else  {?>
			<div class="alert alert-danger" role="alert">
			  <b>Attenzione</b>. Quantità non congrue e non salvate
			</div>	 
		<?php } ?>
		
	<?php } ?>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
	  
	  <form action="impegno.php" method="post" id='frm_view' name='frm_view' class="needs-validation">
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default" id='div_master'>
          <div class="card-header">

				<input type='hidden' name='impegno' id='impegno' value='<?php echo $impegno; ?>'>
				<div class="row">
				  <div class="col-md-12">
					<div class="form-group">
					  <label>Dipendente</label>
					  <?php
						$dip_load=$load_richiesta[0]['dipendente'];
						echo $dip_load;
					  ?>

					</div>
				  </div>
				</div>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->

		  
          <div class="card-body">
			<?php
				if (count($load_old_request)!=0) {
					echo "<div class='alert alert-warning' role='alert'>";
					  echo "<b>Attenzione.</b> E' avvenuta una modifica dopo un impegno";
					  echo "<hr>";
					  echo "<a href='javascript:void(0)' onclick=\"$('#div_dettagli_modifica').toggle()\">";
						echo "Clicca quì per i dettagli";
					  echo "</a>";
					echo "</div>";
					$old_data="?";
					echo "<div id='div_dettagli_modifica' style='display:none'>";
						for ($sca=0;$sca<=count($load_old_request)-1;$sca++) {
							$data_ora=$load_old_request[$sca]['data_ora'];
							if ($old_data!=$data_ora) {							
								$old_data=$data_ora;
								if ($sca!=0) echo "</table><hr>";	
								echo "Data di modifica <b>".date("d-m-Y H:i:s",strtotime($data_ora))."</b>  - <i>Stato della richiesta subito prima della modifica</i>";

								echo "<table class='table table-bordered table-striped'>";
									echo "<tr>";
										echo "<th>Codice Prodotto</th>";
										echo "<th>Taglia</th>";
										echo "<th>ID fornitore</th>";
										echo "<th>Qta richiesta</th>";
										//echo "<th>Qta impegno</th>";
										echo "<th>Qta consegnata</th>";
									echo "</tr>";
							}
							echo "<tr>";
								echo "<td>";
									echo $load_old_request[$sca]['codice_articolo'];
								echo "</td>";
								echo "<td>";
									echo $load_old_request[$sca]['taglia'];
								echo "</td>";
								echo "<td>";
									echo $load_old_request[$sca]['id_fornitore'];
								echo "</td>";
								echo "<td>";
									echo $load_old_request[$sca]['qta_richiesta'];
								echo "</td>";
								/*
								echo "<td>";
									echo $load_old_request[$sca]['qta_impegno'];
								echo "</td>";
								*/
								echo "<td>";
									echo $load_old_request[$sca]['qta_consegnata'];
								echo "</td>";
							echo "</tr>";
								
						}
						echo "</table><br><hr>";
					echo "</div>";
					
					
				}
		  
			?>


				<?php 
					$stato=$load_richiesta[0]['stato'];
					
					$prod_prec="?";
					$taglia_prec="?";
					for ($voci=0;$voci<=count($load_richiesta)-1;$voci++) {?>
						<?php
							$id_ref=$load_richiesta[$voci]['id_ref'];
							$qta_consegnata=$load_richiesta[$voci]['qta_consegnata'];
							
							$id_prodotto=$load_richiesta[$voci]['id_prodotto'];
							$id_richiesta=$load_richiesta[$voci]['id_richiesta'];
							$prodotto_load=$load_richiesta[$voci]['codice_articolo'];
							$taglia_load=$load_richiesta[$voci]['taglia'];
							$qta_richiesta=$load_richiesta[$voci]['qta_richiesta'];
							$giacenza=$load_richiesta[$voci]['giacenza'];
							$giacenza_impegno=$load_richiesta[$voci]['giacenza_impegno'];
							$fornitore=$load_richiesta[$voci]['fornitore'];
							$descrizione_articolo=stripslashes($load_richiesta[$voci]['descrizione_articolo']);
							$qta_impegno_cur=$load_richiesta[$voci]['qta_impegno'];
							
							$storia=$load_richiesta[$voci]['storia'];
							
							$check_full=$main->check_full($id_richiesta,$prodotto_load,$taglia_load,$qta_richiesta);
							$bordo="";
							if ($check_full=="1")
								$bordo="border border-success";


						?>
						<div class="row div_voce">
						 <div class="col-md-6 col-sm-6 <?php echo $bordo;?>">
							<div class="form-group">
							<?php
								echo "<input type='hidden' name='prodotto[]'  value='$descrizione_articolo'>";
							
								echo "<input type='hidden' name='codice_articolo[]'  value='$prodotto_load'>";
								echo "<input type='hidden' name='id_prodotto[]'  value='$id_prodotto'>";
								echo "<input type='hidden' name='storia[]'  value='$storia'>";

								echo "<input type='hidden' name='qta_impegno_cur[]'  value='$qta_impegno_cur'>";	
								
								if (!($prod_prec==$prodotto_load && $taglia_prec==$taglia_load))  {
									if ($voci==0) echo "<label>Prodotto Richiesto</label><br>";
									echo $descrizione_articolo. "<small><br>($prodotto_load)</small>";
								}	
								else {
									echo "";
								}

							?>											
							</div>
						</div>


					  
						<div class="col-md-2 col-sm-2 <?php echo $bordo;?>">
						  <!-- text input -->
						  <div class="form-group">
							<?php
							
								echo "<input type='hidden' name='taglia[]'  value='$taglia_load'>";							
								
								if (!($prod_prec==$prodotto_load && $taglia_prec==$taglia_load))  {
									echo "<label>Taglia</label><br>";
									echo $taglia_load;
								}	
								else 
									echo "";
							?>							
							
						  </div>
						</div>
						
						
						<div class="col-md-2 col-sm-2 <?php echo $bordo;?>">
						  <!-- text input -->
						  <div class="form-group">
							<?php
							
								echo "<input type='hidden' name='qta_richiesta[]'  value='$qta_richiesta'>";	
								
								if (!($prod_prec==$prodotto_load && $taglia_prec==$taglia_load))  {
									if ($voci==0) echo "<label>Q.ta richiesta</label><br>";
									echo $qta_richiesta;
									if (strlen($qta_consegnata)!=0 && $qta_consegnata!="0")
										echo " ($qta_consegnata evaso/i)";
								}	
								else 
																															if (strlen($qta_consegnata)!=0 && $qta_consegnata!="0") 
																																echo " ($qta_consegnata evaso/i)";
							?>	
						  </div>
						</div>

					
						<?php 
							$prod_prec=$prodotto_load;
							$taglia_prec=$taglia_load;
						
							$st="";
							/*
							if (strlen($qta_impegno)>0) {
								if ($qta_richiesta>$qta_impegno) {
									$st="border-color:red";
								} else $qta_impegno=$qta_richiesta;
							}
							*/
							?>
							
							
							<div class="col-md-2 col-sm-2 <?php echo $bordo;?>">
							  <!-- text input -->
							  <div class="form-group">
								<?php if ($voci==0) echo "<label>Evasione</label><br>"; ?>
								<span class="badge bg-success">
									<?php 
										echo $giacenza;
									?>
								</span>
								
								<?php if (1==2) {?>
									<span class="badge bg-secondary">
										<?php echo $qta_impegno_cur;?>
									</span>

									<span class="badge bg-primary">
										<?php 
											echo $giacenza_impegno;
										?>
									</span>
								<?php } ?>
								
								<input type='hidden' name='id_ref[]' value="<?php echo $id_ref; ?>">


								<input type='hidden' value="<?php echo $giacenza; ?>" id='giacenza<?php echo $voci;?>' >
								
								<input type='hidden' name='giacenza_impegno[]' value="<?php echo $giacenza_impegno; ?>" id='giacenza_impegno<?php echo $voci;?>' >								
								

								<?php
									$ro="";
									if ($stato=="3") $ro="readonly";
								?>
								<input type="text" class="form-control" style='<?php echo $st;?>' placeholder="QTA impegno" name="qta_impegno[]" id='qta_impegno<?php echo $voci;?>'  <?php echo $ro; ?>>
							  </div>
							</div>							
						

						

					</div>
					<?php }
						echo "<input type='hidden' id='num_elementi' value='$voci'>";
						$id_dipendente=$load_richiesta[0]['id_dipendente'];
					?>
						<input type='hidden' id='id_dipendente' value='<?php echo $id_dipendente; ?>'>
					
				
				
				


			</div>
				<!-- /.row -->
			

         </div>

		
		
		<!--
		<font size='5'>
			<a href='#!' onclick='clone()'>
				<i class="fas fa-user-plus"></i>
			</a>	
		</font>
		!-->			


<?php 
	if ($stato!="3") {?> 
		<div class="row">			
			<div class="col-md-4 col-sm-2">
				<button type="button" class="btn btn-primary btn-md btn-block" onclick='save()'>Evadi quantità indicate</button>
			</div>	

			

			
				<div class="col-md-4 col-sm-4">
					<input type="text" class="form-control" placeholder="Testo associato a documento di consegna" name="testo_doc" id='testo_doc' maxlength=100>
				</div>
				
				<div class="col-md-2 col-sm-4">
				<?php
					if ($tipo_richiesta=="abb") {?>
					 <a href='#' onclick='firma()'>
						<button type="button" class="btn btn-success btn-md btn-block">Firma</button>
					 </a>
					<?php } else {?>
						<button type="button" class="btn btn-success btn-md btn-block" onclick='allega()'>Allega file</button>
					<?php }
				?>	
				</div>
				
				<div class="col-md-2 col-sm-2">
					<button type="button" class="btn btn-success btn-md btn-block" disabled id='btn_consegna' onclick='consegna()'>Consegna</button>
				</div>
			
		</div>
		
		
			<a href="elenco.php?view=1">
				<button type="button" class=" mt-2 btn btn-secondary btn-lg btn-block">Torna ad elenco impegni</button>
			</a>	

		
					<!-- Sezione allegati in caso di PDF per DPI !-->
						<div id='sez_allegati' style="display:none" class='mt-2'>
							<div class="row">
							<div class="col-md-4 col-sm-12">
							  
							  <!-- Our markup, the important part here! -->
							  <div id="drag-and-drop-zone" class="dm-uploader p-5">
								<h3 class="mb-5 mt-5 text-muted">Trascina il file quì</h3>

								<div class="btn btn-primary btn-block mb-5">
									<span>...altrimenti sfoglia</span>
									<input type="file" title='Click to add Files' />
								</div>
							  </div><!-- /uploader -->

							</div>
							<div class="col-md-4 col-sm-12">
							  <div class="card h-100">
								<div class="card-header">
								  File Inviati
								</div>

								<ul class="list-unstyled p-2 d-flex flex-column col" id="files">
								  <li class="text-muted text-center empty">Nessun File inviato.</li>
								</ul>
							  </div>
							</div>

							<div class="col-4">
							   <div class="card h-100">
								<div class="card-header">
								  Messaggi di debug
								</div>

								<ul class="list-group list-group-flush" id="debug">
								  <li class="list-group-item text-muted empty">Loading plugin....</li>
								</ul>
							  </div>
							</div>



							</div><!-- /file list -->				  


			
							<div class="row">

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
<?php } ?>		

		
<input type='hidden' id='send_richiesta' name='send_richiesta'>
<input type='hidden' id='send_consegna' name='send_consegna'>
<input type='hidden' name='file_pdf' id='file_pdf'>
<!-- file_pdf in caso di consegna dpi viene popolato da demo-config.js altrimenti da genera_pdf.php !-->
  
		</form>
 
		</div>
		

      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>JCsnc</b>
    </div>
    <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="https://www.liofilchem.com">Liofilchem</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="../../plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="../../plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- InputMask -->
<script src="../../plugins/moment/moment.min.js"></script>
<script src="../../plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- date-range-picker -->
<script src="../../plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="../../plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="../../plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- BS-Stepper -->
<script src="../../plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="../../plugins/dropzone/min/dropzone.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!-- Page specific script -->

<!-- per upload -->
<script src="../../dist/js/jquery.dm-uploader.min.js"></script>
<script src="demo-ui.js?ver=1.8"></script>
<script src="demo-config.js?ver=1.5"></script>
<!-- fine upload -->
<script>

function save() {
	num_elementi=$("#num_elementi").val();
	sub=1;
	for (sca=0;sca<=num_elementi-1;sca++) {
		giacenza=$("#giacenza"+sca).val()
		qta_impegno=$("#qta_impegno"+sca).val()
		giacenza=parseInt(giacenza)
		qta_impegno=parseInt(qta_impegno)
		
		if (giacenza.length==0) giacenza=0
		if (qta_impegno.length==0) qta_impegno=0
		st="";
		$("#qta_impegno"+sca).css("border-color","")
		if (giacenza<qta_impegno) {
			sub=0
			$("#qta_impegno"+sca).css("border-color","red")
		}	
		
	}
	$("#send_richiesta").val("SAVE")
	if (sub==1) $("#frm_view").submit();
	else alert("Controllare i campi evidenziati!");
}

function old_save() {
	num_elementi=$("#num_elementi").val();
	sub=1;
	for (sca=0;sca<=num_elementi-1;sca++) {
		giacenza_impegno=$("#giacenza_impegno"+sca).val()
		qta_impegno=$("#qta_impegno"+sca).val()
		giacenza_impegno=parseInt(giacenza_impegno)
		qta_impegno=parseInt(qta_impegno)
		
		if (giacenza_impegno.length==0) giacenza_impegno=0
		if (qta_impegno.length==0) qta_impegno=0
		st="";
		$("#qta_impegno"+sca).css("border-color","")
		if (giacenza_impegno<qta_impegno) {
			sub=0
			$("#qta_impegno"+sca).css("border-color","red")
		}	
		
	}
	$("#send_richiesta").val("SAVE")
	if (sub==1) $("#frm_view").submit();
	else alert("Controllare i campi evidenziati!");
}

function firma() {
	testo_doc=$("#testo_doc").val()
	if (testo_doc.length==0) {
		alert("Definire il testo che sarà associato al documento di consegna!");
		$('#testo_doc').focus()
		return false;
	}
	$("#testo_doc").prop("readonly", true);
	window.open('../firma/firma.php', '_blank');
	$('#btn_consegna').prop("disabled", false);
	$('#btn_consegna').focus()
}

function allega() {
	testo_doc=$("#testo_doc").val()
	if (testo_doc.length==0) {
		alert("Definire il testo che sarà associato al documento di consegna!");
		$('#testo_doc').focus()
		return false;
	}
	$("#sez_allegati").show();
	$("#testo_doc").prop("readonly", true);
}
function consegna() {
	num_elementi=$("#num_elementi").val();
	sub=1;
	for (sca=0;sca<=num_elementi-1;sca++) {
		giacenza_impegno=$("#giacenza_impegno"+sca).val()
		qta_impegno=$("#qta_impegno"+sca).val()
		giacenza_impegno=parseInt(giacenza_impegno)
		qta_impegno=parseInt(qta_impegno)
		
		if (giacenza_impegno.length==0) giacenza_impegno=0
		if (qta_impegno.length==0) qta_impegno=0
		st="";
		$("#qta_impegno"+sca).css("border-color","")
		if (giacenza_impegno<qta_impegno) {
			sub=0
			$("#qta_impegno"+sca).css("border-color","red")
		}	
		
	}
	$("#send_consegna").val("consegna")
	if (sub==1) $("#frm_view").submit();
	else alert("Controllare i campi evidenziati!");
}


</script>
</body>
</html>
