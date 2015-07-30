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
	$('#n1').height($(window).height());
	$('#n2').height($(window).height());
	$('#n3').height($(window).height());
	$('#n4').height($(window).height());
	$('#n5').height($(window).height());
	
	//$('.seccion').height($(window).height());
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
/*--------------------------*/
$.tonumber = 1;
$.toshow = "#n" + $.tonumber;

jQuery.fn.goTo = function(p) {
	$.tonumber = p;
	$.toshow = "#n" + $.tonumber;
	$.scrollTo(($.toshow),600);
	//alert(p);
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


/*$('.bt').each(function(index) {
	 alert(index + ': ' + $(this).text());
	event.preventDefault();
});*/


/*------------------------------------------
var totalSec = 5;
var i=0;
$.secName = "";z

for (i=1; i<=totalSec; i++) {
	$.btName = "#b" + i;
	$.secName = "#n" + i;
	$($.btName).data("name", {n: $.secName});
	document.write($.secName);
	$($.btName).click(function(event) {
		//$(this).goTo(i);
		alert($.btName.data("name").n);
		event.preventDefault();
	});
}*/

/*-----------------------------------------
var totalSec = 5;
var i=0;

for (i=1; i<=totalSec; i++) {
	$.btName = "#b" + i;
	$.secName = "#n" + i;
	
	$($.btName).data("name", { n: $.secName });
	$($.btName).click(function(event) {
		alert($($.btName).data("name").n);
		event.preventDefault();
	});
}*/

//-----------------------------------------
$('#b1').click(function(event) {
		$(this).goTo(1);
		event.preventDefault();
	});
$('#b2').click(function(event) {
		$(this).goTo(2);
		event.preventDefault();
	});
$('#b3').click(function(event) {
		$(this).goTo(3);
		event.preventDefault();
	});
$('#b4').click(function(event) {
		$(this).goTo(4);
		event.preventDefault();
	});
$('#b5').click(function(event) {
		$(this).goTo(5);
		event.preventDefault();
});

/*******************************************************/
$(document).ready(function(){
	$('#menu').centerit();
	$(".subsecs a[title]").tooltip({ position: "bottom right", offset: [10,-10]});
	//$('#menu').buttons();
});

$(window).resize(function() {
	$('#menu').centerit();
});