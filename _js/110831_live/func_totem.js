jQuery(function($) {
	$('.escolha').bind('click',function(){
		mascote = $(this).attr('id');
		$('.masc_perfil').find('img').attr('src','_img/ft_perfil_'+mascote+'.png');
		$('.step1').css('position','absolute').animate({left:-976});
		$('.step2').find('form').css('display','none');
		$('#form_'+mascote).css('display', 'inline');
	});
	
	$('#mascote1_1a').bind('click', function(){ $('#mascote1_1').attr('checked',true); });
	$('#mascote1_2a').bind('click', function(){ $('#mascote1_2').attr('checked',true); });
	$('#mascote1_3a').bind('click', function(){ $('#mascote1_3').attr('checked',true); });
	$('#mascote2_1a').bind('click', function(){ $('#mascote2_1').attr('checked',true); });
	$('#mascote2_2a').bind('click', function(){ $('#mascote2_2').attr('checked',true); });
	$('#mascote2_3a').bind('click', function(){ $('#mascote2_3').attr('checked',true); });
	
	$('#votar').bind('click',function(){
		inputSelecionado = $('input[name='+mascote+']:checked').val();
		$('.step2').css('position','absolute').animate({left:-976});
		$('.mensagem').load('_inc/enviar.php',{tipo:mascote, nomemascote:inputSelecionado}, function() {
			//$('.mensagem').text('Obrigado! Você fez uma ótima escolha.<br> Agora convide seus amigos para votar <br>também e fique na torcida.');
		});
	});
	$('#ver_resultado').bind('click',function(){
		$('body').append('<div id="fundo_light"></div><div id="resultado"></div>');
		$('#fundo_light').animate({opacity:0.8}).fadeIn('slow').css('background','#000');
		$('#resultado').load('_inc/resultado.php', function(){
			$('#fundo_light').click(function(){
				$('#fundo_light, #resultado').remove();
			});
			$('#btn_fechar').click(function(){
				$('#fundo_light, #resultado').remove();
			});
		});
		return false;
	});
	
	$('#votar_novamente').bind('click',function(){ location.reload(); });
	
	if($('#carousel').length){
		/*var largura = 0;
		num = $('#carousel ul li').length;
		largCar = $('#carousel').width();
		$('#carousel ul').width(largCar*num);*/
		$("#carousel").jCarouselLite({
			btnNext: ".next",
			btnPrev: ".prev",
			visible: 1,
			auto: 2000,
			speed: 1000,
			mouseWheel: true
		});
	}
	$('.noticias_home').find('.noticia').eq(0).addClass('primeiro');
	$('.noticias_home').find('.noticia').eq(3).addClass('ultimo');
	$(".ver_galeria").hover(
	  function () {
		$(this).addClass('ativo');
	  }, 
	  function () {
		$(this).removeClass('ativo');
	  }
	);
	
});