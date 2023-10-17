<?php
	$main = new Main_main($db);
	if (isset($_POST['operazione'])) {
		$operazione=$_POST['operazione'];

		if ($operazione=="login") {
			$login=$main->login();
			print json_encode($login);
			exit;
		}
	}	


?>