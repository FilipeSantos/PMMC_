<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Dúvidas sobre os Jogos Abertos do Interior 2011. Envie críticas e sugestões à organização do evento." />
<meta name="keywords" content="Mogi, atendimento, contato, Jogos, Abertos, sugestões, Mogi, Cruzes, dúvidas, telefone, linha." />
<meta name="robots" content="index,follow" />
<meta http-equiv="content-language" content="pt-br" />
<meta name="author" content="Tboom Interactive"  />
<title>Contato | Jogos Abertos do Interior 2011 </title>
<link rel="SHORTCUT ICON" href="/_img/favicon.ico">
<link rel="stylesheet" type="text/css" href="/_css/reset.css">
<link rel="stylesheet" type="text/css" href="/_css/style.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.countdown.css">
<link rel="canonical" href="http://www.jogosabertos2011.com.br<?php echo $_SERVER['REQUEST_URI']; ?>" />
<script type="text/javascript" src="/_js/jquery.js"></script>
<script type="text/javascript" src="/_js/jquery.jparallax.js"></script>
<script type="text/javascript" src="/_js/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript" src="/_js/jquery.countdown.js"></script>
<script type="text/javascript" src="/_js/functions.js"></script>
<script type="text/javascript">
jQuery(function($) {
		form = $('#form_contato');
		formnome = form.find('#nome');
		formemail = form.find('#email');
		formcidade = form.find('#cidade');
		formtelefone = form.find('#telefone');
		formmensagem = form.find('#mensagem');
		formtelefone.mask("(99)9999-9999");
		enviarCadastro();
});
function enviarCadastro(){
	$('#enviar').click(function(){
		var resposta = false;
		var num = 0;
		$.each(form.find('input[type=text]'), function(index, value) { 
			if($(value).val() != false && $(value).val() != '' && $(value).val() != ' ' || $(value).attr('id') == 'telefone'){
				resposta += true;
				$(value).css('border-color','#CBC6C6');
			}else{
				resposta += false;
				$(value).css('border-color','#FF0000');
			}
			num++;
		});
		if(formmensagem.val() == false || formmensagem.val() == '' || formmensagem.val() == ' '){
			num = num-1;
			form.find('#mensagem').css('border-color','#FF0000');
		}else{
			form.find('#mensagem').css('border-color','#CBC6C6');			
		}
		if(num == resposta){
			// filtros
			var emailFilter=/^.+@.+\..{2,}$/;
			var illegalChars= /[\(\)\<\>\,\;\:\\\/\"\[\]]/
			// condição
			if(!(emailFilter.test(formemail.val()))||formemail.val().match(illegalChars)){
				$('#email').css('border-color','#FF0000');
			}else{
				$('.msg_contato').remove();
				$('#btn_contato').html('<div class="loading"><img src="/_img/load.gif" width="34" height="34" alt="Carregando..." /></div>').fadeIn(1000);
				loading($('#btn_contatoo'));
				$('#btn_contato').load('enviar_email.php',
				   {nome:formnome.val(), email:formemail.val(), cidade:formcidade.val(), telefone:formtelefone.val(), mensagem:formmensagem.val()}, 
				   function(){
						$("#form_contato").each(function(){
						   this.reset(); //Cada volta no laço o form atual será resetado
						});												
						enviarCadastro();																						  		
				});
			}
		}else{
			$('.msg_contato').remove();
			$('#btn_contato').html('<input type="submit" class="enviar" id="enviar" value="" /><img src="/_img/btn_preencha.png" width="271" height="31" alt="Preencha todos os campos corretamente." class="msg_contato" />').fadeIn(1000);								
			enviarCadastro();
		}
		return false;
	});
}
function loading(elem){
	elem.html('<div class="loading"><img src="/_img/load.gif" width="34" height="34" alt="Carregando..." /></div>');
}
</script>
</head>

<body>
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/bg.php'); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/header.php') ?>
    <div class="content" id="content">
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/navbar.php') ?>
        <h1 class="title red">
        	<div class="sombra" style="margin:-4px 0 0 -24px; height:65px;"></div> 
        	Fale com mogi
        </h1>
        <div id="content_contato">
        	<div class="collum_left">
            	<p>Mogi das Cruzes possui um canal de atendimento especializado para os Jogos Abertos do Interior 2011. Tire dúvidas e envie suas críticas e sugestões à organização, via internet ou pelo telefone. Estamos à disposição para acolher e retornar sua mensagem.</p>
                <div class="border_topo"></div>
                <form action="enviar_email" method="post" id="form_contato">
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
                            <label>Cidade:</label>
                            <input type="text" name="cidade" id="cidade" />
                        </li>
                    	<li>
                            <label>Telefone:</label>
                            <input type="text" name="telefone" id="telefone" />
                        </li>
                    	<li>
                            <label>mensagem:</label>
                            <textarea name="mensagem" id="mensagem"></textarea>
                        </li>
                    	<li class="btn_contato" id="btn_contato">
                          	<input type="submit" class="enviar" id="enviar" value="" />
                        </li>
                    </ul>
                </form>
                <div class="border_baixo"></div>
            </div>
            <div class="collum_right">
            	<img src="/_img/info_jogosabertos_tel.png" width="307" height="373" alt="Jogos Abertos na Linha (11)4798-5167" />
            </div>
        </div>
	<br clear="all" />
    </div>
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/footer.php'); ?>
    </div>
</body>
</html>
