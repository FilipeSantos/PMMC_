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
        
        class Programacao{
			private $conn;
			private $id;
			private $data;
			private $descricao;
			private $divisao;
			private $modalidade;
			private $local;
			private $prova;
			private $sexo;
			private $categoria;
			private $cidade;
			private $resultadoTipo;
			private $resultadoLinkPdf;
			private $status;
			private $totalCidades;
			private $dataCadastro;
			private $resultadoCidade;
			private $progObj;
			private $itemObj;
  
			public function __construct(){
				$this->conn = new DbConnect();
				$this->id = FALSE;
				$this->cidade = $this->prova = $this->sexo = $this->resultadoCidade = array();
				$this->progObj = NULL;
				$this->totalCidades = '0';
			}
			
			public function set_id($id = 0){
				$this->id = ($id !== 0 && is_numeric($id)) ? (integer) $id : FALSE;
			}
			
			
			public function set_data($dataPub = NULL){
				list($data, $hora) = explode(' ', $dataPub);
				list($dia, $mes, $ano) = explode('/', $data);
				list($hora, $min) = explode(':', $hora);
				if(checkdate($mes, $dia, $ano)){
					$this->data = "'$ano-$mes-$dia $hora:$min:00'";
				} else {
					$this->data = 'NULL';
				}
			}
			
			public function set_descricao($descricao = FALSE){
				$this->descricao = $descricao !== FALSE && !empty($descricao) && trim($descricao) !== '.' ? "'" . mysql_real_escape_string(substr($descricao, 0, 30)) . "'" : 'NULL';
			}
			
			public function set_divisao($divisao = 0){
				if($divisao !== 0 && is_array($divisao)){
					foreach($divisao as $i=>$item){
						if(is_numeric($item) && $item > 0 && $item <= 4){
							$divisao[$i] = (integer) $item;
						} else {
							unset($divisao[$i]);
						}
					}
					$this->divisao = !empty($divisao) ? $divisao : FALSE;
				} else {
					$this->divisao = FALSE;
				}
			}
			
			public function set_modalidade($modalidade = 0){
				if($modalidade !== 0 && is_numeric($modalidade)){
					$mod = new Modalidade();
					$this->modalidade = $mod->verificar_modalidade_valida($modalidade) ? (integer) $modalidade : 'NULL';
					unset($mod);
				} else {
					$this->modalidade = 'NULL';
				}
			}
			
			public function set_local($local = 0, $modalide = TRUE){
				if($local !== 0 && is_numeric($local)){
					$loc = new LocalCompeticao();
					$this->local = $loc->verificar_local_valido($local, (isset($this->modalidade) ? $this->modalidade : 0)) ? (integer) $local : 'NULL';
					unset($loc);
				} else {
					$this->local = 'NULL';
				}
			}
			
			public function set_prova($prova = array()){
				if(is_array($prova) && !empty($prova)){
					$prov = new Prova();
					foreach($prova as $i=>$item){
						if($prov->verificar_prova_valida($item, (isset($this->modalidade) ? $this->modalidade : 0))){
							$prova[$i] = (integer) $item;
						} else {
							unset($prova[$i]);
						}
					}
					$this->prova = !empty($prova) ? $prova : FALSE;
				} else {
					$this->prova = FALSE;
				}
			}
			
			public function set_sexo($sexo = 0){
				if($sexo !== 0 && is_array($sexo)){
					foreach($sexo as $i=>$item){
						if(is_numeric($item) && $item > 0 && $item <= 4){
							$sexo[$i] = (integer) $item;
						} else {
							unset($sexo[$i]);
						}
					}
					$this->sexo = !empty($sexo) ? $sexo : FALSE;
				} else {
					$this->sexo = FALSE;
				}
			}
			
			public function set_categoria($categoria = FALSE){
				if($categoria !== FALSE && is_array($categoria)){
					foreach($categoria as $i=>$item){
						if(is_numeric($item)){
							$categoria[$i] = (integer) $item;
						} else {
							unset($categoria[$i]);
						}
					}
					$this->categoria = !empty($categoria) ? $categoria : FALSE;
				} else {
					$this->categoria = FALSE;
				}
			}
			
			public function set_cidade($cidade = 0){
				if(is_array($cidade) && !empty($cidade)){
					foreach($cidade as $i=>$item){
						if(Cidade::verificar_cidade_valida($item)){
							$cidade[$i] = (integer) $item;
						} else {
							unset($cidade[$i]);
						}
					}
					$this->cidade = !empty($cidade) ? $cidade : FALSE;
					$this->set_total_cidades();
				} else {
					$this->cidade = FALSE;
				}
			}
			
			public function set_resultado_tipo($resultado = FALSE){
				$this->resultadoTipo = $resultado !== FALSE && is_numeric($resultado) ? (integer) $resultado : 0;
			}
			
			public function set_resultado_link_pdf($link = FALSE){
				$this->resultadoLinkPdf = $link !== FALSE && !empty($link) ? "'" . mysql_real_escape_string($link) . "'" : 'NULL';
			}
			
			public function set_status($status = 1){
				$this->status = is_numeric($status) && ((integer) $status === 0 || (integer) $status === 1) ? (integer) $status : 0;
			}
			
			protected function set_total_cidades(){
				if(isset($this->cidade) && is_array($this->cidade)){
					$this->totalCidades = count($this->cidade);
				} else {
					$this->totalCidades = '0';
				}
			}
			
			protected function set_data_cadastro(){
				$this->dataCadastro = "'" . date('Y-m-d H:i:s') . "'";
			}
			
			public function set_resultado_cidade($resultadoCidade = array()){
				if(is_array($resultadoCidade) && !empty($resultadoCidade)){
					foreach($resultadoCidade as $i=>$item){
						$item['resultado'] = isset($item['resultado']) && !empty($item['resultado']) && (strtolower($item['resultado']) == 'v' || strtolower($item['resultado']) == 'e' || strtolower($item['resultado']) == 'd' || strtolower($item['resultado']) == 'w') ? "'" . mysql_real_escape_string(strtolower($item['resultado'])) . "'" : 'NULL';
						$item['total'] = isset($item['total']) && !empty($item['total']) && is_numeric($item['total']) ? (float) mysql_real_escape_string($item['total']) : 'NULL';
						$item['atleta'] = isset($item['atleta']) && !empty($item['atleta']) ? "'" . mysql_real_escape_string($item['atleta']) . "'" : 'NULL';
						$resultadoCidade[$i] = $item;
					}
					$this->resultadoCidade = $resultadoCidade;
				} else {
					$this->resultadoCidade = FALSE;
				}
			}
			
			public function set_resultado_programacao($post = FALSE, $tipo = FALSE){
				
				if($post === FALSE || !is_array($post) || $tipo === FALSE || !is_numeric($tipo)){
					return FALSE;
				}
				$tipo = (integer) $tipo;
				$cidade = array();
				$itemProg = new stdClass();

				$itemProg->resultado_tipo = !empty($post['tipoResultado']) ? "'" . (integer) $post['tipoResultado'] . "'" : '0';
				
				if(isset($post['excluirArquivo'])){
					if(is_null($this->progObj)){
						$this->progObj = $this->get_programacao($this->id);
					}
					$linkPdf = $this->progObj[0]->resultado_link_pdf;
					if($linkPdf !== NULL && file_exists($_SERVER['DOCUMENT_ROOT'] . '/upload/programacao_resultado/' . $linkPdf)){
						$rs = mysql_query("update programacao_item set resultado_link_pdf = NULL where id = $this->id limit 1;");
						if($rs){
							@unlink($_SERVER['DOCUMENT_ROOT'] . '/upload/programacao_resultado/' . $linkPdf);
						}
					}
				}
			  
				if($tipo === 1){
					$post['result'] = count(Utility::removeEmptyItems($post['result'])) === 0 ? FALSE : $post['result'];

					$itemProg->resultado_link_pdf = 'NULL';
					
					if(isset($post['result']) && is_array($post['result']) && !empty($post['result'])){
					  
						$itemProg->resultado_layout_tipo = "'1'";
						$count = 0;
						foreach($post['result'] as $key=>$result){
							$cidade[$count]->id = (integer) $key;
							$cidade[$count]->resultado_total = $result !== NULL ? (float) $result : 'NULL';
							
							$txt = isset($post['nomeAtleta'][$key]) && !empty($post['nomeAtleta'][$key])
								? mysql_real_escape_string($post['nomeAtleta'][$key])
								: FALSE;
								
							if($txt !== FALSE){
								$cidade[$count]->nome_atleta = array();
								array_push($cidade[$count]->nome_atleta, $txt);
							}
							
							$cidade[$count++]->resultado_colocacao = FALSE;
						}
					  
						if($cidade[0]->resultado_total > $cidade[1]->resultado_total){
							$cidade[0]->resultado = "'v'";
							$cidade[1]->resultado = isset($post['wo']) && !empty($post['wo']) ? "'w'" : "'d'";
						} elseif($cidade[1]->resultado_total > $cidade[0]->resultado_total){
							$cidade[1]->resultado = "'v'";
							$cidade[0]->resultado = isset($post['wo']) && !empty($post['wo']) ? "'w'" : "'d'";
						} else {
							$cidade[0]->resultado = "'e'";
							$cidade[1]->resultado = "'e'";
						}
					  
						$this->progObj = $cidade;
						$this->itemObj = $itemProg;
						return TRUE;
					
					} elseif(isset($post['cidadeVenc'])){
	  
						$id = (integer) $post['cidadeVenc'];
						$itemProg->resultado_layout_tipo = "'1'";
						
						if(is_null($this->progObj)){
							$this->progObj = $this->get_programacao($this->id);
						}
						$cidObj = $this->progObj[0]->cidades;
						$count = 0;
					  
						foreach($cidObj as $cid){
							$cidade[$count]->id = $cid->id;
							$cidade[$count]->resultado_total = 'NULL';
							
							$txt = isset($post['nomeAtleta'][$cid->id]) && !empty($post['nomeAtleta'][$cid->id])
								? mysql_real_escape_string($post['nomeAtleta'][$cid->id])
								: FALSE;
								
							if($txt !== FALSE){
								$cidade[$count]->nome_atleta = array();
								array_push($cidade[$count]->nome_atleta, $txt);
							}
							
							$cidade[$count]->resultado_colocacao = FALSE;
							if($id === 0){
								$cidade[$count]->resultado = "'e'";
							} elseif($id === (integer) $cid->id){
								$cidade[$count]->resultado = "'v'";
							} else {
								$cidade[$count]->resultado = isset($post['wo']) && !empty($post['wo']) ? "'w'" : "'d'";
							}
							$count++;
						}
						
						$this->progObj = $cidade;
						$this->itemObj = $itemProg;
						return TRUE;
					  
					} elseif(isset($post['arquivo']) && !empty($post['arquivo'])){
						$arquivo = mysql_real_escape_string($post['arquivo']);
						$itemProg->resultado_link_pdf = "'$arquivo'";
						$itemProg->resultado_layout_tipo = "'3'";
						
						if(isset($post['upload'])){
							$img = new Upload();
							$img->set_dir_temp('/upload/temp');
							$img->set_dir_upload('/upload/programacao_resultado');
							if(!$img->salvar('upload', str_replace('\'', '', $arquivo))){
								return FALSE;
							}
						}
					  
						if(is_null($this->progObj)){
							$this->progObj = $this->get_programacao($this->id);
						}
						$cidObj = $this->progObj[0]->cidades;
						$count = 0;
					  
						foreach($cidObj as $cid){
							$cidade[$count]->id = $cid->id;
							$cidade[$count]->resultado_total = 'NULL';
							$cidade[$count]->nome_atleta = FALSE;
							$cidade[$count]->resultado_colocacao = FALSE;
							$cidade[$count]->resultado = 'NULL';
						}
						
						$this->progObj = $cidade;
						$this->itemObj = $itemProg;
						return TRUE;
					  
					} else {
						return FALSE;
					}
	  
				} elseif($tipo === 2){
					$verificaArr = is_array($post['colocacao']) && is_array($post['cidade']) && is_array($post['atleta']);
					$verificaArr = $verificaArr && (count($post['colocacao']) === 3) && (count($post['cidade']) === 3) && (count($post['atleta']) === 3);
					$arrCidades = Utility::removeEmptyItems($post['cidade']);
					if($verificaArr && count($arrCidades) === 3){
						$itemProg->resultado_link_pdf = 'NULL';
						$itemProg->resultado_layout_tipo = "'2'";
						$colocacao = $post['colocacao'];
						$atleta = $post['atleta'];
						$count = 0;
						$idsPrev = array();
						
						foreach($arrCidades as $key=>$cid){

							if(in_array($cid, $idsPrev)){
								$index = array_pop(array_keys($idsPrev, $cid));
								array_pop($idsPrev);

								$pos = !empty($colocacao[$key]) && is_numeric($colocacao[$key]) && $colocacao[$key] >= 1 && $colocacao[$key] <= 3
									? mysql_real_escape_string($colocacao[$key]) : FALSE;
							  
								if($pos !== FALSE){
									array_push($cidade[$index]->resultado_colocacao, $pos);
								}
								
								$atl = !empty($atleta[$key]) ? mysql_real_escape_string($atleta[$key]) : FALSE;
								if($atl !== FALSE){
									array_push($cidade[$index]->nome_atleta, $atl);
								}
					  
							} else {
								$cidade[$count]->id = $cid;
								$cidade[$count]->resultado_total = 'NULL';
								$cidade[$count]->resultado = 'NULL';
								
								$txt = !empty($atleta[$key]) ? mysql_real_escape_string($atleta[$key]) : FALSE;
								if($txt !== FALSE){
									$cidade[$count]->nome_atleta = array();
									array_push($cidade[$count]->nome_atleta, $txt);
								}
								
								$txt = !empty($colocacao[$key]) && is_numeric($colocacao[$key])
									&& $colocacao[$key] >= 1 && $colocacao[$key] <= 3
									? mysql_real_escape_string($colocacao[$key]) : FALSE;
								if($txt !== FALSE){
									$cidade[$count]->resultado_colocacao = array();
									array_push($cidade[$count]->resultado_colocacao, $txt);
								}
								
								$count++;
							}
							$idsPrev[] = $cid;
						}

						$this->progObj = $cidade;
						$this->itemObj = $itemProg;
						return TRUE;
					
					} elseif(isset($post['arquivo']) && !empty($post['arquivo'])){
						$arquivo = mysql_real_escape_string($post['arquivo']);
						$itemProg->resultado_link_pdf = "'$arquivo'";
						$itemProg->resultado_layout_tipo = "'3'";
						
						if(isset($post['upload'])){
							$img = new Upload();
							$img->set_dir_temp('/upload/temp');
							$img->set_dir_upload('/upload/programacao_resultado');
							if(!$img->salvar('upload', str_replace('\'', '', $arquivo))){
								return FALSE;
							}
						}
						  
						if(is_null($this->progObj)){
							$this->progObj = $this->get_programacao($this->id);
						}
						$cidObj = $this->progObj[0]->cidades;
						$count = 0;
						  
						foreach($cidObj as $cid){
							$cidade[$count]->id = $cid->id;
							$cidade[$count]->resultado_total = 'NULL';
							$cidade[$count]->nome_atleta = FALSE;
							$cidade[$count]->resultado_colocacao = FALSE;
							$cidade[$count]->resultado = 'NULL';
						}
						  
						$this->progObj = $cidade;
						$this->itemObj = $itemProg;
						return TRUE;
					  
					} else {
						return FALSE;
					}

				} elseif($tipo === 3){
					
					$arquivo = !empty($post['arquivo']) ? mysql_real_escape_string($post['arquivo']) : 'NULL';
					$itemProg->resultado_link_pdf = "'$arquivo'";
					$itemProg->resultado_layout_tipo = "'3'";
				
					if(isset($post['upload'])){
						$img = new Upload();
						$img->set_dir_temp('/upload/temp');
						$img->set_dir_upload('/upload/programacao_resultado');
						if($img->salvar('upload', $arquivo) === FALSE){
							return FALSE;
						}
					}
				
					if(is_null($this->progObj)){
						$this->progObj = $this->get_programacao($this->id);
					}
					
					if($this->progObj[0]->cidades !== FALSE){
						$cidObj = $this->progObj[0]->cidades;
						$count = 0;
						$this->progObj = false;
					
						if(is_array($cidObj) && !empty($cidObj)){
							foreach($cidObj as $cid){
								$cidade[$count]->id = $cid->id;
								$cidade[$count]->resultado_total = 'NULL';
								$cidade[$count]->nome_atleta = 'NULL';
								$cidade[$count]->resultado_colocacao = 'NULL';
								$cidade[$count++]->resultado = 'NULL';
							}					
							$this->progObj = $cidade;
						}
					}
					
					$this->itemObj = $itemProg;
					return TRUE;
			  
				} else {
					return FALSE;
				}
			}
			
			private function set_array_salvar($arr, $aspas = TRUE){
				if(isset($arr) && is_array($arr)){
					$glue = ($aspas === TRUE) ? "', '" : ",";
					return "'" . implode($glue, $arr) . "'";
				} else {
					return FALSE;
				}
			}
			
			private function validar(){
				if($this->data !== FALSE && $this->modalidade != FALSE && $this->divisao !== FALSE
				   && $this->sexo !== FALSE && $this->categoria !== FALSE
				) {
					return TRUE;
				} else {
					return FALSE;
				}
			}
			
			public function salvar_resultado_programacao(){

				if($this->id !== FALSE && $this->id !== 0){
					$error = FALSE;
	  
					if(!empty($this->progObj) && is_array($this->progObj) && !empty($this->itemObj) && is_object($this->itemObj)){
						mysql_query("set autocommit = 0;");
						mysql_query("start transaction;");
	  
						$rs = mysql_query("update programacao_item set resultado_tipo = {$this->itemObj->resultado_tipo},
									resultado_link_pdf = {$this->itemObj->resultado_link_pdf},
									resultado_layout_tipo = {$this->itemObj->resultado_layout_tipo},
									data_atualizacao = CURRENT_TIMESTAMP
									where id = $this->id limit 1;");
						$error = $error && (boolean) mysql_error();
	  
						$rs = mysql_query("update programacao_cidade set nome_atleta = NULL, resultado = NULL,
									resultado_colocacao = NULL, resultado_total = NULL
									where id_programacao_item = $this->id;");
						$error = $error && (boolean) mysql_error();

						if(!isset($this->progObj[0]->cidades) || (isset($this->progObj[0]->cidades) && $this->progObj[0]->cidades !== FALSE)){
							foreach($this->progObj as $cidObj){		
								if(isset($cidObj->nome_atleta) && is_array($cidObj->nome_atleta) && !empty($cidObj->nome_atleta)){
									$cidObj->nome_atleta = "'" . mysql_real_escape_string(serialize($cidObj->nome_atleta)) . "'";
								} else {
									$cidObj->nome_atleta = 'NULL';
								}
	
								if(isset($cidObj->resultado_colocacao) && is_array($cidObj->resultado_colocacao) && !empty($cidObj->resultado_colocacao)){
									$cidObj->resultado_colocacao = "'" . mysql_real_escape_string(serialize($cidObj->resultado_colocacao)) . "'";
								} else {
									$cidObj->resultado_colocacao = 'NULL';
								}
			
								$rs = mysql_query("update programacao_cidade set nome_atleta = $cidObj->nome_atleta,
									resultado = $cidObj->resultado, resultado_colocacao = $cidObj->resultado_colocacao,
									resultado_total = $cidObj->resultado_total
									where id_cidade = $cidObj->id and id_programacao_item = $this->id;");
								$error = $error && (boolean) mysql_error();
							}	
						}
						
						if($error === FALSE){
							mysql_query("commit;");
							return TRUE;
						} else {
							mysql_query("rollback;");
							return FALSE;
						}
					}
				}
				return FALSE;
			}
			
			public function salvar_programacao(){
				if($this->validar()){
					$this->conn = new DbConnect();
					$this->set_data_cadastro();
					
					mysql_query("set autocommit = 0;");
					mysql_query("start transaction;");
					
					$this->divisao = $this->set_array_salvar($this->divisao, FALSE);
					$this->sexo = $this->set_array_salvar($this->sexo, FALSE);
					$this->categoria = $this->set_array_salvar($this->categoria, FALSE);
					
					if($this->id === FALSE || $this->id === 0){
						mysql_query("insert into programacao_item(id_modalidade, id_local, descricao, data_hora, divisao, sexo, categoria, resultado_tipo, total_cidades, data_cadastro, status)
							values($this->modalidade, $this->local, $this->descricao, $this->data, $this->divisao, $this->sexo, $this->categoria, '0', $this->totalCidades, $this->dataCadastro, $this->status);");
						$this->id = mysql_insert_id();
					} else {
						mysql_query("update programacao_item set id_modalidade = $this->modalidade, id_local = $this->local, descricao = $this->descricao,
							data_hora = $this->data, divisao = $this->divisao, sexo = $this->sexo, categoria = $this->categoria,
							total_cidades  = $this->totalCidades, resultado_tipo = 0, resultado_layout_tipo = NULL, resultado_link_pdf = NULL,
							data_atualizacao = CURRENT_TIMESTAMP, status = $this->status where id = $this->id limit 1;");
		
						mysql_query("delete from programacao_prova_item where id_programacao_item = $this->id;");
						mysql_query("delete from programacao_cidade where id_programacao_item = $this->id;");

					}
					
					if($this->prova !== FALSE && is_array($this->prova)){
						foreach($this->prova as $prova){
							mysql_query("insert into programacao_prova_item(id_programacao_prova, id_programacao_item) values($prova, $this->id);");
						}
					}
				
					if($this->cidade !== FALSE && is_array($this->cidade)){
					  foreach($this->cidade as $cidade){
							  mysql_query("insert into programacao_cidade(id_cidade, id_programacao_item) values($cidade, $this->id);");
					  }
					}

					if(mysql_error()){
						mysql_query("rollback;");
						return FALSE;
					} else {
						mysql_query("commit;");
						return TRUE;
					}
				}
				return FALSE;
			}
			
			public function get_data_atualizacao(){
				$rs = mysql_query("select unix_timestamp(max(data_atualizacao)) as data from programacao_item where status = 1;");
				if($rs && mysql_num_rows($rs)){
					return mysql_result($rs, 0, 'data');
				}
			}
			
			public function get_datas_programacao($ativo = TRUE){
				$arr = array();
				$cond = ($ativo === TRUE) ? 'status = 1' : '1';
				$rs = mysql_query("select distinct unix_timestamp(date(data_hora)) as data from programacao_item where $cond order by data_hora desc;");
				if($rs && mysql_num_rows($rs)){
					while($item = mysql_fetch_object($rs)){
						$arr[] = $item;
					}
				}
				return (!empty($arr)) ? $arr : FALSE;
			}
			
			public function get_divisoes_programacao($ativo = TRUE){
				$arr = array();
				$cond = ($ativo === TRUE) ? 'status = 1' : '1';
				$rs = mysql_query("select distinct divisao from programacao_item where $cond order by divisao;");
				if($rs && mysql_num_rows($rs)){
					while($item = mysql_fetch_object($rs)){
						$arr[] = $item;
					}
				}

				if(!empty($arr)){
					foreach($arr as $key=>$item){
						$item = explode(',', $item->divisao);
						for($i = 0; $i < ($total = count($item)); $i++){
							if($i === 0){
								if(Utility::in_array_recursive($item[$i], $arr) === FALSE){
									$arr[$key]->divisao = $item[$i];
								} elseif(count($item) > 1) {
									unset($arr[$key]);
								}
								continue;
							}
							$itemArr = new stdClass();
							$itemArr->divisao = $item[$i];
							if(Utility::in_array_recursive($itemArr->divisao, $arr) === FALSE){
								array_push($arr, $itemArr);
							}
						}
					}
				}

				return (!empty($arr)) ? $arr : FALSE;
			}
			
			public function get_provas_item_programacao($id = 0){
				if($id === 0 || !is_numeric($id)){
					return FALSE;
				}
				
				$id = (integer) $id;
				$arr = array();
				$rs = mysql_query("select distinct a.id, a.titulo, a.divisao, a.sexo, a.categoria_idade from programacao_prova as a
								  inner join programacao_prova_item as b on a.id = b.id_programacao_prova
								  inner join programacao_item as c on c.id = b.id_programacao_item and c.id = {$id};");
				if($rs && mysql_num_rows($rs)){
					while($item = mysql_fetch_object($rs)){
						$arr[] = $item;
					}
				}
				return (!empty($arr)) ? $arr : FALSE;
			}
			
			public function get_modalidades_programacao($ativo = TRUE){
				$arr = array();
				$cond = ($ativo === TRUE) ? 'a.status = 1' : 'a.status in (0,1)';
				$rs = mysql_query("select distinct a.id, a.titulo, a.slug from modalidade as a inner join programacao_item as b
					on a.id = b.id_modalidade and $cond order by a.titulo;");
				if($rs && mysql_num_rows($rs)){
					while($item = mysql_fetch_object($rs)){
						$arr[] = $item;
					}
				}
				return (!empty($arr)) ? $arr : FALSE;
			}
			
			public function get_cidades_programacao($modalidade = FALSE, $ativo = TRUE){
				$cond = 'and ';
				if($modalidade !== FALSE && is_numeric($modalidade)){
					$modalidade = (integer) $modalidade;
					$cond = "inner join programacao_item as c on b.id_programacao_item = c.id and c.id_modalidade = $modalidade and ";
				}
				
				$arr = array();
				$cond = $cond . (($ativo === TRUE) ? 'a.status = 1' : 'a.status in (0,1)');
				$rs = mysql_query("select distinct a.id, a.nome, a.slug from cidade as a inner join programacao_cidade as b
					on a.id = b.id_cidade $cond order by a.nome;");
	
				if($rs && mysql_num_rows($rs)){
					while($item = mysql_fetch_object($rs)){
						$arr[] = $item;
					}
				}
				return (!empty($arr)) ? $arr : FALSE;
			}
			
			public function get_locais_programacao($modalidade = FALSE, $ativo = TRUE){
				$cond = 'and ';
				if($modalidade !== FALSE && is_numeric($modalidade)){
					$modalidade = (integer) $modalidade;
					$cond = "inner join local_competicao_modalidade as c on a.id = c.id_local and c.id_modalidade = $modalidade and ";
				}
				
				$arr = array();
				$cond = $cond . (($ativo === TRUE) ? 'a.status = 1' : 'a.status in (0,1)');
				$rs = mysql_query("select distinct a.id, a.nome from local_competicao as a inner join programacao_item as b
					on a.id = b.id_local $cond order by a.nome;");
				if($rs && mysql_num_rows($rs)){
					while($item = mysql_fetch_object($rs)){
						$arr[] = $item;
					}
				}
				return (!empty($arr)) ? $arr : FALSE;
			}
			
			public function get_max_data_item($mod = 0, $cid = 0){
				$cond = '';
				$innerCond = '';
				$result = '';

				if(is_numeric($mod) && $mod != 0){
					$innerCond = 'where';
					$cond .= "a.id_modalidade = {$mod} and";
				}
				if(is_numeric($cid) && $cid != 0){
					$innerCond = 'inner join programacao_cidade as b on';
					$cond .= "a.id = b.id_programacao_item and b.id_cidade = {$cid} and";
				}
				
				$cond = substr($cond, 0, -4);
				
				$rs = mysql_query("select unix_timestamp(a.data_hora) as data from programacao_item as a {$innerCond}
								  {$cond} and date(a.data_hora) >= date(now()) order by a.data_hora limit 1;");

				if($rs && mysql_num_rows($rs)){
					$result = mysql_result($rs, 0, 'data');
				}

				return !empty($result) ? $result : time();
			}
			
			public function get_programacao($id = FALSE, $div = FALSE, $cidade = FALSE, $modalidade = FALSE, $local = FALSE, $data = FALSE, $tsInit = 0, $limit = 0){
				$innerCidade = '';
				$arr = array();
				$query = '';
				$lim = '';
				
				if($id !== FALSE && is_numeric($id)){
					$id = (integer) $id;
					$query .= "a.id = $id and ";
				}

				if($data !== FALSE){
					list($dia, $mes, $ano) = explode('-', $data);
					if(checkdate($mes, $dia, $ano)){
						$data = "'$ano-$mes-$dia'";
					} else {
						$data = "'" . date('Y-m-d') . "'";
					}
					$query .= "date(a.data_hora) = $data and ";
				} elseif(is_numeric($tsInit) && $tsInit != 0){
					$query .= "unix_timestamp(a.data_hora) > $tsInit and ";
				}
			  
				if($div !== FALSE && is_numeric($div) && $div > 0 && $div <= 4){
					$div = (integer) $div;
					$query .= "a.divisao like '%$div%' and ";
				}
			  
				if($cidade !== FALSE && is_numeric($cidade)){
					$cidade = (integer) $cidade;
					$innerCidade = "inner join programacao_cidade as c on c.id_programacao_item = a.id and c.id_cidade = $cidade";
				}
			  
				if($modalidade !== FALSE && is_numeric($modalidade)){
					$modalidade = (integer) $modalidade;
					$query .= "a.id_modalidade = $modalidade and ";
				}
				
				if($local !== FALSE && is_numeric($local)){
					$local = (integer) $local;
					$query .= "a.id_local = $local and ";
				}
				
				if(is_numeric($limit) && $limit != 0){
					$lim = "limit {$limit}";
				}
				
				if(!empty($query)){
					$query = substr($query, 0, -4);
				}
 
				$rs = mysql_query("select distinct a.id, a.id_modalidade, a.id_local, a.descricao, UNIX_TIMESTAMP(a.data_hora) as data, a.divisao, a.sexo, a.categoria, a.resultado_tipo, a.resultado_layout_tipo,
					a.resultado_link_pdf, a.total_cidades, a.data_atualizacao, a.status, b.titulo as modalidade, b.slug as slug_modalidade, d.nome as local from programacao_item as a
					inner join modalidade as b
					on b.id = a.id_modalidade $innerCidade and " . (!empty($query) ? "$query and" : "") . " a.status = 1
					inner join local_competicao as d on a.id_local = d.id order by a.data_hora {$lim};");

				if($rs && mysql_num_rows($rs)){
					$cont = 0;
					while($item = mysql_fetch_object($rs)){
						$arrProvas = $arrCidades = array();
						$rsProvas = mysql_query("select a.id, a.titulo, a.id_modalidade, a.divisao, a.sexo, a.categoria_idade
							from programacao_prova as a inner join programacao_prova_item as b
							on a.id = b.id_programacao_prova and b.id_programacao_item = $item->id;");
						if($rsProvas && mysql_num_rows($rsProvas)){
							while($itemProvas = mysql_fetch_object($rsProvas)){
								$arrProvas[] = $itemProvas;
							}
						}
					  
						$rsCidades = mysql_query("select a.id, a.nome, a.bandeira, a.divisao, a.slug, b.nome_atleta, b.resultado, b.resultado_total,
							b.resultado_colocacao from cidade as a inner join programacao_cidade as b
							on a.id = b.id_cidade and b.id_programacao_item = $item->id order by a.nome;");
						
						if($rsCidades && mysql_num_rows($rsCidades)){
							while($itemCidades = mysql_fetch_object($rsCidades)){
								$arrTemp = &$arrCidades[];
								$arrTemp = $itemCidades;
		
								if(!empty($arrTemp->nome_atleta)){
									$arrTemp->nome_atleta = unserialize($arrTemp->nome_atleta);
								}
								
								if(!empty($arrTemp->resultado_colocacao)){
									$arrTemp->resultado_colocacao = unserialize($arrTemp->resultado_colocacao);
								}
							}
						}
						
						$arr[$cont] = $item;
						$arr[$cont]->provas = !empty($arrProvas) ? $arrProvas : FALSE;
						$arr[$cont]->cidades = !empty($arrCidades) ? $arrCidades : FALSE;
						$cont++;
					}
				}

				return !empty($arr) ? $arr : FALSE;
			}
			
			public function get_resultados_programacao($id = 0, $cidade = 0, $modalidade = 0, $divisao = 0, $data = NULL, $limit = 0){
				$cond = ' and ';
				$div = '';
				$lim = '';
				
				if(is_numeric($id) && (integer) $id !== 0){
					$cond .= ('a.id = ' . (integer) $id . ' and ');
				}
				if(is_numeric($cidade) && (integer) $cidade !== 0){
					$cond .= ('1 inner join programacao_cidade as e on e.id_programacao_item = a.id and e.id_cidade = ' . (integer) $cidade . ' and ');
				}
				if(is_numeric($modalidade) && (integer) $modalidade !== 0){
					$cond .= ('a.id_modalidade = ' . (integer) $modalidade . ' and ');
				}
				if(is_numeric($divisao) && (integer) $divisao !== 0){
					$div = 'and a.divisao like \'%' . (integer) $divisao . '%\' ';
				}
				if($data !== NULL){
					list($dia, $mes, $ano) = explode('-', $data);
					if(checkdate($mes, $dia, $ano)){
						$data = "'$ano-$mes-$dia'";
						$cond .= ('DATE(a.data_hora) = ' . $data . ' and ');
					}
				}
				
				if(is_numeric($limit) && (integer) $limit !== 0){
					$lim = 'limit ' . (integer) $limit;
				}
				
				if(empty($cond)){
					return FALSE;
				}
				
				$cond = substr($cond, 0, -5);
				$arr = array();

				$rs = mysql_query("select distinct a.id, a.id_modalidade, a.id_local, a.descricao,
							UNIX_TIMESTAMP(a.data_hora) as data, a.divisao, a.sexo, a.categoria,
							a.resultado_tipo, a.resultado_layout_tipo, a.resultado_link_pdf,
							a.total_cidades, a.data_atualizacao, a.status, b.titulo as modalidade,
							b.slug as slug_modalidade, d.nome as local from programacao_item as a
						inner join modalidade as b
							on b.id = a.id_modalidade
						inner join local_competicao as d
							on a.id_local = d.id and a.resultado_tipo >= '1' and a.status = 1
							$cond $div
						order by a.data_hora desc $lim;");
				
				if($rs && mysql_num_rows($rs)){
					$cont = 0;
					while($item = mysql_fetch_object($rs)){
						$arrProvas = $arrCidades = array();
						$rsProvas = mysql_query("select a.id, a.titulo, a.id_modalidade, a.divisao, a.sexo, a.categoria_idade
							from programacao_prova as a inner join programacao_prova_item as b
							on a.id = b.id_programacao_prova and b.id_programacao_item = $item->id;");
						if($rsProvas && mysql_num_rows($rsProvas)){
							while($itemProvas = mysql_fetch_object($rsProvas)){
								$arrProvas[] = $itemProvas;
							}
						}
					  
						$rsCidades = mysql_query("select a.id, a.nome, a.bandeira, a.divisao, a.slug, b.nome_atleta, b.resultado, b.resultado_total,
							b.resultado_colocacao from cidade as a inner join programacao_cidade as b
							on a.id = b.id_cidade and b.id_programacao_item = $item->id order by a.nome;");
						
						if($rsCidades && mysql_num_rows($rsCidades)){
							while($itemCidades = mysql_fetch_object($rsCidades)){
								$arrTemp = &$arrCidades[];
								$arrTemp = $itemCidades;
		
								if(!empty($arrTemp->nome_atleta)){
									$arrTemp->nome_atleta = unserialize($arrTemp->nome_atleta);
								}
								
								if(!empty($arrTemp->resultado_colocacao)){
									$arrTemp->resultado_colocacao = unserialize($arrTemp->resultado_colocacao);
								}
							}
						}
						
						$arr[$cont] = $item;
						$arr[$cont]->provas = !empty($arrProvas) ? $arrProvas : FALSE;
						$arr[$cont]->cidades = !empty($arrCidades) ? $arrCidades : FALSE;
						$cont++;
					}
				}
				
				return !empty($arr) ? $arr : FALSE;
			}
			
			public function excluir_programacao(){
				if(isset($this->id) && ($this->id !== FALSE) && is_numeric($this->id)){
					mysql_query("set autocommit = 0;");
					mysql_query("start transaction;");
					
					mysql_query("delete from programacao_cidade where id_programacao_item = {$this->id};");
					mysql_query("delete from programacao_prova_item where id_programacao_item = {$this->id};");
					mysql_query("delete from programacao_item where id = {$this->id} limit 1;");
					
					if(mysql_error()){
						mysql_query("rollback;");
						return FALSE;
					} else {
						mysql_query("commit;");
						return TRUE;
					}
				}
			}
  
			public function __destruct(){
			  $this->conn->close();
			}
        }
?>