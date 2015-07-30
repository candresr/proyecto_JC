<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define("LXP", 25);
define("NOM_SITIO", "PORTAL DE TRANSPARENCIA");

class Ayuda extends CI_Controller
{
	
    function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('date');
        $this->load->helper('text');
        $this->load->helper('url');
        $this->load->helper('file');
        $this->load->helper('download');
        $this->load->helper('html');
        $this->load->helper('inflector');
        $this->load->library('form_validation');
        $this->load->library('session');

        $this->load->model('ayuda_model');
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
//        $replace = "replaceCenterContent(Grid_$nom_tabla);";

        $arbol = $this->ayuda_model->crearArbol();
        $viewData = array('root' => $arbol);
        $this->load->view("ayuda.js.php", $viewData);
    }
    
    function deModulo()
    {
        $nom_tabla  = $this->input->get('nom_tabla');
        $data       = $this->ayuda_model->dataTabla($nom_tabla);
        
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
        $data['modField']   = $this->ayuda_model->dataAllCampos($nom_tabla);        
        $dEtiqueta          = $this->ayuda_model->dataTabla($nom_tabla);
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
        $data           = $this->ayuda_model->dataCampos($nom_tabla, $f_name); 
        $dEtiqueta      = $this->ayuda_model->dataTabla($nom_tabla);
        $elhtml         = $this->load->view('ayuda_field_view', $data, TRUE);
        $viewData       = array(
                            'elhtml' => $elhtml,
                            'titulo' => "$dEtiqueta->etiqueta - $data->f_titulo"
                            );
        $this->load->view("ayudaConten.js.php", $viewData);
    }
    
//    function creaAyuda()
//    {
//        $datos['ayuda'] = $this->ayuda_model->creaAyuda();
//        $this->load->view('ayuda_view', $datos);
//    }
        
}        
?>