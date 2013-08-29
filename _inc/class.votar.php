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
	
	class votar{
		public $bdconn;
		public $dados;
		public function __construct(){
			require('conn.php');
			$this->bdconn = new dbconnect();			
		}
		public function select($fields, $table, $condition, $order, $limit){
			$this->dados = '';
			if(!empty($condition)){
				$condition = ' WHERE ' . $condition;
			}
			if(!empty($order)){
				$order = ' ORDER BY ' . $order;
			}
			$rs = mysql_query("SELECT {$fields} FROM {$table} {$condition} {$order} {$limit}");
			
			$num_rows = @mysql_num_rows($rs);
			
			if($num_rows == 0){
				@mysql_close($this->bdconn);
				return false;
			}else{
				while($row = mysql_fetch_assoc($rs)){
					$this->dados[] = $row;
				}
			}
			return $this->dados;
			mysql_close($this->bdconn);
		}
		public function votarMascote($_POST){
			$tipo = $_POST['tipo'];
			$nomemascote = $_POST['nomemascote'];
			$getVotos = $this->select('*', 'votacao', "tipo = '{$tipo}' AND mascote = '{$nomemascote}'", '', '');
			$numVotos = $getVotos[0]['votos']+1;
			$insere = mysql_query("UPDATE votacao SET votos = '{$numVotos}' WHERE tipo = '{$tipo}' AND mascote = '{$nomemascote}'");
			if($insere){
				echo "Obrigado! Você fez uma ótima escolha. Agora convide seus amigos para votar também e fique na torcida.";
			}else{
				echo 'Erro. Reinicie a página e tente novamente ou entre em <a href="#">contato</a> conosco.';
			}
		}
		public function resultadoVotacao(){
			$getMascotes = $this->select('*', 'votacao', "", 'votos desc', '');
			$totalVotos = 0;
			for($i = 0; $i < count($getMascotes); $i++){
				$totalVotos += $getMascotes[$i]['votos'];
			}
			//echo $totalVotos;
			//echo '<br><br>';
			if($totalVotos == 0){
				$totalVotos = 1;
			}
			$percentVotos = 100/$totalVotos;
						
			for($i = 0; $i < count($getMascotes); $i++){
				$$getMascotes[$i]['mascote'] = floor($getMascotes[$i]['votos']*$percentVotos);
				$getMascotes[$i]['largura'] = round($$getMascotes[$i]['mascote'] * 100 / 134);
				//echo "{$getMascotes[$i]['tipo']} -> {$getMascotes[$i]['mascote']} = <div class=\"load\" style=\"width:{$$getMascotes[$i]['mascote']}%\">{$$getMascotes[$i]['mascote']}%</div></div><br />";
				?>
                <div class="item">
                    <img src="/_img/ft_<?php echo $getMascotes[$i]['tipo'] ?>_result.jpg" width="50" height="50" alt="<?php echo $getMascotes[$i]['mascote'] ?>" align="left" />
                    <div class="info"><?php echo $getMascotes[$i]['mascote'] ?> <!--<span><?php //echo $getMascotes[$i]['votos'] ?></span>--></div>
		    <div class="fundo_result">
                        <div class="porcent_result" style="width: <?php echo $getMascotes[$i]['largura']; ?>%;"></div>
                    </div>
                    <div class="porcent_result_num">
                        <?php echo $$getMascotes[$i]['mascote'] ?>%
                    </div>
                </div>
                <?php
			}
		}
	}
?>