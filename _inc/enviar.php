<?php
	require('class.votar.php');
    $votacao = new votar();
	$votacao->votarMascote($_POST);
?>