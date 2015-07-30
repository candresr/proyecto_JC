<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'modules/admin/models/admin_model'.EXT);

class Ayuda_model extends Admin_model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->dbutil();
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
        
        $this->db->from('sys_roles');
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
            $this->db->order_by('f_orden');
            $cquery = $this->db->get('sys_esq_campos');
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
        $this->db->order_by('nom_tabla', 'asc');
        $query = $this->db->get('sys_esq_tablas');
        return $query->row();
    }
    
    function dataAllCampos($nom_tabla)
    {
        $salida = "";
        $this->db->where('nom_tabla', $nom_tabla);
        $qTabla = $this->db->get('sys_esq_tablas');
        $fila = $qTabla->row();
        $salida .= "El módulo de <b>$fila->etiqueta</b> cuenta con los siguientes campos:".br(2);
        $this->db->where('nom_tabla', $nom_tabla);
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
        $qCampos = $this->db->get('sys_esq_campos');
        return $qCampos->row();
    } 
    
}
?>
