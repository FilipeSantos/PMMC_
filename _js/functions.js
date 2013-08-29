jQuery(document).ready(function(){
	$addThis = {
		init: function(){
			var addthisurl = 'http://s7.addthis.com/js/250/addthis_widget.js';
			if (window.addthis) {
				window.addthis = null;
			}
			window.addthis_share = {
				templates: { twitter: '{{title}} {{url}}' }
			}
			$.getScript(addthisurl);
		}
	}
	
	/*$('#get-bg').load('_inc/bg.php');
	$('#get-header').load('_inc/header.php');
	$('#get-footer').load('_inc/footer.php');
	$('#parallax').height($('html').height()).jparallax();*/
	$('#parallax').jparallax({}, {}, {xtravel: '200px'});
	
	$('a.disabledClick').click(function(){
		return false;	
	});
	
	//esconde menu do top-frame
	$(".sala ul.nav-sub").hide();
	$('.nav-header > .sala').bind('mouseenter click',function(){
		$("ul.nav-sub").stop(true, true).slideDown();
	}).bind('mouseleave',function(){
		$("ul.nav-sub").stop(true, true).slideUp();
	});
	
	//esconde menu principal
	$("#nav-bar li ul.navbar-sub").hide();
	$('#nav-bar > li').bind('mouseenter click',function(){
		$(this).attr('id', 'ativo');
		$(this).find('> a').css('background-color','#e3d200');
		$(this).find('> ul').stop(true, true).show('slow', function(){
			larguraSub = $(this).width();
			larguraSub = $(this).find('> li .sombra').width(larguraSub);
		});
	}).bind('mouseleave',function(){
		$(this).find('> ul').stop(true, true).hide('slow');
		$(this).find('> a').css('background-color','');
		$(this).attr('id', '');
	});
	
	//hover do menu footer
	$('.nav-footer > li').hover(function(){
		$(this).attr('id', 'ativo');
	},
	function(){
		$(this).attr('id', '');
  	});
	;
	$('#time').countdown({until: new Date(2011, 11 - 1, 7), format: 'dn', 
		layout: '<div class="number" id="contador">{d1000}</div><div class="number" id="contador">{d100}</div><div class="number" id="contador">{d10}</div><div class="number" id="contador">{d1}</div>'});
	
	// paginação
	var $widthPag = 0;
	$('.pagination:first a, .pagination:first span').each(function(){
	    $widthPag += $(this).outerWidth(true);
	});
	$('.pagination').width(++$widthPag).css({ 'visibility':'visible' }).hide().fadeIn('slow');
	
	$('.redes_sociais').each(function(){
		$addThis.init();
	});
	
	
	$('span.itemSelected').click(function($event){
		$event.stopPropagation();
		$this = $(this);        
		if(!($this.hasClass('disabled')) && !($this.hasClass('itemSelectedDisabled'))){
		    $this.addClass('disabled');
		    if($this.hasClass('itemSelectedOpen')){
			if(typeof $close != 'undefined'){
			    clearTimeout($close);
			}                
			$this.next().slideUp('fast', function(){
			    $this.removeClass('itemSelectedOpen disabled');
			});
		    } else {
			$this.next().slideDown('normal', function(){
				$this.removeClass('disabled');
				$this.addClass('itemSelectedOpen');
			}).mouseleave(function(){
			    var $this = $(this);
			    $close = setTimeout(function(){
				$this.slideUp('fast', function(){
				    $this.prev().removeClass('itemSelectedOpen disabled');
				});
			    }, 3000);
			}).mouseenter(function(){
			    if(typeof $close != 'undefined'){
				clearTimeout($close);
				delete($close);
			    }
			});
		    }
		}
	});
	
	$('.listSelectItem').mouseleave(function(){
		var $this = $(this);
		$close = setTimeout(function(){
			$this.slideUp('fast', function(){
				$this.prev().removeClass('itemSelectedOpen disabled');
			});
		}, 3000);
	}).mouseenter(function(){
		if(typeof $close != 'undefined'){
			clearTimeout($close);
			delete($close);
		}
	});


	$('.placeSelect .listSelectItem li').hover(function(){
	    $(this).addClass('hover');
	}, function(){
	    $(this).removeClass('hover');
	});

	$('.listSelectItem li').click(function($event){
	    $event.stopPropagation();
	    var $parent = $(this).parents('.listSelectItem');
	    var $txt = $(this).text();
	    var $selected = $parent.prev();
	    var $loading = $selected.find('.loading');

	    var $baseUrl = $(this).hasClass('item-modalidade') ? 'modalidade' : 'categoria';
	    var $permalink = '/' + $baseUrl + '/' + $(this).attr('rel');
	    
	    $('.listSelectItem li').removeClass('active');
	    $(this).addClass('active');
	    if(typeof $close != 'undefined'){
		clearTimeout($close);
	    }

	    $loading.fadeIn();
		    
	    $parent.slideUp('fast', function(){
		    $selected.removeClass('itemSelectedOpen disabled').text($txt.substr(0, 25));
		    window.location = $permalink;
	    });
	});
	
	$('html').click(function(){
		$('.listSelectItem').slideUp('fast', function(){
		    $(this).prev().removeClass('itemSelectedOpen disabled');
		});
	});
	
	$('.contentNoticia img.imgDesc2').each(function(){
		if(typeof $(this).attr('style') != 'undefined'){
			if($(this).attr('style').indexOf('float: right') != -1){
				$(this).addClass('imgDescRight');
			}
		}
	});
	
	$('#boxSideFotos ul  li a, .galeria_f ul li a').hover(function(){
		$(this).stop().fadeTo(300, 1);
	}, function(){
		$(this).stop().fadeTo(200, 0.5);
	});
	
	$('ul#listHomeModalidades li a, ul#listParticipantesModalidades li a, ul.listPartModalidades li a').hover(function(){
		var $this = $(this);
		$this.parent().addClass('itemActive');
		if(typeof $waitShow != 'undefined'){
			clearTimeout($waitShow);
		}
		$waitShow = setTimeout(function(){
			$this.next().stop(true, true).fadeIn();
		}, 100);
	}, function(){
		var $this = $(this);
		if(typeof $waitShow != 'undefined'){
			clearTimeout($waitShow);
		}
		$(this).next().stop(true, true).fadeOut(function(){
			$this.parent().removeClass('itemActive');
		});
	});
	
	$('ul#listHomeModalidades li .tooltip .top, ul#listHomeModalidades li .tooltip .content, ul.listPartModalidades li .tooltip .top, ul.listPartModalidades li .tooltip .content, ul.listPartModalidades li .tooltip .content').hover(function(){
		$(this).parent().stop(true, true).show();
	}, function(){
		$(this).parent().fadeOut();
	});
	
	$('ul#listHomeModalidades li .tooltip .top, ul#listHomeModalidades li .tooltip .content, ul#listPartModalidades li .tooltip .top, ul.listPartModalidades li .tooltip .content, ul.listPartModalidades li .tooltip .content').click(function(){
		window.location = $(this).parent().prev().attr('href');
	});
	
	$('#formSearch').submit(function(){
		if($.trim($(this).find('input:text').val()) == '' || $(this).find('input:text').val().length <= 2){
			$(this).find('input:text').focus();
		} else {
			var $busca = urlencode($('#formSearch').serialize().split('=')[1]);
			window.location = '/busca/' + $busca.substr(0, 1024);
		}
		
		return false;
	});
	
	$('ul#listTab li a').hover(function(){
		if(!($(this).hasClass('active'))){
			$(this).stop().animate({ 'height':'59px', 'marginTop':'0px', 'lineHeight':'59px' }, 'fast', 'easeInQuart');
			
		}
	}, function(){
		if(!($(this).hasClass('active'))){
			$(this).stop().animate({ 'height':'38px', 'marginTop':'21px', 'lineHeight':'38px' }, 'fast', 'easeOutQuart');
		}
	}).click(function(){
		$(this).blur();
		var $this = $(this);
		var $target = $this.attr('href');
		if(!($this.hasClass('active'))){
			if($('ul#listTab li a.active').length){
				$('ul#listTab li a.active').stop().animate({ 'height':'38px', 'marginTop':'21px', 'lineHeight':'38px' }, 'fast', 'easeOutQuart', function(){
					$('ul#listTab li a.active').removeClass('active');
					$this.addClass('active').stop().animate({ 'height':'59px', 'marginTop':'0px', 'lineHeight':'59px' }, 'fast', 'easeInQuart');
				});
			} else {
				$this.stop().animate({ 'height':'59px', 'marginTop':'0px', 'lineHeight':'59px' }, 'fast', 'easeInQuart', function(){
					$this.addClass('active')
				});
			}
			$('#containerTab .tab').hide();
			$('#containerTab ' + $target).fadeIn('slow');
			//$('.scroll-pane').jScrollPane({	scrollbarWidth: 16 });
		}
		return false;
	});
	
	$('ul#infoTabSub li a').click(function(){
		var $this = $(this);
		var $target = $this.attr('href');
		if(!($this.hasClass('active'))){
			$('ul#infoTabSub li a.active').removeClass('active');
			$this.addClass('active');
			$('#containerTab .tabInner').hide();
			$('#containerTab ' + $target).fadeIn('slow');
		}
		return false;
	});
	
	$('#content_sorteio').each(function(){
		var $url = window.location.href;
		var $index = 0;
		var $indexPcd = 0;
		$url = $url.split('#')[1];
		
		if(typeof $url != 'undefined'){
			switch($url){
				case 'primeira-divisao':
					$index = 0;				
					break;
				case 'segunda-divisao':
					$index = 1;
					break;
				case 'divisao-especial':
					$index = 2;
					break;				
				case 'pcd-primeira-divisao':
					$index = 3;
					break;
				case 'pcd-segunda-divisao':
					$index = 3;
			}
		}
		
		if($index == 3){
			var $divPcd = $url.split('pcd-')[1];
			if(typeof $divPcd != 'undefined'){
				switch($divPcd){
					case 'primeira-divisao':
						$indexPcd = 0;
						break;
					case 'segunda-divisao':
						$indexPcd = 1;
				}
			}
		}
		
		$('ul#listTab li a').eq($index).click();
		$('ul#infoTabSub li a').eq($indexPcd).click();
	});
	
	$('#listUpdates').each(function(){
		$(this).find('#listUpdatesScroll').jScrollPane({ 'animateScroll': true, 'verticalGutter': 0, 'autoReinitialise': true });
		$(this).prepend('<span class="bgTop">&nbsp;</span>');
		$getSorteio = setTimeout($atualizaSorteio, 30000);
	});
	
	$('#listUpdates li.new').live('click', function(){
		$this = $(this);
		$this.find('.liInner').animate({ backgroundColor: '#C9252B' }, function(){
			$this.find('.liInner').removeAttr('style');
		});
		$this.find('.txt').animate({ color: '#FFFFFF' }).css({ 'font-weight': 'normal' }, function(){
			$this.find('.txt').removeAttr('style');
		});
		$this.find('a.linkPdf').animate({ backgroundColor: '#F1F1F1', color: '#7C7C7C' }, function(){
			$this.find('a.linkPdf').removeAttr('style');
			$this.removeClass('new');
		});
	});
	
	$('body.body_programacao, body.body_cidades_participantes, body.modalidades-interna').each(function(){
		var $dateIni = 0;
		var $dateEnd = 0;
		var $dateObj = new Date();
		
		$dateObj.setFullYear(2011, 10, 7, 0, 0, 0, 0);
		$dateIni = $dateObj.getTime();
		
		$dateObj.setFullYear(2011, 10, 19, 23, 59, 59, 0);
		$dateEnd = $dateObj.getTime();

		$('.boxSelect').change(function(){
			var $filtro = [];
			$filtro[0] = $('span#dataFilter').attr('rel') != '' ? $('span#dataFilter').attr('rel') : '0';
			$filtro[1] = $('#divisao').val() != '' ? $('#divisao').val() : '0';
			$filtro[2] = $('#cidades').val() != '' ? $('#cidades').val() : '0';
			$filtro[3] = $('#modalidades').val() != '' ? $('#modalidades').val() : '0';
			$filtro[4] = $('#locais').val() != '' ? $('#locais').val() : '0';
			
			if($(this).attr('id') == 'divisao'){
				var $txtParam = 'select-divisao';
			} else {
				var $txtParam = 'select';
			}
			
			$filtroProgramacao($filtro, $txtParam);
		});
		
		$('#prog-title-programacao a').click(function(){
			$isPrev = ($(this).attr('id') == 'prog-prev');
			if(!($(this).hasClass('hide'))){
				var $date = $(this).attr('href').split('#')[1];
				var $filtro = [];
				$filtro[0] = $date;
				$filtro[1] = $('#divisao').val() != '' ? $('#divisao').val() : '0';
				$filtro[2] = $('#cidades').val() != '' ? $('#cidades').val() : '0';
				$filtro[3] = $('#modalidades').val() != '' ? $('#modalidades').val() : '0';
				$filtro[4] = $('#locais').val() != '' ? $('#locais').val() : '0';
				
				var $datePrev = $('#prog-prev').attr('href').split('#')[1];
				var $dateNext = $('#prog-next').attr('href').split('#')[1];
				
				if($datePrev == ''){
					$datePrev = $dateIni;
				} else {
					$dateObj = new Date();
					$dateObj.setFullYear($datePrev.split('-')[2], parseInt($datePrev.split('-')[1]) - 1, $datePrev.split('-')[0]);
					if($isPrev){
						$dateObj.setHours(0, 0, 0, 0);
					} else {
						$dateObj.setHours(23, 59, 59, 0);	
					}
					$datePrev = $dateObj.getTime();
				}
				
				if($dateNext == ''){
					$dateNext = $dateEnd;
				} else {
					$dateObj = new Date();
					$dateObj.setFullYear($dateNext.split('-')[2], parseInt($dateNext.split('-')[1]) - 1, $dateNext.split('-')[0]);
					if($isPrev){
						$dateObj.setHours(0, 0, 0, 0);
					} else {
						$dateObj.setHours(23, 59, 59, 0);	
					}
					$dateNext = $dateObj.getTime();
				}
				
				
				if($(this).attr('id') == 'prog-prev'){
					$datePrev -= 86400;
					$dateNext -= 86400;
					
					$('#prog-next').removeClass('hide');
					
					if($datePrev < $dateIni){
						$('#prog-prev').addClass('hide');
					} else {
						$('#prog-prev').removeClass('hide');
					}
				} else if($(this).attr('id') == 'prog-next'){
					$datePrev += 86400;
					$dateNext += 86400;
					
					$('#prog-prev').removeClass('hide');
					
					if($dateNext > $dateEnd){
						$('#prog-next').addClass('hide');
					} else {
						$('#prog-next').removeClass('hide');
					}
				}
				
				var $dia = '';
				var $mes = '';
				var $ano = '';
				
				if(!($('#prog-prev').hasClass('hide'))){
					$dateObj.setTime($datePrev);
					$dia = ($dateObj.getDate() < 10) ? '0' + $dateObj.getDate() : $dateObj.getDate();
					$mes = ($dateObj.getMonth() < 9) ? '0' + ($dateObj.getMonth() + 1) : ($dateObj.getMonth() + 1);
					$ano = $dateObj.getFullYear();
					$('#prog-prev').attr('href', '#' + $dia + '-' + $mes + '-' + $ano);
				} else {
					$('#prog-prev').attr('href', '#');
				}
				
				if(!($('#prog-next').hasClass('hide'))){
					$dateObj.setTime($dateNext);
					$dia = ($dateObj.getDate() < 10) ? '0' + $dateObj.getDate() : $dateObj.getDate();
					$mes = ($dateObj.getMonth() < 9) ? '0' + ($dateObj.getMonth() + 1) : ($dateObj.getMonth() + 1);
					$ano = $dateObj.getFullYear();
					$('#prog-next').attr('href', '#' + $dia + '-' + $mes + '-' + $ano);
				} else {
					$('#prog-next').attr('href', '#');
				}

				$filtroProgramacao($filtro, 'data');
			}
			return false;
		});
		
		$('.prog-title-cidades a').click(function(){
			$(this).blur();
			if(!($(this).hasClass('disabled'))){
				$('.prog-title-cidades a').addClass('disabled');
				
				var $isPrev = $(this).hasClass('prog-prev');
				var $boxContainer = $(this).parents('.prog-title-cidades');
				
				if(!($(this).hasClass('hide'))){
					var $date = $(this).attr('href').split('#')[1];
					var $filtro = [];
					$filtro[0] = $date;
					$filtro[1] = $('#listTab li a.active').attr('rel');
					
					if(typeof $filtro[1] == 'undefined'){
						$filtro[1] = $('input#idModalidade').val();
					}

					var $datePrev = $boxContainer.find('.prog-prev').attr('href').split('#')[1];
					var $dateNext = $boxContainer.find('.prog-next').attr('href').split('#')[1];

					if($datePrev == ''){
						$datePrev = $dateIni;
					} else {
						$dateObj = new Date();
						$dateObj.setFullYear($datePrev.split('-')[2], (parseInt($datePrev.split('-')[1]) - 1), $datePrev.split('-')[0]);
						if($isPrev){
							$dateObj.setHours(0, 0, 0, 0);
						} else {
							$dateObj.setHours(23, 59, 59, 0);	
						}
						$datePrev = $dateObj.getTime();
					}
					
					if($dateNext == ''){
						$dateNext = $dateEnd;
					} else {
						$dateObj = new Date();
						$dateObj.setFullYear($dateNext.split('-')[2], (parseInt($dateNext.split('-')[1]) - 1), $dateNext.split('-')[0]);

						if($isPrev){
							$dateObj.setHours(0, 0, 0, 0);
						} else {
							$dateObj.setHours(23, 59, 59, 0);	
						}
						$dateNext = $dateObj.getTime();
					}
					
					
					if($(this).hasClass('prog-prev')){
						$datePrev -= 86400;
						$dateNext -= 86400;

						$boxContainer.find('.prog-next').removeClass('hide');
						
						if($datePrev < $dateIni){
							$boxContainer.find('.prog-prev').addClass('hide');
						} else {
							$boxContainer.find('.prog-prev').removeClass('hide');
						}
					} else if($(this).hasClass('prog-next')){
						$datePrev += 86400;
						$dateNext += 86400;
						
						$boxContainer.find('.prog-prev').removeClass('hide');
						
						if($dateNext > $dateEnd){
							$boxContainer.find('.prog-next').addClass('hide');
						} else {
							$boxContainer.find('.prog-next').removeClass('hide');
						}
					}
					
					var $dia = '';
					var $mes = '';
					var $ano = '';
					
					if(!($boxContainer.find('.prog-prev').hasClass('hide'))){
						$dateObj.setTime($datePrev);
						$dia = ($dateObj.getDate() < 10) ? '0' + $dateObj.getDate() : $dateObj.getDate();
						$mes = ($dateObj.getMonth() < 9) ? '0' + ($dateObj.getMonth() + 1) : ($dateObj.getMonth() + 1);
						$ano = $dateObj.getFullYear();
						$boxContainer.find('.prog-prev').attr('href', '#' + $dia + '-' + $mes + '-' + $ano);
					} else {
						$boxContainer.find('.prog-prev').attr('href', '#');
					}
					
					if(!($boxContainer.find('.prog-next').hasClass('hide'))){
						$dateObj.setTime($dateNext);
						$dia = ($dateObj.getDate() < 10) ? '0' + $dateObj.getDate() : $dateObj.getDate();
						$mes = ($dateObj.getMonth() < 9) ? '0' + ($dateObj.getMonth() + 1) : ($dateObj.getMonth() + 1);
						$ano = $dateObj.getFullYear();
						$boxContainer.find('.prog-next').attr('href', '#' + $dia + '-' + $mes + '-' + $ano);
					} else {
						$boxContainer.find('.prog-next').attr('href', '#');
					}

					$filtroProgramacao($filtro, 'data');
				}
			}
			return false;
		});
		
	});
	
	$('select#quadro_medHome').change(function(){
		var $div = $(this).val();
		var $bgLoading = $('.quadro_in .bgLoading');
		var $loading = $('#loadingMedalhas');
		
		if(!isNaN($div)){
			$bgLoading.show().fadeTo('fast', 0.7);
			$loading.fadeIn('slow', function(){
				$.ajax({
					type: 'POST',
					data: 'divisao=' + $div + '&limit=6',
					dataType: 'json',
					url: '/_func/get-classificacao.php',
					success: function(json){
						if(typeof json == 'object' && json == null){
							$bgLoading.fadeTo('fast', 0.1, function(){
								$bgLoading.hide();
							});
							$loading.fadeOut('slow');
						} else if(json.status == "ok" && json.cidades.length){
							var $total = json.cidades.length;
							var $i = 0;
							var $posPrev = 0;
							var $htmlClassificacao = '';
							var $boxAppend = $('.quadro_in .boxContent');

							$waitClassificacao = setInterval(function(){
								if($i < $total){
									$htmlClassificacao = $htmlClassificacao + '<div class="item">\
										<span class="span1 posicao">' + (json.cidades[$i].pos_pontos != $posPrev ? json.cidades[$i].pos_pontos + 'º' : '') + '</span>\
										<span class="span2">\
											<a href="/jogos/cidades-participantes/' + json.cidades[$i].slug + '" title="' + json.cidades[$i].nome + '">\
											<img src="/_img/cidade/bandeiras/' + json.cidades[$i].bandeira + '" alt="' + json.cidades[$i].nome + '"></a>\
										</span>\
										<span class="span3">' + json.cidades[$i].pontos + '</span>\
									</div>';
									$posPrev = json.cidades[$i].pos_pontos;
									$i++;
								} else {
									$boxAppend.hide().html($htmlClassificacao).fadeIn('fast');
									$bgLoading.fadeTo('fast', 0.1, function(){
										$bgLoading.hide();
									});
									$loading.fadeOut('slow');
									clearInterval($waitClassificacao);
								}
							}, 10);
						} else {
							$bgLoading.fadeTo('fast', 0.1, function(){
								$bgLoading.hide();
							});
							$loading.fadeOut('slow');
						}					
					},
					error: function(XMLHttpRequest, textStatus, errorThrown){
						$bgLoading.fadeTo('fast', 0.1, function(){
							$bgLoading.hide();
						});
						$loading.fadeOut('slow');
					}
				});
			});
			
		}
	});
	
	$('#programacaoHome').change(function(){
		var $div = $(this).val();
		var $bgLoading = $('.prograHome .bgLoading');
		var $loading = $('#loadingProgramacao');
		var $boxAppend = $('.prograHome .scroll-pane ul');
		
		if(!isNaN($div)){
			$bgLoading.show().fadeTo('fast', 0.7);
			$loading.fadeIn('slow', function(){
				$.ajax({
					type: 'POST',
					data: 'divisao=' + $div,
					dataType: 'json',
					url: '/_func/get-programacao.php',
					success: function(json){
						if(typeof json == 'object' && json == null){
							$bgLoading.fadeTo('fast', 0.1, function(){
								$bgLoading.hide();
							});
							$loading.fadeOut('slow');
							$boxAppend.html('<li class="nenhumaProva">Nenhuma prova cadastrada</li>');
						} else if(json.status == "ok" && json.item.length){
							var $total = json.item.length;
							var $i = 0;
							var $htmlMedalhas = '';
							
							$waitProg = setInterval(function(){
								if($i < $total){
									$htmlMedalhas = $htmlMedalhas + '<li>\
											<p>\
												<img src="/_img/modalidades/ico_cat' + json.item[$i].id_modalidade + '.png" alt="' + json.item[$i].modalidade + '">\
												<span>' + json.item[$i].data + 'h: ' + '<a href="/modalidade/' + json.item[$i].slug_modalidade + '">' + json.item[$i].modalidade + '</a><br>' + (json.item[$i].provas ? json.item[$i].provas : '') + ' \
													' + (json.item[$i].descricao != null ? (' (' + json.item[$i].descricao + ')') : '') +'</span>\
												<br clear="all" />\
											</p>\
										</li>';
									$i++;
								} else {
									$boxAppend.hide().html($htmlMedalhas).fadeIn('fast');
									$bgLoading.fadeTo('fast', 0.1, function(){
										$bgLoading.hide();
									});
									$loading.fadeOut('slow');
									clearInterval($waitProg);
								}
							}, 10);
						} else {
							$bgLoading.fadeTo('fast', 0.1, function(){
								$bgLoading.hide();
							});
							$loading.fadeOut('slow');
							$boxAppend.html('<li class="nenhumaProva">Nenhuma prova cadastrada</li>');
						}					
					},
					error: function(XMLHttpRequest, textStatus, errorThrown){
						$bgLoading.fadeTo('fast', 0.1, function(){
							$bgLoading.hide();
						});
						$loading.fadeOut('slow');
						$boxAppend.html('<li class="nenhumaProva">Nenhuma prova cadastrada</li>');
					}
				});
			});
			
		}
	});
	
	$('#content_participantes').each(function(){
		$('.l .partProg #listTab li:first a').click();
	});
	
	$('body.modalidades-interna').each(function(){
		$('select#modalidade').change(function(){
			window.location = '/modalidade/' + $(this).val();
		});
	});
	
	if($('#boxFbFeed').length){

		$('#boxFbFeed').fbWall({
			id: 'jogosabertos2011',
			accessToken: 'AAAC7gAoWDQ0BACmpyQ8W769hBnERVdahsgNnQMyJUfQDmZAf8dhoxGLQoCHLY16lnIhWxKICUJIVf93aqmXGXE3wVCJMZD',
			avatarAlternative: '/_img/fb/avatar-alternative.jpg',
			avatarExternal: '/_img/fb/avatar-external.jpg',
			max: 20,
			translateAt : 'às',
			translateLikeThis: 'curtiram isto',
			translateLikesThis: 'curtiu isto',
			translatePeople: 'pessoas',
			showId: '199731200050883'
		});
	}

});

function insertScrollFb(){
	$('#boxScrollFbFeed').jScrollPane({ 'animateScroll': true, 'verticalGutter': 0, 'autoReinitialise': true, 'scrollbarWidth': 16  });
}

$filtroProgramacao = function($filtro, $tipo){
	
	if(typeof $filtro[4] != 'undefined'){
		var $data = 'data=' + $filtro[0] + '&divisao=' + $filtro[1] + '&cidade=' + $filtro[2] + '&modalidade=' + $filtro[3] + '&local=' + $filtro[4];
		var $page = 'programacao';
		var $type = 'text';
	} else {
		if($('input#idModalidade').length){
			var $data = 'data=' + $filtro[0] + '&modalidade=' + $filtro[1];
			var $page = 'modalidades-programacao';
			var $type = 'json';
		} else if($('body').hasClass('body_programacao_resultados')){
			var $data = 'cidade=' + $filtro[2] + '&modalidade=' + $filtro[3] + '&divisao=' + $filtro[1];
			var $page = 'programacao-resultado';
			var $type = 'text';
		} else {
			var $data = 'data=' + $filtro[0] + '&cidade=' + $('input#idCidade').val() + '&divisao=' + $filtro[1];
			var $page = 'cidades-programacao';
			var $type = 'json';
		}
	}

	if($tipo == 'select' || $tipo == 'select-divisao'){
		$('.bgDisabled').show().fadeTo('fast', 0.6);
		if($tipo == 'select-divisao'){
			$('#loadingTop').fadeIn('fast');
		}
	}

	$('.bgDisabledContent').show().fadeTo('fast', 0.6);
	$('#loadingContent').fadeIn('fast');

	$.ajax({
		type: 'POST',
		data: $data,
		dataType: $type,
		url: '/_func/' + $page + '.php',
		success: function($html){
			if(typeof $filtro[4] != 'undefined'){
				$('#boxItens').hide().html($html).fadeIn('slow', function(){
					$('h2#tltDivisao span').text($('#divisao option:selected').text());
					$('#dataFilter').text(data_extenso($filtro[0], false)).attr('rel', $filtro[0]);
					$('.bgDisabled, .bgDisabledContent').fadeTo('fast', 0.1, function(){
						$('.bgDisabled, .bgDisabledContent').hide();
						$('#loadingContent, #loadingTop').fadeOut('fast');
					});
				});
			} else {
				var $box = '';
				
				if($('input#idModalidade').length){
					$box = $('#containerProgramacao');
				} else if($('body').hasClass('body_programacao_resultados')){
					$('#boxItens').hide().html($html).fadeIn();
					$('.bgDisabled, .bgDisabledContent').fadeTo('fast', 0.1, function(){
						$('.bgDisabled, .bgDisabledContent').hide();
						$('#loadingContent, #loadingTop').fadeOut('fast');
					});
				} else {
					switch($filtro[1]){
						case '3':
							$box = $('#containerDivEsp');
							break;
						case '4':
							$box = $('#containerDivExt');
							break;
						default:
							$box = $('#containerDivPrincipal');
					}
				}

				if(typeof $html.status == 'undefined'){
					$programacaoCidades.InsereVazio($box, $filtro);
				} else if($html.status == "ok"){
					$programacaoCidades.Init($html, $filtro);
				} else {
					$programacaoCidades.InsereVazio($box, $filtro);
				}
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			var $box = '';
			
			if($('input#idModalidade').length){
				$box = $('#containerProgramacao');
			} else {
				switch($filtro[1]){
					case '3':
						$box = $('#containerDivEsp');
						break;
					case '4':
						$box = $('#containerDivExt');
						break;
					default:
						$box = $('#containerDivPrincipal');
				}
			}

			$programacaoCidades.InsereVazio($box, $filtro);
		}
	});
}

$programacaoCidades = {
	waitDivItem: [],
	
	Init: function($json, $filtro){
		if($('input#idModalidade').length){
				$boxContainer = $('#containerProgramacao');
		} else {
			switch($filtro[1]){
				case '3':
					$boxContainer = $('#containerDivEsp');
					break;
				case '4':
					$boxContainer = $('#containerDivExt');
					break;
				default:
					$boxContainer = $('#containerDivPrincipal');
			}
		}

		if(typeof $json.divisao == 'undefined' || $json.divisao == ''){
			this.InsereVazio($boxContainer, $filtro);
		} else {
			this.Insere($json.divisao, $boxContainer, $filtro);
		}
	},
	
	Insere: function($json, $boxPrinc, $filtro){

		var $i = 0;
		var $totalPrinc = $json.length;
		var $htmlPrinc = '';
		if($('input#idModalidade').length){
			$htmlPrinc = '<ul>';
		}
		
		var waitDivItem = setInterval(function(){
			if($i < $totalPrinc){
				
				if($('input#idModalidade').length){
					$htmlPrinc = $htmlPrinc + '\
						<li class="item itemProgMod' + (($i % 2) + 1) + '">\
							<b>' + $json[$i].data + 'h: ' + ($json[$i].provas ? $json[$i].provas : '') + ($json[$i].descricao != null ? (' (' + $json[$i].descricao + ')') : '') + '</b><br>\
							<b>Divisão: </b> ' + $json[$i].divisao + '<br />\
							<b>Categoria:</b> ' + $json[$i].sexo + ' / ' + $json[$i].categoria + '<br>\
							<b>Local:</b> ' + $json[$i].local + '<br>\
							<b>' + ($json[$i].cidades ? $json[$i].cidades : '') + '</b>\
						</li>';
				} else {
					$json[$i].cidades = $json[$i].cidades.replace($('input#nomeCidade').val(), '<strong>' + $('input#nomeCidade').val() + '</strong>');

					$htmlPrinc = $htmlPrinc + '\
						<div class="item itemResultCid' + ($i % 2 + 1) + '">\
							<span class="icon"><img src="/_img/modalidades/home_cat' + $json[$i].id_modalidade + '.png" alt="' + $json[$i].categoria + '" /></span>\
							<p>\
								<span><b>' + $json[$i].data + 'h: ' + '<a class="btnBold" href="/modalidade/' + $json[$i].slug_modalidade + '">' + $json[$i].modalidade + '</a>\
								' + (($json[$i].descricao != null || $json[$i].provas != false) ? ' - ' : '') +
									($json[$i].provas ? $json[$i].provas : '') + (($json[$i].descricao != null) ? (' (' + $json[$i].descricao + ')') : '') + '</b></span>\
								<span><b>Categoria:</b> ' + $json[$i].sexo + ' / ' + $json[$i].categoria + '</span>\
								<span><b>Local:</b> ' + $json[$i].local + '</span>\
								<span>' + $json[$i].cidades + '</span>\
							</p>\
							<br clear="all" />\
						</div>';
				}
				$i++;
			} else {
				if($('input#idModalidade').length){
					$htmlPrinc = $htmlPrinc + '</ul>';
				}
				$boxPrinc.hide().html($htmlPrinc).fadeIn();
				if($boxPrinc.prev().find('.dataFilter').length){
					$boxPrinc.prev().find('.dataFilter').text(data_extenso_texto($filtro[0])).attr('rel', $filtro[0]);
				} else {
					$boxPrinc.parents('.scroll-pane').prev().find('.dataFilter').text(data_extenso_texto($filtro[0])).attr('rel', $filtro[0]);
				}
				$('.bgDisabledContent').fadeTo('fast', 0.1, function(){
					$('.bgDisabledContent').hide();
					$('#loadingContent').fadeOut('fast');
					$('.prog-title-cidades a').removeClass('disabled');
				});
				
				clearInterval(waitDivItem);
			}
		}, 10);
	},
	
	InsereVazio: function($box, $filtro){
		if($box != ''){
			$box.hide().html('<div class="item itemNenhumaProva"><p>Nenhuma prova cadastrada.</p></div>').fadeIn();
			if($box.prev().find('.dataFilter').length){
				$box.prev().find('.dataFilter').text(data_extenso_texto($filtro[0])).attr('rel', $filtro[0]);
			} else {
				$box.parents('.scroll-pane').prev().find('.dataFilter').text(data_extenso_texto($filtro[0])).attr('rel', $filtro[0]);
			}
			$('.bgDisabledContent').fadeTo('fast', 0.1, function(){
				$('.bgDisabledContent').hide();
				$('#loadingContent').fadeOut('fast');
				$('.prog-title-cidades a').removeClass('disabled');
			});	
		}
	}
}

$atualizaSorteio = function(){
	$timestamp = parseInt($('#listUpdatesScroll ul li:first').attr('rel'));
	$.ajax({
		type: 'POST',
		data: 'last=' + $timestamp,
		dataType: 'json',
		url: '/_func/sorteio.php',
		success: function($json){
		    if(typeof $json.status == 'undefined'){
			$itemList = '';
			for($index in $json){
				$itemList = $itemList + '<li class="new hidden" rel="' + $json[$index].timestamp + '"><div class="liInner"><div class="img"><div class="imgInner imgMod' + $json[$index].id_modalidade + '">' + $json[$index].modalidade + '</div></div><div class="txt"><span class="hora">' + $json[$index].hora + '</span><span class="info">' + $json[$index].modalidade + ' - ' + $json[$index].divisao + ' - ' + $json[$index].sexo + ' - ' + $json[$index].idade + '</span></div><a class="linkPdf" href="/upload/sorteio_tecnico/' + $index + '.PDF" target="_blank">Visualizar PDF</a><br clear="all" /></div></li>';
				$('span#' + $index).after('<a title="Clique para baixar o PDF desse sorteio técnico" target="_blank" href="/upload/sorteio_tecnico/' + $index + '.PDF" id="' + $index + '">' + $json[$index].idade + '</a>').remove();
			}
			if($('p.atualizaSorteioPrev').length){
				$('#listUpdates').html('<div id="listUpdatesScroll"><ul></ul></div>');
				$('#listUpdatesScroll').jScrollPane({ 'animateScroll': true, 'verticalGutter': 0, 'autoReinitialise': true });
			}
			$('#listUpdatesScroll ul').prepend($itemList);
			$('#listUpdatesScroll ul li.hidden').each(function(){
				var $this = $(this);
				$(this).removeClass('hidden').show('normal', function(){
					$this.find('.liInner').fadeIn('slow');	
				});
			});
		    }
		    $getSorteio = setTimeout($atualizaSorteio, 30000);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
		    alert('Ocorreu um erro. Atualize a página.');
		    $getSorteio = setTimeout($atualizaSorteio, 30000);
		}
	});
}

function loading(elem){
	elem.html('<div class="loading"><img src="_img/load.gif" width="34" height="34" alt="Carregando..." /></div>');
}

function data_extenso($str, $y){
	$data = $str.split('-');
	$dia = $data[0];
	$mes = $data[1];
	$ano = $data[2];
	switch ($mes){
		case '1':
			$mes = "Janeiro";
			break;
		case '2':
			$mes = "Fevereiro";
			break;
		case '3':
			$mes = "Março";
			break;
		case '4':
			$mes = "Abril";
			break;
		case '5':
			$mes = "Maio";
			break;
		case '6':
			$mes = "Junho";
			break;
		case '7':
			$mes = "Julho";
			break;
		case '8':
			$mes = "Agosto";
			break;
		case '9':
			$mes = "Setembro";
			break;
		case '10':
			$mes = "Outubro";
			break;
		case '11':
			$mes = "Novembro";
			break;
		case '12':
			$mes = "Dezembro";
	}
	return $dia + ' de ' + $mes + ($y ? ' de ' + $ano : '');
}

function data_extenso_texto($str){
	var $data = $str.split('-');
	var $dia = Number($data[0]);
	var $mes = Number($data[1]) - 1;
	var $ano = Number($data[2]);
	var $diaSemana = ['domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado'];
	
	var $dateObjDt = new Date();
	$dateObjDt.setFullYear($ano, $mes, $dia, 0, 0, 0, 0);
	
	return $data[0] + '/' + $data[1] + ' - ' + $diaSemana[$dateObjDt.getDay()];
}

function urlencode( str ) {
 
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
 
    return ret;
}

/*
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Uses the built in easing capabilities added In jQuery 1.1
 * to offer multiple easing options
 *
 * TERMS OF USE - jQuery Easing
 * 
 * Open source under the BSD License. 
 * 
 * Copyright © 2008 George McGinley Smith
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *
*/

// t: current time, b: begInnIng value, c: change In value, d: duration
jQuery.easing['jswing'] = jQuery.easing['swing'];

jQuery.extend( jQuery.easing,
{
	def: 'easeOutQuad',
	swing: function (x, t, b, c, d) {
		//alert(jQuery.easing.default);
		return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
	},
	easeInQuad: function (x, t, b, c, d) {
		return c*(t/=d)*t + b;
	},
	easeOutQuad: function (x, t, b, c, d) {
		return -c *(t/=d)*(t-2) + b;
	},
	easeInOutQuad: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t + b;
		return -c/2 * ((--t)*(t-2) - 1) + b;
	},
	easeInCubic: function (x, t, b, c, d) {
		return c*(t/=d)*t*t + b;
	},
	easeOutCubic: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t + 1) + b;
	},
	easeInOutCubic: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t + b;
		return c/2*((t-=2)*t*t + 2) + b;
	},
	easeInQuart: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t + b;
	},
	easeOutQuart: function (x, t, b, c, d) {
		return -c * ((t=t/d-1)*t*t*t - 1) + b;
	},
	easeInOutQuart: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t + b;
		return -c/2 * ((t-=2)*t*t*t - 2) + b;
	},
	easeInQuint: function (x, t, b, c, d) {
		return c*(t/=d)*t*t*t*t + b;
	},
	easeOutQuint: function (x, t, b, c, d) {
		return c*((t=t/d-1)*t*t*t*t + 1) + b;
	},
	easeInOutQuint: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return c/2*t*t*t*t*t + b;
		return c/2*((t-=2)*t*t*t*t + 2) + b;
	},
	easeInSine: function (x, t, b, c, d) {
		return -c * Math.cos(t/d * (Math.PI/2)) + c + b;
	},
	easeOutSine: function (x, t, b, c, d) {
		return c * Math.sin(t/d * (Math.PI/2)) + b;
	},
	easeInOutSine: function (x, t, b, c, d) {
		return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
	},
	easeInExpo: function (x, t, b, c, d) {
		return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;
	},
	easeOutExpo: function (x, t, b, c, d) {
		return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
	},
	easeInOutExpo: function (x, t, b, c, d) {
		if (t==0) return b;
		if (t==d) return b+c;
		if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
		return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
	},
	easeInCirc: function (x, t, b, c, d) {
		return -c * (Math.sqrt(1 - (t/=d)*t) - 1) + b;
	},
	easeOutCirc: function (x, t, b, c, d) {
		return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
	},
	easeInOutCirc: function (x, t, b, c, d) {
		if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
		return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
	},
	easeInElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
	},
	easeOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;
	},
	easeInOutElastic: function (x, t, b, c, d) {
		var s=1.70158;var p=0;var a=c;
		if (t==0) return b;  if ((t/=d/2)==2) return b+c;  if (!p) p=d*(.3*1.5);
		if (a < Math.abs(c)) { a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin (c/a);
		if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
		return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;
	},
	easeInBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*(t/=d)*t*((s+1)*t - s) + b;
	},
	easeOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158;
		return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
	},
	easeInOutBack: function (x, t, b, c, d, s) {
		if (s == undefined) s = 1.70158; 
		if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
		return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
	},
	easeInBounce: function (x, t, b, c, d) {
		return c - jQuery.easing.easeOutBounce (x, d-t, 0, c, d) + b;
	},
	easeOutBounce: function (x, t, b, c, d) {
		if ((t/=d) < (1/2.75)) {
			return c*(7.5625*t*t) + b;
		} else if (t < (2/2.75)) {
			return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
		} else if (t < (2.5/2.75)) {
			return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
		} else {
			return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
		}
	},
	easeInOutBounce: function (x, t, b, c, d) {
		if (t < d/2) return jQuery.easing.easeInBounce (x, t*2, 0, c, d) * .5 + b;
		return jQuery.easing.easeOutBounce (x, t*2-d, 0, c, d) * .5 + c*.5 + b;
	}
});

/*
 *
 * TERMS OF USE - EASING EQUATIONS
 * 
 * Open source under the BSD License. 
 * 
 * Copyright © 2001 Robert Penner
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *
 */