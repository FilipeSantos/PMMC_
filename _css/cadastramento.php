<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Jornalistas interessados em cobrir os Jogos Abertos do Interior 2011 precisam se cadastrar aqui para obterem suas credenciais." />
<meta name="keywords" content="JAI, Jogos, Abertos, Mogi, interior, olimpíadas, esporte, 2011 cadastro, jornalista, reportagem, imprensa, credencial." />
<meta name="robots" content="index,follow" />
<meta http-equiv="content-language" content="pt-br" />
<meta name="author" content="Tboom Interactive"  />
<title>Cadastramento | Jogos Abertos do Interior 2011 </title>
<link rel="SHORTCUT ICON" href="_img/favicon.ico">
<link rel="stylesheet" type="text/css" href="_css/reset.css">
<link rel="stylesheet" type="text/css" href="_css/style.css">
<link rel="stylesheet" type="text/css" href="_css/jquery.countdown.css">
<script type="text/javascript" src="_js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="_js/jquery.jparallax.js"></script>
<script type="text/javascript" src="_js/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript" src="_js/jquery.countdown.js"></script>
<script type="text/javascript" src="_js/functions.js"></script>
<script type="text/javascript">
jQuery(function($) {
		form = $('#form_contato');
		formnome = form.find('#nome');
		formemail = form.find('#email');
		formrg = form.find('#rg');
		formveiculo = form.find('#veiculo');
		formfuncao = form.find('#funcao');
		formtelefone = form.find('#telefone');
		formfax = form.find('#fax');
		formtelefone.mask("(99)9999-9999");
		formfax.mask("(99)9999-9999");
		enviarCadastro();
});
function enviarCadastro(){
	$('#enviar').click(function(){
		var resposta = false;
		var num = 0;
		$.each(form.find('input[type=text]'), function(index, value) { 
			if($(value).val() != false && $(value).val() != '' && $(value).val() != ' ' || $(value).attr('id') == 'fax'){
				resposta += true;
				$(value).css('border-color','#CBC6C6');
			}else{
				resposta += false;
				$(value).css('border-color','#FF0000');
			}
			num++;
		});
		if(num == resposta){
			// filtros
			var emailFilter=/^.+@.+\..{2,}$/;
			var illegalChars= /[\(\)\<\>\,\;\:\\\/\"\[\]]/
			// condição
			if(!(emailFilter.test(formemail.val()))||formemail.val().match(illegalChars)){
				$('#email').css('border-color','#FF0000');
			}else{
				$('.cadastramento_balao').html('<div class="loading"><img src="_img/load.gif" width="34" height="34" alt="Carregando..." /></div>').fadeIn(1000);
				loading($('.cadastramento_balao'));
				$('.cadastramento_balao').load('enviar_cadastro.php',
				   {nome:formnome.val(), email:formemail.val(), rg:formrg.val(), veiculo:formveiculo.val(), funcao:formfuncao.val(), telefone:formtelefone.val(), fax:formfax.val()}, 
				   function(){
						$("#form_contato").each(function(){
						   this.reset(); //Cada volta no laço o form atual será resetado
						});										
						enviarCadastro();
				});
			}
		}else{
			$('.cadastramento_balao').html('Preencha todos os campos corretamente.').fadeIn(1000);
		}
		return false;
	});
}
function loading(elem){
	elem.html('<div class="loading"><img src="_img/load.gif" width="34" height="34" alt="Carregando..." /></div>');
}
</script>
</head>

<body>
	<?php include('_inc/bg.php'); ?>
    <?php include('_inc/header.php') ?>
    <div class="content" id="content">
    	<?php include('_inc/navbar.php') ?>
        <h1 class="title red">
        	<div class="sombra" style="margin:-4px 0 0 -24px; height:65px;"></div> 
        	Cadastramento Imprensa
        </h1>
        <div id="content_contato">
        	<div class="collum_left">
            	<p>Cadastre-se e faça a cobertura completa da maior festa esportiva da América Latina.</p>
                <div class="border_topo"></div>
                <form action="enviar_cadastro.php" method="post" id="form_contato">
                    <ul>
                    	<li>
                            <label>Nome:</label>
                            <input type="text" name="nome" id="nome" />
                        </li>
                    	<li>
                            <label>E-mail:</label>
                            <input type="text" name="email" id="email" />
                        </li>
                    	<li>
                            <label>RG:</label>
                            <input type="text" name="rg" id="rg" />
                        </li>
                    	<li><br></li>
                    	<li>
                            <label>veículo:</label>
                            <input type="text" name="veiculo" id="veiculo" />
                        </li>
                    	<li>
                            <label>Função:</label>
                            <input type="text" name="funcao" id="funcao" />
                        </li>
                    	<li><br></li>
                    	<li>
                            <label>Telefone:</label>
                            <input type="text" name="telefone" id="telefone" />
                        </li>
                    	<li>
                            <label>fax:</label>
                            <input type="text" name="fax" id="fax" />
                        </li>
                    	<li class="btn_contato" id="btn_contato">
                          	<input type="submit" name="enviar" class="enviar" id="enviar" value="" />
                        </li>
                    </ul>
                </form>
                <div class="border_baixo"></div>
            </div>
            <div class="collum_right cadastramento">
            	<div class="cadastramento_balao">
                	Seu cadastro foi efetuado com sucesso. Em 5 dias você receberá um e-mail para a confirmação de credenciamento. Acesse releases e garanta o melhor dos Jogos Abertos do Interior 2011.
                </div>
            </div>
        </div>
    	<?php include('_inc/footer.php'); ?>
    </div>
</body>
</html>
