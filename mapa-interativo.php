<?php
	require($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
	
	if($slugMod = isset($_GET['modalidade']) && !empty($_GET['modalidade']) ? $_GET['modalidade'] : FALSE){
		$modObj = new Modalidade();
		if(!$modObj->verificar_modalidade_valida(0, $slugMod)){
			$slugMod = FALSE;
		}
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Conheça as principais localidades de Mogi das Cruzes e aproveite mais esta festa esportiva. Locais de competições, terminais rodoviários, pontos turísticos, cerimônia de abertura etc." />
<meta name="keywords" content="Mapas, maps, pontos turísticos, terminais rodoviários, locais, localidades, Interior de SP, Mogi" />
<meta name="robots" content="index,follow" />
<meta http-equiv="content-language" content="pt-br" />
<meta name="author" content="Tboom Interactive"  />
<title>Mapa Interativo | Jogos Abertos do Interior 2011 </title>
<link rel="SHORTCUT ICON" href="/_img/favicon.ico">
<link rel="stylesheet" type="text/css" href="/_css/reset.css">
<link rel="stylesheet" type="text/css" href="/_css/style.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.countdown.css">
<link rel="canonical" href="http://www.jogosabertos2011.com.br<?php echo $_SERVER['REQUEST_URI']; ?>" />
<script type="text/javascript" src="/_js/jquery.js"></script>
<script type="text/javascript" src="/_js/jquery.jparallax.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="/_js/util.js"></script>
<script type="text/javascript" src="/_js/jquery.countdown.js"></script>
<script type="text/javascript" src="/_js/functions.js"></script>
<script type="text/javascript" src="/_js/func_mapa.js"></script>
</head>

<body id="body-mapa-interativo"<?php echo $slugMod ? 'rel="' . str_replace('-', '', $slugMod) . '"' : ''; ?>>
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/bg.php'); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/header.php') ?>
    <div class="content" id="content">
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/navbar.php') ?>
        <h1 class="title">
        	<div class="sombra" style="margin:-4px 0 0 -24px; height:65px;"></div> 
        	Mapa interativo
        </h1>
        <div class="page">
        	<div class="sombra" style="height:715px; margin:-27px 0 0 -34px; *margin:-4px 0 0 -940px;"></div>
            <div class="mapa" id="mapa">
            	<img src="/_img/mapa_ilustracao.jpg" width="634" height="639" alt="Mapa" />
            </div>
            <div class="info_mapa">
            	<p>Mogi das Cruzes é a sede dos Jogos Abertos do Interior de 2011. Navegue no mapa da cidade e confira a localização de hotéis, alojamentos, pontos turísticos e muito mais.</p>
                <div class="locais" id="select-local">
                	<span>Selecione</span>
                    <form>
                        <select name="local" id="local"> 
                        <option value="selecione" label="Selecione" id="zero" selected>Selecione</option>
                        <option value="atletismo" label="Atletismo">Atletismo</option>
                        <option value="basquete" label="Basquete">Basquete</option>
                        <option value="biribol" label="Biribol">Biribol</option>
                        <option value="bocha" label="Bocha">Bocha</option>
                        <option value="boxe" label="Boxe">Boxe</option>
                        <option value="capoeira" label="Capoeira">Capoeira</option>
                        <option value="ciclismo" label="Ciclismo">Ciclismo</option>
                        <option value="damas" label="Damas">Damas</option>
                        <option value="futebol" label="Futebol">Futebol</option>
                        <option value="futsal" label="Futsal">Futsal</option>
                        <option value="ginasticaartistica" label="Ginástica Artística">Ginástica Artística</option>
                        <option value="ginasticaritmica" label="Ginástica Rítmica">Ginástica Rítmica</option>
                        <option value="handebol" label="Handebol">Handebol</option>
                        <option value="judo" label="Judô">Judô</option>
                        <option value="karate" label="Karatê">Karatê</option>
                        <option value="kickboxing" label="Kickboxing">Kickboxing</option>
                        <option value="lutaolimpica" label="Luta Olímpica">Luta Olímpica</option>
                        <option value="malha" label="Malha">Malha</option>
                        <option value="natacao" label="Natação">Natação</option>
                        <option value="taekwondo" label="Taekwondo">Taekwondo</option>
                        <option value="tenis" label="Tênis">Tênis</option>
                        <option value="tenisdemesa" label="Tênis de Mesa">Tênis de Mesa</option>
                        <option value="volei" label="Vôlei">Vôlei</option>
                        <option value="voleidepraia" label="Vôlei de Praia">Vôlei de Praia</option>
                        <option value="xadrez" label="Xadrez">Xadrez</option>
                        </select>
                    </form>
                </div>
                <ul class="pins">
                	<!--<li class="pin_alojamentos" id="alojamento">Alojamentos</li>-->
                	<li class="pin_locais" id="competicao">Locais de competição</li>
                	<li class="pin_terminais" id="onibus">Terminais de Ônibus</li>
                	<li class="pin_congresso" id="congresso">Congresso Técnico</li>
                	<li class="pin_pontos_turisticos" id="turismo">Pontos turísticos</li>
                	<li class="pin_parques" id="parque">Parques municipais</li>
                	<li class="pin_tocha" id="cerimonia">Cerimônia de abertura</li>
                	<li class="pin_trem" id="trem">Estações de trem</li>
                	<li class="pin_hospitais" id="hospital">Hospitais</li>
                	<li class="pin_pronto_atendimento" id="atendimento">Pronto Atendimento 24 horas</li>
                	<!--<li class="pin_atletismo" id="atletismo">Atletismo</li>
                	<li class="pin_hoteis" id="hotel">Hotéis</li>-->
                </ul>
           	</div>
        </div>
	<br clear="all" />
    </div>
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/footer.php'); ?>
    </div>
</body>
</html>
