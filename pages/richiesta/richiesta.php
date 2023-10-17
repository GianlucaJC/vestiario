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
	include_once '../../MVC/Models/M_richiesta.php';
	include_once '../../MVC/Controllers/C_richiesta.php';
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
            <h1>Richiesta Vestizione</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
			    <li class="breadcrumb-item">Pages</li>
              <li class="breadcrumb-item active">Nuova Richiesta</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
	  
	<?php if (strlen($send_richiesta)!=0) {?>
		<div class="alert alert-success" role="alert">
		  <b>Operazione completata con successo</b>. Richiesta creata/aggiornata.
		</div>	 
	<?php } ?>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
	  
	  <form action="richiesta.php" method="post" id='frm_view' name='frm_view' class="needs-validation">
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default" id='div_master'>
          <div class="card-header">
				<input type='hidden' name='id_edit_ref' id='id_edit_ref' value='<?php echo $id_edit; ?>'>
				
				<div class="row">
				  <div class="col-md-6">
					<div class="form-group">
					  <label>Tipo richiesta</label>
					  <?php
					  
					  
						if (count($load_richiesta)!=0) {
							echo "<br>";
							if ($load_richiesta[0]['tipo_richiesta']=="abb")
								echo "Abbigliamento";
							else
								echo "DPI";
							
							echo "<input type='hidden' name='tipo_richiesta' id='tipo_richiesta' value='".$load_richiesta[0]['tipo_richiesta']."'>";
						}
					  
					   else {?>
						<div id='div_richiesta'>
						  <select class="form-control select2" style="width: 100%;" required name='tipo_richiesta' id='tipo_richiesta' onchange='select_tipo(this.value)' >
							<option value=''>Select...</option>
							<?php 
									echo "<option value='abb' ";
									if ($load_richiesta[0]['tipo_richiesta']=="abb") echo " selected ";
									echo ">Abbigliamento</option>";

									echo "<option value='dpi' ";
									if ($load_richiesta[0]['tipo_richiesta']=="dpi") echo " selected ";
									echo ">DPI</option>";

							?>

						  </select>
						 </div> 
						 <div id="div_richiesta_descr"></div>
					   <?php } ?>

					</div>
				  </div>

				  <div class="col-md-6">
					<div class="form-group">
					  <label>Dipendente</label>
					  
					  <select class="form-control select2" style="width: 100%;" required name='dipendente' id='dipendente'>
						<option value=''>Select...</option>
						<?php 
							$dip_load=0;
							if (count($load_richiesta)>0) $dip_load=$load_richiesta[0]['id_dipendente'];
							
							for ($sca=0;$sca<=count($elenco_dipendenti)-1;$sca++) {
								echo "<option value='".$elenco_dipendenti[$sca]['id']."' ";
								if ($dip_load==$elenco_dipendenti[$sca]['id']) echo " selected ";
								echo ">";
								echo stripslashes($elenco_dipendenti[$sca]['dipendente']);
								echo "</option>";
							}
						
						?>

					  </select>
					  
					  

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
				
				
					$voce=0;
					for ($voci=0;$voci<=19;$voci++) {?>
						<?php
							$prodotto_load="";
							$taglia_load="";
							$qta_load="";
							
							$disp="display:none";
							if (isset($load_richiesta[$voci]['codice_articolo'])) {
								$prodotto_load=$load_richiesta[$voci]['codice_articolo'];
								$taglia_load=$load_richiesta[$voci]['taglia'];
								$qta_load=$load_richiesta[$voci]['qta_richiesta'];
							
								
								$disp="";
								$voce++;
							}
						?>
						<div class="row div_voce" style='<?php echo $disp; ?>' id='div_voce<?php echo $voci;?>'>
							<div class="col-md-6 col-sm-6">
								
								<?php 
								
									if (count($load_richiesta)==0 || $load_richiesta[0]['tipo_richiesta']=="abb") {
										
										$elenco_prodotti=$elenco_prodotti_abb;
										if ($load_richiesta[0]['tipo_richiesta']=="dpi") 
											$elenco_prodotti=$elenco_prodotti_dpi;
										
										$view="display:block";
										if (count($load_richiesta)==0) $view="display:none";
								?>
								<div class='tipo_abb' class="form-group" style='<?php echo $view;?>'>

								  <label>Prodotti disponibili</label>
								  
								  <a href='javascript:void(0)' onclick='anteprima_all(<?php echo $voci;?>)' >
									<i class="fas fa-images"></i>
								  </a>
								  
								  <select class="form-control select2" style="width: 100%;" name="prodotto[]" id='prodotto<?php echo $voci;?>' onchange="popola_taglia(<?php echo $voci; ?>,this.value)" >
									<option value=''>Select...</option>
									<?php for ($sca=0;$sca<=count($elenco_prodotti)-1;$sca++) {
										$info=$elenco_prodotti[$sca]['descrizione'];
										//" - ".$elenco_prodotti[$sca]['fornitore']." - ".$elenco_prodotti[$sca]['taglia'];
										
										echo "<option  value='".$elenco_prodotti[$sca]['codice_fornitore']."' ";
										if ($prodotto_load==$elenco_prodotti[$sca]['codice_fornitore']) echo " selected ";
										echo ">".$info."</option>";	
									} 
									?>	
								  </select>

								<?php 
										echo "<div class='mt-3 anteprime' style='display:none' id='div_ant$voci'>";
										echo "<h4>Anteprime dei prodotti</h4>";
											
												echo "<div class='row'>";
												for ($sca=0;$sca<=count($elenco_prodotti)-1;$sca++) {
														$info=$elenco_prodotti[$sca]['descrizione'];
														$product_id=$elenco_prodotti[$sca]['id'];
														$c_f=$elenco_prodotti[$sca]['codice_fornitore'];
														
														echo "<div class='col-md-3'>";
															echo "<div class='ms-2 me-auto'>";
															  echo "<div class='fw-bold'>";
																echo "<a href='javascript:void(0)' onclick=\"$('#prodotto$voci').val('$c_f');$('.anteprime').hide();popola_taglia($voci,'$c_f')\">";
																	echo $info;
																echo "</a>";
															  echo "</div>";

																$src="?";
																for ($f=1;$f<=4;$f++) {
																	if ($f==1) $img="../prodotti/files/".$product_id.".jpg";
																	if ($f==2) $img="../prodotti/files/".$product_id.".jpeg";
																	if ($f==3) $img="../prodotti/files/".$product_id.".png";
																	if ($f==4) $img="../prodotti/files/".$product_id.".gif";
																	if (file_exists($img)) $src=$img;
																}	
															  
															  
															  if ($src=="?") echo "Immagine prodotto assente";
															  else echo "<img style='width:35%' src='$src'  class='img-fluid img-thumbnail'>";
															  
															echo "</div>";
															//echo "<span class='badge bg-primary rounded-pill'>14</span>";
														echo "</div>"; 
												}
										
												echo "</div>";
										echo "</div>";		
								?>	
	
								  
								</div>
								
								
								
								<?php } ?>



						<?php
							if (count($load_richiesta)==0 || $load_richiesta[0]['tipo_richiesta']=="dpi") {
							$view="display:block";
							if (count($load_richiesta)==0) $view="display:none";
								$elenco_prodotti=$elenco_prodotti_dpi;
								if ($load_richiesta[0]['tipo_richiesta']=="dpi") 
									$elenco_prodotti=$elenco_prodotti_dpi;
								
								$view="display:block";
								if (count($load_richiesta)==0) $view="display:none";
								?>
								<div class='tipo_dpi' class="form-group" style='<?php echo $view;?>'>

								  <label>Prodotti disponibili</label>
								  
								  <a href='javascript:void(0)' onclick='anteprima_all(<?php echo $voci;?>)' >
									<i class="fas fa-images"></i>
								  </a>
								  
								  <select class="form-control select2" style="width: 100%;" name="prodotto[]" id='prodotto<?php echo $voci;?>' onchange="popola_taglia(<?php echo $voci; ?>,this.value)" >
									<option value=''>Select...</option>
									<?php for ($sca=0;$sca<=count($elenco_prodotti)-1;$sca++) {
										$info=$elenco_prodotti[$sca]['descrizione'];
										//" - ".$elenco_prodotti[$sca]['fornitore']." - ".$elenco_prodotti[$sca]['taglia'];
										
										echo "<option  value='".$elenco_prodotti[$sca]['codice_fornitore']."' ";
										if ($prodotto_load==$elenco_prodotti[$sca]['codice_fornitore']) echo " selected ";
										echo ">".$info."</option>";	
									} 
									?>	
								  </select>

								<?php 
										echo "<div class='mt-3 anteprime' style='display:none' id='div_ant$voci'>";
										echo "<h4>Anteprime dei prodotti</h4>";
											
												echo "<div class='row'>";
												for ($sca=0;$sca<=count($elenco_prodotti)-1;$sca++) {
														$info=$elenco_prodotti[$sca]['descrizione'];
														$product_id=$elenco_prodotti[$sca]['id'];
														$c_f=$elenco_prodotti[$sca]['codice_fornitore'];
														
														echo "<div class='col-md-3'>";
															echo "<div class='ms-2 me-auto'>";
															  echo "<div class='fw-bold'>";
																echo "<a href='javascript:void(0)' onclick=\"$('#prodotto$voci').val('$c_f');$('.anteprime').hide();popola_taglia($voci,'$c_f')\">";
																	echo $info;
																echo "</a>";
															  echo "</div>";

																$src="?";
																for ($f=1;$f<=4;$f++) {
																	if ($f==1) $img="../prodotti/files/".$product_id.".jpg";
																	if ($f==2) $img="../prodotti/files/".$product_id.".jpeg";
																	if ($f==3) $img="../prodotti/files/".$product_id.".png";
																	if ($f==4) $img="../prodotti/files/".$product_id.".gif";
																	if (file_exists($img)) $src=$img;
																}	
															  
															  
															  if ($src=="?") echo "Immagine prodotto assente";
															  else echo "<img style='width:35%' src='$src'  class='img-fluid img-thumbnail'>";
															  
															echo "</div>";
															//echo "<span class='badge bg-primary rounded-pill'>14</span>";
														echo "</div>"; 
												}
										
												echo "</div>";
										echo "</div>";		
								?>	
	
								  
								</div>
								
								
								
								<?php } ?>
							





















							</div>


					  
						<div class="col-md-2 col-sm-2">
						  <!-- text input -->
						  <div class="form-group">
							<label>Taglia</label>
								  <a href='javascript:void(0)' onclick='view_foto(<?php echo $voci;?>)' data-toggle="modal" data-target="#modal_foto">
									<i class="fas fa-camera"></i>
								  </a>							
							<select class="form-control select2" style="width: 100%;" name="taglia[]" id="taglia<?php echo $voci;?>"  >
							<?php
							
								if (strlen($taglia_load)!=0) {
									$taglie=$main_richiesta->taglie($prodotto_load);
									for ($sca=0;$sca<=count($taglie)-1;$sca++) {
										$taglia_ref=$taglie[$sca]['taglia'];
										echo "<option value='$taglia_ref' ";
										if ($taglia_ref==$taglia_load) echo " selected ";
										echo ">$taglia_ref</option>";
									}
								}
							?>
								
							</select>
						  </div>
						</div>
						
						<div class="col-md-2 col-sm-2">
						  <!-- text input -->
						  <div class="form-group">
							<label>Quantità</label>
							<input type="number" class="form-control" placeholder="QTA" name="qta[]" id='qta<?php echo $voci;?>' value="<?php echo $qta_load; ?>">
						  </div>
						</div>				  


						
						<div class="col-md-2 col-sm-2">
						  <!-- text input -->
						  <br>
						  <div class="form-group">
							<a href='javascript:void(0)' onclick='remove_art(<?php echo $voci; ?>)'>
								<i class="far fa-trash-alt"></i>
							</a>	
						  </div>
						</div>							
						
					</div>
					<?php } ?>
					
				
				
				
				<center>
					
					<font size='5'>
						<a href='#!'>
							<i class="fas fa-plus-circle" onclick="add_voce()" > Aggiungi Articolo</i>
						</a>	
					</font>
				</center>	



			</div>
				<!-- /.row -->
			

         </div>

		<div id='div_new_clone'></div>
		
		<!--
		<font size='5'>
			<a href='#!' onclick='clone()'>
				<i class="fas fa-user-plus"></i>
			</a>	
		</font>
		!-->			
		
		<input class="btn btn-primary" type="submit" value="Salva richiesta" id='send_richiesta' name='send_richiesta'>

  
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
  
  
<div class="modal fade bd-example-modal-lg" id="modal_foto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Anteprima Prodotto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id='body_msg_foto' style='overflow:scroll'>
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
      </div>
    </div>
  </div>
</div>  
  
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


<script>
voce=<?php echo $voce; ?>;
  $(function () {
	
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
		
	$( ".div_voce" ).each(function( index ) {
		if ($("#div_voce"+index).is(":visible")){
			console.log("voce", index );
		}
		else $("#div_voce"+index).remove()
		
	});
	
      }, false)
    })


	if (voce==0) add_voce()



 //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date picker
    $('#reservationdate').datetimepicker({
        format: 'L'
    });

    //Date and time picker
    $('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY hh:mm A'
      }
    })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Timepicker
    $('#timepicker').datetimepicker({
      format: 'LT'
    })

    //Bootstrap Duallistbox
    $('.duallistbox').bootstrapDualListbox()

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    $('.my-colorpicker2').on('colorpickerChange', function(event) {
      $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
    })

    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    })

  })

function clone() {
	$( "#div_master" ).clone().appendTo( "#div_new_clone" );	
}	


function popola_taglia(indice,codice_fornitore) {
$("#taglia"+indice)
    .find('option')
    .remove()
    .end();	

	fetch('ajax.php', {
		method: 'post',
		//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
		headers: {
		  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		body: 'operazione=popola_taglia&codice_fornitore='+codice_fornitore
	})
	.then(response => {
		if (response.ok) {
		   return response.json();
		}
	})
	.then(resp=>{
		
		$('#taglia'+indice).append('<option value="">Select...</option>');
		for (sca=0;sca<=resp.length-1;sca++) {
			taglia=resp[sca].taglia
			$('#taglia'+indice).append('<option value="' + taglia + '">' + taglia + '</option>');
		}	


		
	})
	.catch(status, err => {
		return console.log(status, err);
	})
}	

function add_voce() {
	$(".anteprime").hide();
	$("#div_voce"+voce).show()
	$('#prodotto'+voce).prop('required',true);
	$('#taglia'+voce).prop('required',true);
	$('#qta'+voce).prop('required',true);
	
	voce++;
}

function remove_art(voce)  {
	if (!confirm("Sicuri di eliminare l'articolo dalla richiesta?")) return false;
	$("#div_voce"+voce).hide()
	$('#prodotto'+voce).prop('required',false);
	$('#taglia'+voce).prop('required',false);
	$('#qta'+voce).prop('required',false);
	
}

function select_tipo(value) {
	if (value=="abb"){
		$(".tipo_dpi").empty();
		$(".tipo_abb").show();
	}
	else {
		$(".tipo_abb").empty();
		$(".tipo_dpi").show();
	}	
	$("#div_richiesta").hide();
	descr="Abbigliamento"
	if (value=="dpi") descr="DPI";
	$("#div_richiesta_descr").html(descr)
}	

function view_foto(value) {
	
	prodotto=$("#prodotto"+value).val();
	taglia=$("#taglia"+value).val();
	html="";
	if (prodotto.length==0 || taglia.length==0) {
		html="";
		html+="<div class='alert alert-warning' role='alert'>";
			html+="Selezionare il prodotto e la taglia per vedere l'anteprima!";
		html+="</div>";
		$("#body_msg_foto").html(html)
		return false;
	}	

	fetch('ajax.php', {
		method: 'post',
		//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
		headers: {
		  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		body: 'operazione=anteprima&codice_fornitore='+prodotto+"&taglia="+taglia
	})
	.then(response => {
		if (response.ok) {
		   return response.json();
		}
	})
	.then(resp=>{
		html="";
		if (resp.length==0) {
			html+="<div class='alert alert-warning' role='alert'>";
				html+="Non risultano immagini associate a questo prodotto!";
			html+="</div>";
			$("#body_msg_foto").html(html)			
			return false;
		}
		var d = new Date();
		t = d.getTime();
		for (sca=0;sca<=resp.length-1;sca++) {
			fx=resp[sca];
			if (html.length!=0) html+="<hr>";
			html+="<img src='"+fx+"?ver="+t+"' class='rounded' alt='Immagine Articolo'>";
		}
		$("#body_msg_foto").html(html)
		
	})
	.catch(status, err => {
		return console.log(status, err);
	})


}

function anteprima_all(value) {
	$("#div_ant"+value).toggle();
}

</script>
</body>
</html>
