<?php
	$main = new Main_main($db);
	$main_carico = new Main_Carico($db);
	
	$send_richiesta="";
	if (isset($_POST['send_richiesta'])) $send_richiesta=$_POST['send_richiesta'];
	if (strlen($send_richiesta)!=0) $main_carico->save_carico();
	
	$from="";
	if (isset($_GET['from'])) $from=$_GET['from'];
	if (isset($_POST['from'])) $from=$_POST['from'];

?>