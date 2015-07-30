<div class="container" id="main">
      <div class="row">
          
       
        <div class="col-sm-4 col-sm-offset-3">
            <h3>Audio</h3>
            <ul class="sono-podcasts">
            <?php foreach ($playlist as $value) {?>
                <li  autor="<?=$value->autor?>" data-list="<?=base_url().'web/data_list/'.$value->id?>"><?=$value->titulo?></li>
            <?php }?>
          </ul>
        </div>
       <div class="col-sm-5">
          <div class="repro-header">
            <h2 class="repro-titulo"></h2>
            <h2 class="repro-autor"></h2>
          </div>
          <div class="repro">
            <span class="repro-bar"></span>
            <span class="load-bar"></span>
            <span class="btn-repro repro-prev"></span>
            <span class="btn-repro repro-play"></span>
            <span class="btn-repro repro-next"></span>
            <span class="btn-repro repro-download"></span>
            <span class="repro-timebar"></span>
            <span class="repro-time"></span>
            <span class="repro-buffer"></span>
          </div>
          <div class="repro-lista">
            <ul class="lista-temas">
            </ul>
          </div>
        </div>
      </div>
    </div>