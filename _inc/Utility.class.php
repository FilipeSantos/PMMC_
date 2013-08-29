<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
	class Utility{
		public function __construct(){}
		
		public function limit_string($str, $limit, $break = ' ', $after = '...'){
			if(strlen($str) <= $limit){
				return $str;
			}
			$str = substr($str, 0, $limit);
			if(($ret = strrpos($str, $break))){
				$str = substr($str, 0, $ret);
			}
			return $str . $after;
		}
		
		public function data_extenso($str){
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
			return $dia . ' de ' . $mes . ' de ' . $ano;
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

	}
?>