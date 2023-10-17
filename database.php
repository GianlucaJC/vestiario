<?php
class Database{
	//locale
	private $nomehost='localhost';
	private $db_name = "vestiario";
	private $nomeuser='root';
	private $password='giatongia6971';	
	

	//Liofilchem
	/*	
	private $nomehost='192.168.129.30';
	private $db_name="vestiario";
	private $nomeuser='sysadmin';
	private $password='Password01.';
	*/
	
	
	public $conn;
	
	public function getConnection() {
		$this->conn = null;
		try
			{
				$this->conn = new mysqli($this->nomehost,$this->nomeuser,$this->password,$this->db_name);
			}
		catch(mysqli $exception)
			{
			echo "Errore di connessione: " . $exception->getMessage();
			}
		return $this->conn;
		
	}
}

	
?>