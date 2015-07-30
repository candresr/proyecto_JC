<?php $array = array(1,2,3,4,5); ?>
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
<?php
	for($i=1; $i<count($array)+1; $i++)
	{
?>
	$('#n<?php echo $i; ?>').height($(window).height());	
<?php
	}
?>	
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
		})
	$(".arrow_down").hover(
		function () {
			$(".arrow_down img").animate({
				marginTop : "0px"}, 'fast');
		},
		function () {
			$(".arrow_down img").animate({
				marginTop : "-29px"}, 500);
		})
/**************** Parallax *********************************/
$(function() {
	//$.scrollingParallax('img/bg4.png' );
   /*$.scrollingParallax('img/bg4.png', {
        staticSpeed : 1.7,
        loopIt : false,
        staticScrollLimit : false,
        //bgHeight: '100%',
        disableIE6 : true,
		bgRepeat: true
    });*/
    
   $.scrollingParallax('img/bg3.png', {
        staticSpeed : .2,
        loopIt : false,
       // bgHeight : '200%',
        disableIE6 : true,
		bgRepeat: true
    });
    
   /* $.scrollingParallax('img/leaves-background.png', {
        staticSpeed : .17,
        loopIt : true,
        bgHeight : '280%',
        disableIE6Animation : false
    });*/
    $('#img1').scrollingParallax({
		staticSpeed: 1
	});
});

/****************** Buttons functions **************************/
$.tonumber = 1;
$.toshow = "#n" + $.tonumber;

jQuery.fn.goTo = function(p) {
	$.tonumber = p;
	$.toshow = "#n" + $.tonumber;
	$.scrollTo(($.toshow),600);
}

$('.arrow_up').click(function(event) {
	if($.tonumber > 1) {
		$.tonumber -= 1;
		$(this).goTo($.tonumber);
	}
	event.preventDefault();
});

$('.arrow_down').click(function(event) {
	if($.tonumber < 5) {
		$.tonumber += 1;
		$(this).goTo($.tonumber);
		event.preventDefault();
	}
});

/*---------------------------------------------*/

<?php
	for($i=1; $i<count($array)+1; $i++)
	{
?>
	$('#b<?php echo $i; ?>').click(function(event) {
		$(this).goTo(<?php echo $i; ?>);
		event.preventDefault();
	});	
<?php
	}
?>
/*******************************************************/
$(document).ready(function(){
	$('#menu').centerit();
	/*$(".subsecs a[title]").tooltip({ position: "bottom right", offset: [10,-10], effect: 'slide'});*/
	
});

$(window).resize(function() {
	$('#menu').centerit();
});

</script>