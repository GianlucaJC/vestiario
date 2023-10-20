<?php
	$main = new Main_main($db);
	$main_impegno = new Main_Impegno($db);

	$send_richiesta=$_POST['send_richiesta'];
	
	$save_impegni="";
	if (strlen($send_richiesta)!=0) {
		$save_impegni=$main_impegno->save_impegni();
	}



	$impegno=$_POST['impegno'];
	
	$close_r=$_POST['close_r'];
	if ($close_r=="close") {
		$close=$main_impegno->close_richiesta($impegno);
	}
	$testo_doc="No testo";
	if (isset($_POST['testo_doc'])) $testo_doc=$_POST['testo_doc'];
	$load_richiesta=array();
	if (strlen($impegno)!=0) {
		$load_richiesta=$main_impegno->load_richiesta($impegno);
	}

	
	$send_consegna=$_POST['send_consegna'];
	$nofirma=0;$file_pdf="";$genera=0;
	if (strlen($send_consegna)!=0) {
		$tipo_richiesta=$load_richiesta[0]['tipo_richiesta'];
		$id_dipendente=$load_richiesta[0]['id_dipendente'];
		
		if ($tipo_richiesta=="abb")	{
			include("genera_pdf.php"); //viene popolata la variabile $file_pdf
			//in caso di richiesta dpi la variabile $file_pdf viene generata via POST
			if (strlen($file_pdf)!=0 && $genera==1) $main_impegno->archivia_pdf($testo_doc,$tipo_richiesta,$impegno,$file_pdf,$id_dipendente);
		}	
		
		//dpi
		if (isset($_POST['file_pdf']) && $tipo_richiesta=="dpi") {
			$file_pdf=$_POST['file_pdf'];
			$main_impegno->archivia_pdf($testo_doc,$tipo_richiesta,$impegno,$file_pdf,$id_dipendente);
		}	
		
		//ricarico la richiesta dopo aver aggiornato gli impegni
		$load_richiesta=$main_impegno->load_richiesta($impegno);

	}
	$load_old_request=$main_impegno->load_old_request($impegno);
	

	
?>