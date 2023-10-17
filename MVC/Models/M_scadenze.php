<?php
class Main_Scadenze
	{
	private $conn;

	
	// costruttore
	public function __construct($db){
		$this->conn = $db;
		$this->conn->set_charset("utf8");
	}


	function elenco() {
		$date = date("Y-m-d");
		$data=date('Y-m-d', strtotime($date. ' + 10 days'));			

		$cond="ri.data_scadenza<='$data'";
		if (isset($_POST['btn_filtro'])) $cond="ri.data_scadenza<='".$_POST['min_scad']."'";
		

		$sql="SELECT ri.id id_ref,d.dipendente,p.descrizione,ri.taglia,ri.data_scadenza
			FROM `vestiario`.`richieste_items` ri 
			INNER JOIN `vestiario`.richieste r ON ri.id_richiesta=r.id
			INNER JOIN `vestiario`.dipendenti d ON r.id_dipendente=d.id
			INNER JOIN `vestiario`.prodotti p ON ri.codice_articolo=codice_fornitore and ri.taglia=p.taglia
			WHERE $cond
			GROUP BY ri.codice_articolo,ri.taglia";
		$result = $this->conn->query($sql);

		$resp=array();
		while($results = $result->fetch_assoc()){
			$resp[]=$results;
		}
		return $resp;
	}
	
	public function annulla_scadenze() {
		$scad=$_POST['scad'];
		for ($sca=0;$sca<=count($scad)-1;$sca++) {
			$id_ref=$scad[$sca];
			$sql="UPDATE `vestiario`.richieste_items SET data_scadenza=NULL WHERE id=$id_ref";
			$result = $this->conn->query($sql);
		}
	}
	
}
?>