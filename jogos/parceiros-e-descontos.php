<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Conheça a casa dos Jogos Abertos do Interior 2011. Navegue pelo mapa interativo da cidade, e veja toda a infraestrutura preparada para receber o evento." />
<meta name="keywords" content="Mapa, Mogi, Jogos, Abertos, pontos, turísticos, interior, SP, localização, Casa, infraestrutura" />
<meta name="robots" content="index,follow" />
<meta http-equiv="content-language" content="pt-br" />
<meta name="author" content="Tboom Interactive"  />
<title>Parceiros e Descontos | Jogos Abertos do Interior 2011 </title>
<link rel="SHORTCUT ICON" href="/_img/favicon.ico">
<link rel="stylesheet" type="text/css" href="/_css/reset.css">
<link rel="stylesheet" type="text/css" href="/_css/style.css">
<link rel="stylesheet" type="text/css" href="/_css/style_2.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.scrollpane.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.countdown.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.selectmenu.css">
<link rel="canonical" href="http://www.jogosabertos2011.com.br<?php echo $_SERVER['REQUEST_URI']; ?>" />
<script type="text/javascript" src="/_js/jquery.js"></script>
<script type="text/javascript" src="/_js/jquery.ui.core.js"></script>
<script type="text/javascript" src="/_js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="/_js/jquery.ui.selectmenu.js"></script>
<script type="text/javascript" src="/_js/jquery.jparallax.js"></script>
<script type="text/javascript" src="/_js/jcarousellite_1.0.1.min.js"></script>
<script type="text/javascript" src="/_js/jquery.scrollpane.js"></script>
<script type="text/javascript" src="/_js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="/_js/jquery.em.js"></script>
<script type="text/javascript" src="/_js/jquery.countdown.js"></script>
<script type="text/javascript" src="/_js/functions.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#parceiros').selectmenu({ style:'dropdown',width: 300});
	$('select#parceiros').change(function(){
		window.location = '/jogos/parceiros-e-descontos/' + $('select#parceiros').val();
	});
});
</script>
</head>

<body>
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/bg.php'); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/header.php') ?>
    <div class="content p_modalidade" id="content">
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/navbar.php') ?>
        <h1 class="title">
        	<div class="sombra" style="margin:-4px 0 0 -24px; height:65px;"></div>
        	Parceiros e Descontos
        </h1>
        <div id="content_parceiros">
		<?php
			$servico = isset($_GET['servico']) && is_numeric($_GET['servico']) ? (integer) $_GET['servico'] : FALSE;
			require($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Parceiro.class.php');
			$serv = Parceiro::get_servicos();
                        if($serv && is_array($serv)) :
		?>
            <form id="frmParceiros">
                <label>Escolha o tipo de serviço</label>
		<select name="servico" id="parceiros">
			<?php foreach($serv as $i=>$item) :
				if($servico === FALSE && $i === 0){
					$tltServico = $item->titulo;
				} elseif($servico == $item->id){
					$tltServico = $item->titulo;
				}
			?>
				<option value="<?php echo $item->id; ?>"<?php echo ($servico == $item->id) ? ' selected="selected"' : ''; ?>><?php echo $item->titulo; ?></option>
			<?php endforeach; ?>
                </select>
	    </form>
		<?php endif; ?>
	    
                <div class="bgGray"></div>
                <div class="conteudo">			
			<h2 class="subtitle"><?php echo $tltServico; ?></h2>
			<?php
				$parceiros = Parceiro::get_parceiro(FALSE, TRUE, TRUE, '', (isset($servico) && $servico !== FALSE ? array($servico) : ''));
				if($parceiros && is_array($parceiros)) :
			?>
				<ul>
					<?php foreach($parceiros as $item) : ?>
						<li class="item">
						    <span id="title"><?php echo $item->nome; ?></span><br>
						    
						<?php if($item->descricao != NULL) : ?>
							<span id="desc"><?php echo Utility::limit_string($item->descricao, 180); ?></span><br>
						<?php endif; if($item->desconto != 0) : ?>
							Desconto oferecido: <span id="desconto"><?php echo $item->desconto; ?> de desconto</span><br>
						<?php endif; if($item->endereco != NULL) : ?>
							Endereço: <span id="endereco"><?php echo $item->endereco; ?></span><br>
						<?php endif; if($item->telefone != NULL) : ?>
							Telefone: <span id="telefone"><?php echo $item->telefone; ?></span><br>
						<?php endif; if($item->email != NULL) : ?>
							E-mail: <span id="email"><a href="mailto:<?php echo $item->email; ?>"><?php echo $item->email; ?></a></span><br>
						<?php endif; if($item->email != NULL) : ?>
							Site: <span id="site"><a href="<?php echo $item->site; ?>" target="_blank"><?php echo $item->site; ?></a></span>
						<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
        </div>     
        <div class="veja_tambem">
            <h3>Veja Também</h3>
            <div class="item">
                <h4><a href="/jogos/dicas">Dicas</a></h4>
                <div class="imagem">
                    <a href="/jogos/dicas"><img src="/_img/ft_veja_dicas.jpg" width="210" height="84" alt="Imagem 1" /></a>
                </div>
                <p><a href="/jogos/dicas">As Informações práticas que você pode precisar para aproveitar melhor os Jogos.</a></p>
            </div>
            <div class="item">
                <h4><a href="/mapa-interativo">mapa interativo</a></h4>
                <div class="imagem">
                    <a href="/mapa-interativo"><img src="/_img/ft_veja_mapa.jpg" width="210" height="84" alt="Imagem 1" /></a>
                </div>
                <p><a href="/mapa-interativo">Saiba onde estão os principais locais e pontos turísticos da cidade.</a></p>
            </div>
            <div class="item">
                <h4><a href="/cidade/cidade-sede">Cidade sede</a></h4>
                <div class="imagem">
                    <a href="/cidade/cidade-sede"><img src="/_img/ft_veja_cidade.jpg" width="210" height="84" alt="Imagem 1" /></a>
                </div>
                <p><a href="/cidade/cidade-sede">Saiba por que Mogi das Cruzes foi eleita a casa dos Jogos Abertos deste ano.</a></p>
            </div>
            
        </div>
	<br clear="all" />
    </div>
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/footer.php'); ?>
    </div>
</body>
</html>
