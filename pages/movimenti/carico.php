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
	include_once '../../MVC/Models/M_carico.php';
	include_once '../../MVC/Controllers/C_carico.php';
	

$descr_movimento="Carico";
if ($from=="2") $descr_movimento="Scarico";
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Carico/Scarico</title>

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
		  
            <h1>Movimenti di <?php echo $descr_movimento; ?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
			    <li class="breadcrumb-item">Pages</li>
              <li class="breadcrumb-item active"><?php echo $descr_movimento; ?></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
	  
	<?php if (strlen($send_richiesta)!=0) {?>
		<div class="alert alert-success" role="alert">
		  <b>Operazione completata con successo</b>
		</div>	 
	<?php } ?>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
	  
	  <form action="carico.php" method="post" id='frm_view' name='frm_view' class="needs-validation">
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default" id='div_master'>
          <div class="card-header">


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


				<div id='div_cont'></div>
				
				<center>
					
					<font size='5'>
						<a href='#!'>
							<i class="fas fa-plus-circle" onclick="elementi()" > Aggiungi Movimento</i>
						</a>	
					</font>
				</center>	



			</div>
				<!-- /.row -->
			

         </div>

		
		
		<input type='hidden' name='from' id='from' value="<?php echo $from; ?>">
		<input class="btn btn-primary" type="submit" value="Salva movimento" id='send_richiesta' name='send_richiesta'>

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


<script>

  $(function () {
	voce=-1
	elementi()

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






function remove_art(voce)  {
	if (!confirm("Sicuri di eliminare l'articolo?")) return false;
	$("#div_voce"+voce).remove()

	
}


function new_obj(id) {
			
	$("#avviso"+id).empty()
	
	$("#taglia"+id).find('option').remove().end();
	$("#fornitore"+id).find('option').remove().end();
	
	codice_prodotto=$("#prodotto"+id).val()
	fetch('ajax.php', {
		method: 'post',
		//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
		headers: {
		  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		body: 'operazione=popola_taglia&codice_prodotto='+codice_prodotto
	})
	.then(response => {
		if (response.ok) {
		   return response.json();
		}
	})
	.then(resp=>{
		html="<font color='red'>Codice inesistente!</font>"
		if (!resp || resp.length==0) {
			$("#avviso"+id).html(html)
			return;
		}
		$('#taglia'+id).append('<option value="">Select...</option>');
		for (sca=0;sca<=resp.length-1;sca++) {
			taglia=resp[sca].taglia
			$('#taglia'+id).append('<option value="' + taglia + '">' + taglia + '</option>');
		}	


		
	})
	.catch(status, err => {
		return console.log(status, err);
	})	
}

function select_fornitore(id) {
	$("#fornitore"+id).find('option').remove().end();
	codice_prodotto=$("#prodotto"+id).val()
	taglia=$("#taglia"+id).val()

	fetch('ajax.php', {
		method: 'post',
		//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
		headers: {
		  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		body: 'operazione=fornitori&codice_prodotto='+codice_prodotto+"&taglia="+taglia
	})
	.then(response => {
		if (response.ok) {
		   return response.json();
		}
	})
	.then(resp=>{
		$('#fornitore'+id).append('<option value="">Select...</option>');
		for (sca=0;sca<=resp.length-1;sca++) {
			id_fornitore=resp[sca].id
			fornitore=resp[sca].fornitore
			$('#fornitore'+id).append('<option value="' + id_fornitore + '">' + fornitore + '</option>');
		}	


		
	})
	.catch(status, err => {
		return console.log(status, err);
	})		
}

function elementi() {
	voce++
	html="";
	html+="<div class='row div_voce'  id='div_voce"+voce+"'>";
		 html+="<div class='col-md-4 col-sm-4'>";
			html+="<div class='form-group'>";
				if (voce==0) html+="<label>Prodotto</label>";
			 html+="<input type='text' class='form-control prod' name='prodotto[]' id='prodotto"+voce+"' placeholder='Codice' required>";
			html+="<div id='avviso"+voce+"' class='mt-1'></div>";
			html+="</div>";
		html+="</div>";

		html+="<div class='col-md-2 col-sm-2'>";
			
			html+="<div class='form-group'>";

			if (voce==0) html+="<label>Taglia</label>";
			
			html+="<select class='form-control select2' style='width: 100%;' name='taglia[]' id='taglia"+voce+"' onchange='select_fornitore("+voce+")' required>";
			html+="</select>";

			html+="</div>";
			
		html+="</div>";

		html+="<div class='col-md-2 col-sm-2'>";
			
			html+="<div class='form-group'>";

			if (voce==0) html+="<label>Fornitore</label>";
			
			html+="<select class='form-control select2' style='width: 100%;' name='fornitore[]' id='fornitore"+voce+"' required>";
			html+="</select>";

			html+="</div>";
			
		html+="</div>";
		

		html+="<div class='col-md-2 col-sm-2'>";
			html+="<div class='form-group'>";
				if (voce==0) html+="<label>Quantità</label>";
			 html+="<input type='text' class='form-control' name='qta[]' id='qta"+voce+"' placeholder='Qta' required>";
			html+="</div>";
		html+="</div>";	

		html+="<div class='col-md-2 col-sm-2'>";
			if (voce==0) html+="<br>";
			html+="<div class='form-group'>";
				html+="<a href='javascript:void(0)' onclick='remove_art("+voce+")'>";
					html+="<i class='far fa-trash-alt'></i>";
				html+="</a>";
			html+="</div>";
		html+="</div>";
		

	html+="</div>";	
		

$("#div_cont").append(html);

$('.prod').on('keydown', function(e) {
  if (e.keyCode === 9 || e.keyCode === 13) {
	e.preventDefault();
  	e.stopImmediatePropagation();
	id=this.id
	value=id.substr(8);
    new_obj(value);
  }
});


}





</script>
</body>
</html>
