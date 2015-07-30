<?php
class Ayuda_model extends CI_Model
{
	
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
        $this->load->library('session');
    }
	
    
		
     /**
     * <b>Method: crearArbol</b>
     * @method	Consulta las tablas Roles, Esquema de Tablas y Esquema de Campos para de acuerdo a el usuario que esta en session mostrar una ayuda particular, 
     * @param	parametros de session
     * @return	returna el json que crea el arbol de navegacion
     * @author	Juan Carlos Lopez Guillot
     * */
    function crearArbol()
    {
        $userId = $this->session->userdata("userId");
        
        $this->db->from('roles');
        $this->db->where('visible', 'true');
        $this->db->where('id_user', $userId);
        $qroles = $this->db->get();
        $nrol = $qroles->num_rows();
        $r = 1;
        $arbol = "{  
                    text:'Root',  
                    children:[  
                    ";
        
        foreach($qroles->result() as $rol)
        {
            $arbol .= " {
                        text:'$rol->titulo',  
                        iconCls:'ceo-icon', 
                        nom_tabla: '$rol->nom_tabla',
                        listeners: {
                            click: function(n) {
                                Ext.Ajax.request({
                                    url: BASE_URL + 'ayuda/deModulo',
                                    method: 'GET',
                                    params: { nom_tabla: '$rol->nom_tabla' },
                                    success: function(action, request) {
                                    eval(action.responseText);
                                    },
                                    failure: function(action, request) {
                                    var obj = Ext.util.JSON.decode(action.responseText);
                                               Ext.Msg.show({
                                                            title: 'Error',
                                                            msg: 'Ha ocurrido un error en la conexi&oacute;n con el servidor',
                                                            minWidth: 200,
                                                            modal: true,
                                                            icon: Ext.Msg.INFO,
                                                            buttons: Ext.Msg.OK
                                                        });         
                                    } 
                                });   
                                }
                           },
                        children:[{ 
                            text:'Campos del Modulo',
                            iconCls:'developer-icon',  
                            listeners: {
                            click: function(n) {
                                Ext.Ajax.request({
                                    url: BASE_URL + 'ayuda/deLosCampos',
                                    method: 'GET',
                                    params: { nom_tabla: '$rol->nom_tabla' },
                                    success: function(action, request) {
                                    eval(action.responseText);
                                    },
                                    failure: function(action, request) {
                                    var obj = Ext.util.JSON.decode(action.responseText);
                                               Ext.Msg.show({
                                                            title: 'Error',
                                                            msg: 'Ha ocurrido un error en la conexi&oacute;n con el servidor',
                                                            minWidth: 200,
                                                            modal: true,
                                                            icon: Ext.Msg.INFO,
                                                            buttons: Ext.Msg.OK
                                                        });         
                                            } 
                                        });   
                                    }
                               },
                            children:[
                        ";
            
            $this->db->where('nom_tabla', $rol->nom_tabla);
            $this->db->where('eliminado !=', 'si');
            $this->db->order_by('f_orden');
            $cquery = $this->db->get('esq_campos');
            $ncam = $cquery->num_rows();
            $n = 1;
            foreach($cquery->result() as $cam)
            {
                $arbol .= "{    
                            text:'$cam->f_titulo',  
                            iconCls:'developer-icon', 
                            listeners: {
                            click: function(n) {
                                Ext.Ajax.request({
                                    url: BASE_URL + 'ayuda/delCampo',
                                    method: 'GET',
                                    params: { nom_tabla: '$rol->nom_tabla', f_name: '$cam->f_name' },
                                    success: function(action, request) {
                                    eval(action.responseText);
                                    },
                                    failure: function(action, request) {
                                    var obj = Ext.util.JSON.decode(action.responseText);
                                               Ext.Msg.show({
                                                            title: 'Error',
                                                            msg: 'Ha ocurrido un error en la conexi&oacute;n con el servidor',
                                                            minWidth: 200,
                                                            modal: true,
                                                            icon: Ext.Msg.INFO,
                                                            buttons: Ext.Msg.OK
                                                        });         
                                         } 
                                   }); 
                                }
                            },
                            leaf:true
                            }
                            ";
                if($ncam != $n){ $arbol .= ","; }
                $n = $n+1;
            }
            $arbol .= "]}]}";
            if($nrol != $r){ $arbol .= ","; }
            $r = $r+1;
        }
        $arbol .= "]}";
        return $arbol;
    }    
    
    
    function dataTabla($nom_tabla)
    {
        $this->db->where('nom_tabla', $nom_tabla);
        $query = $this->db->get('esq_tablas');
        return $query->row();
    }
    
    function dataAllCampos($nom_tabla)
    {
        $salida = "";
        $this->db->where('nom_tabla', $nom_tabla);
        $this->db->where('eliminado', 'no');
        $qTabla = $this->db->get('esq_tablas');
        $fila = $qTabla->row();
        $salida .= "El módulo de <b>$fila->etiqueta</b> cuenta con los siguientes campos:".br(2);
        $this->db->where('nom_tabla', $nom_tabla);
        $this->db->where('eliminado', 'no');
        $this->db->order_by('f_orden');
        $qEsquema = $this->db->get('esq_campos');
        foreach($qEsquema->result() as $row)
        {
            $salida .= "Campo <b>$row->f_titulo</b>:".br();
            $salida .= "La etiqueta de este campo es <b>$row->f_titulo</b>".br();
            $salida .= "El nombre del campo en la base de datos es <b>$row->f_name</b>".br(2);
        }
        return $salida;
    }
    
    function dataCampos($nom_tabla, $f_name)
    {
        $this->db->where('nom_tabla', $nom_tabla);
        $this->db->where('f_name', $f_name);
        $this->db->where('eliminado', 'no');
        $qCampos = $this->db->get('esq_campos');
        return $qCampos->row();
    } 
    
//    function creaAyuda()
//    {
//        $salida = "";
//        $this->db->where('eliminado', 'no');
//        $this->db->order_by('orden');
//        $qTablas = $this->db->get('esq_tablas');
//        
//        foreach($qTablas->result() as $trow)
//        {    
//            $etiqueta       = $trow->etiqueta;
//            $nom_tabla      = $trow->nom_tabla;
//            $descripcion    = $trow->descripcion;
//            
//            $salida .= "<div class='contHelpMod'>";
//            $salida .= "<span class='negrita'>Módulo de $etiqueta </span><br/><br/>";
//            $salida .= "Etiqueta del Módulo: <span class='negrita'>$etiqueta</span> <br/> ";
//            $salida .= "Nombre de la tabla en la base de datos: <span class='negrita'>$nom_tabla</span> <br/><br/>";
//            $salida .= $descripcion;
//            $salida .= "</div>";
//            
//            $this->db->where('nom_tabla', $nom_tabla);
//            $this->db->where('eliminado', 'no');
//            $this->db->order_by('f_orden');
//            $qCampos = $this->db->get('esq_campos');
//            
//            foreach($qCampos->result() as $crow)
//            {    
//                $f_titulo   = $crow->f_titulo;
//                $f_name     = $crow->f_name;
//                $f_campo    = $crow->f_campo;
//                $f_ayuda    = $crow->f_ayuda;
//                $salida .= "<br><div class='contHelpMod'>Campo <span class='negrita'>$f_titulo </span><br/>";
//                $salida .= "Etiqueta del Campo: <span class='negrita'>$f_titulo</span> <br/> ";
//                $salida .= "Nombre del campo en base de datos: <span class='negrita'>$f_name</span><br/>";
//                $salida .= "Tipo de campo: <span class='negrita'>$f_campo</span> <br/><br/>";
//                $salida .= $f_ayuda; 
//                $salida .= "</div>";
//            }
//            $salida .= "<br/>----------------------------------------<br/><br/>";
//        }
//        return $salida;
//    }
    
}
?>
