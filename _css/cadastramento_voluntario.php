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
<title>Cadastramento de Voluntários | Jogos Abertos do Interior 2011 </title>
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
		formtelefone = form.find('#telefone');
		formDataNasc = form.find('#data_nasc');
		formRua = form.find('#rua');
		formBairro = form.find('#bairro');
		formCidade = form.find('#cidade');
		formProfissao = form.find('#profissao');
		
		formtelefone.mask("(99)9999-9999");
		formDataNasc.mask("99/99/9999");
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

				$('html').animate({ scrollTop: $('#content_contato').offset().top }, 800);
				
				$.ajax({
						type: 'POST',
						data: $('#form_contato').serialize(),
						dataType: 'text',
						url: 'enviar_cadastro_voluntario.php',
						cache: false,
						success: function($msg){
							$('.cadastramento_balao').html($msg);
								$("#form_contato").each(function(){
							   this.reset();
							});
						}
				});
				return false;
				/*$('.cadastramento_balao').load('enviar_cadastro_voluntario.php',
				   {
						$('#form_contato').serialize()
						}, 
				   function(){
						$("#form_contato").each(function(){
						   this.reset(); //Cada volta no laço o form atual será resetado
						});										
						enviarCadastro();
				});*/
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
		Cadastramento de Voluntários
	</h1>
	
	<div class="caixa_voluntario">
		<div class="sombra" style="margin:-4px 0 0 -6px; height:205px;"></div> 
			<p>Inscreva-se aqui para o programa de voluntariado dos Jogos Abertos do Interior 2011. A data limite de cadastro é até 31 de agosto. Confira a seguir tudo o que você precisa saber sobre o nosso processo de seleção:</p>
	    <p>
		- O candidato selecionado passará por treinamento, que será oferecido pelo Comitê Organizador;<br />
		- Voluntários irão auxiliar na organização das provas dos Jogos Abertos do Interior, no período de 7 a 19 de novembro;<br />
		- Não é necessário que o voluntário trabalhe em todos os dias do evento. Isso dependerá de sua disponibilidade;<br />
		- No campo Disponibilidade de horário, marque quais os períodos do dia você pode atuar;<br />
		- Só é permitida a inscrição de pessoas maiores de 18 anos;<br />
		- O voluntário não terá vínculo empregatício ou remuneração pelo trabalho realizado;<br />
		- Estudantes universitários receberão certificado de participação contando com estágio<br />
	    </p>
	    <p><b>Agora que você já sabe como funciona, é só preencher os campos abaixo. Sua participação é fundamental para o sucesso dos Jogos Abertos.</b></p>
	</div>
	<div id="content_contato" class="voluntario">
		<div class="collum_left">
		<div class="border_topo"></div>
		<form action="enviar_cadastro_voluntario.php" method="post" id="form_contato">
		    <ul>
				<li>
				    <label>Nome:</label>
				    <input type="text" name="nome" id="nome" />
				</li>
				<li>
				    <label>Telefone:</label>
				    <input type="text" name="telefone" id="telefone" />
				</li>
				<li>
				    <label>E-mail:</label>
				    <input type="text" name="email" id="email" />
				</li>
				<li>
				    <label>Rua:</label>
				    <input type="text" name="rua" id="rua" />
				</li>
				<li>
				    <label class="s">Bairro</label>
				    <input type="text" name="bairro" id="bairro" class="s" />
				    <label class="s">Cidade</label>
				    <input type="text" name="cidade" id="cidade" class="s" />
				</li>
				<li>
				    <label>Profissão:</label>
				    <input type="text" name="profissao" id="profissao" />
				</li>
				<li>
				    <label>Data de Nasc.:</label>
				    <input type="text" name="data_nasc" id="data_nasc" />
				</li>
				<li>
				    <label class="disponibilidade">Disponibilidade de horário:</label>
				    <input type="checkbox" name="periodo[]" id="manha" class="inputCheck" value="1"><label for="manha" class="lbldisp">Manhã</label>
				    <input type="checkbox" name="periodo[]" id="tarde" class="inputCheck" value="2"><label for="tarde" class="lbldisp">Tarde</label>
				    <input type="checkbox" name="periodo[]" id="noite" class="inputCheck" value="3"><label for="noite" class="lbldisp">Noite</label>
				</li>
				<li class="itemModalidades">
				    <label class="modalidades">Modalidades preferidas:</label><br>
				    <ul class="modalidades">
					<li>
					    <input type="checkbox" id="modalidade1" name="modalidade[]" value="1"><label for="modalidade1">Futebol</label><br clear="all">
					    <input type="checkbox" id="modalidade2" name="modalidade[]" value="2"><label for="modalidade2">Vôlei</label><br clear="all">
					    <input type="checkbox" id="modalidade3" name="modalidade[]" value="3"><label for="modalidade3">Judô</label><br clear="all">
					    <input type="checkbox" id="modalidade4" name="modalidade[]" value="4"><label for="modalidade4">Boxe</label><br clear="all">
					    <input type="checkbox" id="modalidade5" name="modalidade[]" value="5"><label for="modalidade5">Basquete</label><br clear="all">
					    <input type="checkbox" id="modalidade6" name="modalidade[]" value="6"><label for="modalidade6">Handebol</label><br clear="all">
					    <input type="checkbox" id="modalidade7" name="modalidade[]" value="7"><label for="modalidade7">Natação</label><br clear="all">
					    <input type="checkbox" id="modalidade8" name="modalidade[]" value="8"><label for="modalidade8">Luta Olímpica</label><br clear="all">
					    <input type="checkbox" id="modalidade9" name="modalidade[]" value="9"><label for="modalidade9">Tênis de mesa</label><br clear="all">
					</li>
					<li>
					    <input type="checkbox" id="modalidade10" name="modalidade[]" value="10"><label for="modalidade10">Ciclismo</label><br clear="all">
					    <input type="checkbox" id="modalidade11" name="modalidade[]" value="11"><label for="modalidade11">Ginástica rítmica</label><br clear="all">
					    <input type="checkbox" id="modalidade12" name="modalidade[]" value="12"><label for="modalidade12">Ginástica Artística</label><br clear="all">
					    <input type="checkbox" id="modalidade13" name="modalidade[]" value="13"><label for="modalidade13">Tênis</label><br clear="all">
					    <input type="checkbox" id="modalidade14" name="modalidade[]" value="14"><label for="modalidade14">Biribol</label><br clear="all">
					    <input type="checkbox" id="modalidade15" name="modalidade[]" value="15"><label for="modalidade15">Capoeira</label><br clear="all">
					    <input type="checkbox" id="modalidade16" name="modalidade[]" value="16"><label for="modalidade16">Taekwondo</label><br clear="all">
					    <input type="checkbox" id="modalidade17" name="modalidade[]" value="17"><label for="modalidade17">Malha</label><br clear="all">
					</li>
					<li>
					    <input type="checkbox" id="modalidade18" name="modalidade[]" value="18"><label for="modalidade18">Bocha</label><br clear="all">
					    <input type="checkbox" id="modalidade19" name="modalidade[]" value="19"><label for="modalidade19">Damas</label><br clear="all">
					    <input type="checkbox" id="modalidade20" name="modalidade[]" value="20"><label for="modalidade20">Atletismo</label><br clear="all">
					    <input type="checkbox" id="modalidade21" name="modalidade[]" value="21"><label for="modalidade21">Vôlei de praia</label><br clear="all">
					    <input type="checkbox" id="modalidade22" name="modalidade[]" value="22"><label for="modalidade22">Xadrez</label><br clear="all">
					    <input type="checkbox" id="modalidade23" name="modalidade[]" value="23"><label for="modalidade23">Futsal</label><br clear="all">
					    <input type="checkbox" id="modalidade24" name="modalidade[]" value="24"><label for="modalidade24">Karatê</label><br clear="all">
					    <input type="checkbox" id="modalidade25" name="modalidade[]" value="25"><label for="modalidade25">Kickboxing</label><br clear="all">
					</li>
								</ul>
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
			Obrigado por vestir a camisa dos Jogos Abertos. Estamos torcendo pra você entrar em campo com a gente, em breve.
		</div>
	    </div>
	</div>
	<?php include('_inc/footer.php'); ?>
    </div>
</body>
</html>