$(document).ready(function (){
	var ocupado = 0;
	var pausa = null;
	jQuery.fn.tool = function(p) {
		alert (p);
	//Cuando se pasa por encima del icono
	$(p).mouseover(function (){
		if(pausa) clearTimeout(pausa);
		if (ocupado == 0){
			ocupado = 1;
			$('.ttip').css({
				//Aparece (ya que estaba en display none)
				top: 0,
				display: 'block',
				opacity: 0
			}).animate({
				//Sube 20px y pasa a ser opaco
				opacity: 1
			}, 200,
			function (){
				ocupado = 0;
			});
		}
	});
	//Cuando sale de la zona del icono
	$(p).mouseout(function (){
		
		pausa = setTimeout(function () {
			pausa = null;
			if (ocupado == 0){
				ocupado = 1;
				$('.ttip').css({
					opacity: 1
				}).animate({
					opacity: 0
				}, 'fast',
				function(){
				$('.ttip').css({
						display: 'none',
						top: 0
					});
					ocupado = 0;
				});
			}else {
				$('.ttip').css({
					display: 'none'
				});
				ocupado = 0;
			}
		}, 100)
	});
	
	}
});
