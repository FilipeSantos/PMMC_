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

	class Categoria{
		public function __construct(){}
		
		public static function get_list_categ(){
			$conn = new DbConnect();
			$categ = array();
			
			$rs = mysql_query("SELECT id, titulo from categoria where 1 order by titulo;") or die();
			
			while($item = mysql_fetch_object($rs)){
				$categ[] = $item;
			}

			$conn->close();
			return $categ;
		}
		
		public static function get_categ($all = FALSE){
			$conn = new DbConnect();
			$categ = array();
			
			if($all === FALSE) {
				$rs = mysql_query("SELECT a.titulo, a.permalink, categ, count(distinct b.idNoticia) FROM categoria AS a
					INNER JOIN noticia_categoria AS b ON a.id = b.idCategoria group by a.id order by a.titulo;") or die();
			} else {
				$rs = mysql_query("SELECT id, titulo, permalink, categ from categoria where 1 order by titulo;") or die();
			}
			
			
			while($item = mysql_fetch_object($rs)){
				$categ[] = $item;
			}

			$conn->close();
			return $categ;
		}
	}
?>