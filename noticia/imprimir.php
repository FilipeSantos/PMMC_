<?php
	$idNot = (isset($_GET['id']) && !empty($_GET['id'])) ? addslashes($_GET['id']) : FALSE;
	if($idNot){
		require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/db.class.php');
		require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/functions.php');
		$conn = new DbConnect();
		
		$rs = mysql_query("select titulo, texto, date_format(dataPublicacao, '%d\/%m\/%Y') as data from noticia where id = $idNot and status = 1 and dataPublicacao <= CURRENT_TIMESTAMP limit 1;");
		if(mysql_num_rows($rs)){
			$noticia = array();
			$noticia['titulo'] = mysql_result($rs, 0, 'titulo');
			$noticia['texto'] = mysql_result($rs, 0, 'texto');
			$noticia['data'] = mysql_result($rs, 0, 'data');
			$rs = mysql_query("select a.titulo, a.permalink from categoria as a inner join noticia_categoria as b on a.id = b.idCategoria and b.idNoticia = $idNot;");
			$categorias = array();
			while($item = mysql_fetch_assoc($rs)){
				$categorias[] = $item;
			}
		}
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Organização, pré-jogos, atletas, modalidades, competições, cidades participantes e os últimos acontecimentos dos Jogos Abertos de Mogi das Cruzes. Clique aqui" />
<meta name="keywords" content="últimas, notícias, Mogi, fatos, acontecimentos, organização, JAI, Jogos, Abertos, Interior, 2011" />
<meta name="robots" content="noindex, nofollow" />
<meta http-equiv="content-language" content="pt-br" />
<meta name="author" content="Tboom Interactive"  />
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<title>Imprimir | Notícias | Jogos Abertos do Interior 2011 <?php echo $idNot ? '| ' . $noticia['titulo'] : '' ?></title>
<link rel="SHORTCUT ICON" href="/_img/favicon.ico">
<link rel="stylesheet" type="text/css" href="/_css/reset.css">
<link rel="stylesheet" type="text/css" href="/_css/style.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.countdown.css">
<link rel="stylesheet" type="text/css" href="/_css/print.css" />
<script type="text/javascript" src="/_js/jquery.js"></script>
<script type="text/javascript" src="/_js/jquery.jparallax.js"></script>
<script type="text/javascript" src="/_js/jquery.countdown.js"></script>
<script type="text/javascript" src="/_js/functions.js"></script>
<script type="text/javascript">

$(document).ready(function(){
	window.print();
});
</script>
</head>

<body>
        <div id="content_not">
		<div id="header"><img src="/_img/print_header.gif" width="100%" alt="Jogos Abertos do Interior 2011" border="0" /></div>
		<div id="post">
		<?php if($idNot && isset($noticia)) : ?>
			<?php
				$txtCategorias = '';
				foreach($categorias as $categoria){
					$txtCategorias = $txtCategorias . $categoria['titulo']  .', ';
				}
			?>
			<!-- Data -->
			<p class="data"><?php echo $noticia['data'] . ' - ' . substr($txtCategorias, 0, -2); ?></p>
			<!-- Título -->
			<h2><?php echo stripslashes($noticia['titulo']); ?></h2>
			<div class="contentNoticia">
				<?php echo stripslashes($noticia['texto']); ?>
			</div>
			<br clear="all" />
		<?php endif; ?>
		</div>
		<div id="footer"><img src="/_img/print_footer.gif" width="100%" alt="Jogos Abertos do Interior 2011" border="0" /></div>
	</div>
</body>
</html>