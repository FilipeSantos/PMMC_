<div id="result_vot" style="display:inline">
	<a href="" title="fechar" id="btn_fechar">
		<img src="/_img/btn_fechar.png" alt="Fechar" />
    </a>
	<h1>Resultado parcial da vota&ccedil;&atilde;o</h1>
	<?php
		require('class.votar.php');
		$votacao = new votar();
		$votacao->resultadoVotacao();
	?>
</div>