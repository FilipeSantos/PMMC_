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

	class Video{
		private $type;
		public $thumb;
		public $date;
		public $title;
		private $inicio;
		private $total;
		private $aprovado;
		
		public function __construct(){}

		public static function get_infos($ids = array()){
			if(is_array($ids) && !empty($ids)){
				$infos = array();
				
				foreach($ids as $i=>$id){
				        date_default_timezone_set('America/Sao_Paulo');
					if($id['tipo'] == 'youtube'){
						$url = 'http://gdata.youtube.com/feeds/api/videos/' . $id['id'];
					} elseif($id['tipo'] == 'vimeo'){
						$url = 'http://vimeo.com/api/v2/video/' . $id['id'] . '.xml';
					}
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_HEADER, FALSE);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$dados = curl_exec($ch);
					$xml = new SimpleXMLElement($dados);

					$infos[$i]['tipo'] = $id['tipo'];
					$infos[$i]['id'] = $id['id'];
					
					if($id['tipo'] == 'youtube'){
						$group = $xml->children('http://search.yahoo.com/mrss/')->group->children('http://search.yahoo.com/mrss/');
						$dataFormat = date_format(date_create($xml->published), 'H,i,s,m,d,Y');
						list($h, $mi, $s, $m, $d, $y) = explode(',', $dataFormat);
						
						$infos[$i]['titulo'] = addslashes(urlencode((string) $xml->title));
						$infos[$i]['data'] = date('d\/m\/Y H:i', gmmktime($h, $mi, $s, $m, $d, $y));
						$infos[$i]['descricao'] = addslashes(urlencode((string) $group->description));
						$infos[$i]['thumb1'] = (string) addslashes($group->thumbnail[1]->attributes()->url);
						$infos[$i]['thumb2'] = (string) addslashes($group->thumbnail[2]->attributes()->url);
						$infos[$i]['thumb3'] = (string) addslashes($group->thumbnail[3]->attributes()->url);
					} elseif($id['tipo'] == 'vimeo') {
					          $dataFormat = date_format(date_create(addslashes($xml->video->upload_date)), 'H,i,s,m,d,Y');
						list($h, $i, $s, $m, $d, $y) = explode(',', $dataFormat);
						
						$infos[$i]['titulo'] = (string) $xml->video->title;
						$infos[$i]['data'] = date('d\/m\/Y H:i', gmmktime($h, $m, $s, $m, $d, $y));
						$infos[$i]['thumb'] = $xml->video->thumbnail_medium;
					}
				}

				if(!empty($infos)){
					return $infos;
				}
				
				return false;
			}
			
			return false;
		}
	}
?>