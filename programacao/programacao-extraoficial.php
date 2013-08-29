<?php
	require($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Programacao.class.php');

	$progObj = new Programacao();
	$divisoesProg = $progObj->get_divisoes_programacao(FALSE);
	$modalidadesProg = $progObj->get_modalidades_programacao(FALSE);
	$cidadesProg = $progObj->get_cidades_programacao(FALSE, TRUE);
	$locaisProg = $progObj->get_locais_programacao(FALSE, TRUE);
	$programacao = $progObj->get_programacao(FALSE, FALSE, FALSE, FALSE, FALSE, date('d-m-Y'));
	$atualizacao = $progObj->get_data_atualizacao();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Dúvidas sobre os Jogos Abertos do Interior 2011. Envie críticas e sugestões à organização do evento." />
<meta name="keywords" content="Mogi, sorteio, técnico, Jogos, Abertos, sugestões, Mogi, Cruzes, competição, cidades, linha." />
<meta name="robots" content="index,follow" />
<meta http-equiv="content-language" content="pt-br" />
<meta name="author" content="Tboom Interactive"  />
<title>Programação Extraoficial | Jogos Abertos do Interior 2011</title>
<link rel="SHORTCUT ICON" href="/_img/favicon.ico">
<link rel="stylesheet" type="text/css" href="/_css/reset.css">
<link rel="stylesheet" type="text/css" href="/_css/style.css">
<link rel="stylesheet" type="text/css" href="/_css/style_2.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.scrollpane.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.countdown.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.selectmenu.css">
<link rel="canonical" href="http://www.jogosabertos2011.com.br<?php echo $_SERVER['REQUEST_URI']; ?>" />
<script type="text/javascript" src="/_js/jquery.js"></script>
<script type="text/javascript" src="/_js/jquery.ui.core.js"></script>
<script type="text/javascript" src="/_js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="/_js/jquery.ui.selectmenu.js"></script>
<script type="text/javascript" src="/_js/jquery.jparallax.js"></script>
<script type="text/javascript" src="/_js/jquery.countdown.js"></script>
<script type="text/javascript" src="/_js/jquery.jscrollpane.js"></script>
<script type="text/javascript" src="/_js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="/_js/jquery.em.js"></script>
<script type="text/javascript" src="/_js/util.js"></script>
<script type="text/javascript" src="/_js/functions.js"></script>
<script>
	$(function(){
		$('.scroll-pane').jScrollPane({ 'animateScroll': true, 'verticalGutter': 0, 'autoReinitialise': true, 'scrollbarWidth': 16  });
	});
	jQuery(function() {
		$('#divisao').selectmenu({ style:'dropdown',width: 250});
		$('#cidades').selectmenu({ style:'dropdown',width: 250});	
		$('#modalidades').selectmenu({ style:'dropdown',width: 250});	
		$('#locais').selectmenu({ style:'dropdown',width: 250});			
	});

</script>
</head>

<body class="body_programacao">
	
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/bg.php'); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/header.php') ?>
    <div class="content" id="content">
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/navbar.php') ?>
        <h1 class="title red">
        	<div class="sombra" style="margin:-4px 0 0 -24px; height:65px;"></div> 
        	Programação Extraoficial
        </h1>
        <br clear="all" />
        
	<div id="content_prog" class="content">
		
		<?php if($divisoesProg !== FALSE) : ?>
			<div id="boxSelect" style="float: left;">
				<label id="lblCidPart">Selecione a divisão:</label>
				<select name="divisao" id="divisao" class="boxSelect" autocomplete="off">
					<option value="">Todas as Divisões</option>
					<?php foreach($divisoesProg as $item) : ?>
						<option value="<?php echo $item->divisao; ?>"><?php echo $item->divisao == '3' ? 'Divisão Especial' : ($item->divisao == '4' ? 'Modalidades Extras' : $item->divisao . 'ª Divisão'); ?></option>
					<?php endforeach; ?>
				</select>
				<div class="bgGray"></div>
				<div class="bgDisabled">&nbsp;</div>
			</div>
		<?php endif; ?>
		
		<div class="containerLoading">
			<div class="boxLoading" id="loadingTop"><img src="/_img/load.gif" alt="Carregando..." /></div>
		</div>
		
		<a href="/programacao/20111027_prog_geral.pdf" title="Programação Extraoficial" target="_blank">
			<img class="prog-geral" src="/_img/prog_geral.jpg" alt="Confira a Programação Geral" />
		</a>
		
		<br clear="all" />
		
		<div id="containerTab">
			<div class="nav_prog">
				<p>Navegue pelos filtros abaixo para refinar sua busca</p>
				
				<?php if($modalidadesProg !== FALSE) : ?>
					<div class="boxSelect">
						<label>Modalidade:</label>
						<select name="modalidades" id="modalidades" class="boxSelect" autocomplete="off">
							<option value="">Todas as modalidades</option>
							<?php foreach($modalidadesProg as $item) : ?>
								<option value="<?php echo $item->id; ?>"><?php echo $item->titulo; ?></option>
							<?php endforeach; unset($item); ?>
						</select>
						<div class="bgGray"></div>
						<div class="bgDisabled">&nbsp;</div>
					</div>
				<?php endif; ?>
				
				<?php if($locaisProg !== FALSE) : ?>
					<div class="boxSelect">
						<label>Local de competição:</label>
						<select name="locais" id="locais" class="boxSelect" autocomplete="off">
							<option value="">Todos os locais</option>
							<?php foreach($locaisProg as $item) : ?>
								<option value="<?php echo $item->id; ?>"><?php echo $item->nome; ?></option>
							<?php endforeach; unset($item); ?>
						</select>
						<div class="bgGray"></div>
						<div class="bgDisabled">&nbsp;</div>
					</div>
				<?php endif; ?>
				
				<?php if($cidadesProg !== FALSE) : ?>
					<div class="boxSelect boxSelectMod">
						<label>Cidade:</label>
						<select name="cidades" id="cidades" class="boxSelect" autocomplete="off">
							<option value="">Todas as cidades</option>
							<?php foreach($cidadesProg as $item) : ?>
								<option value="<?php echo $item->id; ?>"><?php echo $item->nome; ?></option>
							<?php endforeach; unset($item); ?>
						</select>
						<div class="bgGray"></div>
						<div class="bgDisabled">&nbsp;</div>
					</div>
				<?php endif; ?>
	
			</div>
			
			<h2 id="tltDivisao">Programação Extraoficial - <span>TODAS AS DIVISÕES</span></h2>
			
			<div id="prog-title-programacao" class="prog-title">
				<a href="#<?php echo date('d-m-Y', strtotime('-1 day')); ?>" id="prog-prev"<?php echo strtotime('-1 day') < mktime(0,0,0,11,7,11) ? ' class="hide"' : '' ; ?>>&nbsp;</a>
				<span id="dataFilter" class="data dataFilter" rel="<?php echo date('d-m-Y'); ?>"><?php echo Utility::data_extenso(date('d\/m\/Y'), FALSE); ?></span>
				<a href="#<?php echo date('d-m-Y', strtotime('+1 day')); ?>" id="prog-next"<?php echo strtotime('+1 day') > mktime(23,59,59,11,19,11) ? ' class="hide"' : '' ; ?>>&nbsp;</a>
				<span class="resultado">resultado</span>
			</div>
			<div class="scroll-pane">
				<div class="bgDisabledContent">&nbsp;</div>
				<div class="boxLoading" id="loadingContent"><img src="/_img/load.gif" alt="Carregando..." /></div>
				<?php if($programacao === FALSE) : ?>
				
					<div id="boxItens">
						<p class="infoNenhumEvento">Nenhum evento cadastrado.</p>
					</div>
					
				<?php else :
				
					/*echo '<pre>';
					print_r($programacao);
					echo '</pre>';*/
				?>

				<div id="boxItens">
					<?php foreach($programacao as $key=>$item) : ?>
						<div class="item item<?php echo ($key % 2 == 0) ? '1' : '2'; ?>">
							<div class="data">
								<div class="data-left">
									<span><?php echo date('H\hi', $item->data); ?></span>
									<img src="/_img/modalidades/ico_cat<?php echo $item->id_modalidade; ?>.png" alt="<?php echo htmlentities($item->modalidade); ?>" />
								</div>
								<div class="data-right">
									<h4><a href="/modalidade/<?php echo htmlspecialchars($item->slug_modalidade); ?>"><?php echo htmlspecialchars($item->modalidade); ?></a></h4>
									<p>
										<?php
											if(is_array($item->provas) && !empty($item->provas)) {
												$txt = '';
												foreach($item->provas as $prova){
														$txt .= "{$prova->titulo}, ";
												}
												$posLast = strrpos($txt = substr($txt, 0, -2), ',');
												if($posLast !== FALSE){
														$item->provas = substr_replace($txt, ' e', $posLast, 1);
												} else {
														$item->provas = $txt;
												}
											}
											
											$sexo = explode(',', $item->sexo);
											$txt = '';
											foreach($sexo as $itemSexo){
												switch($itemSexo){
													case '1':
													  $txt .= 'Masculino, ';
													  break;
													case '2':
													  $txt .= 'Feminino, ';
													  break;
													case '3':
													  $txt .= 'Misto, ';
												}
											}
											$posLast = strrpos($txt = substr($txt, 0, -2), ',');
											if($posLast !== FALSE){
												$item->sexo = substr_replace($txt, ' e', $posLast, 1);
											} else {
												$item->sexo = $txt;
											}
											
											$categoria = explode(',', $item->categoria);
											$txt = '';
											foreach($categoria as $cat){
													switch($cat){
													  case '0':
														$txt .= 'Livre, ';
														break;
													  default:
														$txt .= "Até $cat anos, ";
													}
											}
											$posLast = strrpos($txt = substr($txt, 0, -2), ',');
											if($posLast !== FALSE){
												$item->categoria = substr_replace($txt, ' e', $posLast, 1);
											} else {
												$item->categoria = $txt;
											}
											
											$txtCidade = '';
											if(is_array($item->cidades) && !empty($item->cidades)) {
												$txt = '';
												foreach($item->cidades as $cid){
													$txt .= '<a href="/jogos/cidades-participantes/' . htmlspecialchars($cid->slug) . '">' . htmlspecialchars($cid->nome) . '</a>, ';
												}
												$posLast = strrpos($txt = substr($txt, 0, -2), ',');
												if($posLast !== FALSE){
													$txtCidade = substr_replace($txt, ' e', $posLast, 1);
												} else {
													$txtCidade = $txt;
												}
											}
										?>
										
										<?php if(!empty($item->provas)) : ?>
											<b>Prova:  </b><?php echo htmlspecialchars($item->provas); ?><br />
										<?php endif; ?>
										<b>Categoria: </b><?php echo htmlspecialchars($item->sexo) . ' / ' . htmlspecialchars($item->categoria); ?><br>
										<b>Local de Competição: </b><?php echo htmlspecialchars($item->local); ?> <a href="/mapa-interativo?modalidade=<?php echo htmlentities($item->slug_modalidade); ?>">(veja mapa dos Locais de Competição)</a><br>
										<?php if(!empty($txtCidade)) : ?>
											<b>Cidades : </b><?php echo $txtCidade; ?><br>
										<?php endif; ?>
									</p>
								</div>
							</div>
							<?php
								if((integer) $item->resultado_layout_tipo !== 0) :
									if((integer) $item->resultado_layout_tipo === 1){
										if(!empty($item->cidades[0]->nome_atleta)){
											$tipoResult = '3';
										} else {
											$tipoResult = '1';
										}
									} elseif((integer) $item->resultado_layout_tipo === 2){
										$tipoResult = '2';
									} else {
										$tipoResult = '4';
									}

							?>
								<div class="res resultado_<?php echo $tipoResult; ?>">
									
									<?php if($tipoResult === '1') : ?>
									
										<p class="<?php echo $item->cidades[0]->resultado === 'v' ? 'vitoria' : ''; ?>">
											<span class="pts"><?php echo $item->cidades[0]->resultado_total; ?></span>
											<img src="/_img/cidade/bandeiras/<?php echo $item->cidades[0]->bandeira; ?>" title="<?php echo htmlspecialchars($item->cidades[0]->nome); ?>" />
											<span class="nome"><?php echo $item->cidades[0]->nome . ($item->cidades[1]->resultado === 'w' ? ' <span class="cidWo">(venceu por W.O.)</span>' : ''); ?></span>
										</p>
										<span class="x">X</span>
										<p class="<?php echo $item->cidades[1]->resultado === 'v' ? 'vitoria' : ''; ?>">
											<span class="pts"><?php echo $item->cidades[1]->resultado_total; ?></span>
											<img src="/_img/cidade/bandeiras/<?php echo $item->cidades[1]->bandeira; ?>" title="<?php echo htmlspecialchars($item->cidades[1]->nome); ?>" />
											<span class="nome"><?php echo $item->cidades[1]->nome . ($item->cidades[0]->resultado === 'w' ? ' <span class="cidWo">(venceu por W.O.)</span>' : ''); ?></span>
										</p>
										
									<?php
										elseif($tipoResult === '2') :
											
											$arrOrdCid = Utility::object2Array($item->cidades);
											$arrOrdCidPrint = array();
											$i = 0;
											$j = 0;
								
											do{
												if(is_array($arrOrdCid[$i]['resultado_colocacao'])){
													if(count($arrOrdCid[$i]['resultado_colocacao']) === 1){
														$arrOrdCidPrint[$j] = $arrOrdCid[$i];
														$arrOrdCidPrint[$j]['resultado_colocacao'] = array_shift($arrOrdCidPrint[$j]['resultado_colocacao']);
														if(is_array($arrOrdCidPrint[$j]['nome_atleta']) && !empty($arrOrdCidPrint[$j]['nome_atleta'])){
															$arrOrdCidPrint[$j]['nome_atleta'] = array_shift($arrOrdCidPrint[$j]['nome_atleta']);
														}
														$j++;
													} else {
														$itemColTemp = array_shift($arrOrdCid[$i]['resultado_colocacao']);
														if(is_array($arrOrdCid[$i]['nome_atleta']) && !empty($arrOrdCid[$i]['nome_atleta'])){
															$itemAltTemp = array_shift($arrOrdCid[$i]['nome_atleta']);
														}
														$arrOrdCid[] = $arrOrdCidPrint[$j] = $arrOrdCid[$i];
														$arrOrdCidPrint[$j]['resultado_colocacao'] = $itemColTemp;
														$arrOrdCidPrint[$j]['nome_atleta'] = isset($itemAltTemp) ? $itemAltTemp : NULL;
														$j++;
													}
												}
												$i++;
											} while($i < count($arrOrdCid));
											
											for($i = 0; $i < 3; $i++){
												for($j = $i; $j < 3; $j++){
													if($arrOrdCidPrint[$i]['resultado_colocacao'] > $arrOrdCidPrint[$j]['resultado_colocacao']){
														list($arrOrdCidPrint[$i], $arrOrdCidPrint[$j]) = array($arrOrdCidPrint[$j], $arrOrdCidPrint[$i]);
													}
												}
											}
										
											$arrOrdCidPrint = array_map('Utility::array2Object', $arrOrdCidPrint);
									?>
										
										<?php
											for($i = 0; $i < 3; $i++) : ?>
											<p<?php echo $i === 2 ? ' class="last"' : ''; ?>>
												<span class="pts"><?php echo $arrOrdCidPrint[$i]->resultado_colocacao; ?>º</span>
												<img src="/_img/cidade/bandeiras/<?php echo $arrOrdCidPrint[$i]->bandeira; ?>" title="<?php echo htmlspecialchars($arrOrdCidPrint[$i]->nome); ?>" />
												<span class="nome"><b><?php echo $arrOrdCidPrint[$i]->nome_atleta; ?></b><br />
												(<?php echo $arrOrdCidPrint[$i]->nome; ?>)</span>
											</p>
										<?php endfor; ?>
									
									<?php elseif($tipoResult === '3') : ?>
									
										<p class="<?php echo $item->cidades[0]->resultado === 'v' ? 'vitoria' : ''; ?>">
											<span class="pts"><?php echo $item->cidades[0]->resultado_total; ?></span>
											<img src="/_img/cidade/bandeiras/<?php echo $item->cidades[0]->bandeira; ?>" title="<?php echo htmlspecialchars($item->cidades[0]->nome); ?>" />
											<span class="nome"><b><?php echo $item->cidades[0]->nome_atleta[0]; ?></b><br />
											(<?php echo $item->cidades[0]->nome . ($item->cidades[1]->resultado === 'w' ? ' <span class="cidWo">[venceu por W.O.]</span>' : ''); ?>)</span>
										</p>
										<span class="x">X</span>
										<p class="<?php echo $item->cidades[1]->resultado === 'v' ? 'vitoria' : ''; ?>">
											<span class="pts"><?php echo $item->cidades[1]->resultado_total; ?></span>
											<img src="/_img/cidade/bandeiras/<?php echo $item->cidades[1]->bandeira; ?>" title="<?php echo htmlspecialchars($item->cidades[1]->nome); ?>" />
											<span class="nome"><b><?php echo $item->cidades[1]->nome_atleta[0]; ?></b><br />
											(<?php echo $item->cidades[0]->nome . ($item->cidades[0]->resultado === 'w' ? ' <span class="cidWo">[venceu por W.O.]</span>' : ''); ?>)</span>
										</p>
									
									<?php elseif($tipoResult === '4' && $item->resultado_link_pdf !== NULL) : ?>
									
											<a href="/upload/programacao_resultado/<?php echo $item->resultado_link_pdf; ?>" target="_blank" class="ver_res"><span>ver resultado</span></a>

									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
				
				
				<?php if(FALSE) : ?>
					<div class="item item2">
						<div class="data">
							<div class="data-left">
								<span>09h00</span>
								<img src="/_img/modalidades/ico_cat1.png" alt="" />
							</div>
							<div class="data-right">
								<h4>Modalidade</h4>
								<p>
									<b>Prova:  </b>Semi-Finais<br>
									<b>Categoria: </b>Feminino /Livre<br>
									<b>Local de Competição: </b>Local da Competição (veja mapa dos Locais de Competição)<br>
									<b>Cidades : </b>Nome da Cidade, Nome da Cidade, Nome da Cidade<br>
								</p>
							</div>
						</div>
						<div class="res resultado_2">
							<p>
								<span class="pts">1º</span>
								<img src="/_img/flag.jpg" />
								<span class="nome"><b>alexandre ferreirO DA SILVA</b>
								(santa rita do passa quatro)</span>
							</p>
							<p>
								<span class="pts">2º</span>
								<img src="/_img/flag.jpg" />
								<span class="nome"><b>alexandre ferreirO DA SILVA</b>
								(santa rita do passa quatro)</span>
							</p>
							<p>
								<span class="pts">3º</span>
								<img src="/_img/flag.jpg" />
								<span class="nome"><b>alexandre ferreirO DA SILVA</b>
								(santa rita do passa quatro)</span>
							</p>
						</div>
					</div>
					
					<div class="item item1">
						<div class="data">
							<div class="data-left">
								<span>09h00</span>
								<img src="/_img/modalidades/ico_cat1.png" alt="" />
							</div>
							<div class="data-right">
								<h4>Modalidade</h4>
								<p>
									<b>Prova:  </b>Semi-Finais<br>
									<b>Categoria: </b>Feminino /Livre<br>
									<b>Local de Competição: </b>Local da Competição (veja mapa dos Locais de Competição)<br>
									<b>Cidades : </b>Nome da Cidade, Nome da Cidade, Nome da Cidade<br>
								</p>
							</div>
						</div>
						<div class="res resultado_3">
							<p>
								<span class="pts"></span>
								<img src="/_img/flag.jpg" />
								<span class="nome"><b>alexandre ferreirO DA SILVA</b>
								(santa rita do passa quatro)</span>
							</p>
							<span class="x">X</span>
							<p>
								<span class="pts"></span>
								<img src="/_img/flag.jpg" />
								<span class="nome"><b>alexandre ferreirO DA SILVA</b>
								(santa rita do passa quatro)</span>
							</p>
						</div>
					</div>
					
					<div class="item item2">
						<div class="data">
							<div class="data-left">
								<span>09h00</span>
								<img src="/_img/modalidades/ico_cat1.png" alt="" />
							</div>
							<div class="data-right">
								<h4>Modalidade</h4>
								<p>
									<b>Prova:  </b>Semi-Finais<br>
									<b>Categoria: </b>Feminino /Livre<br>
									<b>Local de Competição: </b>Local da Competição (veja mapa dos Locais de Competição)<br>
									<b>Cidades : </b>Nome da Cidade, Nome da Cidade, Nome da Cidade<br>
								</p>
							</div>
						</div>
						<div class="res resultado_4">
							<a href="#" class="ver_res"><span>ver resultado</span></a>
						</div>
					</div>
					<div class="item item1">
						<div class="data">
							<div class="data-left">
								<span>09h00</span>
								<img src="/_img/modalidades/ico_cat1.png" alt="" />
							</div>
							<div class="data-right">
								<h4>Modalidade</h4>
								<p>
									<b>Prova:  </b>Semi-Finais<br>
									<b>Categoria: </b>Feminino /Livre<br>
									<b>Local de Competição: </b>Local da Competição (veja mapa dos Locais de Competição)<br>
									<b>Cidades : </b>Nome da Cidade, Nome da Cidade, Nome da Cidade<br>
								</p>
							</div>
						</div>
						<div class="res resultado_4">
							<a href="#" class="ver_res"><span>ver resultado</span></a>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<p class="update updateProgramacao">Última atualização: <?php echo date('d\/m - H:i', $atualizacao); ?></p>
		</div>
			
		<div class="veja_tambem">
        	<h3>Veja Também</h3>
        	<div class="item">
            	<h4><a href="/modalidades">locais de competição</a></h4>
                <div class="imagem">
                	<a href="/modalidades"><img src="/_img/ft_veja_locais.jpg" width="210" height="84" alt="Imagem 1" /></a>
                </div>
                <p><a href="/modalidades">Saiba por que Mogi das Cruzes foi eleita a casa dos Jogos Abertos deste ano.</a></p>
            </div>
            <div class="item">
            	<h4><a href="/jogos/cidades-participantes">programação</a></h4>
                <div class="imagem">
                	<a href="/jogos/cidades-participantes"><img src="/_img/ft_veja_cidade.jpg" width="210" height="84" alt="Imagem 1" /></a>
                </div>
                <p><a href="/jogos/cidades-participantes">Saiba tudo o que acontece na maior festa esportiva da América Latina.</a></p>
            </div>
        	<div class="item">
            	<h4><a href="/resultados/classificacao">classificação</a></h4>
                <div class="imagem">
                	<a href="/resultados/classificacao"><img src="/_img/ft_veja_classificacao.jpg" width="210" height="84" alt="Imagem 1" /></a>
                </div>
                <p><a href="/resultados/classificacao">Fique ligado na corrida pela liderança dos Jogos Abertos 2011</a></p>
            </div>        	
        </div>  
	</div>
	<br clear="all" />
	</div>
	
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/footer.php'); ?>
    </div>
</body>
</html>
