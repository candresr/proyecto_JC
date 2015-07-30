<div class="container descargas" id="main">

    <div class="row promo-row">
<?php foreach ($contenido as $value) { ?>
 
     
        <div class="col-sm-6">
          <div class="row">
              <div class="col-sm-4">
              <h2><?=$value->titulo?></h2>
              <p class="text-read"><?=$value->descripcion?></p>
             </div>
              <div class="col-sm-8"><?=anchor('web/generic/descargas/'.$value->id.'/'.$value->imagen,img('uploads/web_descargas/med_'.$value->imagen),'class="descarga"')?></div>
          </div>
        </div>
     
     
<?php } ?>
    </div>   
</div>

