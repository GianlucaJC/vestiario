<?php
class Main_Sottoscorta
	{
	private $conn;

	
	// costruttore
	public function __construct($db){
		$this->conn = $db;
		$this->conn->set_charset("utf8");
	}


	function elenco() {
		$cond="giacenza<=sottoscorta";
		if (isset($_POST['btn_avvia'])) $cond="giacenza<=".$_POST['min_ss'];
		

		$sql="SELECT p.*,f.fornitore from `prodotti` p
				INNER JOIN `fornitori` f ON p.id_fornitore=f.id
				WHERE p.dele=0 and $cond
				ORDER BY p.descrizione";
		$resp=array();
		$result=$this->conn->query($sql);
		while($results = $result->fetch_assoc()){
			$resp[]=$results;
		}
		return $resp;
	}
	
	function qta_from_richieste() {
		$sql="SELECT ri.codice_articolo,ri.taglia,sum(ri.qta_richiesta-ri.qta_consegnata) qta_req 
			FROM `richieste` r 
			INNER JOIN `richieste_items` ri ON r.id=ri.id_richiesta 
			INNER JOIN `prodotti` p ON p.codice_fornitore=ri.codice_articolo and p.taglia=ri.taglia
			WHERE ri.qta_richiesta-ri.qta_consegnata>0
			GROUP BY ri.codice_articolo,ri.taglia ";

		$resp=array();
		$result=$this->conn->query($sql);
		while($results = $result->fetch_assoc()){
			$codice_articolo=$results['codice_articolo'];
			$taglia=$results['taglia'];
			$qta=$results['qta_req'];
			$ref=$codice_articolo.$taglia;
			$resp[$ref]=$qta;
		}
		return $resp;		
	}
}
?>