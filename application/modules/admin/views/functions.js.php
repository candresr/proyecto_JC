<?php $array = $arrpos;?>
<script type="application/javascript">
/**************** Menu Positioning *********************************/
jQuery.fn.centerit = function () {
	$('#menu').stop().animate({
		top: (($(window).height()/2) - ($('#menu').height() /2 )),
		}, 2000, 'easeOutExpo', function() {
			//Something to do when the animation ends
});
$('#varrows').stop().animate({
	top: (($(window).height()/2) - ($('#varrows').height() /2 )),
		}, 2000, 'easeInOutQuad', function() {
			//Something to do when the animation ends
});

/****************** Section height ******************************/
<?php for($i=1; $i<count($array)+1; $i++) { ?>
    $('#n<?=$i?>').height($(window).height());	
<?php } ?>	
}

/**************** Arrows Hover Animation *********************/
$(".arrow_up").hover(
	function () {
		$(".arrow_up img").animate({
			marginTop : "-29px"}, 'fast');
	},
	function () {
		$(".arrow_up img").animate({
			marginTop : "0px"}, 500);
});
$(".arrow_down").hover(
	function () {
		$(".arrow_down img").animate({
			marginTop : "0px"}, 'fast');
	},
	function () {
		$(".arrow_down img").animate({
			marginTop : "-29px"}, 500);
});

/**************** Parallax *********************************/
$(function() {
   $.scrollingParallax('assets/img/bg_big.png', {
        staticSpeed : .2,
        loopIt : false,
       // bgHeight : '200%',
        disableIE6 : true,
		bgRepeat: true
    });
    $('#img1').scrollingParallax({
		staticSpeed: 1
	});
});

/****************** Navigation functions **************************/
$.tonumber 	= 1;
$.toshow 	= "#n" + $.tonumber;

jQuery.fn.goTo = function(p, q) {
	$.tonumber = p;
	$.toshow = "#n" + $.tonumber;
	$.scrollTo(($.toshow),600);
}
//Navegación con las flechas laterales derechas
$('.arrow_up').click(function(event) {
	if($.tonumber > 1) {
		$.tonumber -= 1;
		$(this).goTo($.tonumber);
		$(this).toggleActive($.tonumber);
		event.preventDefault();
	}
});

$('.arrow_down').click(function(event) {
	if($.tonumber < 5) {
		$.tonumber += 1;
		$(this).goTo($.tonumber);
		$(this).toggleActive($.tonumber);
		event.preventDefault();
	}
});

/*******************************************************/
//Esta función hace toggle de la sección activa sobre el menú lateral
jQuery.fn.toggleActive = function(a) {
	$.toact = "#b" + a;
	$('.bt').removeClass('menuact');
	$($.toact).addClass('menuact');
}

//Este bucle le envia a cada botón principal el vinculo correspondiente
<?php for($i=1; $i<count($array)+1; $i++) { ?>
	$('#b<?=$i?>').click(function(event) {
		$(this).goTo(<?=$i?>);
		$(this).toggleActive(<?=$i?>);
		event.preventDefault();
	});	
<?php } ?>

/*******************************************************/
//Este bucle le envia a cada sub-botón la acción de toggle independiente
<?php for($i=1; $i<count($array)+1; $i++) { ?>
	$('#s<?=$i?> li > a').click(function(event) {
		$('#s<?=$i?> li > a').css('background-position', 'top');
		$(this).css('background-position', 'bottom');
		console.log($(this));
	});	
<?php } ?>

/*******************************************************/
$(document).ready(function(){
	$('#menu').centerit();
	$.totalsecs = <?=count($array)?>;

	//init markers
	$('ul.subsecs li:first-child > a').css('background-position', 'bottom');
	$('#b1').addClass('menuact');
});

$(window).resize(function() {
	$('#menu').centerit();
});

$('#error_validation').on('click', function(event) {
    $(this).fadeOut();
});

$('#formcontac').submit(function() {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '<?=base_url()?>inicio/procesarForm',
        data: $(this).serialize(),
        success: function(data) {
            if(data.valido=='FALSE'){
                $('#error_validation').html(data.validations).fadeIn();
            } else {
                $('#error_validation').html(data.denuncias).addClass('done').fadeIn();
            }
        }
    })        
    return false;
});

</script>