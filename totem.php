<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Site oficial dos Jogos Abertos do Interior 2011. Notícias, fotos, vídeos e tudo sobre as disputas, competidores e delegações" />
<meta name="keywords" content="JAI, Jogos, Abertos, Mogi, interior, olimpíadas, esporte, 2011, competição, campeonato, atletismo" />
<meta name="robots" content="index,follow" />
<meta http-equiv="content-language" content="pt-br" />
<meta name="author" content="Tboom Interactive"  />
<meta property="og:type" content="website" />
<meta property="og:url" content="http://www.jogosabertos2011.com.br" />

<?php
	if(isset($_GET['mascote'])){
		$imagem = $_GET['mascote'];
		echo '<meta property="og:title" content="Na primeira disputa dos Jogos Abertos do Interior você decide o vencedor" />
			<meta property="og:description" content="Acabei de votar no meu mascote favorito para os Jogos Abertos do Interior de 2011. Escolha você também, e ajude Mogi das Cruzes a decidir esta disputa."/>
			<meta property="og:image" content="http://www.jogosabertos2011.com.br/_img/ft_'.$imagem.'_face.jpg" />';
	}else{
		echo '<meta property="og:title" content="Mogi das Cruzes – Cidade-sede dos Jogos Abertos do Interior 2011" />
<meta property="og:description" content="O maior evento esportivo da América Latina está on-line e mais interativo. Acesse notícias, vídeos e conteúdos completos sobre os Jogos Abertos. Navegue no melhor do esporte, agora e sempre."/>';
	}
?>
<meta property="og:site_name" content="Jogos Abertos do Interior - Mogi das Cruzes" />
<title>Jogos Abertos do Interior de 2011</title>
<link rel="SHORTCUT ICON" href="_img/favicon.ico">
<link rel="stylesheet" type="text/css" href="/_css/reset.css">
<link rel="stylesheet" type="text/css" href="/_css/style.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.countdown.css">
<script type="text/javascript" src="/_js/jquery.js"></script>
<script type="text/javascript" src="/_js/jquery.jparallax.js"></script>
<script type="text/javascript" src="/_js/jcarousellite_1.0.1.min.js"></script>
<script type="text/javascript" src="/_js/jquery.countdown.js"></script>
<script type="text/javascript" src="/_js/functions.js"></script>
<script type="text/javascript" src="/_js/func_totem.js"></script>
</head>

<body class="totem">
    <div id="header">
    	<div class="logo_jogos">
        	<h1><a href="/totem.php" title="Home | Jogos Abertos do Interior 2011 "><img src="/_img/logo_mogi.png" width="329" height="110" alt="Mogi das Cruzes - Jogos abertos 2011" /></a></h1>
        </div>
    	<div class="logo_mogi">
        	<a href="http://www.mogidascruzes.sp.gov.br" target="_blank"><img src="/_img/logo_mogi_header.png" width="117" height="97" alt="Mogi das Cruzes" /></a>
        </div>
        <div class="contador">
        	<h3>JOGOS ABERTOS</h3>
            <div class="sentence">faltam</div>
            <div class="time" id="time">
            	<div class="number" id="contador">0</div>
                <div class="number" id="contador">2</div>
                <div class="number" id="contador">0</div>
                <div class="number" id="contador">1</div>
            </div>
            <div class="sentence">dias</div>
            <span style="display:inline-block;text-align:center;text-transform:uppercase;font-size: 12px;margin-top: 5px;">de 7 a 19 de novembro</span>
        </div>
    </div>
    <div class="content" id="content">
    	
        <div class="votation">
        	<div class="sombra" style="height:313px"></div>
            <ul>
            	<li class="step1" style="display:block;">
                	<p class="info">Defina qual destes personagens representará os Jogos Abertos realizados em Mogi. </p>
                    <div class="mascote_op1">
                    	<img src="/_img/ft_mascote1.png" width="257" height="244" alt="Mascote 1" align="left" />
                        <input class="escolha" id="mascote1" type="button" alt="Escolher mascote 1" />
                    </div>
                    <div class="mascote_op2">
                    	<img src="/_img/ft_mascote2.png" width="257" height="257" alt="Mascote 2" align="left" />
                        <input class="escolha" id="mascote2" type="button" alt="Escolher mascote 2" />
                    </div>
                </li>
            	<li class="step2" style="display:block;">
                	<div class="seta" style="margin-left:68px;"></div>
                    <p class="info1">Agora, <br>dê um nome a ele<br>e confirme seu voto</p>
                	<div class="seta" style="margin-left:19px;"></div>
                    <form id="form_mascote1">
                        <a href="#" id="mascote1_1a">
                            <input type="radio" name="mascote1" value="Mogianinho" id="mascote1_1" checked><span>Mogianinho</span>
                        </a><br />
                        <a href="#" id="mascote1_2a">
            	            <input type="radio" name="mascote1" value="Mogianito" id="mascote1_2"><span>Mogianito</span><br />
                        </a><br />
                        <a href="#" id="mascote1_3a">
        	                <input type="radio" name="mascote1" value="Atletinha" id="mascote1_3"><span>Atletinha</span>
                        </a><br />
                    </form>
                    <form id="form_mascote2">
                        <a href="#" id="mascote2_1a">
                            <input type="radio" name="mascote2" value="Caquito" id="mascote2_1" checked><span>Caquito</span><br />
                        </a><br />
                        <a href="#" id="mascote2_2a">
	                        <input type="radio" name="mascote2" value="Caco" id="mascote2_2"><span>Caco</span><br />
                        </a><br />
                        <a href="#" id="mascote2_3a">
    	                    <input type="radio" name="mascote2" value="Cacolino" id="mascote2_3"><span>Cacolino</span>
                        </a><br />
                    </form>
                	<div class="seta"></div>
                    <div class="votar" id="votar"></div>
                </li>
                <li class="step3">
                	<div class="masc_perfil">
                    	<img src="/_img/ft_perfil_mascote1.png" width="191" height="194" alt="Mascote 1" />
                    </div>
                    <div class="mensagem">
                    	Obrigado! Você fez uma ótima escolha. <br>Agora convide seus amigos para votar <br>também e fique na torcida.
                    </div>
                    <div class="botoes">
                        <a href="#" id="ver_resultado"><img src="/_img/btn_resultado.png" width="262" height="52" align="left" alt="Veja o resultado parcial" /></a>
                        <a href="#" id="votar_novamente"><img src="/_img/btn_votar_novamente.png" width="186" height="52" align="left" alt="Votar novamente" /></a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</body>
</html>
