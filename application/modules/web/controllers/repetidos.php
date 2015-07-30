<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Repetidos Class
 * @package         web
 * @subpackage      controllers
 * @author          Cesar Andres Ramirez <candresramirez@gmail.com>
 * @copyright       Por definir
 * @license         Por definir
 * @version         v1.0 
 *  * */
class Repetidos extends CI_Controller
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
        $this->load->helper('cookie');
        $this->load->library('zip');
        $this->load->library('user_agent');
        $this->load->library('form_validation');
	    $this->load->library('pagination');
        $this->load->library('session');
        $this->load->library('table');
	    $this->load->database();

        $this->load->model('repetidos_model');
        $this->load->model('web_model');
        
    }

    /**
     * <b>Method: inicio_re</b>
     * @method  Metodo de procesamiento de datos para redirigir a la seccion del menu en el cual se invoque
     * @param   $param
     * @return  return
     * @author  Cesar Andres Ramirez
     * */
    function inicio_re($dato)
    {
        
        switch ($dato['ref']) {
            case 'inicio':
                $result             = $this->repetidos_model->crea_menu();
                $data['menu']       = $result['menu'];
                $dato['furia']      = $this->repetidos_model->acceso('furia');
                $dato['ccs']        = $this->repetidos_model->acceso('ccs');
                $dato['sono']       = $this->repetidos_model->acceso('sono');
                $dato['sonoid']     = $this->repetidos_model->accesoId('sono');
                $dato['ccsid']     = $this->repetidos_model->accesoId('ccs');
                $dato['avila']      = $this->repetidos_model->contenido();
                $dato['imgavila']   = $this->repetidos_model->imgcont();
                $dato['galeria']    = $this->repetidos_model->galeria();
                $dato['fechaDN']    = $this->dateserver("%A %d");
                $dato['fechaM']    = $this->dateserver("%b");
                $data['css']        = $this->repetidos_model->css($dato['id']);
                $data['content']    = $this->load->view('home',$dato,TRUE);
                break;
            case 'programacion':
                if(empty($dato['dia'])) $dia = $this->dateserver("%d"); else $dia = $dato['dia'];
                if(empty($dato['mes'])) $mes = $this->dateserver("%m"); else $mes = $dato['mes'];
                if(empty($dato['idP'])) $idP = $this->repetidos_model->idFecha($dia,$mes); else $idP = $dato['idP'];
                $result             = $this->repetidos_model->crea_menu();
                $data['menu']       = $result['menu'];
                $data['css']        = $this->repetidos_model->css($dato['id']);
                $dato['dias']       = $this->dayweek($dato['id']);
                $dato['diaP']       = $this->repetidos_model->programacion($dato['id'],$dia,$mes);
                $dato['contenido']  = $this->repetidos_model->programacionDia($idP);
                $data['content']    = $this->load->view('programacion',$dato,TRUE);
                break;
            case 'agenda':
                $result             = $this->repetidos_model->crea_menu();
                $data['menu']       = $result['menu'];
                $data['css']        = 'agenda';
                $dato['contenido']  = $this->repetidos_model->dinamic('web_agenda');
                $data['content']    = $this->load->view('agenda',$dato,TRUE);
            break;
            case 'promos':
                $result             = $this->repetidos_model->crea_menu();
                $data['menu']       = $result['menu'];
                $data['css']        = $this->repetidos_model->css($dato['id']);
                $dato['contenido']  = $this->repetidos_model->dinamic('web_promos');
                $data['content']    = $this->load->view('promos',$dato,TRUE);
            break;
            case 'quienes':
                $result             = $this->repetidos_model->crea_menu();
                $data['menu']       = $result['menu'];
                $data['css']        = $this->repetidos_model->css($dato['id']);
                $dato['contenido']  = $this->repetidos_model->generic($dato['id']);
                $data['content']    = $this->load->view('quienes',$dato,TRUE);
                break;
           case 'contacto':
                $result             = $this->repetidos_model->crea_menu();
                $data['menu']       = $result['menu'];
                $data['css']        = $this->repetidos_model->css($dato['id']);
                $dato['contenido']  = $this->repetidos_model->generic($dato['id']);
                $data['content']    = $this->load->view('quienes',$dato,TRUE);
                break;
            case 'sono':

                $result             = $this->repetidos_model->crea_menu();
                $data['menu']       = $result['menu'];
                $data['css']        = 'sono-avila';
                $dato['playlist']   = $this->repetidos_model->dinamic('web_sono');
                $dato['contenido']  = '';
                $data['content']    = $this->load->view('sonoavila',$dato,TRUE);
                break;
            case 'descargas':
                $result             = $this->repetidos_model->crea_menu();
                $data['menu']       = $result['menu'];
                $data['css']        = $this->repetidos_model->css($dato['id']);
                $dato['contenido']  = $this->repetidos_model->dinamic('web_descargas');
                $data['content']    = $this->load->view('descargas',$dato,TRUE);
                break;
            case 'actualidad':
                if(empty($dato['idN'])) $idN = ''; else $idN = $dato['idN'];
                $result             = $this->repetidos_model->crea_menu();
                $data['menu']       = $result['menu'];
                $data['css']        = $this->repetidos_model->css($dato['id']);
                $dato['contenido']  = $this->repetidos_model->dinamic('web_noticias');
                $dato['id']         = $dato['id'];
                $dato['noticia']    = $this->repetidos_model->noticia($idN);
                $data['content']    = $this->load->view('actualidad',$dato,TRUE);
                break;
        }

        $configuracion      = $this->repetidos_model->configuracion();
        $data['titulo']     = $configuracion->titulo;    
        $data['descripcion']= $configuracion->descripcion;         
        $data['palabra_clave']= $configuracion->palabra_clave;         
        $data['dueno']      = $configuracion->dueno;         
        $data['mail']       = $configuracion->mail;        
        $data['paginado']   = $configuracion->paginado;
        $data['base']       = $configuracion->base;
        
        return $data;
    }
    
    /**
     * <b>Method: dayweek</b>
     * @method  Metodo para la seleccion de los programas en la parrilla de programacion TV 
     * teniendo como primer elemento de la lista el dia presente
     * @param   $param
     * @return  return listado de dias
     * @author  Cesar Andres Ramirez
     * */
    function dayweek($id) {

	$esp = array('LUNES','MARTES','MIÉRCOLES','JUEVES','VIERNES','SÁBADO','DOMINGO');
	$ing = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
        $dias = '';
        $currentDate = date("d F Y");
        for($i=0;$i<=6;$i++){
            $incrementedTime = strtotime("+$i day", strtotime($currentDate));
            $fecha = date('l', $incrementedTime);
            $traslate = str_replace($ing,$esp,$fecha);
            $value = date("d/m", $incrementedTime);
            $dias .= '<li><a href="'.base_url().'web/generic/programacion/'.$id.'/'.$value.'"><span class="prog-dias">'.$traslate.'</span><span class="prog-fecha">'.$value.'</span></a></li>';
        }

        return $dias;
    }
    /**
     * <b>Method: dateserver</b>
     * @method  Metodo que devuelve la fecha del servidor en lenguaje espanol
     * @param   $param
     * @return  return fecha formateada es_ES
     * @author  Cesar Andres Ramirez
     * */
    function dateserver($param) {
        $lenguage = 'es_ES.UTF-8';
        putenv("LANG=$lenguage");
        setlocale(LC_ALL, $lenguage);
        return strftime($param); 
    }    
}        
?>