<div class="container" id="main">

    <div class="row promo-row">
<?php foreach ($contenido as $value) { ?>
 
     
        <div class="col-sm-6">
          <div class="row">
              <div class="col-sm-4">
              <h2><?=$value->titulo?></h2>
              <p class="text-read"><?=$value->descripcion?></p>
             </div>
            <div class="col-sm-8"><?=$value->video?></div>
          </div>
        </div>
     
     
<?php } ?>
    </div>   
</div>
