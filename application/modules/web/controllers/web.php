<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Web Class
 * @package         web
 * @subpackage      controllers
 * @author          Cesar Andres Ramirez <candresramirez@gmail.com>
 * @copyright       Por definir
 * @license         Por definir
 * @version         v1.0 
 *  * */
include_once("repetidos.php");
class Web extends Repetidos
{

    /**
     * <b>Method: index</b>
     * @method	Metodo al que se invoca por defecto al entrar al sitio web
     * @param	$param
     * @return	return
     * @author	Cesar Andres Ramirez
     * */
    function index ()
    {	
        $dato['id']      = 1;
        $dato['ref']     = 'inicio';
        $data            = $this->inicio_re($dato);
        $this->load->view('index_view', $data);
 
    }
    /**
     * <b>Method: generic</b>
     * @method  Metodo que recibe el parametro via GET para se enviado a la funcion inicio_re
     * @param   $param
     * @return  data resultado de el proceso de base de datos
     * @author  Cesar Andres Ramirez
     * */
    function generic(){ 
        $dato['id']        = $this->uri->segment(4);
        $dato['ref']       = $this->uri->segment(3);
        if($this->uri->segment(3) == 'programacion'){
            $dato['dia'] = $this->uri->segment(5);
            $dato['mes'] = $this->uri->segment(6);
            $dato['idP'] = $this->uri->segment(7);
        }
        if($this->uri->segment(3) == 'actualidad'){
            $dato['idN'] = $this->uri->segment(5);
        }
        if($this->uri->segment(3) == 'descargas'){
            if($this->uri->segment(5)) $this->download($this->uri->segment(5));   
        }
        if($this->uri->segment(3) == 'sono'){
//            $idT = $this->uri->segment(5);
//            $dato['idN'] = $idT;
          
        }
     
        $data              = $this->inicio_re($dato);
        $this->load->view('index_view', $data);
    }
    /**
     * <b>Method: download</b>
     * @method  Metodo para desacarga de archivos
     * @param   nombre del archivo
     * @return  return null
     * @author  Cesar Andres Ramirez
     * */
    function download($param) {
        $name = trim($param);
        $data = file_get_contents('uploads/web_descargas/'.$name); 
        force_download($name, $data);
    }
    /**
     * <b>Method: data_list</b>
     * @method  Metodo para retornar informacion de archivos mp3 para escuchar en reproductor
     * @param   id
     * @return  return nombre del archivo encontrado
     * @author  Cesar Andres Ramirez
     * */
    function data_list() {
        $id = $this->uri->segment(3);   
        $datos = $this->repetidos_model->data_list($id);
        echo $datos;

    }
    /**
     * <b>Method: index</b>
     * @method  Metodo para retornar informacion del tipo de background por cada seccion del menu
     * @param   id
     * @return  return nombre del archivo encontrado
     * @author  Cesar Andres Ramirez
     * */
    function temas() {
        $id = $this->uri->segment(3);   
        $data = $this->repetidos_model->temas($id);
        return $data;

    }    
    
     /**
     * <b>Method: detect_mobile</b>
     * @method	Metodo que permite identificar si el dispositivo desde donde estoy navegando es mobil
     * @param	$param
     * @return	return true si es mobil, false de lo contrario
     * @author	Cesar Andres Ramirez
     * */
    function detect_mobile()
    {
        if(preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipad|ipaq|ipod|j2me|java|midp|mini|mmp|mobi|motorola|nec-|nokia|palm|panasonic|philips|phone|sagem|sharp|sie-|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|xoom|zte)/i', $_SERVER['HTTP_USER_AGENT'])) 
            return true; 
        else
            return false;
    }

    /**
     * <b>Method: procesarForm</b>
     * @method  Metodo para el formulario de contacto para ser enviado por correo electronico
     * @param   $param
     * @return  return true envio exitoso, false de lo contrario
     * @author  Cesar Andres Ramirez
     * */
    function procesarForm()
    {
        $nombre         = $this->input->post('nombre');
        $organizacion   = $this->input->post('organizacion');
        $ciudad         = $this->input->post('ciudad');
        $pais           = $this->input->post('pais');
        $email          = $this->input->post('email');
        $telefono       = $this->input->post('telefono');
        $servicio       = $this->input->post('servicio');
        $mensaje        = $this->input->post('mensaje');
        
        $this->form_validation->set_rules('nombre', 'Nombre completo', 'trim|required|alpha|xss_clean');
        $this->form_validation->set_rules('organizacion', 'Organizacion', 'trim|required|alpha_numeric|xss_clean');
        $this->form_validation->set_rules('ciudad', 'Ciudad', 'trim|required|alpha_numeric|xss_clean');
        $this->form_validation->set_rules('pais', 'País', 'trim|required|alpha|xss_clean');
        $this->form_validation->set_rules('email', 'Correo-e', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('telefono', 'Teléfono', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('servicio', 'Área de servicio', 'trim|required|xss_clean');
        $this->form_validation->set_rules('mensaje', 'Mensaje', 'trim|required|xss_clean');
  
        if ($this->form_validation->run() == FALSE)
        {
            $error_validacion = validation_errors();
            $error_validacion = preg_replace("[\n|\r|\n\r]", " ", $error_validacion);
            $salida = array('valido'=>'FALSE', 'validations'=> $error_validacion);
            echo json_encode($salida);
            
        } else {
            $cuerpo = " Nombre: $nombre<br/>
                        Organizacion: $organizacion<br/>
                        Ciudad: $ciudad<br/>
                        País: $pais<br/>
                        Correo-e: $email<br/>
                        Teléfono: $telefono<br/>
                        Enviado a: $servicio<br/>
                        Mensaje: $mensaje";
            $asunto = 'Mensaje enviado desde Web Site Rialfi';           
            
        $this->load->library('email');

        $this->email->from($email, $nombre);
        $this->email->to($servicio);
        $this->email->subject($asunto);
        $this->email->message($cuerpo);
        
        if($this->email->send())
        {    
            $elHtml = 'Su solicitud se ha enviado satisfactoriamente';
            $salida = array(
                'valido'=>'TRUE', 
                'denuncias'=> $elHtml
                );
        } else {
            $salida = array(
                'valido'=>'FALSE', 
                'validations'=> 'Ha ocurrido un error, intentelo nuevamente en unos minutos'
                );
        }    
        echo json_encode($salida);
        //echo $this->email->print_debugger();
        }
    }		
   
}        
?>

