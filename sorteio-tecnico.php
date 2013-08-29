<?php
	$dataAtual = time();
	$dataSorteio = mktime(10, 0, 0, 10, 22, 2011);
	
	$verificaSorteio = ($dataAtual >= $dataSorteio);
	$verificaParam = isset($_GET['sorteio']) ? TRUE : FALSE;
	$verificaSorteio = $verificaSorteio || $verificaParam;
	
	if($verificaSorteio){
		require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/SorteioTecnico.class.php');
		date_default_timezone_set('America/Sao_Paulo');

		$sorteio = new SorteioTecnico(FALSE);
		$divPrimeira = $sorteio->GetDivisao('primeira');
		$divSegunda = $sorteio->GetDivisao('segunda');
		$divEspecial = $sorteio->GetDivisao('especial');
		$files = $sorteio->GetLoadedFiles();
		$finalSorteio = count($files) >= 138;
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Dúvidas sobre os Jogos Abertos do Interior 2011. Envie críticas e sugestões à organização do evento." />
<meta name="keywords" content="Mogi, sorteio, técnico, Jogos, Abertos, sugestões, Mogi, Cruzes, competição, cidades, linha." />
<meta name="robots" content="index,follow" />
<meta http-equiv="content-language" content="pt-br" />
<meta name="author" content="Tboom Interactive"  />
<title>Sorteio Técnico | Jogos Abertos do Interior 2011</title>
<link rel="SHORTCUT ICON" href="/_img/favicon.ico">
<link rel="stylesheet" type="text/css" href="/_css/reset.css">
<link rel="stylesheet" type="text/css" href="/_css/style.css">
<link rel="stylesheet" type="text/css" href="/_css/style_2.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.countdown.css">
<link rel="canonical" href="http://www.jogosabertos2011.com.br<?php echo $_SERVER['REQUEST_URI']; ?>" />
<script type="text/javascript" src="/_js/jquery.js"></script>
<script type="text/javascript" src="/_js/jquery.jparallax.js"></script>
<script type="text/javascript" src="/_js/jquery.countdown.js"></script>
<script type="text/javascript" src="/_js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="/_js/jquery.jscrollpane.js"></script>
<script type="text/javascript" src="/_js/jquery.color.js"></script>
<script type="text/javascript" src="/_js/functions.js"></script>
</head>

<body id="sorteio-tecnico">
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/bg.php'); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/header.php') ?>
    <div class="content" id="content">
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/navbar.php') ?>
        <h1 class="title red">
        	<div class="sombra" style="margin:-4px 0 0 -24px; height:65px;"></div> 
        	Sorteio Técnico
        </h1>
        <br clear="all" />
        
		<div id="content_sorteio">
			<p class="first">Aqui, você acompanha o sorteio técnico das chaves dos Jogos Abertos em tempo real e fica sabendo,antes de<br />todo mundo, quais serão as principaisdisputas da competição. Clique na categoria do seu esporte favorito e confira.</p>
			<ul id="listTab">
			    <li><a href="#box1a">1ª Divisão</a></li>
			    <li><a href="#box2a">2ª Divisão</a></li>
			    <li><a href="#box3a">Divisão Especial</a></li>
			</ul>
		      
			
			<div id="containerTab">
				<div class="tab" id="box1a">
					<div class="info">&nbsp;</div>
					<?php if($divPrimeira !== FALSE) :
						$tltPrev = '';
						$sexoPrev = '';
						
						foreach($divPrimeira as $i=>$item) :
					?>
							<?php if($item->titulo != $tltPrev) : $tltPrev = $item->titulo; $sexoPrev = ''; ?>
								<?php if($i) : ?>
									</div>
								</div>
								<?php endif; ?>
								<div class="item">
									<div class="img"><div class="imgInner imgMod<?php echo $item->id; ?>"><?php echo $item->titulo; ?></div></div>
									<h3><?php echo $item->titulo; ?></h3>
							<?php endif; ?>						
							
								<?php if($item->sexo != $sexoPrev) :
									if(!empty($sexoPrev)) : ?>
										</div>
									<?php
										endif;
										$sexoPrev = $item->sexo;
									?>
									
									<span class="lblSexo"><?php echo ($item->sexo == 1) ? 'Masculino' : (($item->sexo == 2) ? 'Feminino' : 'Misto'); ?>:</span>
									<div class="contLinks">
								<?php endif;
									if($item->idade == 0){
										$idade = 'Livre';
									} else {
										$idade = 'Até ' . $item->idade . ' anos';
									}
								?>
								
								<?php if($sorteio->HasFile($item->sigla_pdf)) : ?>
									<a id="<?php echo $item->sigla_pdf; ?>" href="/upload/sorteio_tecnico/<?php echo $item->sigla_pdf; ?>.PDF" target="_blank" title="Clique para baixar o PDF desse sorteio técnico"><?php echo $idade; ?></a>
								<?php else : ?>
									<span id="<?php echo $item->sigla_pdf; ?>" class="linkDisabled" title="Esse sorteio técnico ainda não foi realizado"><?php echo $idade; ?></span>
								<?php endif; ?>
					<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<div class="tab" id="box2a">
					<div class="info">&nbsp;</div>
					<?php if($divSegunda !== FALSE) :
						$tltPrev = '';
						$sexoPrev = '';
						
						foreach($divSegunda as $i=>$item) :
					?>
							<?php if($item->titulo != $tltPrev) : $tltPrev = $item->titulo; $sexoPrev = ''; ?>
								<?php if($i) : ?>
									</div>
								</div>
								<?php endif; ?>
								<div class="item">
									<div class="img"><div class="imgInner imgMod<?php echo $item->id; ?>"><?php echo $item->titulo; ?></div></div>
									<h3><?php echo $item->titulo; ?></h3>
							<?php endif; ?>						
							
								<?php if($item->sexo != $sexoPrev) :
									if(!empty($sexoPrev)) : ?>
										</div>
									<?php
										endif;
										$sexoPrev = $item->sexo;
									?>
									
									<span class="lblSexo"><?php echo ($item->sexo == 1) ? 'Masculino' : (($item->sexo == 2) ? 'Feminino' : 'Misto'); ?>:</span>
									<div class="contLinks">
								<?php endif;
									if($item->idade == 0){
										$idade = 'Livre';
									} else {
										$idade = 'Até ' . $item->idade . ' anos';
									}
								?>
								
								<?php if($sorteio->HasFile($item->sigla_pdf)) : ?>
									<a id="<?php echo $item->sigla_pdf; ?>" href="/upload/sorteio_tecnico/<?php echo $item->sigla_pdf; ?>.PDF" target="_blank" title="Clique para baixar o PDF desse sorteio técnico"><?php echo $idade; ?></a>
								<?php else : ?>
									<span id="<?php echo $item->sigla_pdf; ?>" class="linkDisabled" target="_blank" title="Esse sorteio técnico ainda não foi realizado"><?php echo $idade; ?></span>
								<?php endif; ?>
					<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<div class="tab" id="box3a">
					<div class="info">&nbsp;</div>
					<?php if($divEspecial !== FALSE) :
						$tltPrev = '';
						$sexoPrev = '';
						
						foreach($divEspecial as $i=>$item) :
					?>
							<?php if($item->titulo != $tltPrev) : $tltPrev = $item->titulo; $sexoPrev = ''; ?>
								<?php if($i) : ?>
									</div>
								</div>
								<?php endif; ?>
								<div class="item">
									<div class="img"><div class="imgInner imgMod<?php echo $item->id; ?>"><?php echo $item->titulo; ?></div></div>
									<h3><?php echo $item->titulo; ?></h3>
							<?php endif; ?>						
							
								<?php if($item->sexo != $sexoPrev) :
									if(!empty($sexoPrev)) : ?>
										</div>
									<?php
										endif;
										$sexoPrev = $item->sexo;
									?>
									
									<span class="lblSexo"><?php echo ($item->sexo == 1) ? 'Masculino' : (($item->sexo == 2) ? 'Feminino' : 'Misto'); ?>:</span>
									<div class="contLinks">
								<?php endif;
									if($item->idade == 0){
										$idade = 'Livre';
									} else {
										$idade = 'Até ' . $item->idade . ' anos';
									}
								?>
								
								<?php if($sorteio->HasFile($item->sigla_pdf)) : ?>
									<a id="<?php echo $item->sigla_pdf; ?>" href="/upload/sorteio_tecnico/<?php echo $item->sigla_pdf; ?>.PDF" target="_blank" title="Clique para baixar o PDF desse sorteio técnico"><?php echo $idade; ?></a>
								<?php else : ?>
									<span id="<?php echo $item->sigla_pdf; ?>" class="linkDisabled" target="_blank" title="Esse sorteio técnico ainda não foi realizado"><?php echo $idade; ?></span>
								<?php endif; ?>
					<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
	        </div>
		
	<div class="veja_tambem">
    	<h3>Veja Também</h3>
    	<div class="item">
        	<h4><a href="/jogos/historia-dos-jogos">história dos jogos</a></h4>
            <div class="imagem">
            	<a href="/jogos/historia-dos-jogos"><img src="/_img/ft_vejatambem1.jpg" width="210" height="84" alt="Imagem 1" /></a>
            </div>
            <p><a href="/jogos/historia-dos-jogos">De 1936 a 2011, os fatos mais marcantes dos tradicionais Jogos Abertos.</a></p>
        </div>
    	<div class="item">
        	<h4><a href="/cidade/cidade-sede">Cidade-sede</a></h4>
            <div class="imagem">
            	<a href="/cidade/cidade-sede"><img src="/_img/ft_vejatambem2.jpg" width="210" height="84" alt="Imagem 1" /></a>
            </div>
            <p><a href="/cidade/cidade-sede">Saiba por que Mogi das Cruzes foi eleita a casa dos Jogos Abertos deste ano.</a></p>
        </div>
    	<div class="item">
        	<h4><a href="/modalidades">modalidades</a></h4>
            <div class="imagem">
            	<a href="/modalidades"><img src="/_img/ft_vejatambem3.jpg" width="210" height="84" alt="Imagem 1" /></a>
            </div>
            <p><a href="/modalidades">27 modalidades vão esquentar as disputas dos Jogos Abertos de 2011. Clique e conheça.</a></p>
        </div>
    </div>
	<br clear="all" />
	</div>

	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/footer.php'); ?>
    </div>
</body>
</html>
