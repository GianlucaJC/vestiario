<?php
class Main_Fornitori
	{
	private $conn;

	
	// costruttore
	public function __construct($db){
		$this->conn = $db;
		$this->conn->set_charset("utf8");
	}


	function elenco($id="") {
		$cond="1";
		if (strlen($id)!=0) $cond="id=$id";
		$sql="SELECT * from `fornitori` WHERE $cond and dele=0 ORDER BY fornitore";
		
		$resp=array();
		$result=$this->conn->query($sql);
		$elem=0;
		while($results = $result->fetch_assoc()){
			$fornitore = stripslashes($results['fornitore']);
			$telefono = stripslashes($results['telefono']);
			$mail = stripslashes($results['mail']);
			$resp[$elem]['id']=$results['id'];
			$resp[$elem]['denominazione']=$fornitore;
			$resp[$elem]['telefono']=$telefono;
			$resp[$elem]['mail']=$mail;
			$elem++;
		}
		return $resp;
	}

	function delete_fornitore($id_delete) {
		$sql="UPDATE `fornitori` SET dele=1 WHERE id=$id_delete";
		$result=$this->conn->query($sql);
		return result;
	}

	function save() {
		$id_save=$_POST['id_save'];
		$fornitore=$_POST['fornitore'];
		$fornitore=strtoupper($fornitore);
		$telefono=$_POST['telefono'];
		$mail=$_POST['mail'];
		$fornitore=addslashes($fornitore);
		$telefono=addslashes($telefono);
		$mail=addslashes($mail);
		if (strlen($id_save)!=0)
			$sql="UPDATE `fornitori` SET fornitore='$fornitore', telefono='$telefono', mail='$mail' WHERE id=$id_save";
		else
			$sql="INSERT INTO `fornitori` (fornitore, telefono, mail) VALUES ('$fornitore', '$telefono', '$mail')";
		
		$result=$this->conn->query($sql);
		return $result;
		
	}
}
?>