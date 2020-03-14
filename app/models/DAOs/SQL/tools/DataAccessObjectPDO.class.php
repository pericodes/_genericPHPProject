<?php
	require_once 'app/models/tools/DataBaseFactory.class.php';

abstract class DataAccessObjectPDO {

	protected $tableName; 
	protected $voClass; 
	private $dataBase;
	
	public function getTableName():string
	{
		return $this->tableName;
	}

	function __construct($dataBaseName, string $tableName, string $voClass = "")
	{
		$this->dataBase = DataBaseFactory::getDataBase($dataBaseName);
		$this->tableName = $tableName; 
		$this->voClass = $voClass; 

	}
	protected function parseArrayToVO(array $array, string $voClass = ""):array
    {
		$voClass = $voClass ? $voClass :$this->voClass;
		if($voClass){
			return array_map(function ($array) use ($voClass){return new $voClass($array);}, $array);
		}else{
			throw new Exception("Not VO class found.");
			
		}
    }

	protected function query()
	{
		# code...
	}

	protected function getConnection()
	{
		return $this->dataBase->getConnection();
	}
	protected function prepare(string $sql): PDOStatement
	{
		return $this->getConnection()->prepare( $sql );
	}

	protected static function addWhereValuesFromArray(string $sql, array &$values) : string
	{	
		if(count($values)> 0){
			$keys = array_keys($values);
			$sql .= " WHERE :".$keys[0]." = ".$keys[0]."_value"; 
			for ($i=1; $i < count($keys); $i++) { 
				$sql .= " AND :".$keys[$i]." = ".$keys[$i]."_value"; 
			}
		}
		
		return $sql;
	}
	protected static function bindWhereValuesFromArray(string $sql, array $values, $st = null): PDOStatement 
	{
		$st =  $this->prepare($sql);
		foreach ($values as $key => $value) {
			$st->bindValue( ":".$key, $key, PDO::PARAM_STR );
			$st->bindValue( ":".$key."_value", $value, PDO::PARAM_STR );
		}
		return $st; 
	}
	public function deleteById(int $id):int
	{
		return $this->dataBase->getConnection()->exec("DELETE FROM $this->tableName WHERE id = $id");
	}


	/*
	public function query_fetch_all(string $sql, int $resulttype = MYSQLI_ASSOC) {

	}
	*/
	public function pagination(string $select = "*", $orderBy = FALSE, int $actualPage = 1, int $itemsPerPage = 10){

		$itemsPerPage = $itemsPerPage > 0 ? $itemsPerPage : 10;
		$totalItems = $this->dataBase->getConnection()->query("SELECT count(*) FROM $this->tableName")->fetchColumn();
		$totalPages = ceil($totalItems/$itemsPerPage);
		
		return ["actualPage" => $actualPage, 
				"totalPages" => $totalPages, 
				"itemsPerPage" => $itemsPerPage, 
				"items" => $this->getItems($select, $orderBy, $itemsPerPage, ($actualPage-1)*$itemsPerPage)];
	}

	public function getItems(string $select = "*", array $where = [], $orderBy = FALSE, int $rows = 0, int $starterRow = 0){
		$sql = "SELECT $select FROM $this->tableName";
		//try {
			if($order){
				$sql .= " ORDER BY :order";
			}
			if($rows){
				$sql .= " LIMIT :rows";
			}
			if ($starterRow) {
				$sql .= " OFFSET :starterRow";
			}

			$sql = addWhereValuesFromArray($sql, $where); 

			$st = $this->dataBase->getConnection()->prepare( $sql );
			//$st->bindValue( ":tableName", $this->tableName, PDO::PARAM_STR );
			if($order){
				$st->bindValue( ":order", $rows, PDO::PARAM_STR );
			}
			if($rows){
				$st->bindValue( ":rows", $rows, PDO::PARAM_INT );
			}
			if ($starterRow) {
				$st->bindValue( ":starterRow", $starterRow, PDO::PARAM_INT );
			}

			$st->execute();
			return $st->fetchAll();
	}

	public function findById(int $id, string $select = "*", int $fetch_style = PDO::FETCH_ASSOC)	
	{
		$sql = "SELECT $select FROM $this->tableName WHERE id = :id";
		$st = $this->prepare( $sql );
		$st->bindValue( ":id", $id, PDO::PARAM_INT );
		$st->execute();
		return $st->fetch($fetch_style);

	}

	/*public function getItems($order = FALSE,int $rows = FALSE,int $starterRow = FALSE)
	{	
		$sql = "SELECT * FROM $this->tableName";
		//try {
			if($order){
				$sql .= " ORDER BY :order";
				if($starterRow)
					$sql .= " LIMIT :starterRow, :rows";
				elseif ($rows) {
					$sql .= " LIMIT :rows";
				}
			}

			$st = $this->dataBase->getConnection()->prepare( $sql );
			//$st->bindValue( ":tableName", $this->tableName, PDO::PARAM_STR );
			if($order){
				$st->bindValue( ":order", $rows, PDO::PARAM_STR );
				if($starterRow){
					$st->bindValue( ":starterRow", $starterRow, PDO::PARAM_INT );
					$st->bindValue( ":rows", $rows, PDO::PARAM_INT );
				}elseif ($rows) {
					$st->bindValue( ":rows", $rows, PDO::PARAM_INT );
				}
			}
			$st->execute();
			return $st->fetchAll();
		//} catch ( PDOException $e ) {
		//	throw new Exception ( "PDO query fail: " . $e->getMessage() );
		//}
		

	}*/
}
?>