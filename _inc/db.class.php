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
    
	class DbConnect{
		public $conn;
		private $db;
		public function __construct(){
			if(strripos($_SERVER['HTTP_HOST'], 'jogosabertos.dev') !== FALSE){
				$server = 'localhost';
				$user = 'root';
				$pass = '';
				$db = 'dev_jogosabertos_v2';
			} elseif(strripos($_SERVER['HTTP_HOST'], 'jogosabertos2011.tboom.net') !== FALSE){
				$server = '186.202.13.16';
				$user = 'tboom18';
				$pass = 'Ja1102';
				$db = 'tboom18';
			} else {
				$server = '187.45.196.242';
				$user = 'tboom14';
				$pass = 'Tbjogos11';
				$db = 'tboom14';
			}
			$this->conn = @mysql_connect($server, $user, $pass);
			$this->db = mysql_select_db($db);
			$this->charset('utf8');
			$this->timezone('America/Sao_Paulo');
		}
		public function charset($str){
			mysql_set_charset($str, $this->conn);
		}
		public function timezone($str){
			date_default_timezone_set($str);
		}
		public function close(){
			$close = mysql_close($this->conn);
			$close = NULL;
		}
	}
?>