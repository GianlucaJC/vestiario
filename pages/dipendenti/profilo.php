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
	
	include_once '../../MVC/Models/M_dipendenti.php';
	include_once '../../MVC/Controllers/C_profilo.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profilo | User Profile</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
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
        <a href="../../index3.html" class="nav-link">Home</a>
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
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Profilo</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
			  <li class="breadcrumb-item">Pages</li>
              <li class="breadcrumb-item active">User Profile</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
                       src="../../dist/img/boxed-bg.jpg"
                       alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">
					<?php 
						echo $profilo[0]['dipendente'];
					?>
				</h3>

                <!--
				<p class="text-muted text-center">
					Responsabile QA
				</p>
				!-->

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>PDF di consegne</b> <a class="float-right">
					<?php echo count($elenco_pdf_abb)+count($elenco_pdf_dpi); ?>
					</a>
                  </li>
                  <li class="list-group-item">
                    <b>Reparto</b> 
					<a class="float-right" onclick="$('#div_reparto').toggle();">
						<?php echo $profilo[0]['reparto']; ?>
						<?php echo " (".$profilo[0]['sotto_reparto'].")"; ?>
					</a>
					<?php
						if (isset($_POST['reparto_ref'])) {
							echo "<div class='alert alert-success mt-5' role='alert'>";
							  echo "Profilo dipendente aggiornato con successo!";
							echo "</div>";
						}
					?>
					<form action='' method='post'>
					  <div class="form-group mt-3" id='div_reparto' style='display:none'>
						<label for="reparto_ref">Definisci Reparto</label>						
						<select type='text' name='reparto_ref' id='reparto_ref' class="form-control" required >
							<?php 
							
								echo "<option value=''>Select...</option>";
								for ($sca=0;$sca<=count($reparti_e_sr)-1;$sca++) {
									$id_rep=$reparti_e_sr[$sca]['id_rep'];
									$id_sr=$reparti_e_sr[$sca]['id_sr'];
									if (strlen($id_sr)==0) $id_sr=0;
									$id_ref="$id_rep|$id_sr";
									
									$reparto=$reparti_e_sr[$sca]['reparto'];
									$sotto_reparto=$reparti_e_sr[$sca]['sotto_reparto'];
									$ref_rep="$reparto ($sotto_reparto)";
									echo "<option value='$id_ref' ";
									if ($reparto_ref==$id_ref) echo " selected ";
									echo ">".$ref_rep."</option>";
								}

							?>
						</select>
						<br>
					
						<button type="submit" class="btn btn-primary">Salva</button>
					  </div>
					</form>
                  </li>
                </ul>

                <!--
				<a href="#" class="btn btn-primary btn-block"><b>Modifica</b></a>
				!-->
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->


            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#abb" data-toggle="tab">Abbigliamento</a></li>
                  <li class="nav-item"><a class="nav-link" href="#dpi" data-toggle="tab">DPI</a></li>
                  <li class="nav-item"><a class="nav-link" href="#dot" data-toggle="tab">Dotazione</a></li>

                </ul>
              </div><!-- /.card-header -->
              
			  
			  <div class="card-body">
                <div class="tab-content">
                  
                  <!-- /.tab-pane -->
                  <div class="tab-pane active" id="abb">
                    <!-- The timeline -->


					<table id="example1" class="table table-bordered table-striped">
					  <thead>
					  <tr>
						<th>File</th>

						<th>Data-Ora</th>
					  </tr>
					  </thead>
					  <tbody>
						  
						<?php
							for ($sca=0;$sca<=count($elenco_pdf_abb)-1;$sca++) {?>
							
								  <!-- timeline time label -->
									<?php
										$data_ora=$elenco_pdf_abb[$sca]['data_ora'];
										$tipo_richiesta=$elenco_pdf_abb[$sca]['tipo_richiesta'];
										$dx=date("d-m-Y H:i:s",strtotime($data_ora));
										
										$fxx=$elenco_pdf_abb[$sca]['filename'];
										$href="info/$tipo_richiesta/$ref/$fxx";
										
										echo "<tr>";
											echo "<td>";
												echo "<a href='$href' target='_blank'>";
													echo $elenco_pdf_abb[$sca]['testo_doc'];
												echo "</a>";
											echo "</td>";
											echo "<td>";
												echo $dx;
											echo "</td>";
										echo "</tr>";	
										
									  ?>
						  <?php } ?>
					  
					  </tbody>
					  <tfoot>

					  </tfoot>
					</table>

                  </div>
                  <!-- /.tab-pane -->

                  <div class="tab-pane" id="dpi">


					<table id="example2" class="table table-bordered table-striped">
					  <thead>
					  <tr>
						<th>File</th>

						<th>Data-Ora</th>
					  </tr>
					  </thead>
					  <tbody>
						  
						<?php
							for ($sca=0;$sca<=count($elenco_pdf_dpi)-1;$sca++) {?>
							
								  <!-- timeline time label -->
									<?php
										$data_ora=$elenco_pdf_dpi[$sca]['data_ora'];
										$tipo_richiesta=$elenco_pdf_dpi[$sca]['tipo_richiesta'];
										$dx=date("d-m-Y H:i:s",strtotime($data_ora));
										
										$fxx=$elenco_pdf_dpi[$sca]['filename'];
										$href="info/$tipo_richiesta/$ref/$fxx";
										
										echo "<tr>";
											echo "<td>";
												echo "<a href='$href' target='_blank'>";
													echo $elenco_pdf_dpi[$sca]['testo_doc'];
												echo "</a>";
											echo "</td>";
											echo "<td>";
												echo $dx;
											echo "</td>";
										echo "</tr>";	
										
									  ?>
						  <?php } ?>
					  
					  </tbody>
					  <tfoot>

					  </tfoot>
					</table>

                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="dot">
				  

				  
					<table id="example3" class="table table-bordered table-striped">
					  <thead>
					  <tr>
						<th>Tipo</th>
						<th>Codice</th>
						<th>Descrizione</th>
						<th>Taglia</th>
						<!--
						<th>Scadenza</th>
						!-->
					  </tr>
					  </thead>
					  <tbody>
						  
						<?php
						
							foreach ($elenco_dotazione as $codice_articolo=>$v) {?>
							
								  <!-- timeline time label -->
									<?php
									
										$tipo_prod=$elenco_dotazione[$codice_articolo][0]['tipo_prod'];
										$descrizione_articolo=stripslashes($elenco_dotazione[$codice_articolo][0]['descrizione_articolo']);
										$taglia=$elenco_dotazione[$codice_articolo][0]['taglia'];
										$data_scadenza=$elenco_dotazione[$codice_articolo][0]['data_scadenza'];
										$qta_consegnata=$elenco_dotazione[$codice_articolo][0]['qta_consegnata'];
										
										
										echo "<tr>";
											echo "<td>";
												echo $tipo_prod;
											echo "</td>";
											echo "<td>";
												//if (count($elenco_dotazione[$codice_articolo])>1) {
													echo "<a href='#' onclick=\"view_story($ref,'$codice_articolo','$taglia');\" data-toggle='modal' data-target='#modal_story'>";
														echo $codice_articolo;
													echo "</a>";	
												//}
												//else	
													//echo $codice_articolo;
											echo "</td>";
											echo "<td>";
												echo $descrizione_articolo;
											echo "</td>";
											echo "<td>";
												echo $taglia;
											echo "</td>";
											/*
											echo "<td>";
												echo $data_scadenza;
											echo "</td>";
											*/
										echo "</tr>";	
										
									  ?>
						  <?php } ?>
					  
					  </tbody>
					  <tfoot>

					  </tfoot>
					</table>

				  </div>
                  <!-- /.tab-pane -->

                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
			  
			  
			  
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      
    </div>
    <strong>Copyright &copy; <?php echo date("Y"); ?>  <a href="https://www.liofilchem.com">Liofilchem</a> </strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
  
<div class="modal fade bd-example-modal-lg" id="modal_story" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Storia richiesta prodotto</h5>
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



<script>
  $(function () {
    $("#example1").DataTable({
		"responsive": true, "lengthChange": false, "autoWidth": false,
		//, "colvis"
		"buttons": ["copy", "excel"],
		"language": {
			"lengthMenu": "Mostra _MENU_ documenti per pagina &nbsp&nbsp",
			"zeroRecords": "Nessun prodotto trovato",
			"info": "Pagina mostrata _PAGE_ di _PAGES_ di _TOTAL_ documenti",
			"infoEmpty": "Non ci sono documenti",
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
	
    $("#example2").DataTable({
		"responsive": true, "lengthChange": false, "autoWidth": false,
		//, "colvis"
		"buttons": ["copy", "excel"],
		"language": {
			"lengthMenu": "Mostra _MENU_ documenti per pagina &nbsp&nbsp",
			"zeroRecords": "Nessun prodotto trovato",
			"info": "Pagina mostrata _PAGE_ di _PAGES_ di _TOTAL_ documenti",
			"infoEmpty": "Non ci sono documenti",
			"infoFiltered": "(filtrate da _MAX_ record totali)",
			"search":         "Cerca:",
			"paginate": {
				"first":      "Prima",
				"last":       "Ultima",
				"next":       "Successiva",
				"previous":   "Precedente"
			}
		
		}	  
    }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');


	
    $("#example3").DataTable({
		"responsive": true, "lengthChange": false, "autoWidth": false,
		//, "colvis"
		"buttons": ["copy", "excel", "pdf"],
		"language": {
			"lengthMenu": "Mostra _MENU_ prodotti per pagina &nbsp&nbsp",
			"zeroRecords": "Nessun prodotto trovato",
			"info": "Pagina mostrata _PAGE_ di _PAGES_ di _TOTAL_ prodotti",
			"infoEmpty": "Non ci sono prodotti associati",
			"infoFiltered": "(filtrate da _MAX_ record totali)",
			"search":         "Cerca:",
			"paginate": {
				"first":      "Prima",
				"last":       "Ultima",
				"next":       "Successiva",
				"previous":   "Precedente"
			}
		
		}	  
    }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');

  });
  
  function view_story(ref,codice_articolo,taglia) {
	fetch('ajax.php', {
		method: 'post',
		//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
		headers: {
		  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		body: 'operazione=view_story&ref='+ref+"&codice_articolo="+codice_articolo+"&taglia="+taglia
	})
	.then(response => {
		if (response.ok) {
		   return response.json();
		}
	})
	.then(resp=>{
		html="Storia richiesta prodotto: <font color='blue'><b>"+codice_articolo+"</b></font> Taglia: <font color='blue'><b>"+taglia+"</b></font>"
		$("#exampleModalLabel").html(html)
		html="";
		html+="<table id='example4' class='table table-bordered table-striped'>";
			html+="<thead>";
				html+="<tr>";
					html+="<th>Data richiesta</th>";
					html+="<th>Qta richiesta</th>";
					html+="<th>Qta consegnata</th>";
					html+="<th>Data scadenza</th>";
				html+="</tr>";
			html+="</thead>";
			html+="<tbody>";
				for (sca=0;sca<=resp.length-1;sca++) {
					html+="<tr>";
						html+="<td>"+resp[sca].data_richiesta+"</td>";
						html+="<td>"+resp[sca].qta_richiesta+"</td>";
						html+="<td>"+resp[sca].qta_consegnata+"</td>";
						html+="<td>"+resp[sca].data_scadenza+"</td>";
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
				"lengthMenu": "Mostra _MENU_ prodotti per pagina &nbsp&nbsp",
				"zeroRecords": "Nessun prodotto trovato",
				"info": "Pagina mostrata _PAGE_ di _PAGES_ di _TOTAL_ prodotti",
				"infoEmpty": "Non ci sono prodotti associati",
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
</script>

</body>
</html>


