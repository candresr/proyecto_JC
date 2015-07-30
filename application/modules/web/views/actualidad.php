   <div class="container" id="actualidad">
      <div class="row">
        <div class="col-sm-3">
            <div class="row act-historico">
                <h2>noticias anteriores</h2>
                <?php foreach ($contenido as $value) { ?>
                    <?=anchor('web/generic/actualidad/'.$id.'/'.$value->id,$value->titulo,'')?>
                <?php } ?>
         
           </div>
        </div>
          <?php $new = $noticia?>
        <div class="col-sm-9">
          <div class="row">
            <div class="head-noticia">
              <div class="col-sm-4">
                  <?=img(array('src' => 'uploads/web_noticias/'.$new->imagen, 'class' => 'img-responsive'))?>
                
              </div>
              <div class="col-sm-8">
                <span class="titulo-noticia"><h2><?=$new->titulo?></h2></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="cuerpo-noticia">
                <p><?=$new->descripcion?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

