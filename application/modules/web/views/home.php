   <!-- Center Container-->
    <div class="container">
      
      <!-- Titular -->
      <div class="row" id="titular">
          <div class="col-xs-4" id="avila-hoy">
            <h1>Ávila hoy</h1>
          </div>
          <div class="col-xs-7 col-xs-offset-1" id="date">
            <h1><?=$fechaDN?><span class="title-bold"><?=$fechaM?></span></h1>
          </div>
      </div>
      
      <!-- Main -->
      <div class="row" id="main-content">
            <?=$imgavila?>
      </div>
      
      <!-- Nueva Temporada -->
      <div class="row" id="nueva-temporada">
        <div class="col-sm-6">
          <h1>nueva temporada</h1>
        </div>
        <div class="col-sm-6" id="slider-main">
          <div id="nt-slider-prev"></div>
          <div id="nt-slider">
              <?=$galeria?>
          </div>
          <div id="nt-slider-next"></div>
        </div>
      </div>
      
      <!-- Accesos -->
      <div class="row" id="accesos">
        
        <div class="col-sm-4" id="acc-titanes">
          <div class="row">
            <div class="acc-col-1">
                <?=  anchor('https://www.facebook.com/avilatv.suena',img('assets/img/titanes_op.jpg'),'target="_blank"')?>
                
            </div>
            <div class="acc-col-2">
            <h2>Contrapuntea</h2>
                <?=$furia?>
            </div>
          </div>
        </div>
        
        <div class="col-sm-4" id="acc-ola">
          <div class="row">
            <div class="acc-col-1">
             <?=  anchor('web/generic/agenda/'.$ccsid,img('assets/img/ola_op.jpg'))?>
            </div>
            <div class="acc-col-2">
                <h2>Agarra Calle</h2>
                <?=$ccs?>
            </div>
          </div>
        </div>
        
        <div class="col-sm-4" id="acc-sono">
          <div class="row">
            <div class="acc-col-1">
              <?=  anchor('web/generic/sono/'.$sonoid,img('assets/img/sono_op.jpg'))?>
            </div>
            <div class="acc-col-2">
            <h2>Óyela</h2>
                <?=$sono?>
            </div>
          </div>
        </div>
      </div>
    </div><!-- Center Container-->