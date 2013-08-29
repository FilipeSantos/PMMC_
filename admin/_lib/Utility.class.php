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
	
	class Utility{
		public function __construct(){}
		
		public static function limit_string($str, $limit, $break = ' ', $after = '...'){
			if(strlen($str) <= $limit){
				return $str;
			}
			$str = substr($str, 0, $limit);
			if(($ret = strrpos($str, $break))){
				$str = substr($str, 0, $ret);
			}
			return $str . $after;
		}
		
		public static function removeEmptyItems($item){
			if (is_array($item)) {
				return array_filter($item, array('self','removeEmptyItems'));
			}
		       
			if ($item === 0 || $item === '0' || !empty($item)) {
				return true;
			}
		}
		
		public static function data_extenso($str, $y = TRUE){
			list($dia, $mes, $ano) = explode('/', $str);
			switch ($mes){
				case 1:
					$mes = "Janeiro";
					break;
				case 2:
					$mes = "Fevereiro";
					break;
				case 3:
					$mes = "Março";
					break;
				case 4:
					$mes = "Abril";
					break;
				case 5:
					$mes = "Maio";
					break;
				case 6:
					$mes = "Junho";
					break;
				case 7:
					$mes = "Julho";
					break;
				case 8:
					$mes = "Agosto";
					break;
				case 9:
					$mes = "Setembro";
					break;
				case 10:
					$mes = "Outubro";
					break;
				case 11:
					$mes = "Novembro";
					break;
				case 12:
					$mes = "Dezembro";
			}
			return $dia . ' de ' . $mes . ($y ? ' de ' . $ano : '');
		}
		
		public static function data_extenso_texto($cod){
			$txt = '';
			if(is_numeric($cod) && $cod > 0 && $cod < 8){
				$cod = (integer) $cod;
				
				switch($cod){
					case 1:
						$txt = 'segunda-feira';
						break;
					case 2:
						$txt = 'terça-feira';
						break;
					case 3:
						$txt = 'quarta-feira';
						break;
					case 4:
						$txt = 'quinta-feira';
						break;
					case 5:
						$txt = 'sexta-feira';
						break;
					case 6:
						$txt = 'sábado';
						break;
					case 7:
						$txt = 'domingo';
				}
			}
			
			return !empty($txt) ? $txt : FALSE;
		}
		
		public function Download($path, $speed = null){
			if (is_file($path) === true){
			     echo 'Bla';
			    $file = fopen($path, 'rb');
			    $speed = (isset($speed) === true) ? round($speed * 1024) : 524288;
				echo 'Bla';
			    if (is_resource($file) === true)
			    {
				set_time_limit(0);
				ignore_user_abort(false);
		    
				while (ob_get_level() > 0)
				{
				    ob_end_clean();
				}
		    
				header('Expires: 0');
				header('Pragma: public');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Content-Type: application/octet-stream');
				header('Content-Length: ' . sprintf('%u', filesize($path)));
				header('Content-Disposition: attachment; filename="' . basename($path) . '"');
				header('Content-Transfer-Encoding: binary');
		    
				while (feof($file) !== true)
				{
				    echo fread($file, $speed);
		    
				    while (ob_get_level() > 0)
				    {
					ob_end_flush();
				    }
		    
				    flush();
				    sleep(1);
				}
		    
				fclose($file);
			    }
		    
			    exit();
			}
		    
			return false;
		}
		
		public static function in_array_recursive($needle, $haystack) {
			$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($haystack));
			foreach($it as $element) {
				if($element == $needle) {
					return TRUE;
				}
			}
			return FALSE;
		}
		
		public static function object2Array($d){
			if (is_object($d)){
			    $d = get_object_vars($d);
			}
			
			if (is_array($d)){
			    return array_map(array('self', 'object2Array'), $d);
			} else {
			    return $d;
			}
		}
		
		public static function array2Object($d){
			if (is_array($d)){
			    return (object) array_map(array('self','array2Object'), $d);
			} else {
			    return $d;
			}
		}


	}
?>