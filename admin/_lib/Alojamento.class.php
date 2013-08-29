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

	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
	
	class Alojamento{
		public function __construct(){}
		
		public static function get_list_alojamentos(){
			$conn = new DbConnect();
			$rs = mysql_query("select id, nome from cidade where status = 1 order by nome;");
			if($rs && mysql_num_rows($rs)){
				$arr = array();
				while($item = mysql_fetch_object($rs)){
					$arr[] = $item;
				}
				return $arr;
			}
			return FALSE;
		}
		
		public static function get_alojamentos($idCidade = FALSE){
			$idCidade = $idCidade !== FALSE && is_numeric($idCidade) ? (integer) $idCidade : FALSE;
			if($idCidade !== FALSE){
				$cond = "inner join cidade_alojamento as b on a.id = b.idAlojamento and b.idCidade = $idCidade";
			} else {
				$cond = "inner join cidade_alojamento as b on a.id = b.idAlojamento";
			}
			$conn = new DbConnect();
			$rs = mysql_query("select distinct a.id, a.nome, a.endereco, a.info_tel, a.latitude, a.longitude from
					  alojamento as a $cond order by a.id;");
			if($rs && mysql_num_rows($rs)){
				$arr = array();
				while($item = mysql_fetch_object($rs)){
					$arr[] = $item;
				}
				$conn->close();
				return $arr;
			}
			return FALSE;
		}
	}
?>