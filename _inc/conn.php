<?php
	$break = explode('/', basename(__FILE__));
	$current = array_pop($break);
	$break = explode('/', $_SERVER['SCRIPT_NAME']);
	$parent = array_pop($break);
	
	if($parent == $current){
	    Header("HTTP/1.0 404 Not Found");
	    @include_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/404.php');
	    die();
	}
	
	class dbconnect{
		public $conn;
		public $db;		
		public function __construct(){
			$server = 'localhost';
			$user = 'root';
			$pass = '';
			$db = 'dev_jogosabertos_v2';
			//$server = '187.45.196.242';
			//$user = 'tboom14';
			//$pass = 'Tbjogos11';
			//$db = 'tboom14';
			$this->conn = @mysql_connect($server, $user, $pass) || die('Sem Conexao');
			$this->db = mysql_select_db($db) || die('Banco nao encontrado');
		}	
	}
?>