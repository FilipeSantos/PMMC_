$(document).ready(function(){
    
    $('#tbVotos tr:even td, .tbVotos tr:even td').addClass('even');
    
    $('.tbClass tr').hover(function(){
	$(this).find('td').addClass('tdHover');
    }, function(){
	$(this).find('td').removeClass('tdHover');
    });
    
    $('a.btnNotAprovar').live('click', function(){
	var $this = $(this);
	var $id = $this.attr('href').split('#')[1];
	$.ajax({
	    type: 'POST',
	    data: 'id=' + $id,
	    dataType: "json",
	    url: '/admin/_func/noticia-aprovar.php',
	    success: function(json){
		if(json.status == "ok"){
		    $this.remove();
		    alert('Notícia aprovada!');
		} else {
		    alert('Ocorreu um erro. Tente novamente.');
		}
	    },
	    error: function(XMLHttpRequest, textStatus, errorThrown){
		alert('Ocorreu um erro. Tente novamente.');
	    }
	});
	return false;
    });
    
    $('#dataPub, .inputDate').datetimepicker({
	    timeOnlyTitle: 'Horário de publicação',
	    timeText: 'Horário',
	    hourText: 'Hora',
	    minuteText: 'Minuto',
	    secondText: 'Segundo',
	    currentText: 'Agora',
	    closeText: 'Ok',
	    prevText: 'Mês anterior',
	    nextText: 'Próximo mês',
	    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
	    dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
	    dateFormat: 'dd/mm/yy'
	});
    
    $('.itemFirstGal').each(function(){
	var $item = $('input:radio:checked').attr('id');
	if(typeof $item != 'undefined'){
	    $('.itemCont .itemRequired').removeClass('required');
	    $('#box' + $item + ' .itemRequired').addClass('required');
	    $('#box' + $item + ', .contGaleriaRight').fadeIn();
	}
    });

    $('.itemFirstGal input:radio').click(function(){
	var $alvo = '#box' + $(this).attr('id');
	$('.boxContainerGal .itemCont').hide();
	$('.itemRequired').each(function(){
	    $(this).removeClass('required error valid');
	    $('label[for="' + $(this).attr('id') + '"]').removeClass('error valid');
	});
	$('.itemCateg label').removeClass('error');
	
	$($alvo).find('.itemRequired').addClass('required');
	$('.itemCateg .itemRequired').addClass('required');
	$('#formCadastrarGaleria').each(function(){
	    this.reset();
	});
	$('.boxMsg p').hide();
	
	$($alvo).fadeIn();
	if($(this).attr('id') == 'TipoVideo'){
	    $('.itemHideVideo').hide();
	    $('.itemUrlVideo').show();
	    $('.contGaleriaRight').hide();
	} else {
	    $('.contGaleriaRight').hide().fadeIn();
	}
	$(this).attr('checked','checked');
    });

    $('a#btnLoadVideo').live('click', function(){
	var $url = $.trim($('#youtubeUrl').val());
	$url = urlencode($url);
	if($url){
	    $('.contLoadingInsertVideo .boxLoading').fadeIn('fast');
	    $.ajax({
		type: 'POST',
		data: 'url=' + $url,
		dataType: "json",
		cache: false,
		url: '/admin/_func/galeria-getVideoInfo.php',
		success: function(json){
		    $('.contLoadingInsertVideo .boxLoading').hide();
		    if(typeof json.status != 'undefined' && json.status == 'ok'){
			var $alvo = $('.itemHideVideo');
			
			var $id = json.id;
			var $titulo = stripslashes(urldecode(json.titulo));
			var $data = stripslashes(json.data);
			var $descricao = stripslashes(urldecode(json.descricao));
			var $thumb1 = stripslashes(json.thumb1);
			var $thumb2 = stripslashes(json.thumb2);
			var $thumb3 = stripslashes(json.thumb3);
			
			$('.itemUrlVideo').hide();
			$('#infoUrlVideo').html('<iframe width="350" height="287" src="http://www.youtube.com/embed/' + $id + '" frameborder="0" allowfullscreen></iframe>');
			$alvo.find('#idVideo').val($id);
			$alvo.find('#tituloVideo').val($titulo);
			$alvo.find('#descricaoVideo').val($descricao);
			$alvo.find('#dataVideo').val($data);
			$alvo.find('#thumbVideoYb1').html('<img src="' + $thumb1 + '" alt="Thumb" />');
			$alvo.find('#thumbVideoYb2').html('<img src="' + $thumb2 + '" alt="Thumb" />');
			$alvo.find('#thumbVideoYb3').html('<img src="' + $thumb3 + '" alt="Thumb" />');
			$alvo.find('#inputThumbVideo').val($thumb1);
			
			$alvo.fadeIn('slow');
			$('.contGaleriaRight').fadeIn();
		    }
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
		    $('.contLoadingInsertVideo .boxLoading').hide();
		    alert('Ocorreu um erro. Tente novamente.');
		}
	    });
	}
	return false;
    });
    
    $('a#btnAltVideo').live('click', function(){
	var $alvo = $('.itemHideVideo');
	$alvo.hide();
	$('.contGaleriaRight').hide();
	$alvo.find('input:text').val('');
	$alvo.find('#idVideo').val('');
	$alvo.find('textarea').text('');
	$('#youtubeUrl').val('');
	$('.itemUrlVideo').fadeIn();
	return false;
    });
    
    $('.jcropContentThumbVideo').each(function(){
	var $active = $('ul#listImgYb li').index($('ul#listImgYb li.showItem'));
	if($active == 0){
	    $('.btnNavPrev').addClass('btnDisabled');
	} else if($active == 2){
	    $('.btnNavNext').addClass('btnDisabled');
	}
    });

    $('.jcropContentThumbVideo span.btnNav').live('click', function(){
	var $this = $(this);
	if(!($this.hasClass('btnDisabled'))){
	    var $active = $('ul#listImgYb li').index($('ul#listImgYb li.showItem'));
	    var $total = $('ul#listImgYb li').size();
	    if($this.hasClass('btnNavPrev') && $active){
		$('ul#listImgYb li').eq($active--).removeClass('showItem').prev().addClass('showItem');
	    } else if($this.hasClass('btnNavNext') && $active < ($total - 1)){
		$('ul#listImgYb li').eq($active++).removeClass('showItem').next().addClass('showItem');
	    }
	    
	    $('.btnNav').removeClass('btnDisabled');
	    if($active == 0){
		$('.btnNavPrev').addClass('btnDisabled');
	    } else if($active == ($total - 1)){
		$('.btnNavNext').addClass('btnDisabled');
	    }
	    
	    $('#inputThumbVideo').val($('ul#listImgYb li.showItem img').attr('src'));
	}
    });
    
    $("#tags").fcbkcomplete({
	json_url: '/admin/_func/getTags.php',
	complete_text: 'Digite a tag e tecle \'Enter\'...',
	addontab: true,
	maxitems: 2,
	height: 10,
	newel: true,
	filter_case: false,
	delay: 0,
	cache: true
    });
    
    $('input.maininput').live('focus', function(){
	$('.facebook-auto').fadeIn('fast');
    });
    
    $('#categoriaTemp').keypress(function(event){
	if(event.which == 13){
	    $('a#btnAddCateg').click();
	    return false;
	}
    });
    
    $('#youtubeUrl').keypress(function(event){
	if(event.which == 13){
	    $('a#btnLoadVideo').click();
	    return false;
	}
    });
    
    $('a#btnAddCateg').click(function(){
	$this = $(this);
	var $categOrigin = $('#categoriaTemp').val();
	var $categ = $.trim($categOrigin.toLowerCase());
	if($categ && $categ != ''){
	    
	    if($(this).hasClass('addServico')){
		var $url = 'servico.php';
	    } else {
		var $url = 'categoria.php';
	    }
	    
	    $.ajax({
		type: 'POST',
		data: 'categ=' + $('#categoriaTemp').val(),
		dataType: 'json',
		url: '/admin/_func/' + $url,
		success: function($json){
		    if($json.status == 'ok'){
			var $dataCateg = $('.listCateg ul').clone();
			var $insert = false;
			if($dataCateg.find('li').size()){
			    for($i=0; $i < $dataCateg.find('li').size(); $i++){
				var $nomeItem = $dataCateg.find('li').eq($i).find('label').text().toLowerCase();
				if($categ < $nomeItem){
				    $insert = true;
				    if($this.hasClass('addServico')){
					$('.listCateg ul li').eq($i).before('<li><input type="checkbox" name="servico[]" id="servico' + $json.id + '" \
					    value="' + $json.id + '" class="check required" checked="checked" />\
					    <label for="servico' + $json.id + '">' + $categOrigin + '</label>&nbsp;&nbsp;&nbsp;<a href="#' + $json.id + '" class="excluirCateg excluirServico">[Excluir]</a><br clear="all" /></li>');
				    } else {
					$('.listCateg ul li').eq($i).before('<li><input type="radio" name="categoria[]" id="categoria' + $json.id + '" \
					    value="' + $json.id + '" class="check required" checked="checked" />\
					    <label for="categoria' + $json.id + '">' + $categOrigin + '</label>&nbsp;&nbsp;&nbsp;<a href="#' + $json.id + '" class="excluirCateg">[Excluir]</a><br clear="all" /></li>');
				    }
				    break;
				}
			    }
			}
			if(!($insert)){
			    if($this.hasClass('addServico')){
				$('.listCateg ul').append('<li><input type="checkbox" name="servico[]" id="servico' + $json.id + '" \
					value="' + $json.id + '" class="check required" checked="checked" />\
					<label for="servico' + $json.id + '">' + $categOrigin + '</label><a href="#' + $json.id + '" class="excluirCateg excluirServico">[Excluir]</a><br clear="all" /></li>');
			    } else {
				$('.listCateg ul').append('<li><input type="radio" name="categoria[]" id="categoria' + $json.id + '" \
				    value="' + $json.id + '" class="check required" checked="checked" />\
				    <label for="categoria' + $json.id + '">' + $categOrigin + '</label><a href="#' + $json.id + '" class="excluirCateg">[Excluir]</a><br clear="all" /></li>');
			    }
			}
			$('#categoriaTemp').val('');
		    } else if($json.status == 'existe'){
			$('.listCateg ul li input[value="' + $json.id + '"]').attr('checked','checked');
			$('#categoriaTemp').val('');
		    }
		}
	    });	   
	}
	return false; 
    });
    
    $('a.excluirCateg').live('click', function(){
	var $id = $(this).attr('href').split('#')[1];
	$(this).parent().addClass('item-excluir');
	$(this).prev().addClass('item-excluir');
	
	if($(this).hasClass('excluirServico')){
	    var $url = 'servico-excluir.php';
	} else {
	    var $url = 'categoria-excluir.php';
	}
       
       $.ajax({
	    type: 'POST',
	    data: 'categ=' + $id,
	    dataType: 'json',
	    url: '/admin/_func/' + $url,
	    success: function($json){
		if($json.status == 'ok'){
		    $('.listCateg ul li.item-excluir').fadeOut('slow', function(){
			$(this).remove();
		    });
		} else {
		    $('.listCateg ul li.item-excluir').removeClass('item-excluir');
		}
	    }
       });
       return false; 
    });
    
    $('#formCadastrarGaleria').validate({ errorContainer: $('.boxMsg p.error'), errorLabelContainer: $('#msgLabel'),
	submitHandler: function() {
	    $('.boxMsg p').hide();
	    $('.boxLoading').fadeIn('fast');
	    $wait = setTimeout(function(){
		$.ajax({
		    type: "POST",
		    data: $("#formCadastrarGaleria").serialize(),
		    dataType: "json",
		    url: $("#formCadastrarGaleria").attr('action'),
		    success: function(json){
			if(json.status == "ok"){
			    if($('#idCategoria').length){
				var $msg = 'Dados alterados com sucesso!';
			    } else {
				var $msg = 'Item cadastrado com sucesso!';
			    }
			    alert($msg);
			    window.location = '/admin/galeria-multimidia.php';
			} else if(json.status == "erro") {
			    $('.boxLoading, .boxMsg p').hide();
			    $('.boxMsg p.errorServer').fadeIn('fast');			   
			    $wait = setTimeout(function(){
				$('.boxMsg p').fadeOut('fast');
			    }, 5000);
			}
		    },
		    error: function(XMLHttpRequest, textStatus, errorThrown){
			$('.contLoadingGaler, .boxMsg p').hide();
			$('.boxMsg p.errorServer').fadeIn('fast');			   
			$wait = setTimeout(function(){
			    $('.boxMsg p').fadeOut('fast');
			}, 5000);
		    }
		});
	    }, 1000);
	},
	highlight: function(element, errorClass, validClass) {
	    if (element.type === 'radio' || element.type === 'checkbox') {
		$('label[for="' + $(element).attr('name').replace('[]','') + '"]').addClass(errorClass).removeClass(validClass);
		$('input[name="' + $(element).attr('name') + '"]').next().addClass(errorClass).removeClass(validClass);
		$('input[name="' + $(element).attr('name') + '"]').addClass(errorClass).removeClass(validClass);
	    } else if(element.type === 'textarea'){
		$('iframe#' + $(element).attr('id')  + '_ifr').addClass(errorClass).removeClass(validClass);
		$(element).prev().addClass(errorClass).removeClass(validClass);
	    } else {
		$(element).addClass(errorClass).removeClass(validClass);
		$(element).prev().addClass(errorClass).removeClass(validClass);
	    }
	},
	unhighlight: function(element, errorClass, validClass) {
	    if (element.type === 'radio' || element.type === 'checkbox') {
		$('label[for="' + $(element).attr('name').replace('[]','') + '"]').removeClass(errorClass).addClass(validClass);
		$('input[name="' + $(element).attr('name') + '"]').next().removeClass(errorClass).addClass(validClass);
		$('input[name="' + $(element).attr('name') + '"]').removeClass(errorClass).addClass(validClass);
	    } else if(element.type === 'textarea'){
		$('iframe#' + $(element).attr('id')  + '_ifr').removeClass(errorClass).addClass(validClass);
		$(element).prev().removeClass(errorClass).addClass(validClass);
	    } else {
		$(element).removeClass(errorClass).addClass(validClass);
		$(element).prev().removeClass(errorClass).addClass(validClass);
	    }
	}
    });
    
    $('#formCadastrarParceiro').validate({ errorContainer: $('.boxMsg p.error'), errorLabelContainer: $('#msgLabel'),
	submitHandler: function() {
	    $('.boxMsg p').hide();
	    $('#boxLoading').fadeIn('fast');
	    $wait = setTimeout(function(){
		$.ajax({
		    type: "POST",
		    data: $("#formCadastrarParceiro").serialize(),
		    dataType: "json",
		    url: $("#formCadastrarParceiro").attr('action'),
		    success: function(json){
			if(json.status == "ok"){
			    if($('#idParceiro').length){
				var $msg = 'Dados alterados com sucesso!';
			    } else {
				var $msg = 'Item cadastrado com sucesso!';
			    }
			    alert($msg);
			    window.location = '/admin/parceiros-e-descontos.php';
			} else if(json.status == "erro") {
			    $('#boxLoading, .boxMsg p').hide();
			    $('.boxMsg p.errorServer').fadeIn('fast');			   
			    $wait = setTimeout(function(){
				$('.boxMsg p').fadeOut('fast');
			    }, 5000);
			}
		    },
		    error: function(XMLHttpRequest, textStatus, errorThrown){
			$('#boxLoading, .boxMsg p').hide();
			$('.boxMsg p.errorServer').fadeIn('fast');			   
			$wait = setTimeout(function(){
			    $('.boxMsg p').fadeOut('fast');
			}, 5000);
		    }
		});
	    }, 1000);
	},
	highlight: function(element, errorClass, validClass) {
	    if (element.type === 'radio' || element.type === 'checkbox') {
		$('label[for="' + $(element).attr('name').replace('[]','') + '"]').addClass(errorClass).removeClass(validClass);
		$('input[name="' + $(element).attr('name') + '"]').next().addClass(errorClass).removeClass(validClass);
		$('input[name="' + $(element).attr('name') + '"]').addClass(errorClass).removeClass(validClass);
	    } else if(element.type === 'textarea'){
		$('iframe#' + $(element).attr('id')  + '_ifr').addClass(errorClass).removeClass(validClass);
		$(element).prev().addClass(errorClass).removeClass(validClass);
	    } else {
		$(element).addClass(errorClass).removeClass(validClass);
		$(element).prev().addClass(errorClass).removeClass(validClass);
	    }
	},
	unhighlight: function(element, errorClass, validClass) {
	    if (element.type === 'radio' || element.type === 'checkbox') {
		$('label[for="' + $(element).attr('name').replace('[]','') + '"]').removeClass(errorClass).addClass(validClass);
		$('input[name="' + $(element).attr('name') + '"]').next().removeClass(errorClass).addClass(validClass);
		$('input[name="' + $(element).attr('name') + '"]').removeClass(errorClass).addClass(validClass);
	    } else if(element.type === 'textarea'){
		$('iframe#' + $(element).attr('id')  + '_ifr').removeClass(errorClass).addClass(validClass);
		$(element).prev().removeClass(errorClass).addClass(validClass);
	    } else {
		$(element).removeClass(errorClass).addClass(validClass);
		$(element).prev().removeClass(errorClass).addClass(validClass);
	    }
	}
    });
    
    $('input:text.url').focus(function(){
	if($(this).val().indexOf('http://') != 0){
	    $(this).val('http://' + $(this).val());
	}
    }).blur(function(){
	if($.trim($(this).val()) == 'http://'){
	    $(this).val('');
	} else if($.trim($(this).val()).indexOf('http://') != 0){
	    $(this).val('http://' + $(this).val());
	}
    });
    
    $('a#btnAddCidadeMod').live('click', function(){
	var $index = parseInt($(this).attr('href').split('#')[1]);
	$(this).attr('href', '#' + ($index + 1));
	var $content = $('#itemCopyOrigin').clone();
	$('#itemCopyOrigin').removeAttr('id');
	$content.find('input, select').each(function(){
	    var $baseName = $(this).attr('name').split('[')[0];
	    var $baseId = $(this).attr('id').split('-')[0];
	    $(this).attr('name', $baseName + '[' + $index + ']');
	    $(this).attr('id', $baseId + '-' + $index);
	});
	$content.attr('id', 'itemCopyOrigin');
	$('.itensCopy:last').after($content);
	return false;
    });

    if($('body').hasClass('noticia')){
	
	loadImage(['/img/loading.gif', '/img/loading2.gif', '/img/loading3.gif', '/admin/img/loading.gif', '/admin/img/loading2.gif', '/admin/img/loading3.gif']);

	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",
		
		// Theme options
		theme_advanced_buttons1 : "image,|,formatselect,|,bold,italic,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,|,blockquote,|,link",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		document_base_url : 'http://' + window.location.hostname + '/',
		relative_urls : false,

		// Example content CSS (should be your site CSS)
		content_css : "/admin/css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		]
	});

	$('a.fancybox').click(function(){
	    if(!($(this).hasClass('btnDisabled'))){
		var $urlImg = $(this).attr('href');
		$.fancybox({ 'href': $urlImg, 'transitionIn': 'elastic', 'transitionOut': 'elastic', 'speedIn': 500, 'speedOut': 300, 'padding': 5, 'margin': 20, 'overlayOpacity': 0.70, 'overlayColor': '#FFFFFF' });
	    }	    
	    return false;
	});
	
	$('.imgUpload').each(function(){
	    $id = '#' + $(this).attr('id');
	    createUploadifyInstance($id, false);
	});
	
	$('a.btnActImg').live('click', function(){
	    $tipoImg = $(this).parents('.item').attr('rel');
	    var $img = $(this).attr('href').split('?')[0];
	    if($(this).attr('id') == 'btnSalvarImg'){
		var $item = $(this).parent();
		var $parent = $(this).parents('.item');		
		var $info = 'x=' + $item.find('.x').val();
		var $info = $info + '&y=' + $item.find('.y').val();
		var $info = $info + '&x2=' + $item.find('.x2').val();
		var $info = $info + '&y2=' + $item.find('.y2').val();
		var $info = $info + '&w=' + $item.find('.w').val();
		var $info = $info + '&h=' + $item.find('.h').val();
		$item.find('.boxImg').append('<div class="contentSave" style="width:\
					     ' + $item.find('.boxImg .jcrop-holder img').width() + 'px; height: ' + $item.find('.boxImg .jcrop-holder img').height() + 'px">\
					    <div class="bg">&nbsp;</div><img src="/admin/img/loading3.gif" alt="Carregando" /><p style="width:\
					     ' + $item.find('.boxImg .jcrop-holder img').width() + 'px;">Salvando Imagem...</p></div>');
		$.ajax({
		    type: 'POST',
		    data: 'url=' + $img + '&acao=salvar&tipo=' + $tipoImg + '&' + $info,
		    dataType: 'json',
		    url: '/admin/_func/imagem.php',
		    success: function($json){
			$('.boxItensDestaq').fadeTo(1, 1.0);
			$('.boxItensDestaq select').removeAttr('disabled');
			$('a.fancybox').removeClass('btnDisabled');
			if($json.status == 'ok'){
			    loadImage([$json.url + '?' + new Date().getTime()], 'salvar', $parent.find('.jcropContent'));
			}
		    }
		});
	    } else if($(this).attr('id') == 'btnExcluirImg'){
		var $box = $(this).parent();
		var $editItem = ($('input#tipoNoticia').val() == 'editar') ? $('input#tipoNoticia').attr('rel') : '';
		if(window.confirm('Tem certeza que deseja excluir essa imagem?')){
		    $box.parents('.item').find('.imgUploadUrl').val('');
		    $.ajax({
			type: 'POST',
			data: 'url=' + $img + '&acao=deleteTemp&idNoticia=' + $editItem + '&tipoImg=' + $tipoImg,
			dataType: 'json',
			url: '/admin/_func/imagem.php'
		    });
		    $box.fadeOut(function(){
			$('.boxItensDestaq').fadeTo(1, 0.5);
			$('.boxItensDestaq select').each(function(){
			    $(this).attr('disabled', 'disabled'); 
			    $(this).find('option:first').attr('selected', 'selected');
			});
			$('a.fancybox').addClass('btnDisabled');
			$box.remove();
		    });
		}		
	    }	    
	    return false;
	});
	
	$('#formNoticia').validate({ errorContainer: $('.boxMsgError p.msgError'), errorLabelContainer: $('#msgLabel'),
	    submitHandler: function() {
		$('.boxMsgError p').hide();
		$('.loadingNot').css({ 'visibility': 'visible' }).hide().fadeIn();
		$wait = setTimeout(function(){
		    $.ajax({
			type: "POST",
			data: $("#formNoticia").serialize(),
			dataType: "json",
			url: $("#formNoticia").attr('action'),
			success: function(json){
			    $('.loadingNot').css({ 'visibility': 'hidden' });
			    if(json.status == "ok"){
				alert('Notícia salva com sucesso!');
				window.location = "/admin/noticias.php";				
			    } else {
				$('p.msgErrorServer').fadeIn('fast');
				$wait = setTimeout(function(){
				    $('p.msgErrorServer').fadeOut('fast');
				}, 10000);
			    }
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
			    $('.loadingNot').css({ 'visibility': 'hidden' });
			    $('p.msgErrorServer').fadeIn('fast');
			    $wait = setTimeout(function(){
				$('p.msgErrorServer').fadeOut('fast');
			    }, 10000);
			}
		    });
		}, 1000);
	    },
	    highlight: function(element, errorClass, validClass) {
		if (element.type === 'radio' || element.type === 'checkbox') {
		    $('label[for="' + $(element).attr('name').replace('[]','') + '"]').addClass(errorClass).removeClass(validClass);
		    $('input[name="' + $(element).attr('name') + '"]').next().addClass(errorClass).removeClass(validClass);
		    $('input[name="' + $(element).attr('name') + '"]').addClass(errorClass).removeClass(validClass);
		} else if(element.type === 'textarea'){
		    $('iframe#' + $(element).attr('id')  + '_ifr').addClass(errorClass).removeClass(validClass);
		    $(element).prev().addClass(errorClass).removeClass(validClass);
		} else {
		    $(element).addClass(errorClass).removeClass(validClass);
		    $(element).prev().addClass(errorClass).removeClass(validClass);
		}
	    },
	    unhighlight: function(element, errorClass, validClass) {
		if (element.type === 'radio' || element.type === 'checkbox') {
		    $('label[for="' + $(element).attr('name').replace('[]','') + '"]').removeClass(errorClass).addClass(validClass);
		    $('input[name="' + $(element).attr('name') + '"]').next().removeClass(errorClass).addClass(validClass);
		    $('input[name="' + $(element).attr('name') + '"]').removeClass(errorClass).addClass(validClass);
		} else if(element.type === 'textarea'){
		    $('iframe#' + $(element).attr('id')  + '_ifr').removeClass(errorClass).addClass(validClass);
		    $(element).prev().removeClass(errorClass).addClass(validClass);
		} else {
		    $(element).removeClass(errorClass).addClass(validClass);
		    $(element).prev().removeClass(errorClass).addClass(validClass);
		}
	    }
	});
	
    } else if($('body').hasClass('home')) {
	
	$('html').click(function() {
	    $('a.btnRelat').removeClass('hover');
	    $('.boxInfoRelatorios').hide();
	});

	
	$('p.noticiaExcluida').each(function(){
	    var $hide = setTimeout(function(){
		$('p.noticiaExcluida').fadeOut();
	    }, 5000);
	});
	
	$('a.btnRelat').click(function(event){
	    event.stopPropagation();
	    $(this).toggleClass('hover');
	    $('.boxInfoRelatorios').toggle();
	    return false; 
	});
	
	$('.boxInfoRelatorios').click(function(event){
	    event.stopPropagation();
	});
	
	var $widthPag = 0;
	$('.pagination:first a, .pagination:first span').each(function(){
	    $widthPag += $(this).outerWidth(true);
	});
	$('.pagination').width($widthPag).css({ 'position':'static' });
	
	/*$('a.btnStatus').live('click', function(){
	    var $this = $(this);
	    var $acao = $(this).attr('href').split('|')[0];
	    var $id = $(this).attr('href').split('|')[1];
	    $.ajax({
		type: "POST",
		data: 'acao=' + $acao + '&id=' + $id,
		dataType: "json",
		url: '/admin/alterarStatus.php',
		success: function(json){
		    if(json.status == "ok"){
			if($acao == 'aprovar'){
			    var $html = '<span class="aprovado">Aprovado</span><a class="btnStatus" href="reprovar|' + $id + '">Reprovar</a>';
			    var $txt = ' aprovado ';
			} else {
			    var $html = '<a class="btnStatus" href="aprovar|' + $id + '">Aprovar</a><span class="reprovado">Reprovado</span>';
			    var $txt = ' reprovado ';
			}
			$this.parents('td').html($html);
			alert('Participante' + $txt + 'com sucesso!');
		    } else {
			alert('Ocorreu um erro. Tente novamente.');
		    }
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
		    alert('Ocorreu um erro. Tente novamente.');
		}
	    });
	    return false;
	});
	
	$('select#filtro').change(function(){
	    $(this).parents('form').submit();
	});*/
	
    } else if($('body').hasClass('login')){
	$('#formLogin').validate({ errorContainer: $('.boxMsg p.error'), errorLabelContainer: $('#msgLabel'),
	    submitHandler: function() {
		$('.boxMsg p').hide();
		$('#boxLoading').fadeIn('fast');
		$wait = setTimeout(function(){
		    $.ajax({
			type: "POST",
			data: $("#formLogin").serialize(),
			dataType: "json",
			url: $("#formLogin").attr('action'),
			success: function(json){
			    if(json.status == "ok"){
				window.location = "index.php";
			    } else if(json.status == "erro") {
				$('#boxLoading').hide();
				$('.boxMsg p.errorServer').fadeIn('fast');
				$('#formLogin #inputNome').focus();
				$wait = setTimeout(function(){
				    $('.boxMsg p.errorServer').fadeOut('fast');
				}, 5000);
			    }
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
			    $('#boxLoading').hide();
			    $('.boxMsg p.errorServer').show();
			    $('#formLogin #inputNome').focus();
			    $wait = setTimeout(function(){
				$('.boxMsg p.errorServer').fadeOut('fast');
			    }, 5000);
			}
		    });
		}, 1000);
	    },
	    highlight: function(element, errorClass, validClass) {
		if (element.type === 'radio') {
		    this.findByName(element.name).addClass(errorClass).removeClass(validClass);
		} else {
		    $(element).prev().addClass(errorClass).removeClass(validClass);
		}
	    },
	    unhighlight: function(element, errorClass, validClass) {
		if (element.type === 'radio') {
		    this.findByName(element.name).removeClass(errorClass).addClass(validClass);
		} else {
		    $(element).prev().removeClass(errorClass).addClass(validClass);
		}
	    }
	});
	
	$('a#btnBoxSenha').click(function(){
	    $('.formInit, .boxMsg p').hide().find('.form').val('');
	    $('.formEsqueciSenha').find('.form').val('').end().fadeIn('slow');
	    $('.form, label').removeClass('error');
	});
	
	$('a#btnVoltarLogin').click(function(){
	    $('.formEsqueciSenha, .boxMsg p').hide().find('.form').val('');
	    $('.formInit').find('.form').val('').end().fadeIn('slow');
	    $('.form, label').removeClass('error');
	});
	
	$('a#btnEsqueciSenha').click(function(){
	    if($('#emailEsqueciSenha').prev().hasClass('error') || $.trim($('#emailEsqueciSenha').val()) == ''){
		return false;
	    } else {
		$('.boxMsg p').hide();
	    $('#boxLoading').fadeIn('fast');
		$wait = setTimeout(function(){
		    $.ajax({
			type: "POST",
			data: 'email=' + $.trim($('#emailEsqueciSenha').val()),
			dataType: "json",
			url: '/admin/_func/reset-password.php',
			success: function(json){
			    if(json.status == "ok"){
				alert('Foi');
			    } else if(json.status == "erro") {
				$('#boxLoading').hide();
				$('#boxMsg p.errorServer').fadeIn('fast');
				$('#formLogin #inputNome').focus();
				$wait = setTimeout(function(){
				    $('#boxMsg p.errorServer').fadeOut('fast');
				}, 5000);
			    }
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
			    $('#boxLoading').hide();
			    $('#boxMsg p.errorServer').show();
			    $('#formLogin #inputNome').focus();
			    $wait = setTimeout(function(){
				$('#boxMsg p.errorServer').fadeOut('fast');
			    }, 5000);
			}
		    });
		}, 1000);
	    }
	});
    } else {
    
	$('#formCadastrarUsuario').validate({ errorContainer: $('.boxMsg p.error'), errorLabelContainer: $('#msgLabel'),
	    submitHandler: function() {
		$('.boxMsg p').hide();
		$('#boxLoading').fadeIn('fast');
		$wait = setTimeout(function(){
		    $.ajax({
			type: "POST",
			data: $("#formCadastrarUsuario").serialize(),
			dataType: "json",
			url: $("#formCadastrarUsuario").attr('action'),
			success: function(json){
			    if(json.status == "ok"){
				alert('Dados atualizados com sucesso!');
				window.location = '/admin/index.php';
			    } else if(json.status == "erro") {
				$('#boxLoading, .boxMsg p').hide();
				if(json.info.indexOf('cpf-cadastrado') != -1){
				    $('.boxMsg p.errorServerCpf').fadeIn('fast');
				} else if(json.info.indexOf('email-cadastrado') != -1){
				    $('.boxMsg p.errorServerEmail').fadeIn('fast');
				} else if(json.info.indexOf('senha') != -1) {
				    $('.boxMsg p.errorServerSenha').fadeIn('fast');
				} else {
				    $('.boxMsg p.errorServer').fadeIn('fast');
				}
			       
				$wait = setTimeout(function(){
				    $('.boxMsg p').fadeOut('fast');
				}, 5000);
			    }
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
			    $('#boxLoading, .boxMsg p').hide();
			    $('.boxMsg p.errorServer').fadeIn('fast');
			    $wait = setTimeout(function(){
				$('.boxMsg p').fadeOut('fast');
			    }, 5000);
			}
		    });
		}, 1000);
	    },
	    highlight: function(element, errorClass, validClass) {
		if (element.type === 'radio') {
		    this.findByName(element.name).addClass(errorClass).removeClass(validClass);
		} else {
		    $(element).prev().addClass(errorClass).removeClass(validClass);
		}
	    },
	    unhighlight: function(element, errorClass, validClass) {
		if (element.type === 'radio') {
		    this.findByName(element.name).removeClass(errorClass).addClass(validClass);
		} else {
		    $(element).prev().removeClass(errorClass).addClass(validClass);
		}
	    }
	});
	
	$('#formCadastrarRelease').validate({ errorContainer: $('.boxMsg p.error'), errorLabelContainer: $('#msgLabel'),
	    submitHandler: function() {
		$('.boxMsg p').hide();
		$('#boxLoading').fadeIn('fast');
		$wait = setTimeout(function(){
		    $.ajax({
			type: "POST",
			data: $("#formCadastrarRelease").serialize(),
			dataType: "json",
			url: $("#formCadastrarRelease").attr('action'),
			success: function(json){
			    if(json.status == "ok"){
				if($('#idRelease').length){
				    var $msg = 'Dados alterados com sucesso!';
				} else {
				    var $msg = 'Item cadastrado com sucesso!';
				}
				if($('#newRelease').length){
				    var $id = json.id;
				    var $token = $('#newRelease').val();
				    $('.enviandoEmail p').fadeIn();
				    $.ajax({
					type: 'POST',
					data: 'token=' + $token + '&id=' + $id,
					dataType: "json",
					url: '/admin/_func/envia-email-releases.php',
					success: function(json){
					    $('.enviandoEmail p').hide();
					    $('#boxLoading').hide();
					    if(typeof json.status == 'undefined'){
						alert('O release foi salvo com sucesso, porém ocorreu um erro durante o envio dos e-mails.');
					    } else if(json.status == 'ok'){
						alert($msg);
					    } else {
						alert('O release foi salvo com sucesso, porém ocorreu um erro durante o envio dos e-mails.');
					    }
					    window.location = '/admin/releases.php';
					},
					error: function(XMLHttpRequest, textStatus, errorThrown){
					    alert('O release foi salvo com sucesso, porém ocorreu um erro durante o envio dos e-mails.');
					    window.location = '/admin/releases.php';
					}
				    });
				} else {
				    alert($msg);
				    window.location = '/admin/releases.php';
				}
			    } else if(json.status == "erro") {
				$('#boxLoading, .boxMsg p').hide();
				$('.boxMsg p.errorServer').fadeIn('fast');			   
				$wait = setTimeout(function(){
				    $('.boxMsg p').fadeOut('fast');
				}, 5000);
			    }
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
			    $('#boxLoading, .boxMsg p').hide();
			    $('.boxMsg p.errorServer').fadeIn('fast');			   
			    $wait = setTimeout(function(){
				$('.boxMsg p').fadeOut('fast');
			    }, 5000);
			}
		    });
		}, 1000);
	    },
	    highlight: function(element, errorClass, validClass) {
		if (element.type === 'radio') {
		    this.findByName(element.name).addClass(errorClass).removeClass(validClass);
		} else {
		    $(element).prev().addClass(errorClass).removeClass(validClass);
		}
	    },
	    unhighlight: function(element, errorClass, validClass) {
		if (element.type === 'radio') {
		    this.findByName(element.name).removeClass(errorClass).addClass(validClass);
		} else {
		    $(element).prev().removeClass(errorClass).addClass(validClass);
		}
	    }
	});
	
	Releases = {
	    EnviaEmail: function($index, $total){
		alert($index + '  -  ' + $total);
	    },
	    Completo: function(){
		alert('Release cadastrado e e-mails enviados com sucesso!');
		window.location = '/admin/releases.php';
	    }
	}
	
	$('#formCadastrarBoletim').validate({ errorContainer: $('.boxMsg p.error'), errorLabelContainer: $('#msgLabel'),
	    submitHandler: function() {
		$('.boxMsg p').hide();
		$('#boxLoading').fadeIn('fast');
		$wait = setTimeout(function(){
		    $.ajax({
			type: "POST",
			data: $("#formCadastrarBoletim").serialize(),
			dataType: "json",
			url: $("#formCadastrarBoletim").attr('action'),
			success: function(json){
			    if(json.status == "ok"){
				if($('#idBoletim').length){
				    var $msg = 'Dados alterados com sucesso!';
				} else {
				    var $msg = 'Item cadastrado com sucesso!';
				}
				alert($msg);
				window.location = '/admin/boletins.php';
			    } else if(json.status == "erro") {
				$('#boxLoading, .boxMsg p').hide();
				$('.boxMsg p.errorServer').fadeIn('fast');			   
				$wait = setTimeout(function(){
				    $('.boxMsg p').fadeOut('fast');
				}, 5000);
			    }
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
			    $('#boxLoading, .boxMsg p').hide();
			    $('.boxMsg p.errorServer').fadeIn('fast');			   
			    $wait = setTimeout(function(){
				$('.boxMsg p').fadeOut('fast');
			    }, 5000);
			}
		    });
		}, 1000);
	    },
	    highlight: function(element, errorClass, validClass) {
		if (element.type === 'radio') {
		    this.findByName(element.name).addClass(errorClass).removeClass(validClass);
		} else {
		    $(element).prev().addClass(errorClass).removeClass(validClass);
		}
	    },
	    unhighlight: function(element, errorClass, validClass) {
		if (element.type === 'radio') {
		    this.findByName(element.name).removeClass(errorClass).addClass(validClass);
		} else {
		    $(element).prev().removeClass(errorClass).addClass(validClass);
		}
	    }
	});
	
	$('#formCadastrarBanco').validate({ errorContainer: $('.boxMsg p.error'), errorLabelContainer: $('#msgLabel'),
	    submitHandler: function() {
		$('.boxMsg p').hide();
		$('#boxLoading').fadeIn('fast');
		$wait = setTimeout(function(){
		    $.ajax({
			type: "POST",
			data: $("#formCadastrarBanco").serialize(),
			dataType: "json",
			url: $("#formCadastrarBanco").attr('action'),
			success: function(json){
			    if(json.status == "ok"){
				alert('Item cadastrado com sucesso!');
				window.location = '/admin/banco-de-arquivos.php';
			    } else if(json.status == "erro") {
				$('#boxLoading, .boxMsg p').hide();
				$('.boxMsg p.errorServer').fadeIn('fast');			   
				$wait = setTimeout(function(){
				    $('.boxMsg p').fadeOut('fast');
				}, 5000);
			    }
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
			    $('#boxLoading, .boxMsg p').hide();
			    $('.boxMsg p.errorServer').fadeIn('fast');			   
			    $wait = setTimeout(function(){
				$('.boxMsg p').fadeOut('fast');
			    }, 5000);
			}
		    });
		}, 1000);
	    },
	    highlight: function(element, errorClass, validClass) {
		if (element.type === 'radio') {
		    this.findByName(element.name).addClass(errorClass).removeClass(validClass);
		} else {
		    $(element).prev().addClass(errorClass).removeClass(validClass);
		}
	    },
	    unhighlight: function(element, errorClass, validClass) {
		if (element.type === 'radio') {
		    this.findByName(element.name).removeClass(errorClass).addClass(validClass);
		} else {
		    $(element).prev().removeClass(errorClass).addClass(validClass);
		}
	    }
	});
	
	$('#email').blur(function(){
	    var $val = $.trim($(this).val());
	    $('.itemVerifyEmail span').fadeOut('fast');
	    if($(this).prev().hasClass('valid')){
		var $wait = setTimeout(function(){
		    if(typeof $emailPrev == 'undefined' || $val != $emailPrev){
			$('.itemVerifyEmail .boxLoading').show();
			$.ajax({
			    type: 'POST',
			    data: 'email=' + $val,
			    dataType: 'json',
			    url: '/admin/_func/verifica-email.php',
			    success: function($json){
				if($json.status == 'cadastrado'){
				    $('.itemVerifyEmail .boxLoading').hide();
				    $('.itemVerifyEmail > span').find('em').text($val).end().fadeIn();
				    $('#email').prev().removeClass('valid').addClass('error');
				    if(typeof $validator_emails == 'undefined'){
					$validator_emails = [];
				    }
				    $validator_emails.push($val);
				} else {
				    $('.itemVerifyEmail .boxLoading').fadeOut();
				}
			    },
			    error: function(XMLHttpRequest, textStatus, errorThrown){
				$('.itemVerifyEmail .boxLoading').fadeOut();
			    }
			});
			$emailPrev = $val;
		    }
		}, 50);
	    }
	});
	
	$('#cpf').blur(function(){
	    var $val = $.trim($(this).val());
	    var $cpfOrigin = $.trim($('#cpfOrigin').val());
	    $('.itemVerifyCpf span').fadeOut('fast');
	    if($(this).prev().hasClass('valid') && (typeof $cpfOrigin != 'undefined' && $cpfOrigin != $val)){
		var $wait = setTimeout(function(){
		    if(typeof $cpfPrev == 'undefined' || $val != $cpfPrev){
			$('.itemVerifyCpf .boxLoading').show();
			$.ajax({
			    type: 'POST',
			    data: 'cpf=' + $val,
			    dataType: 'json',
			    url: '/admin/_func/verifica-cpf.php',
			    success: function($json){
				if($json.status == 'cadastrado'){
				    $('.itemVerifyCpf .boxLoading').hide();
				    $('.itemVerifyCpf > span').find('em').text($val).end().fadeIn();
				    $('#cpf').prev().removeClass('valid').addClass('error');
				    if(typeof $validator_cpf == 'undefined'){
					$validator_cpf = [];
				    }
				    $validator_cpf.push($val);
				} else {
				    $('.itemVerifyCpf .boxLoading').fadeOut();
				}
			    },
			    error: function(XMLHttpRequest, textStatus, errorThrown){
				$('.itemVerifyEmail .boxLoading').fadeOut();
			    }
			});
			$cpfPrev = $val;
		    }
		}, 50);
	    }
	});
	
	$('a.btnStatus').live('click', function(){
	    var $this = $(this);
	    var $acao = $(this).attr('href').split('|')[0];
	    var $id = $(this).attr('href').split('|')[1];
	    var $user = $(this).parents('tr').find('td.userNome').text();
	    var $ativos = parseInt($('.infoSecao > p > .ativos').text());
	    
	    if($(this).hasClass('btnStatusRelease')){
		var $url = 'release-status.php';
		var $class = ' btnStatusRelease';
	    } else if($(this).hasClass('btnStatusUsuario')){
		var $url = 'usuario-status.php';
		var $class = ' btnStatusUsuario';
	    } else if($(this).hasClass('btnStatusBanco')){
		var $url = 'banco-status.php';
		var $class = ' btnStatusBanco';
	    } else if($(this).hasClass('btnStatusGaleria')){
		var $url = 'galeria-status.php';
		var $class = ' btnStatusGaleria';
	    } else if($(this).hasClass('btnStatusParceiro')){
		var $url = 'parceiro-status.php';
		var $class = ' btnStatusParceiro';
	    } else if($(this).hasClass('btnStatusBoletim')){
		var $url = 'boletim-status.php';
		var $class = ' btnStatusBoletim';
	    }
	    
	    $.ajax({
		type: 'POST',
		data: 'acao=' + $acao + '&id=' + $id,
		dataType: 'json',
		url: '/admin/_func/' + $url,
		success: function(json){
		    if(json.status == "ok"){
			if($acao == 'aprovar'){
			    var $html = '<span class="aprovado">Ativo</span><a class="btnStatus' + $class + '" href="reprovar|' + $id + '">Desativar</a>';
			    var $txt = ' ativado ';
			    $('.infoSecao > p > .ativos').text(++$ativos);
			} else {
			    var $html = '<a class="btnStatus' + $class + '" href="aprovar|' + $id + '">Ativar</a><span class="reprovado">Inativo</span>';
			    var $txt = ' desativado ';
			    $('.infoSecao > p > .ativos').text(--$ativos);
			}
			if($this.hasClass('btnStatusRelease')){
			    var $txtConfirm = 'Release \'' + $user + '\'' + 'com sucesso!';
			} else if($this.hasClass('btnStatusUsuario')){
			    var $txtConfirm = 'Usuário \'' + $user + '\'' + $txt + 'com sucesso!';
			} else if($this.hasClass('btnStatusBanco')){
			    var $txtConfirm = 'Arquivo \'' + $user + '\'' + $txt + 'com sucesso!';
			} else if($this.hasClass('btnStatusGaleria')){
			    var $txtConfirm = 'Item \'' + $user + '\'' + $txt + 'com sucesso!';
			} else if($this.hasClass('btnStatusParceiro')){
			    var $txtConfirm = 'Parceiro \'' + $user + '\'' + $txt + 'com sucesso!';
			} else if($this.hasClass('btnStatusBoletim')){
			    var $txtConfirm = 'Boletim \'' + $user + '\'' + $txt + 'com sucesso!';
			}
			$this.parents('td').html($html);
			alert($txtConfirm);
		    } else {
			alert('Ocorreu um erro. Tente novamente.');
		    }
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
		    alert('Ocorreu um erro. Tente novamente.');
		}
	    });
	    return false;
	});
	
	$('a.btnExcluir').live('click', function(){
	    var $user = $(this).parents('tr').find('td.userNome').text();
	    var $this = $(this);
	    var $id = $(this).attr('href').split('#')[1];
	    var $idElem = ($(this).attr('id') != null)  ? $(this).attr('id') : $(this).attr('rel');
	    var $total = parseInt($('.infoSecao > p > .total').text());
	    var $ativos = parseInt($('.infoSecao > p > .ativos').text());
	    var $verificaAtivo = $(this).parents('td').prev().find('span.aprovado').length;
	    if($idElem == 'btnExcluirRelease'){
		var $msg = 'Tem certeza que deseja excluir o release \'' + $user + '\'?';
		var $msgConfirm = 'Release \'' + $user + '\' excluído com sucesso!';
		var $url = 'release-excluir.php';
	    } else if($idElem == 'btnExcluirUsuario'){
		var $msg = 'Tem certeza que deseja excluir o usuário \'' + $user + '\'?';
		var $msgConfirm = 'Usuário \'' + $user + '\' excluído com sucesso!';
		var $url = 'usuario-excluir.php';
	    } else if($idElem == 'btnExcluirBanco'){
		var $msg = 'Tem certeza que deseja excluir o arquivo \'' + $user + '\'?';
		var $msgConfirm = 'Arquivo \'' + $user + '\' excluído com sucesso!';
		var $url = 'banco-excluir.php';
	    } else if($idElem == 'btnExcluirNoticia'){
		var $msg = 'Tem certeza que deseja excluir a notícia \'' + $user + '\' ?';
		var $msgConfirm = 'Notícia excluída com sucesso!';
		var $url = 'noticia-excluir.php';
	    } else if($idElem == 'btnExcluirGaleria'){
		var $msg = 'Tem certeza que deseja excluir o item \'' + $user + '\' ?';
		var $msgConfirm = 'Item excluído com sucesso!';
		var $url = 'galeria-excluir.php';
	    } else if($idElem == 'btnExcluirParceiro'){
		var $msg = 'Tem certeza que deseja excluir o parceiro \'' + $user + '\' ?';
		var $msgConfirm = 'Parceiro excluído com sucesso!';
		var $url = 'parceiro-excluir.php';
	    } else if($idElem == 'btnExcluirBoletim'){
		var $msg = 'Tem certeza que deseja excluir o boletim \'' + $user + '\' ?';
		var $msgConfirm = 'Boletim excluído com sucesso!';
		var $url = 'boletim-excluir.php';
	    } else if($idElem == 'btnExcluirProgramacao'){
		var $msg = 'Tem certeza que deseja excluir o item \'' + $user + '\' da programação ?';
		var $msgConfirm = 'Item excluído com sucesso!';
		var $url = 'programacao-excluir.php';
	    }
	    
	    if(confirm($msg)){
		$.ajax({
		    type: "POST",
		    data: 'id=' + $id,
		    dataType: "json",
		    url: '/admin/_func/' + $url,
		    success: function(json){
			if(json.status == "ok"){
			    $started = 0;
			    $('.infoSecao > p > .total').text(--$total);
			    if($verificaAtivo){
				$('.infoSecao > p > .ativos').text(--$ativos);
			    }
			    $this.parents('tr').find('td').fadeOut('slow', function(){
				if(!($started++)){
				    $waitAlert = setTimeout(function(){
					alert($msgConfirm);
					$('#tbVotos tr td').removeClass('even');
					$('#tbVotos tr:even td').addClass('even');
					clearTimeout($waitAlert);
				    }, 500);
				}			    
			    });
			} else {
			    alert('Ocorreu um erro. Tente novamente.');
			}
		    },
		    error: function(XMLHttpRequest, textStatus, errorThrown){
			alert('Ocorreu um erro. Tente novamente.');
		    }
		});
		return false;
	    }
	});
	
	$('a.btnAltSenha').click(function(){
	    $('.itemNovaSenha .form').val('').prev().removeClass('error valid').end().toggleClass('required');
	    $('.boxMsg p').hide();
	    $('.itemNovaSenha').slideToggle();
	    if($(this).text() == '[alterar senha]'){
		$(this).text('[não alterar]');
	    } else {
		$(this).text('[alterar senha]');
	    }
	    return false;
	});
	
	$('#data').datetimepicker({
	    timeOnlyTitle: 'Horário de publicação',
	    timeText: 'Horário',
	    hourText: 'Hora',
	    minuteText: 'Minuto',
	    secondText: 'Segundo',
	    currentText: 'Agora',
	    closeText: 'Ok',
	    prevText: 'Mês anterior',
	    nextText: 'Próximo mês',
	    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
	    dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
	    dateFormat: 'dd/mm/yy'
	});
	
	$('#arquivo').each(function(){
	    if(!($(this).hasClass('disabled'))){
		if($(this).hasClass('typeAll')){
		    createUploadifyInstanceArquivo('#' + $(this).attr('id'), '', '');
		} else if($(this).hasClass('fileBoletim')){
		    createUploadifyInstanceArquivo('#' + $(this).attr('id'), '*.pdf', 'Arquivos PDF', 'boletim-upload.php');
		} else {
		    createUploadifyInstanceArquivo('#' + $(this).attr('id'));
		}	    
	    }	
	});
    
	$('a.excArquivo').click(function(){
	    $(this).hide();
	    $(this).prev().hide();
	    $('#hdArquivo').val('');
	    $('#uploadArquivo').val('true');
	    if($('#arquivoUploader').length){
		$('#arquivoUploader').show();
	    } else {
		createUploadifyInstanceArquivo('#arquivo', '', '');
	    }
	    return false;
	});
	
	$('.imgUpload').each(function(){
	    $id = '#' + $(this).attr('id');
	    createUploadifyInstance($id, false);
	});
    
	$('#formCadastrarBanco a.btnActImg').live('click', function(){
	    $tipoImg = $(this).parents('.item').attr('rel');
	    var $img = $(this).attr('href');
	    if($(this).attr('id') == 'btnSalvarImg'){
		var $item = $(this).parent();
		var $parent = $(this).parents('.item');		
		var $info = 'x=' + $item.find('.x').val();
		var $info = $info + '&y=' + $item.find('.y').val();
		var $info = $info + '&x2=' + $item.find('.x2').val();
		var $info = $info + '&y2=' + $item.find('.y2').val();
		var $info = $info + '&w=' + $item.find('.w').val();
		var $info = $info + '&h=' + $item.find('.h').val();
		$item.find('.boxImg').append('<div class="contentSave" style="width:\
					     ' + $item.find('.boxImg .jcrop-holder img').width() + 'px; height: ' + $item.find('.boxImg .jcrop-holder img').height() + 'px">\
					    <div class="bg">&nbsp;</div><img src="/admin/img/loading3.gif" alt="Carregando" /><p style="width:\
					     ' + $item.find('.boxImg .jcrop-holder img').width() + 'px;">Salvando Imagem...</p></div>');
		$.ajax({
		    type: 'POST',
		    data: 'url=' + $img + '&acao=salvar&tipo=' + $tipoImg + '&' + $info + '&local=banco_de_arquivos/thumb',
		    dataType: 'json',
		    url: '/admin/_func/imagem.php',
		    success: function($json){
			if($json.status == 'ok'){
			    loadImage([$json.url + '?' + new Date().getTime()], 'salvar', $parent.find('.jcropContent'));
			}
		    }
		});
	    } else if($(this).attr('id') == 'btnExcluirImg'){
		var $box = $(this).parent();
		var $editItem = ($('input#tipoNoticia').val() == 'editar') ? $('input#tipoNoticia').attr('rel') : '';
		if(window.confirm('Tem certeza que deseja excluir essa imagem?')){
		    $box.parents('.item').find('.imgUploadUrl').val('');
		    $('#uploadImg').val('true');
		    $.ajax({
			type: 'POST',
			data: 'url=' + $img + '&acao=deleteTemp&idBanco=' + $editItem + '&tipoImg=' + $tipoImg + '&local=/upload/banco_de_arquivos/thumb',
			dataType: 'json',
			url: '/admin/_func/imagem.php'
		    });
		    $box.fadeOut(function(){
			$box.remove();
		    });
		}		
	    }	    
	    return false;
	});
	
	$('#formCadastrarGaleria a.btnActImg').live('click', function(){
	    $tipoImg = $(this).parents('.item').attr('rel');
	    var $img = $(this).attr('href');
	    if($(this).attr('id') == 'btnSalvarImg'){
		var $item = $(this).parent();
		var $parent = $(this).parents('.item');		
		var $info = 'x=' + $item.find('.x').val();
		var $info = $info + '&y=' + $item.find('.y').val();
		var $info = $info + '&x2=' + $item.find('.x2').val();
		var $info = $info + '&y2=' + $item.find('.y2').val();
		var $info = $info + '&w=' + $item.find('.w').val();
		var $info = $info + '&h=' + $item.find('.h').val();
		$item.find('.boxImg').append('<div class="contentSave" style="width:\
					     ' + $item.find('.boxImg .jcrop-holder img').width() + 'px; height: ' + $item.find('.boxImg .jcrop-holder img').height() + 'px">\
					    <div class="bg">&nbsp;</div><img src="/admin/img/loading3.gif" alt="Carregando" /><p style="width:\
					     ' + $item.find('.boxImg .jcrop-holder img').width() + 'px;">Salvando Imagem...</p></div>');
		$.ajax({
		    type: 'POST',
		    data: 'url=' + $img + '&acao=salvar&tipo=' + $tipoImg + '&' + $info,
		    dataType: 'json',
		    url: '/admin/_func/imagem.php',
		    success: function($json){
			if($json.status == 'ok'){
			    loadImage([$json.url + '?' + new Date().getTime()], 'salvar', $parent.find('.jcropContent'));
			}
		    }
		});
	    } else if($(this).attr('id') == 'btnExcluirImg'){
		var $box = $(this).parent();
		var $editItem = '';
		if(window.confirm('Tem certeza que deseja excluir essa imagem?')){
		    $box.parents('.item').find('.imgUploadUrl').val('');
		    $('#uploadImg').val('true');
		    $.ajax({
			type: 'POST',
			data: 'url=' + $img + '&acao=deleteTemp&tipoImg=' + $tipoImg,
			dataType: 'json',
			url: '/admin/_func/imagem.php'
		    });
		    $box.fadeOut(function(){
			$box.remove();
		    });
		}		
	    }	    
	    return false;
	});

	var $widthPag = 0;
	$('.pagination:first a, .pagination:first span').each(function(){
	    $widthPag += $(this).outerWidth(true);
	});
	$('.pagination').width($widthPag).css({ 'position':'static' });
	
	$('.tel').mask('(99) 9999-9999');
	$('.cpf').mask('999.999.999-99');
    }

    $('ul#menuTop li a.disabled').click(false);

    // Quadro de Medalhas - Classificação

    $('select#modalidadeClass').change(function(){
	var $val = $(this).val();
	if($val == ''){
	    $('#containerModalidades .boxItemMedalha').hide().fadeIn();
	} else {
	    $('#containerModalidades .boxItemMedalha').hide();
	    $('#containerModalidades #boxMod' + $val).fadeIn();
	}
    });

    $('.itemCheckClass input.check').change(function(){
	$formAjaxSend.disabled($('#formClassificacao'));
	var $index = $(this).val();
	$('.itemHide').hide();
	$('select#cidade').html('<option value=""></option>');
	$('.infosTotal strong').text('0');
	$('#loading1 .boxLoading').fadeIn('fast');
	$.ajax({
	    type: 'POST',
	    data: 'divisao=' + $index,
	    dataType: 'json',
	    url: '/admin/_func/get-list-cidades.php',
	    success: function(json){
		if(typeof json.status == 'undefined' || json.status == 'error'){
		    $formAjaxSend.enabled($('#formClassificacao'));
		    $('#loading1 .boxLoading').fadeOut('fast');
		} else if(json.status == 'ok'){
		    $i = 0;
		    $listCidades = '';
		    $loopCidades = setInterval(function(){
			if($i >= json.cidades.length){
			    $('select#cidade').append($listCidades);
			    $('#itemClassCidade').fadeIn();
			    delete($listCidades);
			    $('#loading1 .boxLoading').fadeOut('fast');
			    clearInterval($loopCidades);
			    if($('input#actEditar').length){
				var $cidade = $('input#actEditar').val().split('|')[0];
				$('select#cidade option[value="' + $cidade + '"]').attr('selected', 'selected');
				$('input#actEditar').remove();
				$loadModalidades($cidade);
			    }
			    $formAjaxSend.enabled($('#formClassificacao'));
			} else {
			    $listCidades = $listCidades + '<option value="' + json.cidades[$i].id + '">' + json.cidades[$i].nome + '</option>';
			    $i++;
			}
		    }, 1);
		}
	    },
	    error: function(XMLHttpRequest, textStatus, errorThrown){
		$formAjaxSend.enabled($('#formClassificacao'));
		$('#loading1 .boxLoading').fadeOut('fast');
	    }
	});
    });    
    
    $('select#cidade').change(function(){
	$loadModalidades($(this).val());
    });
    
    $loadModalidades = function($index){
	$formAjaxSend.disabled($('#formClassificacao'));
	$actLocal = $('form.boxFormClass').attr('id').split('form')[1].toLowerCase();
	$('select#modalidadeClass').html('<option value="">Todas</option>');
	$('#containerModalidades').html('');
	$('.infosTotal strong').text('0');
	$('#loading2 .boxLoading').fadeIn('fast');
	if($index != ''){
	    $.ajax({
		type: 'POST',
		data: 'cidade=' + $index,
		dataType: 'json',
		url: '/admin/_func/get-modalidades-' + $actLocal + '.php',
		success: function(json){
		    if(typeof json.status == 'undefined' || json.status == 'error'){
			$formAjaxSend.enabled($('#formClassificacao'));
			$('#loading2 .boxLoading').fadeOut('fast');
		    } else if(json.status == 'ok'){
			$i = 0;
			$listModalidades = $boxItensModalidades = '';
			$totalOuro = $totalPrata = $totalBronze = $pontos = 0;
			$loopModalidades = setInterval(function(){
			    if($i >= json.modalidades.length){
				$boxItensModalidades = $boxItensModalidades + '<br clear="all" />';
				$('select#modalidadeClass').append($listModalidades);
				$('#containerModalidades').append($boxItensModalidades);
				$('#itemClassModalidade, #containerModalidades, .infosTotal, #boxFormBot').fadeIn();
				$('#loading2 .boxLoading').fadeOut('fast');
				if($hasMedalhas){
				    $('#txtTotal-ouro').text($totalOuro);
				    $('#txtTotal-prata').text($totalPrata);
				    $('#txtTotal-bronze').text($totalBronze);
				    $('#txtTotal').text($totalOuro + $totalPrata + $totalBronze);
				} else if($hasClassificacao){
				    $('#txtTotal').text($pontos);
				    if($('#txtTotal').text() != null){
					$('#txtTotal').text($('#txtTotal').text().replace('.',','));
				    }
				}
				delete($listModalidades, $totalOuro, $totalPrata, $totalBronze, $pontos);
				clearInterval($loopModalidades);
				$formAjaxSend.enabled($('#formClassificacao'));
			    } else {
				if($hasMedalhas){
				    $totalOuro = $totalOuro + parseInt(json.modalidades[$i].medalha_ouro);
				    $totalPrata = $totalPrata + parseInt(json.modalidades[$i].medalha_prata);
				    $totalBronze = $totalBronze + parseInt(json.modalidades[$i].medalha_bronze);
				    
				    $listModalidades = $listModalidades + '<option value="' + json.modalidades[$i].id + '">' + json.modalidades[$i].titulo + '</option>';
				    $boxItensModalidades = $boxItensModalidades + '\
					<div id="boxMod' + json.modalidades[$i].id + '" class="boxItemMedalha">\
					    <h4>' + json.modalidades[$i].titulo + '</h4>\
					    <label for="ouro[' + json.modalidades[$i].id + ']">Ouro</label>\
					    <span class="valPrev" title="Quantidade Atual">' + json.modalidades[$i].medalha_ouro + '</span>\
					    <input type="text" rel="ouro" name="ouro[' + json.modalidades[$i].id + ']" id="ouro[' + json.modalidades[$i].id + ']" class="required numero-int digits form formMedalha hint" value="' + json.modalidades[$i].medalha_ouro + '" maxlength="7" /><br /><br />\
					    <label for="prata[' + json.modalidades[$i].id + ']">Prata</label>\
					    <span class="valPrev" title="Quantidade Atual">' + json.modalidades[$i].medalha_prata + '</span>\
					    <input type="text" rel="prata" name="prata[' + json.modalidades[$i].id + ']" id="prata[' + json.modalidades[$i].id + ']" class="required numero-int digits form formMedalha" value="' + json.modalidades[$i].medalha_prata + '" maxlength="7" /><br /><br />\
					    <label for="bronze[' + json.modalidades[$i].id + ']">Bronze</label>\
					    <span class="valPrev" title="Quantidade Atual">' + json.modalidades[$i].medalha_bronze + '</span>\
					    <input type="text" rel="bronze" name="bronze[' + json.modalidades[$i].id + ']" id="bronze[' + json.modalidades[$i].id + ']" class="required numero-int digits form formMedalha" value="' + json.modalidades[$i].medalha_bronze + '" maxlength="7" />\
					    <br clear="all" />\
					</div>';
				} else if($hasClassificacao){
				    $pontos = $pontos + parseFloat(json.modalidades[$i].pontos);
				    $listModalidades = $listModalidades + '<option value="' + json.modalidades[$i].id + '">' + json.modalidades[$i].titulo + '</option>';
				    $boxItensModalidades = $boxItensModalidades + '\
					<div id="boxMod' + json.modalidades[$i].id + '" class="boxItemMedalha">\
					    <h4>' + json.modalidades[$i].titulo + '</h4>\
					    <label for="pontos[' + json.modalidades[$i].id + ']">Pontos</label>\
					    <span class="valPrev" title="Quantidade Atual">' + json.modalidades[$i].pontos.replace('.',',') + '</span>\
					    <input type="text" rel="pontos" name="pontos[' + json.modalidades[$i].id + ']" id="pontos[' + json.modalidades[$i].id + ']" class="required number form formMedalha hint" value="' + json.modalidades[$i].pontos.replace('.',',') + '" maxlength="7" /><br /><br />\
					    <br clear="all" />\
					</div>';
				}
				$i++;
			    }
			}, 5);
		    }
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
		    $formAjaxSend.enabled($('#formClassificacao'));
		    $('#loading2 .boxLoading').fadeOut('fast');
		}
	    });
	}
    }
    
    $('.boxItemMedalha input').live('focus', function(){
	this.select();
	if($hasMedalhas){
	    $valPrev = parseInt($(this).val());
	} else if($hasClassificacao){
	    $valPrev = parseFloat($(this).val().replace(',', '.'));
	}	
	if(isNaN($valPrev)){
	    $valPrev = 0;
	}
    }).live('blur', function(){
	$this = $(this);
	$wait = setTimeout(function(){
	    if(!($this.hasClass('error'))){
		if($hasMedalhas){
		    var $tipo = $this.attr('rel');
		    var $val = parseInt($this.val());
		    $('#txtTotal-' + $tipo).text(parseInt($('#txtTotal-' + $tipo).text()) + ($val - $valPrev));
		    $('#txtTotal').text(parseInt($('#txtTotal-ouro').text()) + parseInt($('#txtTotal-prata').text()) + parseInt($('#txtTotal-bronze').text()));
		} else if($hasClassificacao){
		    
		    var $total = 0;
		    var $objInput = $('.boxItemMedalha input');
		    var $length = $objInput.length;
		    
		    for($i = 0; $i < $length; $i++){
			$total += parseFloat($objInput.eq($i).val().replace(',','.'));
		    }
		    $('#txtTotal').text(roundDecimalNumber($total, 1));
		    $('#txtTotal').text($('#txtTotal').text().replace('.',','));
		}
	    }
	}, 50);
    });
    
    $('#formMedalhas, #formClassificacao').validate({ errorContainer: $('.boxMsg p.error'), errorLabelContainer: $('#msgLabel'),
	submitHandler: function() {
	    $('.boxMsg p').hide();
	    $('#boxLoading').fadeIn('fast');
	    $wait = setTimeout(function(){
		$.ajax({
		    type: "POST",
		    data: $(".boxFormClass").serialize(),
		    dataType: "json",
		    url: $(".boxFormClass").attr('action'),
		    success: function(json){
			$('#boxLoading').hide();
			if(typeof json.status == 'undefined'){
			    alert('Nops');
			} else if(json.status == "ok"){
			    $('select#cidade, select#modalidadeClass').html('<option value=""></option>');
			    $('#containerModalidades').html('');
			    $('.itemHide').fadeOut('fast');
			    $('input.check:checked').removeAttr('checked');
			    $('#msgSucesso').fadeIn();
			    var $wait = setTimeout(function(){
				$('#msgSucesso').fadeOut();
			    }, 6000)
			} else if(json.status == "erro") {
			    alert('Nops');
			}
		    },
		    error: function(XMLHttpRequest, textStatus, errorThrown){
			$('.boxMsg p').hide();
			$('.boxMsg p.errorServer').fadeIn('fast');			   
			$wait = setTimeout(function(){
			    $('.boxMsg p').fadeOut('fast');
			}, 5000);
		    }
		});
	    }, 10);
	},
	highlight: function(element, errorClass, validClass) {
	    if (element.type === 'radio' || element.type === 'checkbox') {
		$('label[for="' + $(element).attr('name').replace('[]','') + '"]').addClass(errorClass).removeClass(validClass);
		$('input[name="' + $(element).attr('name') + '"]').next().addClass(errorClass).removeClass(validClass);
		$('input[name="' + $(element).attr('name') + '"]').addClass(errorClass).removeClass(validClass);
	    } else if(element.type === 'textarea'){
		$('iframe#' + $(element).attr('id')  + '_ifr').addClass(errorClass).removeClass(validClass);
		$(element).prev().addClass(errorClass).removeClass(validClass);
	    } else {
		$(element).addClass(errorClass).removeClass(validClass);
		$(element).prev().addClass(errorClass).removeClass(validClass);
	    }
	},
	unhighlight: function(element, errorClass, validClass) {
	    if (element.type === 'radio' || element.type === 'checkbox') {
		$('label[for="' + $(element).attr('name').replace('[]','') + '"]').removeClass(errorClass).addClass(validClass);
		$('input[name="' + $(element).attr('name') + '"]').next().removeClass(errorClass).addClass(validClass);
		$('input[name="' + $(element).attr('name') + '"]').removeClass(errorClass).addClass(validClass);
	    } else if(element.type === 'textarea'){
		$('iframe#' + $(element).attr('id')  + '_ifr').removeClass(errorClass).addClass(validClass);
		$(element).prev().removeClass(errorClass).addClass(validClass);
	    } else {
		$(element).removeClass(errorClass).addClass(validClass);
		$(element).prev().removeClass(errorClass).addClass(validClass);
	    }
	}
    });
    
    $('.tbClass, .tbProg, .boxFormProg').each(function(){
	//$('a.btnVerTodosClass, a.btnResultProg').fancybox({ 'transitionIn': 'elastic', 'transitionOut': 'elastic', 'speedIn': 500, 'speedOut': 300, 'padding': 5, 'margin': 20, 'overlayOpacity': 0.70, 'overlayColor': '#FFFFFF', 'onComplete': act_result });
	$('a.btnResultProg').fancybox({ 'transitionIn': 'elastic', 'transitionOut': 'elastic', 'speedIn': 500, 'speedOut': 300, 'padding': 5, 'margin': 20, 'overlayOpacity': 0.70, 'overlayColor': '#FFFFFF', 'onComplete': act_result });
	$('a.btnVerTodosClass').click(function(){
	    $(this).parents('form').submit();
	    return false;
	});
    });
    
    $('.boxFormClass').each(function(){
	$hasMedalhas = $('form#formMedalhas').length ? true : false;
	$hasClassificacao = $('form#formClassificacao').length ? true : false;
	if($('input#actEditar').length){
	    var $div = $('input#actEditar').val().split('|')[1];
	    $('input.check[value="' + $div + '"]').click();
	}
    });
    
    
    // Programação
    
    $('#formProgramacao').each(function(){
	$boxDivisao = $('.containerDivisao');
	$boxSexo = $('.containerSexo');
	$boxCategoria = $('.containerCategoria');
	$boxProva = $('.containerProvas');
	$boxCidade = $('.containerCidades');
	$boxLocal = $('select#local');
	
	$prefixMod = '';
	$cidadesMod = [];
	
	if($('input#id').length){
	    $itens = $boxCidade.find('input');
	    if($('#itemProgCidade input').length){
		$total = $boxCidade.find('input').size();
		$i = 0;
		$prefixMod = $('select#modalidade').find('option:selected').attr('rel');
		$waitCidade = setInterval(function(){
		    var $rel = $itens.eq($i).attr('rel').split('|');
		    $cidadesMod[$i++] = $rel;
		    if($i == $total){
			delete($itens, $total, $i);
			clearInterval($waitCidade);
			$cidades.show(true);
			$provas.show();
		    }
		}, 10);
	    }
	}
    });
    
    $('#itemProgModalidade #modalidade').change(function(){
	$index = $.trim($(this).val());
	if($index != ''){
	    $prefixMod = $(this).find('option:selected').attr('rel');
	    $cidadesMod = [];
	    $validaUnico = true;
	    
	    $('#loading2 .boxLoading').fadeIn('fast');
	    $formAjaxSend.disabled($('#formProgramacao'));
	    $('.itemHide').hide();
	    
	    $boxDivisao.html('');
	    $boxSexo.html('');
	    $boxCategoria.html('');
	    $boxProva.html('');
	    $boxCidade.html('');
	    $.ajax({
		type: 'POST',
		data: 'modalidade=' + $index,
		dataType: 'json',
		url: '/admin/_func/get-infosProgramacaoModalidade.php',
		success: function(json){
		    if(typeof json.status == 'undefined' || json.status == 'error'){
			$formAjaxSend.enabled($('#formProgramacao'));
			$('#loading2 .boxLoading').fadeOut('fast');
		    } else if(json.status == 'ok'){

			if(typeof json.divisao != 'undefined' && json.divisao.length){
			    var $contentDiv = '';
			    var $check = (json.divisao.length == 1) ? 'checked="checked"' : '';
			    $validaUnico = $validaUnico && ($check != '');
			    
			    for($i = 0; $i < json.divisao.length; $i++){
				var $txt = '';
				
				if(json.divisao[$i] == 3){
				    var $txt = 'Divisão Especial';
				} else if(json.divisao[$i] == 4){
				    var $txt = 'Modalidades Extras';
				} else {
				    var $txt = json.divisao[$i] + 'ª Divisão';
				}
				
				$contentDiv = $contentDiv +
				    '<div class="itemInnerRadio">\
					<input type="checkbox" name="divisao[]" id="divisao' + json.divisao[$i] + '" value="' + json.divisao[$i] + '"\
					    class="check checkDiv" autocomplete="off"' + $check + ' rel="' + json.divisao[$i] + '" />\
					<label for="divisao' + json.divisao[$i] + '">' + $txt + '</label>\
				    </div>';
			    }
			    $contentDiv = $contentDiv + '<br clear="all" />';			
			    $boxDivisao.html($contentDiv).parent().fadeIn('fast');
			}
			
			if(typeof json.local != 'undefined' && json.local.length){
			    var $contentDiv = '';
			    var $check = (json.local.length == 1) ? ' selected="selected"' : '';
			    
			    for($i = 0; $i < json.local.length; $i++){
				$contentDiv = $contentDiv +
				    '<option value="' + json.local[$i].id + '"' + $check + '>' + json.local[$i].nome + '</option>';
			    }
			    $boxLocal.html($contentDiv).parent().fadeIn('fast');
			}
			
			if(typeof json.sexo != 'undefined' && json.sexo.length){
			    var $contentDiv = '';
			    var $check = (json.sexo.length == 1) ? 'checked="checked"' : '';
			    $validaUnico = $validaUnico && ($check != '');
			    
			    for($i = 0; $i < json.sexo.length; $i++){
				var $txt = '';
				
				if(json.sexo[$i] == 1){
				    var $txt = 'Masculino';
				    var $txtIndex = 'mas';
				} else if(json.sexo[$i] == 2){
				    var $txt = 'Feminino';
				    var $txtIndex = 'fem';
				} else {
				    var $txt = 'Misto';
				    var $txtIndex = 'mis';
				}
				
				$contentDiv = $contentDiv +
				    '<div class="itemInnerRadio">\
					<input type="checkbox" name="sexo[]" id="sexo' + json.sexo[$i] + '" value="' + json.sexo[$i] + '"\
					    class="check checkSexo" autocomplete="off"' + $check + ' rel="' + $txtIndex + '" />\
					<label for="sexo' + json.sexo[$i] + '">' + $txt + '</label>\
				    </div>';
			    }
			    
			    $contentDiv = $contentDiv + '<br clear="all" />';
			    $boxSexo.html($contentDiv).parent().fadeIn('fast');
			}
			
			if(typeof json.categoria != 'undefined' && json.categoria.length){
			    var $contentDiv = '';
			    var $check = (json.categoria.length == 1) ? 'checked="checked"' : '';
			    $validaUnico = $validaUnico && ($check != '');
			    
			    for($i = 0; $i < json.categoria.length; $i++){
				var $txt = '';
				
				if(json.categoria[$i] == 0){
				    var $txt = 'Livre';
				    var $txtIndex = 'l';
				} else {
				    var $txt = 'Até ' + json.categoria[$i] + ' anos';
				    var $txtIndex = json.categoria[$i];
				}
				
				$contentDiv = $contentDiv +
				    '<div class="itemInnerRadio">\
					<input type="checkbox" name="categoria[]" id="categoria' + json.categoria[$i] + '" value="' + json.categoria[$i] + '"\
					    class="check checkCateg" autocomplete="off"' + $check + ' rel="' + $txtIndex + '" />\
					<label for="categoria' + json.categoria[$i] + '">' + $txt + '</label>\
				    </div>';
			    }
			    
			    $contentDiv = $contentDiv + '<br clear="all" />';
			    $boxCategoria.html($contentDiv).parent().fadeIn('fast');
			}
    
			if(typeof json.prova != 'undefined' && json.prova.length){
			    var $contentDiv = '';
			    var $check = (json.prova.length == 1) ? 'checked="checked"' : '';
			    var $divPrev = '';
			    var $sexoPrev = '';
			    var $categPrev = '';
			    
			    for($i = 0; $i < json.prova.length; $i++){
				
				if($divPrev != json.prova[$i].divisao){
				    $sexoPrev = '';
				    $categPrev = '';

				    if(json.prova[$i].divisao == '3'){
					var $txtDiv = '<h3 class="tltDiv tltDivFloat">Divisão Especial</h3>';
				    } else if(json.prova[$i].divisao == '4'){
					var $txtDiv = '<h3 class="tltDiv tltDivFloat">Modalidades Extras</h3>';
				    } else {
					var $txtDiv = '<h3 class="tltDiv tltDivFloat">' + json.prova[$i].divisao + 'ª Divisão</h3><a class="btnSelectAll" href="#boxProvaSexo">[selecionar todas]</a><a class="btnDeleteAll" href="#boxProvaSexo">[desfazer seleção]</a>';
				    }
				    
				    $contentDiv = $contentDiv + '<div class="boxProvaDiv" rel="' + json.prova[$i].divisao + '">' + $txtDiv;
				}
				$divPrev = json.prova[$i].divisao;

				if($sexoPrev != json.prova[$i].sexo){
				    if(json.prova[$i].sexo == '1'){
					var $txtSexo = '<h3 class="tltSexo">Masculino</h3>';
				    } else if(json.prova[$i].sexo == '2'){
					var $txtSexo = '<h3 class="tltSexo">Feminino</h3>';
				    } else if(json.prova[$i].sexo == '3'){
					var $txtSexo = '<h3 class="tltSexo">Misto</h3>';
				    }
				    
				    $contentDiv = $contentDiv + '<div class="boxProvaSexo" rel="' + json.prova[$i].sexo + '">' + $txtSexo;
				    
				}
				$sexoPrev = json.prova[$i].sexo;
				
				if($categPrev != json.prova[$i].categoria_idade){
				    if(json.prova[$i].categoria_idade == '0'){
					var $txtCateg = '<h3 class="tltCateg">Livre</h3>';
				    } else {
					var $txtCateg = '<h3 class="tltSexo">Até ' + json.prova[$i].categoria_idade + ' anos</h3>';
				    }
				    
				    $contentDiv = $contentDiv + '<div class="boxProvaCateg" rel="' + json.prova[$i].categoria_idade + '">' + $txtCateg;
				}
				$categPrev = json.prova[$i].categoria_idade;
				
				$contentDiv = $contentDiv +
				    '<div class="itemInnerRadio">\
					<input type="checkbox" name="prova[]" id="prova' + json.prova[$i].id + '" value="' + json.prova[$i].id + '"\
					    class="check" autocomplete="off"' + $check + ' />\
					<label for="prova' + json.prova[$i].id + '">' + json.prova[$i].titulo + '</label>\
				    </div>';
				
				if(typeof json.prova[$i + 1] == 'undefined' || (typeof json.prova[$i + 1] != 'undefined' && ($divPrev != json.prova[$i + 1].divisao))){
				    $contentDiv = $contentDiv + '<br clear="all" /></div>';
				    $sexoPrev = '';
				    $categPrev = '';
				}
				
				if(typeof json.prova[$i + 1] == 'undefined' || (typeof json.prova[$i + 1] != 'undefined' && ($sexoPrev != json.prova[$i + 1].sexo))){
				    $contentDiv = $contentDiv + '<br clear="all" /></div>';
				    $categPrev = '';
				}
				
				if(typeof json.prova[$i + 1] == 'undefined' || (typeof json.prova[$i + 1] != 'undefined' && ($categPrev != json.prova[$i + 1].categoria_idade))){
				    $contentDiv = $contentDiv + '<br clear="all" /></div><br clear="all" />';
				}
			    }
			    
			    $boxProva.html($contentDiv).parent().fadeIn('fast');
			} else {
			    $('.containerProvas').html('');
			}
			
			if(typeof json.cidade != 'undefined' && json.cidade.length){
			    var $contentDiv = '';
			    var $check = (json.cidade.length == 1) ? 'checked="checked"' : '';
			    var $i = 0;
			    
			    $waitCid = setInterval(function(){
				$item = json.cidade.shift();
				$contentDiv = $contentDiv +
				    '<div class="itemInnerRadio itemInnerRadioHide">\
					<input type="checkbox" name="cidade[]" id="cidade' + $item.id + '" value="' + $item.id + '"\
					    class="check" autocomplete="off"' + $check + ' />\
					<label for="cidade' + $item.id + '">' + $item.nome + '</label>\
				    </div>';
				$cidadesMod[$i++] = $item.modalidade_sub;
				
				if(json.cidade.length == 0){
				    $contentDiv = $contentDiv + '<br clear="all" />';
				    $boxCidade.html($contentDiv).parent().fadeIn('fast');
				    $formAjaxSend.enabled($('#formProgramacao'));
				    $('#loading2 .boxLoading').fadeOut('fast');
				    $('#boxFormBot').fadeIn();
				    clearInterval($waitCid);
				}
			    }, 10);
			} else {
			    $formAjaxSend.enabled($('#formProgramacao'));
			    $('#loading2 .boxLoading').fadeOut('fast');
			    $('#boxFormBot').fadeIn();
			}
			
			if($validaUnico){
			    $boxCidade.find('.itemInnerRadio').show();
			}
			
		    }
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
		    $formAjaxSend.enabled($('#formProgramacao'));
		    $('#loading2 .boxLoading').fadeOut('fast');
		}
	    });
	}	
    });
    
    $('.containerDivisao input, .containerCategoria input, .containerSexo input').live('change', function(){
	$cidades.show();
	if($.trim($('.containerProvas').text()) != ''){
	    $provas.show();
	}	
    });
    
    $('a.btnSelectAll').live('click', function(){
	var $target = $(this).attr('href').split('#')[1];
	$(this).parent().find('.' + $target).each(function(){
	    $(this).find('input').each(function(){
		$(this).attr('checked','checked');
	    });
	});
	return false;
    });
    
    $('a.btnDeleteAll').live('click', function(){
	var $target = $(this).attr('href').split('#')[1];
	$(this).parent().find('.' + $target).each(function(){
	    $(this).find('input').each(function(){
		$(this).removeAttr('checked');
	    });
	});
	return false;
    });

    $cidades = {
	show: function($show){
	    $itensMod = [];
	    $index = 0;
	    $validDiv = $validCateg = $validSexo = false;
	    
	    if(typeof $itemDiv == 'undefined'){
		var $itemDiv = $('.containerDivisao input');
		var $totalDiv = $itemDiv.length;
	    }
	    if(typeof $itemCateg == 'undefined'){
		var $itemCateg = $('.containerCategoria input');
		var $totalCateg = $itemCateg.length;
	    }
	    if(typeof $itemSexo == 'undefined'){
		var $itemSexo = $('.containerSexo input');
		var $totalSexo = $itemSexo.length;
	    }	
    
	    for($i = 0; $i < $totalDiv; $i++){
		$itensBaseDiv = '';
		if($itemDiv.eq($i).is(':checked')){
		    $validDiv = true;
		    $itensMod[$index] = $prefixMod + $itemDiv.eq($i).attr('rel');
		    $itensBaseDiv = $itensMod[$index];
		    for($j = 0; $j < $totalCateg; $j++){
			if($itemCateg.eq($j).is(':checked')){
			    $validCateg = true;
			    $itensMod[$index] = $itensBaseDiv + $itemCateg.eq($j).attr('rel');
			    $itensBaseCateg = $itensMod[$index];
			    for($k = 0; $k < $totalSexo; $k++){
				if($itemSexo.eq($k).is(':checked')){
				    $validSexo = true;
				    $itensMod[$index++] = $itensBaseCateg + $itemSexo.eq($k).attr('rel');
				}
			    }
			    if($validSexo == false){
				delete($itensMod[$index]);
				break;
			    }
			}
		    }
		    if($validCateg == false){
			delete($itensMod[$index]);
			break;
		    }
		}
	    }

	    if($itensMod.length){
		$itemCidades = $('.containerCidades .itemInnerRadio');
		$itemCidades.hide();
		for($i = 0; $i < $cidadesMod.length; $i++){
		    for($j = 0; $j < $cidadesMod[$i].length; $j++){
			for($k = 0; $k < $itensMod.length; $k++){
			    if($itensMod[$k] == $cidadesMod[$i][$j]){
				$itemCidades.eq($i).show();
			    }
			}
		    }
		}
	    }
	    
	    if(typeof $show != 'undefined' && $show == true){
		$('#itemProgCidade, #boxFormBot').fadeIn('fast');
		$('#loading2 .boxLoading').fadeOut('fast');
	    }
	}
    }
    
    $provas = {
	show: function(){
	    var $checkDiv = $('input.checkDiv');
	    var $checkSexo = $('input.checkSexo');
	    var $checkCateg = $('input.checkCateg');
	    $('.boxProvaDiv').hide();
	    $('.boxProvaSexo').hide();
	    $('.boxProvaCateg').hide();
	    $checkDiv.each(function(){
		if($(this).attr('checked')){
		    $('.boxProvaDiv[rel="' + $(this).val() + '"]').show();
		}
	    });
	    
	    $checkSexo.each(function(){
		if($(this).attr('checked')){
		    $('.boxProvaSexo[rel="' + $(this).val() + '"]').show();
		}
	    });
	    
	    $checkCateg.each(function(){
		if($(this).attr('checked')){
		    $('.boxProvaCateg[rel="' + $(this).val() + '"]').show();
		}
	    });
	    
	    $('#itemProgProva').show();
	}
    }

    $('#formProgramacao').validate({ errorContainer: $('.boxMsg p.error'), errorLabelContainer: $('#msgLabel'),
	submitHandler: function() {
	    
	    if($('input#provaResultado').length){
		var $confirmEdit = window.confirm('Ao salvar essas alterações, você vai precisar cadastrar o resultado dessa prova novamente. Deseja continuar?');
		if(!$confirmEdit){
		    window.location = '/admin/programacao.php';
		    return false;
		}
	    }
	    
	    $('.boxMsg p').hide();
	    $('#boxLoading').fadeIn('fast');
	    $('#itemProgCidade .itemInnerRadio input').each(function(){
		if($(this).is(':checked') && !($(this).is(':visible'))){
		    $(this).removeAttr('checked');
		}
	    });
	    
	    $('.containerProvas .boxProvaCateg').each(function(){
		if(!($(this).is(':visible'))){
		    $(this).find('input').each(function(){
			$(this).removeAttr('checked');
		    });
		}
	    });
	    
	    $wait = setTimeout(function(){
		$.ajax({
		    type: "POST",
		    data: $("#formProgramacao").serialize(),
		    dataType: "json",
		    url: $("#formProgramacao").attr('action'),
		    success: function(json){
			$('#boxLoading').hide();
			if(typeof json.status == 'undefined'){
			    $('.boxMsg p').hide();
			    $('.boxMsg p.errorServer').fadeIn('fast');
			    $wait = setTimeout(function(){
				$('.boxMsg p').fadeOut('fast');
			    }, 5000);
			} else if(json.status == "ok"){
			    $('.itemHideValidate').hide();
			    $('#msgSucesso').fadeIn('slow');
			    $('#formProgramacao')[0].reset();
			    $('#btnVoltar').show();
			    $waitSucess = setTimeout(function(){
				$('#msgSucesso').fadeOut('fast');
			    }, 10000);
			    if($('input#provaResultado').length){
				$('#boxFormBot').show().find('.item, input').hide();
				$('#boxFormBot a.btnResultProg').text($('a.btnResultProg').text().replace('Editar', 'Cadastrar')).parent().show();
			    }
			} else if(json.status == "error") {
			    $('.boxMsg p').hide();
			    $('.boxMsg p.errorServer').fadeIn('fast');
			    $wait = setTimeout(function(){
				$('.boxMsg p').fadeOut('fast');
			    }, 5000);
			}
		    },
		    error: function(XMLHttpRequest, textStatus, errorThrown){
			$('.boxMsg p').hide();
			$('.boxMsg p.errorServer').fadeIn('fast');
			$wait = setTimeout(function(){
			    $('.boxMsg p').fadeOut('fast');
			}, 5000);
		    }
		});
	    }, 10);
	},
	highlight: function(element, errorClass, validClass) {
	    if (element.type === 'radio' || element.type === 'checkbox') {
		$('label[for="' + $(element).attr('name').replace('[]','') + '"]').addClass(errorClass).removeClass(validClass);
		$('input[name="' + $(element).attr('name') + '"]').next().addClass(errorClass).removeClass(validClass);
		$('input[name="' + $(element).attr('name') + '"]').addClass(errorClass).removeClass(validClass);
	    } else if(element.type === 'textarea'){
		$('iframe#' + $(element).attr('id')  + '_ifr').addClass(errorClass).removeClass(validClass);
		$(element).prev().addClass(errorClass).removeClass(validClass);
	    } else {
		$(element).addClass(errorClass).removeClass(validClass);
		$(element).prev().addClass(errorClass).removeClass(validClass);
	    }
	},
	unhighlight: function(element, errorClass, validClass) {
	    if (element.type === 'radio' || element.type === 'checkbox') {
		$('label[for="' + $(element).attr('name').replace('[]','') + '"]').removeClass(errorClass).addClass(validClass);
		$('input[name="' + $(element).attr('name') + '"]').next().removeClass(errorClass).addClass(validClass);
		$('input[name="' + $(element).attr('name') + '"]').removeClass(errorClass).addClass(validClass);
	    } else if(element.type === 'textarea'){
		$('iframe#' + $(element).attr('id')  + '_ifr').removeClass(errorClass).addClass(validClass);
		$(element).prev().removeClass(errorClass).addClass(validClass);
	    } else {
		$(element).removeClass(errorClass).addClass(validClass);
		$(element).prev().removeClass(errorClass).addClass(validClass);
	    }
	}
    });
    
    $('.formFiltroProg').submit(function(){
	$(this).find('select').each(function(){
	    if($.trim($(this).val()) == ''){
		$(this).removeAttr('name');
	    }
	});
    });
    
    $('#formResultProgramacao input.checkVencedor').live('change', function(){
	$('#formResultProgramacao .formConfResult').val('');
    });
    
    $('#formResultProgramacao input.formConfResult').live('blur', function(){
	var $atualiza = true;
	for($i = 0; $i < 2; $i++){
	    if($.trim($('#formResultProgramacao input.formConfResult').eq($i).val()) == ''){
		$atualiza = false;
	    }
	}
	if($atualiza){
	    $('#formResultProgramacao input.checkVencedor:checked').attr('checked', false);
	}
    });

});

function act_result(){
    if($('table.tbProg').length){
	
	if($.trim($('#hdArquivo').val()) != ''){
	    $edit = true;
	} else {
	    $edit = false;
	}
	
	$('#formResultProgramacao').validate({ errorContainer: $('.boxMsg p.error'), errorLabelContainer: $('#msgLabel'),
	    submitHandler: function() {
		$('.boxMsg p').hide();
		$('#boxLoading').fadeIn('fast');
		$('#itemProgCidade .itemInnerRadio input').each(function(){
		    if($(this).is(':checked') && !($(this).is(':visible'))){
			$(this).removeAttr('checked');
		    }
		});
		$wait = setTimeout(function(){
		    $.ajax({
			type: "POST",
			data: $("#formResultProgramacao").serialize(),
			dataType: "json",
			url: $("#formResultProgramacao").attr('action'),
			success: function(json){
			    $('#boxLoading').hide();
			    if(typeof json == 'object' && json == null){
				$('.boxMsg p').hide();
				$('.boxMsg p.msgErrorServer').fadeIn('fast');
				$wait = setTimeout(function(){
				    $('.boxMsg p').fadeOut('fast');
				}, 5000);
			    } else if(json.status == "ok"){
				$.fancybox({ 'href': '#lighboxSucesso', 'transitionIn': 'elastic', 'transitionOut': 'elastic', 'speedIn': 500, 'speedOut': 300, 'padding': 5, 'margin': 20, 'overlayOpacity': 0.70, 'overlayColor': '#FFFFFF' });
			    } else if(json.status == "error") {
				$('.boxMsg p').hide();
				$('.boxMsg p.msgErrorServer').fadeIn('fast');
				$wait = setTimeout(function(){
				    $('.boxMsg p').fadeOut('fast');
				}, 5000);
			    }
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
			    $('#boxLoading').hide();
			    $('.boxMsg p').hide();
			    $('.boxMsg p.msgErrorServer').fadeIn('fast');
			    $wait = setTimeout(function(){
				$('.boxMsg p').fadeOut('fast');
			    }, 5000);
			}
		    });
		}, 10);
	    },
	    highlight: function(element, errorClass, validClass) {
		if (element.type === 'radio' || element.type === 'checkbox') {
		    $('label[for="' + $(element).attr('name').replace('[]','') + '"]').addClass(errorClass).removeClass(validClass);
		    $('input[name="' + $(element).attr('name') + '"]').next().addClass(errorClass).removeClass(validClass);
		    $('input[name="' + $(element).attr('name') + '"]').addClass(errorClass).removeClass(validClass);
		} else if(element.type === 'textarea'){
		    $('iframe#' + $(element).attr('id')  + '_ifr').addClass(errorClass).removeClass(validClass);
		    $(element).prev().addClass(errorClass).removeClass(validClass);
		} else {
		    $(element).addClass(errorClass).removeClass(validClass);
		    $(element).prev().addClass(errorClass).removeClass(validClass);
		}
	    },
	    unhighlight: function(element, errorClass, validClass) {
		if (element.type === 'radio' || element.type === 'checkbox') {
		    $('label[for="' + $(element).attr('name').replace('[]','') + '"]').removeClass(errorClass).addClass(validClass);
		    $('input[name="' + $(element).attr('name') + '"]').next().removeClass(errorClass).addClass(validClass);
		    $('input[name="' + $(element).attr('name') + '"]').removeClass(errorClass).addClass(validClass);
		} else if(element.type === 'textarea'){
		    $('iframe#' + $(element).attr('id')  + '_ifr').removeClass(errorClass).addClass(validClass);
		    $(element).prev().removeClass(errorClass).addClass(validClass);
		} else {
		    $(element).removeClass(errorClass).addClass(validClass);
		    $(element).prev().removeClass(errorClass).addClass(validClass);
		}
	    }
	});
	
	$('a.btnBoxPdfResult').click(function(){
	    $(this).after('<strong class="strgPdf">Arquivo:</strong>').hide();
	    createUploadifyInstanceArquivo('#arquivo', '*.pdf', 'Arquivos PDF', 'programacao-resultado-upload.php');
	    return false;
	});
	
	$('a.excArquivo.excProg').click(function(){
	    $(this).hide();
	    $('input#hdArquivo').val('');
	    $('span.userArquivo').html('').hide();
	    $('strong.strgPdf').hide();
	    
	    if($edit && !($('input#uploadArquivo').length)){
		$('input#hdArquivo').after('<input type="hidden" name="upload" id="uploadArquivo"><input type="hidden" name="excluirArquivo" id="excluirArquivo" />');
	    }
	    
	    destroyUploadifyInstance('#arquivo');
	    if($('#boxResultProg').hasClass('resultado-tipo3')){
		$('strong.strgPdf').show();
		createUploadifyInstanceArquivo('#arquivo', '*.pdf', 'Arquivos PDF', 'programacao-resultado-upload.php');
	    } else {
		$('a.btnBoxPdfResult').removeClass('hideBtn').show();
	    }

	    return false;
	});
    }
}

jQuery.validator.addMethod("numero-int", function(value, element) {
   return this.optional(element) || (!(/^0+/.test(value)) || value == '0');
}, 'Número Inválido');

jQuery.validator.addMethod("email-list", function(value, element) {
    if(typeof $validator_emails == 'undefined'){
	$validator_emails = [];
	return true;
    }
    
    for($i=0; $i < $validator_emails.length; $i++){
	if($validator_emails[$i].indexOf(value) != -1){
	    return false;
	}
    }
    
    return true;
}, 'Email Inválido');

jQuery.validator.addMethod("cpf", function(value, element) {
    cpf = value.toString().replace( /\.|\-/g, "" );
    var expReg = /^0+$|^1+$|^2+$|^3+$|^4+$|^5+$|^6+$|^7+$|^8+$|^9+$/;
    if (cpf.length != 11 || cpf.match(expReg) ){
        return false;
    }
    add = 0;
    for (i=0; i < 9; i ++){
        add += parseInt(cpf.charAt(i)) * (10 - i);
    }
    rev = 11 - (add % 11);
    if (rev == 10 || rev == 11){
        rev = 0;
    }
    if (rev != parseInt(cpf.charAt(9))){
        return false;
    }
    add = 0;
    for (i = 0; i < 10; i ++){
        add += parseInt(cpf.charAt(i)) * (11 - i);
    }
    rev = 11 - (add % 11);
    if (rev == 10 || rev == 11){
        rev = 0;
    }
    if (rev != parseInt(cpf.charAt(10))){
        return false;
    }
    return true;
}, "Informe um CPF válido.");

jQuery.validator.addMethod("cpf-list", function(value, element) {
    if(typeof $validator_cpf == 'undefined'){
	$validator_cpf = [];
	return true;
    }
    
    for($i=0; $i < $validator_cpf.length; $i++){
	if($validator_cpf[$i].indexOf(value) != -1){
	    return false;
	}
    }
    
    return true;
},'CPF inválido.');

$formAjaxSend = {
    disabled: function($form){
	$form.find('input:text, input:radio, input:checkbox, select, textarea').attr('disabled','disabled').removeClass('enabled').addClass('disabled');
    },
    enabled: function($form){
	$form.find('input:text, input:radio, input:checkbox, select, textarea').removeAttr('disabled').removeClass('disabled').addClass('enabled');
    }
}

function roundDecimalNumber(num, dec) {
    var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
    return result;
}

function loadImage($urls, $callback, $obj){
    if(typeof $callback == 'undefined'){
	$callback = false;
    }
    if(typeof $obj == 'undefined'){
	$obj = false;
    }
    for(i in $urls){
        $image = new Image();
        $($image).load(function(){
	    if($callback && $obj){
		insertImg($callback, $obj, $urls[i]);
	    }
	}).attr('src', $urls[i]);
    }
}

function insertImg($callback, $obj, $img){
    switch($callback){
	case 'salvar':
	    $start = false;
	    $obj.find('.jcrop-holder, .contentSave').fadeOut(function(){
		if(!($start)){
		    $start = true;
		    $obj.html('');
		    $obj.append('<img src="' + $img + '" alt="Thumb" /><a class="btnActImg btnActImgAlt" id="btnExcluirImg" href="' + $img + '">Excluir</a><br clear="all" />');
		    if($obj.parents('.item').hasClass('itemImgThumb')){
			$obj.addClass('jcropContentThumb');
		    } else if($obj.parents('.item').hasClass('itemImgThumbBanco')) {
			$obj.addClass('jcropContentBanco');
		    } else if($obj.parents('.item').hasClass('itemImgThumbGaleria')){
			$obj.addClass('jcropContentGaleria');
		    } else {
			var $url = window.location.href;
			$url = $url.split('/admin/')[0];
			$url += $img;
			$url = $url.split('?')[0];
			$obj.find('a.btnActImg').before('<span class="urlImg"><strong>URL:</strong><br />' + $url  + '</span>');
			$obj.addClass('jcropContentImg');
		    }
		    $idInput = '#' + $obj.prev('input').attr('id');
		    $idImg = '#' + $obj.parents('.item').find('input:first').attr('id');
		    destroyUploadifyInstance($idImg);
		    if($obj.hasClass('jcropContentBanco') || $obj.hasClass('jcropContentGaleria')){
			$img = $img.split('/');
			$img = $img[$img.length - 1];
			$img = $img.split('?')[0];
			$($idInput).val($img);
		    } else {
			$($idInput).val($img);
		    }		
		    createUploadifyInstance($idImg, 'Alterar Imagem');
		}		
	    });
	    break;
	case 'upload':

    }
}

function createUploadifyInstanceArquivo($id, $formato, $txtFormato, $actUrl){
    if(typeof $formato == 'undefined'){
	$formato = '*.pdf';
    }
    if(typeof $txtFormato == 'undefined'){
	$txtFormato = 'Arquivos PDF';
    }
    if(typeof $actUrl == 'undefined'){
	$actUrl = 'release-upload.php';
    }
    $($id).uploadify({
	'uploader'  	: '/admin/swf/uploadify.swf',
	'script'    	: '/admin/_func/' + $actUrl,
	'cancelImg' 	: '/admin/img/cancel.png',
	'folder'    	: '/upload/temp',
	'buttonText'	: 'Procurar',
	'fileDesc'	: $txtFormato,
	'fileExt'	: $formato,
	'method'	: 'post',
	'scriptAccess' 	: 'sameDomain',
	'sizeLimit'   	: 10485760,
	'queueID'	: 'queueUpload',
	'auto'      	: true,
	'scriptData'  	: {'acao':'upload'},
	'onOpen'      : function(event,ID,fileObj) {
	    $('span.userArquivo').after('<span class="txtStatusUpload" style="line-height: 22px;">Carregando arquivo ' + fileObj.name + '. Aguarde...</span>');
	},
	'onComplete': function(event, queueID, fileObj, response, data) {
	    $($id).uploadifyClearQueue();
	    $('span.txtStatusUpload').remove();
	    if(response == 'error'){
		alert('Houve um erro durante o upload. Tente novamente.');
	    } else{
		$('#arquivoUploader').hide();
		$('#arquivo').parent().find('.userArquivo').text(response).fadeIn();
		$('a.excArquivo').fadeIn();
		$('#hdArquivo').val(response);
		
		if($($id).parents('form').attr('id') == 'formResultProgramacao'){
		    for($i = 0; $i < 3; $i++){
			$('select#colocacao' + $i + ' option:eq(' + $i + ')').attr('selected', 'selected');
			$('select#cidade' + $i).val('');
			$('input#atleta' + $i).val('');
		    }
		    $('#resultProgConfronto input:text').val('');
		    $('#resultProgConfronto input:radio').removeAttr('checked');
		}
	    }
	}
    });
}

function createUploadifyInstance($id, $txt){
    if(!($txt)){
	$txt = 'Procurar Imagem';
    }
    var $hasUrl = $($id).parent().find('.imgUploadUrl').val();
    if($.trim($hasUrl) && $hasUrl != ''){
	var $urlImg = $hasUrl.replace('/upload/', '');
	$urlImg = $urlImg.replace('.jpg', '');
	$urlImg = $urlImg.split('?')[0];
	$txt = 'Alterar Imagem';
    } else {
	$urlImg = 'NULL';
    }
    
    $($id).uploadify({
	'uploader'  	: '/admin/swf/uploadify.swf',
	'script'    	: '/admin/_func/imagem.php',
	'cancelImg' 	: '/admin/img/cancel.png',
	'folder'    	: '/upload/temp',
	'buttonText'	: $txt,
	'fileDesc'	: 'Arquivos de imagem (JPG, GIF ou PNG)',
	'fileExt'	: '*.jpg;*.gif;*.png',
	'method'	: 'post',
	'scriptAccess' 	: 'sameDomain',
	'sizeLimit'   	: 10485760,
	'auto'      	: true,
	'scriptData'  	: {'acao':'upload','urlImg':$urlImg},
	'onOpen'      	: function(event,ID,fileObj) {
	    $('input.imgUpload').each(function(){
		$idElem = $(this).attr('id') + ID;
		if($('#' + $idElem).html() != null){
		    $localAppend = $('#' + $idElem).parents('.item:first');
		}
	    });
	},
	'onComplete': function(event, queueID, fileObj, response, data) {
	    $($id).uploadifyClearQueue();
	    if(response == 'error'){
		alert('Ocorreu um erro. Tente novamente.');
	    } else{
		response = response + '?' + new Date().getTime();
		$localAppend.find('.jcropContent').remove();
		$tipoImg = $localAppend.attr('rel');
		$htmlImg = '\
		    <div class="jcropContent">\
			<input type="hidden" name="x" class="x" value="" />\
			<input type="hidden" name="y" class="y" value="" />\
			<input type="hidden" name="x2" class="x2" value="" />\
			<input type="hidden" name="y2" class="y2" value="" />\
			<input type="hidden" name="w" class="w" value="" />\
			<input type="hidden" name="h" class="h" value="" />\
			<div class="boxImg">\
			    <img class="jcropTarget" src="' + response + '" />\
			</div>\
			<a class="btnActImg" id="btnSalvarImg" href="' + response + '">Salvar</a>\
			<a class="btnActImg" id="btnExcluirImg" href="' + response + '">Excluir</a>\
			<br clear="all" />\
		    </div>\
		';
		$localAppend.append($htmlImg);
		$showCoords = function(c){
		    $('.jcropContent.selected .x').val(c.x);
		    $('.jcropContent.selected .y').val(c.y);
		    $('.jcropContent.selected .x2').val(c.x2);
		    $('.jcropContent.selected .y2').val(c.y2);
		    $('.jcropContent.selected .w').val(c.w);
		    $('.jcropContent.selected .h').val(c.h);
		}

		if($tipoImg == 'thumb'){
		    $ratio = 1.50246;
		} else if($tipoImg == 'thumbBanco') {
		    $ratio = 1.1667;
		} else if($tipoImg == 'thumbGaleria'){
		    $ratio = 1;
		} else {
		    $ratio = 1.5013;
		}
		$localAppend.find('.jcropTarget').Jcrop({ onChange: $showCoords, onSelect: $showCoords, setSelect: [100, 100, 50, 50], aspectRatio: $ratio }).parents('.jcropContent').addClass('selected');
		
		$('.jcropContent').live('mouseenter', function(){
		    $(this).addClass('selected');
		}).live('mouseleave', function(){
		    $(this).removeClass('selected');
		});
	    }
	}
    });
}

function destroyUploadifyInstance($id){
    $($id + 'Queue').remove();
    swfobject.removeSWF($id + 'Uploader');
    $($id + 'Uploader').remove();
    $($id).parent().find('.imgUploadUrl').val('');
}


/* HINT */
/**
* @author Remy Sharp
* @url http://remysharp.com/2007/01/25/jquery-tutorial-text-box-hints/
*/
(function($) {

	$.fn.hint = function(blurClass) {
		if (!blurClass) {
			blurClass = 'blur';
		}

		return this.each(function() {
			// get jQuery version of 'this'
			var $input = $(this),

			// capture the rest of the variable to allow for reuse
      title = $input.attr('title'),
      $form = $(this.form),
      $win = $(window);

			function remove() {
				if ($input.val() === title && $input.hasClass(blurClass)) {
					$input.val('').removeClass(blurClass);
				}
			}

			// only apply logic if the element has the attribute
			if (title) {
				// on blur, set value to title attr if text is blank
				$input.blur(function() {
					if (this.value === '') {
						$input.val(title).addClass(blurClass);
					}
				}).focus(remove).blur(); // now change all inputs to title

				// clear the pre-defined text when form is submitted
				$form.submit(remove);
				$win.unload(remove); // handles Firefox's autocomplete
			}
		});
	};

})(jQuery);
/* HINT */

function addslashes(str) {
    str = str.replace(/\\/g,'\\\\');
    str = str.replace(/\'/g,'\\\'');
    str = str.replace(/\"/g,'\\"');
    str = str.replace(/\0/g,'\\0');
    return str;
}

function stripslashes(str) {
    str=str.replace(/\\'/g,'\'');
    str=str.replace(/\\"/g,'"');
    str=str.replace(/\\0/g,'\0');
    str=str.replace(/\\\\/g,'\\');
    return str;
}

function escapeHtml(unsafe) {
  return unsafe
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

function urlencode(str) {
 
    var histogram = {}, tmp_arr = [];
    var ret = (str+'').toString();
 
    var replacer = function(search, replace, str) {
        var tmp_arr = [];
        tmp_arr = str.split(search);
        return tmp_arr.join(replace);
    };
 
    // The histogram is identical to the one in urldecode.
    histogram["'"]   = '%27';
    histogram['(']   = '%28';
    histogram[')']   = '%29';
    histogram['*']   = '%2A';
    histogram['~']   = '%7E';
    histogram['!']   = '%21';
    histogram['%20'] = '+';
    histogram['\u20AC'] = '%80';
    histogram['\u0081'] = '%81';
    histogram['\u201A'] = '%82';
    histogram['\u0192'] = '%83';
    histogram['\u201E'] = '%84';
    histogram['\u2026'] = '%85';
    histogram['\u2020'] = '%86';
    histogram['\u2021'] = '%87';
    histogram['\u02C6'] = '%88';
    histogram['\u2030'] = '%89';
    histogram['\u0160'] = '%8A';
    histogram['\u2039'] = '%8B';
    histogram['\u0152'] = '%8C';
    histogram['\u008D'] = '%8D';
    histogram['\u017D'] = '%8E';
    histogram['\u008F'] = '%8F';
    histogram['\u0090'] = '%90';
    histogram['\u2018'] = '%91';
    histogram['\u2019'] = '%92';
    histogram['\u201C'] = '%93';
    histogram['\u201D'] = '%94';
    histogram['\u2022'] = '%95';
    histogram['\u2013'] = '%96';
    histogram['\u2014'] = '%97';
    histogram['\u02DC'] = '%98';
    histogram['\u2122'] = '%99';
    histogram['\u0161'] = '%9A';
    histogram['\u203A'] = '%9B';
    histogram['\u0153'] = '%9C';
    histogram['\u009D'] = '%9D';
    histogram['\u017E'] = '%9E';
    histogram['\u0178'] = '%9F';
 
    // Begin with encodeURIComponent, which most resembles PHP's encoding functions
    ret = encodeURIComponent(ret);
 
    for (search in histogram) {
        replace = histogram[search];
        ret = replacer(search, replace, ret) // Custom replace. No regexing
    }
 
    // Uppercase for full PHP compatibility
    return ret.replace(/(\%([a-z0-9]{2}))/g, function(full, m1, m2) {
        return "%"+m2.toUpperCase();
    });
}

function urldecode(str) {
 
    var histogram = {};
    var ret = str.toString();
 
    var replacer = function(search, replace, str) {
        var tmp_arr = [];
        tmp_arr = str.split(search);
        return tmp_arr.join(replace);
    };
 
    // The histogram is identical to the one in urlencode.
    histogram["'"]   = '%27';
    histogram['(']   = '%28';
    histogram[')']   = '%29';
    histogram['*']   = '%2A';
    histogram['~']   = '%7E';
    histogram['!']   = '%21';
    histogram['%20'] = '+';
    histogram['\u20AC'] = '%80';
    histogram['\u0081'] = '%81';
    histogram['\u201A'] = '%82';
    histogram['\u0192'] = '%83';
    histogram['\u201E'] = '%84';
    histogram['\u2026'] = '%85';
    histogram['\u2020'] = '%86';
    histogram['\u2021'] = '%87';
    histogram['\u02C6'] = '%88';
    histogram['\u2030'] = '%89';
    histogram['\u0160'] = '%8A';
    histogram['\u2039'] = '%8B';
    histogram['\u0152'] = '%8C';
    histogram['\u008D'] = '%8D';
    histogram['\u017D'] = '%8E';
    histogram['\u008F'] = '%8F';
    histogram['\u0090'] = '%90';
    histogram['\u2018'] = '%91';
    histogram['\u2019'] = '%92';
    histogram['\u201C'] = '%93';
    histogram['\u201D'] = '%94';
    histogram['\u2022'] = '%95';
    histogram['\u2013'] = '%96';
    histogram['\u2014'] = '%97';
    histogram['\u02DC'] = '%98';
    histogram['\u2122'] = '%99';
    histogram['\u0161'] = '%9A';
    histogram['\u203A'] = '%9B';
    histogram['\u0153'] = '%9C';
    histogram['\u009D'] = '%9D';
    histogram['\u017E'] = '%9E';
    histogram['\u0178'] = '%9F';
 
    for (replace in histogram) {
        search = histogram[replace]; // Switch order when decoding
        ret = replacer(search, replace, ret) // Custom replace. No regexing   
    }
 
    // End with decodeURIComponent, which most resembles PHP's encoding functions
    ret = decodeURIComponent(ret);
 
    return ret;
}