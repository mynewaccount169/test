<?php
define("PATH", "http://testisite.zzz.com.ua/");
class Db {

	protected $db;
	function __construct(){

$config = require 'config.php';

$this->db = new PDO('mysql:host='.$config['host'].';dbname='.$config['name'].';charset=utf8;',$config['user'],$config['password']);

	}
	public function query($sql, $params = []){// запрос в БД(защищенный от инъекций)
$stmt = $this->db->prepare($sql);
if(!empty($params)){

	foreach($params as $key => $val){
		if(is_int($val)){

			$type = PDO::PARAM_INT;
		}else{

			$type = PDO::PARAM_STR;
		}

		$stmt->bindValue(':'.$key,$val,$type);

	}
}
		$stmt->execute();
		return $stmt;
	}

	public function row($sql, $params = []){
			$result = $this->query($sql,$params);
			return $result->fetchAll(PDO::FETCH_ASSOC);

		}
		public function column($sql, $params = []){
			$result = $this->query($sql,$params);
			return $result->fetchColumn();

		}
		public function lastInsertId() {
		return $this->db->lastInsertId();
	}


}
?>
