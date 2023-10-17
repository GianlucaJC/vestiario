<?php
class Main_Reparti
	{
	private $conn;

	
	// costruttore
	public function __construct($db){
		$this->conn = $db;
		$this->conn->set_charset("utf8");
	}


	function elenco() {
		$sql="SELECT r.id,r.reparto,s.reparto sotto_reparto from `reparti` r
				LEFT OUTER JOIN `sotto_reparti` s ON r.id=s.id_reparto
				 ORDER BY r.reparto,s.reparto";
		
		$resp=array();
		$result=$this->conn->query($sql);
		$elem=0;
		while($results = $result->fetch_assoc()){
			$id_reparto = $results['id_reparto'];
			$reparto = stripslashes($results['reparto']);
			$sotto_reparto = stripslashes($results['sotto_reparto']);
			$resp[$elem]['id_reparto']=$id_reparto;
			$resp[$elem]['reparto']=$reparto;
			$resp[$elem]['sotto_reparto']=$sotto_reparto;
			$elem++;
		}
		return $resp;
	}


}
?>