<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'modules/admin/controllers/admin'.EXT);
$path = getcwd();
define('MYPATH', $path);

class Ayuda extends Admin
{	
    function __construct()
    {
        parent::__construct();
        $this->entity = get_Class($this);
        $this->entityModel = get_Class($this).'_model';
        $this->load->model($this->entityModel, 'model_class');
    }

    /**
     * <b>Method: index</b>
     * @method	Metodo al que se invoca por defecto al entrar en la ayuda del sistema
     * @param	$param
     * @return	return
     * @author	Juan Carlos Lopez Guillot
     * */
    function index ()
    {		
        $arbol = $this->model_class->crearArbol();
        $viewData = array('root' => $arbol);
        $this->load->view("ayuda.js.php", $viewData);
    }
    
    function deModulo()
    {
        $nom_tabla  = $this->input->get('nom_tabla');
        $data       = $this->model_class->dataTabla($nom_tabla);
        
        $elhtml     = $this->load->view('ayuda_mod_view', $data, TRUE);
        $viewData   = array(
                            'elhtml' => $elhtml,
                            'titulo' => $data->etiqueta
                            );
        $this->load->view("ayudaConten.js.php", $viewData);
    }
    
    function deLosCampos()
    {
        $nom_tabla          = $this->input->get('nom_tabla');
        $data['modField']   = $this->model_class->dataAllCampos($nom_tabla);        
        $dEtiqueta          = $this->model_class->dataTabla($nom_tabla);
        $elhtml             = $this->load->view('ayuda_modfield_view', $data, TRUE);
        $viewData = array(
                            'elhtml' => $elhtml,
                            'titulo' => $dEtiqueta->etiqueta
                            );
        $this->load->view("ayudaConten.js.php", $viewData);
    }
    
    function delCampo()
    {
        $nom_tabla      = $this->input->get('nom_tabla');
        $f_name         = $this->input->get('f_name');
        $data           = $this->model_class->dataCampos($nom_tabla, $f_name); 
        $dEtiqueta      = $this->model_class->dataTabla($nom_tabla);
        $elhtml         = $this->load->view('ayuda_field_view', $data, TRUE);
        $viewData       = array(
                            'elhtml' => $elhtml,
                            'titulo' => "$dEtiqueta->etiqueta - $data->f_titulo"
                            );
        $this->load->view("ayudaConten.js.php", $viewData);
    }
        
}        
?>