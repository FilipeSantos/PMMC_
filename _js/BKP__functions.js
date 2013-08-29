jQuery(document).ready(function(){
	/*$('#get-bg').load('_inc/bg.php');
	$('#get-header').load('_inc/header.php');
	$('#get-footer').load('_inc/footer.php');
	$('#parallax').height($('html').height()).jparallax();*/
	jQuery('#parallax').jparallax({}, {}, {xtravel: '50px', ytravel: '10px'});
	
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
});
function loading(elem){
	elem.html('<div class="loading"><img src="_img/load.gif" width="34" height="34" alt="Carregando..." /></div>');
}