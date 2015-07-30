<?php
class Repetidos_model extends CI_Model
{
	
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
    }
	
    function crea_menu()
    {
        $this->db->where('estatus', 'publicado');
        $this->db->where('eliminado !=', 'si');
        //$this->db->where('pertenece', '');
        $this->db->order_by('posicion', 'asc');
        $query = $this->db->get('web_menu');
       
        $menu       = "";
   
        foreach($query->result() as $row_m)
        { 
            $menu .= '<li>'.anchor($row_m->href.$row_m->id,$row_m->titulo,'').'</li>';
        }
 
        $salida['menu']     = $menu;
        return $salida;
        
    }
    function css($id) {
        $this->db->where('eliminado !=', 'si');
        $this->db->where('id', $id);
        $query = $this->db->get('web_menu');
        $row = $query->row();

        return $row->css;
    }
    function crea_submenu($id)
    {
            
            $this->db->where('estatus', 'publicado');
            $this->db->where('eliminado !=', 'si');
            $this->db->where('pertenece', $id);
            $this->db->order_by('posicion', 'asc');
            $query_sm = $this->db->get('menu');
            
            $submenu .= "<div id='submenu' class='borderbot'>";
            $submenu .= "<ul class='tabs'>";
            $n = 1;
            foreach($query_sm->result() as $row_c)
            {
                if(!empty($row_c->contenido)){
                    $idcss = 'id='.$n;
                    $submenu .= "<li>".anchor('inicio/content_menu/'.$row_c->id,$row_c->titulo,$idcss)."</li>";
                }else{
                    $submenu .= "<li>".anchor($row_c->href,$row_c->titulo,'')."</li>"; 
                }
                $n++;
            }
            $submenu .= "</ul>"; 
            $submenu .= "</div>";
            $submenu .= "<div  id='text' class='panes'>";
            foreach($query_sm->result() as $row_cc){
                if(!empty($row_cc->contenido)){
                    $submenu .= "<div><p>".$row_cc->contenido."</p></div>";
                }else{
                    switch ($row_cc->href) {
                        case 'inicio/organigrama':
                             $submenu .= "<div><p><div id='chart' class='orgChart'>".$this->organigrama()."</div></p></div>";
                            break;
                        
                        default:
                            $submenu .= "<div><p>Sin contenido</p></div>";
                            break;
                    }
                   
                } 

            }
            $submenu .= "</div>";
            
        
        return $submenu;
    }
    function generic($id){
        $this->db->where('estatus', 'publicado');
        $this->db->where('eliminado !=', 'si');
        $this->db->where('id', $id);
        $query = $this->db->get('web_menu');
        $row = $query->row();

        return $row->contenido;
    }
    function dinamic($tabla) {
        $curdate = 'fecha >= CURDATE()';
        $this->db->where('estatus', 'publicado');
        $this->db->where('eliminado !=', 'si');
        if($tabla == 'web_agenda')$this->db->where($curdate);
        if($tabla == 'web_agenda')$this->db->order_by('fecha', 'asc'); else $this->db->order_by('id', 'desc');
        if($tabla == 'web_promos')$this->db->limit(12);
        if($tabla == 'web_acceso')$this->db->limit(1);
        if($tabla == 'web_noticias')$this->db->limit(10);

        $query = $this->db->get($tabla);
        
        return $query->result();
    }

    function galeria(){
        $this->db->where('estatus', 'publicado');
        $this->db->where('eliminado !=', 'si'); 
        $this->db->where('tipo', 'imagen');
        $this->db->order_by('id', 'asc');
        //$this->db->limit(1);
        $query_g = $this->db->get('web_galerias');
        //echo $this->db->last_query(); die;
        $galeria = '';
        foreach ($query_g->result() as $gal) {
  
            if(file_exists(getcwd().'/uploads/web_galerias/'.$gal->id)){

                $galeria .= $this->img($gal->id);
            } 
            
        }
     
        return $galeria;
    }
    
    function img($id){
        
        $this->db->where('eliminado !=', 'si');
        $this->db->where('id_gal', $id);
        $this->db->where('tipo', 'imagen');
        $this->db->order_by('id', 'asc');
        $query = $this->db->get('web_archivos');
        $img = "";
        //$row = $query->row();  
        foreach ($query->result() as $row) {
            $img .= '<div>'.anchor('uploads/web_galerias/'.$id."/med_".$row->archivo,img('uploads/web_galerias/'.$id."/peq_".$row->archivo),'data-lightbox='.$id.'').'</div>';
        }
        
        return $img;
    }


    function areaServicios()
    {
        $this->db->where('clave', 'contac_form');
        $this->db->where('eliminado !=', 'si');
        $this->db->order_by('orden', 'asc');
        $query = $this->db->get('categorias');
        $servicio = array();
        $servicio[] = 'Elija una...';
        foreach($query->result() as $row)
        {
            $servicio[$row->valor] = $row->categoria;
        }
        $box_att = 'class = "resetscale"';
        return form_dropdown('servicio', $servicio, '', $box_att);
    }
    
    
    function programacionDia($id){
        $this->db->where('id', $id);
        $this->db->where('eliminado !=', 'si');
        $query = $this->db->get('web_programacion');
        $row = $query->row();
        //echo $id.'-----'.$this->db->last_query();
        $news = "";
        if(empty($id)){
            $news .= '<div class="prog-titulo"><h2>Sin contenido</h2></div>';
            $news .= '<div class="prog-content">';
            $news .= '<div class="prog-img">Sin contenido</div>';
            $news .= '<div class="prog-texto">Sin contenido</div></div>';
        }  else {
            $news .= '<div class="prog-titulo"><h2>'.$row->titulo.'</h2></div>';
            $news .= '<div class="prog-content">';
            $news .= '<div class="prog-img">'.img('uploads/web_programacion/med_'.$row->imagen).'</div>';
            $news .= '<div class="prog-texto">'.$row->descripcion.'</div>';
            $news .= '<div class="prog-links">'.$this->capitulos($id).'</div></div>';
        }
        
        return $news; 
    }
    
    function contenido(){
        $this->db->where('estatus', 'publicado');
        $this->db->where('eliminado', 'no');
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('web_contenido');
        $cont = "";
       
        foreach ($query->result() as $row) {
                
            $cont .= "<h3>".$row->titulo."</h3>";
            $cont .= "<p>".$row->descripcion."</p>";
        }
        return $cont; 
    }
    function imgcont(){
        $this->db->where('estatus', 'publicado');
        $this->db->where('eliminado', 'no');
        $this->db->order_by('id', 'desc');
        $this->db->limit(2);
        $query = $this->db->get('web_contenido');
        $img = "";
        $i = 0;
        foreach ($query->result() as $row) {  
            $image_properties = array('src' => 'uploads/web_contenido/med_'.$row->imagen,'class' => 'img-responsive','data-toggle' => 'modal', 'data-target' => '#modal_'.$i);
          
             $img .=  '<div class="col-sm-6 noti">
                            <div class="row">';
                               $img .= '<div class="col-xs-4 col-xs-offset-1 noti-izq">';
                              if($i == 0){
                                  
                                  $img .= '<div class="row noti-eq"><!--[if !IE]><!-->';
                                  $img .= '<img src="'.base_url().'assets/img/eq.svg" class="img-responsive" />';
                                  $img .= '<!--<![endif]--><!--[if IE]>';
                                  $img .= '<img src="'.base_url().'assets/img/barras.gif" class="img-responsive" alt="eq"/><![endif]--></div>';
                              }else{
                               
                                  $img .= '<div class="row noti-eq"><!--[if !IE]><!-->';
                                  $img .= '<img src="'.base_url().'assets/img/eq2.svg" class="img-responsive" />';
                                  $img .= '<!--<![endif]--><!--[if IE]>';
                                  $img .= '<img src="'.base_url().'assets/img/barras.gif" class="img-responsive" alt="eq"/><![endif]--></div>';
                              }
                                
             $img .=         '<div class="row noti-inf">
                                  <div class="noti-hora">h '.$row->hora.'</div>
                                  <div class="noti-titulo">'.$row->titulo.'</div>
                                </div>
                              </div>
                              <div class="col-xs-6 noti-der">
                                <div class="row">'.img($image_properties).'</div>
                               
                              </div>
                            </div>
                          </div>';
             $img .= '<div class="modal fade" id="modal_'.$i.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">'.$row->descripcion.'</div>
                          </div>
                        </div>
                      </div>';
             $i++;
            
        }
        return $img; 
    }
    function acceso($param){
        $this->db->where('estatus', 'publicado');
        $this->db->where('eliminado', 'no');
        $this->db->where('pertenece', $param);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('web_acceso');
        $row = $query->row();
        $acc = "";
    
        $acc .= "<h3>".$row->titulo."</h3>";
        $acc .= "<p>".$row->descripcion."</p>";
        return $acc; 
    }
    function accesoId($param) {
        $this->db->where('estatus', 'publicado');
        $this->db->where('eliminado', 'no');
        $this->db->where('pertenece', $param);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('web_acceso');
        $row = $query->row();
        return $row->id;
    }
    function programacion($id,$dia,$mes) {
        $value = $dia.'/'.$mes;
        $fecha = date('Y').'-'.$mes.'-'.$dia;
        $dia = $this->convFecha($fecha);
        $this->db->where('estatus', 'publicado');
        $this->db->where('eliminado', 'no');
        $this->db->where('fecha_ini <', $fecha);
        $this->db->where('fecha_fin >', $fecha);
        $this->db->where('dia', $dia);
        $this->db->order_by('hora', 'asc');
        $query = $this->db->get('web_programacion');
//        echo $this->db->last_query();
        $cont = "";
       
        foreach ($query->result() as $row) {
            $cont .= '<li><a href="'.base_url().'web/generic/programacion/'.$id.'/'.$value.'/'.$row->id.'"><span class="prog-hora">'.$row->hora.'</span><span class="prog-nombre">'.$row->titulo.'</span></a></li>';
        }
        return $cont; 
        
    }
    function convFecha($param) {
         $esp = array('lunes','martes','miércoles','jueves','viernes','sábado','domingo');
         $ing = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
         $fecha = date('l', strtotime($param));
         return str_replace($ing,$esp,$fecha);

    }
    function idFecha($dia,$mes) {
        
        $fecha = date('Y').'-'.$mes.'-'.$dia;
        $dia = $this->convFecha($fecha);
        $this->db->where('estatus', 'publicado');
        $this->db->where('eliminado', 'no');
        $this->db->where('fecha_ini <', $fecha);
        $this->db->where('fecha_fin >', $fecha);
        $this->db->where('dia', $dia);
        $query = $this->db->get('web_programacion');
        $row = $query->row();
        
        if($query->num_rows() > 0){
            return $row->id;
        }else{
            return false;
        }
         
    }
    function document(){
        $this->db->where('eliminado !=', 'si');
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('informacion');
        $css = "class='infolinks' target=_bank";
        $doc = "";
        $doc .= '<span class="infotitle">Para descargar, haga click en el título.</span>';
        $doc .= '<img src='.base_url().'assets/img/ico_down.png class="follow" style="position: absolute;"/>';
        foreach ($query->result() as $value) {
            $doc .= anchor('uploads/informacion/'.$value->documento,$value->titulo,$css);
        }
        return $doc;
    }
    function contacto($id){
        $this->db->where('estatus', 'publicado');
        $this->db->where('eliminado !=', 'si');
        $this->db->where('id', $id);
        $query = $this->db->get('menu');
        $row = $query->row();
  
        return $row->contenido;
    }

    function configuracion()
    {
        $this->db->from('sys_siteconfig');
//        $this->db->where('eliminado !=', 'si');
        $this->db->limit(1);
        $query = $this->db->get();
        $row = $query->row();
        return $row;
    }
    function organigrama(){
        $this->db->where('pertenece', 0);
        $query = $this->db->get('organigrama');
        $fila = $query->row();
        
        $org = "";
        $org .= "<ul id='org' style='display:none'><li><div class='nombre_org'>";
        $org .= $fila->nombre;
        $org .= "</div><div class='cargo'>".$fila->funcion."</div>";
        $org .= $this->nodos($fila->id); 
        $org .= "</li></ul>";
        return $org;
    }

    function nodos($id){
        $this->db->where('pertenece', $id);
        $query = $this->db->get('organigrama');
        if($query){
            $org2 = "";
            $org2 .= "<ul>";
            foreach ($query->result() as $key ) {
                $org2 .= "<li><div class='nombre_org'>";
                $org2 .=  $key->nombre;
                $org2 .= "</div><div class='cargo'>".$key->funcion."</div>";
                $id = $key->id;
                $org2 .= $this->nodos($id);
                $org2 .= "</li>";
            }
            $org2 .= "</ul>";
        return $org2;
        }        
    }
    function noticia($id){
        $this->db->where('estatus', 'publicado');
        $this->db->where('eliminado !=', 'si');
        if(!empty($id))$this->db->where('id', $id);
        if(empty($id))$this->db->order_by('id', 'desc');
        if(empty($id))$this->db->limit(1);
        $query = $this->db->get('web_noticias');
        
        return $query->row(); 
    }
   
    function data_list($id) {

        $this->db->where('id_gal', $id);
        $this->db->where('tipo', 'sonido');
        $this->db->where('eliminado !=', 'si'); 
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('web_archivos');
  
        $out = '';
        foreach ($query->result() as $value) {
           $out .= '<li audiourl="'.base_url().'uploads/web_galerias/'.$id.'/'.$value->archivo.'"><span class="tema">'.$value->titulo.'</span></li>';
        }
       
        return $out; 
    }
    function capitulos($id) {
        $this->db->where('id_prog', $id);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('web_capitulos');
        
        $out = '';
        $http = 'http://';
        if($query->num_rows() > 0){
            foreach ($query->result() as $value) {
                $out .= anchor($http.$value->enlace,$value->titulo,'target="_blank"');                
            }  
        }else{
            $out .= '<span>No hay capítulos disponibles</span>';
        }
        return $out;
    }
 
}
?>
