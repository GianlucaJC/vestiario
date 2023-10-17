<?php
	$main = new Main_main($db);
	$stat=$main->count_ric();
	$check_sottoscorta=$main->check_sottoscorta();
	$check_scadenze=$main->check_scadenze();
?>