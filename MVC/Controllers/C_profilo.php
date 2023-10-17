<?php
	$ref=$_GET['ref'];
	$main_dipendenti = new Main_Dipendenti($db);

	$reparto_ref="";
	if (isset($_POST['reparto_ref'])) {
		$reparto_ref=$_POST['reparto_ref'];
		$save_reparto=$main_dipendenti->save_reparto($reparto_ref,$ref);
	}	
	
	
	$elenco_pdf_abb=$main_dipendenti->elenco_pdf($ref,"abb");
	$elenco_pdf_dpi=$main_dipendenti->elenco_pdf($ref,"dpi");
	$profilo=$main_dipendenti->elenco($ref);
	$reparti_e_sr=$main_dipendenti->reparti_e_sr();
	
	$elenco_dotazione=$main_dipendenti->elenco_dotazione($ref);
	
?>