 <div class="container" id="agenda">
      <div class="row">
          
        <div class="col-md-4">
          <h2>lunes</h2>
          <ul class="agenda-events">
              <?php foreach ($contenido as $value) { ?>
                 <?php if($value->dia == 'lunes'){
                     $exp = explode('-',$value->fecha);
                     $fecha = $exp[2].'/'.$exp[1].'/'.$exp[0];
                 ?>
                    <li><?='<span class="agenda-year">'.$fecha.' </span><span class="agenda-time">'.$value->hora.' </span><span class="agenda-title">'.$value->titulo.'</span>'?></li>
        
              <?php }} ?>
          </ul>
        </div>
        <div class="col-md-4">
          <h2>martes</h2>
          <ul class="agenda-events">
            <?php foreach ($contenido as $value) { ?>
                 <?php if($value->dia == 'martes'){
                     $exp = explode('-',$value->fecha);
                     $fecha = $exp[2].'/'.$exp[1].'/'.$exp[0];
                 ?>
                    <li><?='<span class="agenda-year">'.$fecha.' </span><span class="agenda-time">'.$value->hora.' </span><span class="agenda-title">'.$value->titulo.'</span>'?></li>
        
              <?php }} ?>
          </ul>
        </div>
        <div class="col-md-4">
          <h2>miércoles</h2>
          <ul class="agenda-events">
           <?php foreach ($contenido as $value) { ?>
                 <?php if($value->dia == 'miércoles'){
                     $exp = explode('-',$value->fecha);
                     $fecha = $exp[2].'/'.$exp[1].'/'.$exp[0];
                 ?>
                    <li><?='<span class="agenda-year">'.$fecha.' </span><span class="agenda-time">'.$value->hora.' </span><span class="agenda-title">'.$value->titulo.'</span>'?></li>
        
            <?php }} ?>
          </ul>
        </div>
      </div>
     
      <div class="row">
        <div class="col-md-4">
          <h2>jueves</h2>
          <ul class="agenda-events">
            <?php foreach ($contenido as $value) { ?>
                 <?php if($value->dia == 'jueves'){
                     $exp = explode('-',$value->fecha);
                     $fecha = $exp[2].'/'.$exp[1].'/'.$exp[0];
                 ?>
                    <li><?='<span class="agenda-year">'.$fecha.' </span><span class="agenda-time">'.$value->hora.' </span><span class="agenda-title">'.$value->titulo.'</span>'?></li>
        
            <?php }} ?>
          </ul>
        </div>
        <div class="col-md-4">
          <h2>viernes</h2>
          <ul class="agenda-events">
            <?php foreach ($contenido as $value) { ?>
                 <?php if($value->dia == 'viernes'){
                     $exp = explode('-',$value->fecha);
                     $fecha = $exp[2].'/'.$exp[1].'/'.$exp[0];
                 ?>
                   <li><?='<span class="agenda-year">'.$fecha.' </span><span class="agenda-time">'.$value->hora.' </span><span class="agenda-title">'.$value->titulo.'</span>'?></li>
        
            <?php }} ?>
          </ul>
        </div>
        <div class="col-md-4">
          <h2>sábado</h2>
          <ul class="agenda-events">
            <?php foreach ($contenido as $value) { ?>
                 <?php if($value->dia == 'sábado'){
                     $exp = explode('-',$value->fecha);
                     $fecha = $exp[2].'/'.$exp[1].'/'.$exp[0];
                 ?>
                    <li><?='<span class="agenda-year">'.$fecha.' </span><span class="agenda-time">'.$value->hora.' </span><span class="agenda-title">'.$value->titulo.'</span>'?></li>
        
            <?php }} ?>
          </ul>
        </div>
      </div>
      <div class="row" id="agenda">
        <div class="col-md-8">
          <h2>domingo</h2>
          <ul class="agenda-events">
            <?php foreach ($contenido as $value) { ?>
                 <?php if($value->dia == 'domingo'){
                     $exp = explode('-',$value->fecha);
                     $fecha = $exp[2].'/'.$exp[1].'/'.$exp[0];
                 ?>
                   <li><?='<span class="agenda-year">'.$fecha.' </span><span class="agenda-time">'.$value->hora.' </span><span class="agenda-title">'.$value->titulo.'</span>'?></li>
        
            <?php }} ?>
          </ul>
        </div>
      </div>
    </div>

