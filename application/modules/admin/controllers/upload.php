<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends CI_controller {

	function __construct() {
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

            $this->load->model('upload_model');
	}


	/*
	  <b>Method:	winUpload()</b>
	 * @method		Levanta la ventana de upload, espera dos parametros opId, y fieldId
	 * @param		
	 * @return		return
	 * @author		Juan Carlos Lopez
	 * @version		v1.0 03/11/11 10:45 AM
	 * */

	function winUpload()
        {
            $galId = $this->input->get('opId');

            $dataUpload = array(
                    'title' => 'Manejador de Archivos',
                    'name' => 'wUpload',
                    'galId' => $opId
            );
            $this->load->view('upload.js.php', $dataUpload);
	}

	function do_upload($params)
        {

            if (!empty($params) && $params[0] == 'process')
            {
                    $dataProcess = $this->dyna_views->processForm();
                    $result = FALSE;

                    //Seleccionar y fijar el modulo al que pertenece el campo
                    $module = $this->upload_model->getFolderPathByFieldId($dataProcess['data']['file_field_id']);
                    if ($module == 'cuerpos_policiales')
                            $instancia_id = $this->session->userdata('cuerpo_policial_id');
                    elseif ($module == 'funcionarios')
                            $instancia_id = $this->session->userdata('funcionario_id');
                    elseif ($module == 'personas')
                            $instancia_id = $this->session->userdata('persona_id');
                    //Fijar el tipo de campo
                    $tipo = $this->upload_model->getTipoCampo($dataProcess['data']['file_field_id']);

                    //Validar que solo se cargue una imagen por campo fileupload (single file)
                    if (($tipo == 'fileupload') && ($this->upload_model->getCountFilesByCampoId($dataProcess['data']['file_field_id'], $instancia_id) > 0)) {
                            $result = TRUE;
                            $msg = $this->lang->line('file_single_upload_error');
                    } else {
                            //Generar el nombre del archivo
                            $nombre_archivo = md5($dataProcess['data']['file_field_id'] . $instance_id . date('Y-m-d h:i:s'));

                            //Generar la ruta donde se guardara el archivo
                            $path = $this->upload_model->generatePath('upload_data', $module, $instancia_id);
                            if (!file_exists($path)) {
                                    if (!mkdir($path, 0755, TRUE)) {
                                            $result = TRUE;
                                            $msg = $this->lang->line('file_load_error');
                                            throwResponse("Subida de Archivo", $result, $msg);
                                            die;
                                    }
                            }
                            $config['upload_path'] = $path;
                            $config['allowed_types'] = 'jpg|jpeg|png|gif|doc|txt|xls|ods|pps|ppt|odt|odp|pdf|rtf';
                            $config['max_size'] = 1024 * 2;
                            $config['file_name'] = $nombre_archivo;
                            $config['remove_spaces'] = TRUE;

                            $this->load->library('upload', $config);
                            if (!$this->upload->do_upload()) {
                                    $error = $this->upload->display_errors();
                                    $error = preg_replace("[\n|\r|\n\r|<p>]", " ", $error);
                                    $error = preg_replace("[</p>]", "<br>", $error);
                                    $upload_data = $this->upload->data();
                                    $msg = '';
                                    $msg .= $error;
                                    $msg .= 'Ha ocurrido algun error al subir su archivo al servidor, verifique que ha podido pasar!<br><br>';

                                    foreach ($upload_data as $item => $value) {
                                            $msg .= $item . ": " . $value . "<br>";
                                    }
                                    $result = TRUE;
                            } else {

                                    $upload_data = $this->upload->data();
                                    $file_name = $upload_data['file_name'];
                                    $file_type = $upload_data['file_type'];
                                    $file_ext = $upload_data['file_ext'];
                                    $file_size = $upload_data['file_size'];
                                    $is_image = $upload_data['is_image'];
                                    $image_width = $upload_data['image_width'];
                                    $image_height = $upload_data['image_height'];

                                    if ($is_image == 1)
                                            $this->create_img($path, $image_width, $image_height, $file_name);

                                    if ($file_ext == ".flv") {
                                            $video = $path . "/" . $file_name;
                                            $out = $this->ceate_video_thum($video, $path);
                                            chmod($out . "1.jpg", 0777);
                                    }

                                    //Generar el arreglo para el insert en la tabla archivos
                                    $arr_insert_archivos = array(
                                            'nombre' => $nombre_archivo, //Cambiar nombre luego de integrar con Juan Carlos
                                            'titulo' => $dataProcess['data']['title_file'],
                                            'extension' => $file_ext,
                                            'ruta' => $path,
                                            'extension' => $file_ext,
                                            'observaciones' => 'Data de Prueba Reynaldo y Eliel', //Cable
                                    );

                                    //Generar el arreglo para el insert en la tabla relaciones archivos
                                    $arr_insert_relaciones = array(
                                            'campo_id' => $dataProcess['data']['file_field_id'],
                                            'instancia_id' => $instancia_id,
                                            'observaciones' => 'Data de Prueba', //Cable
                                    );

                                    //Fijar los valores de los arreglos de sesion para almacenar los archivos en la BD
                                    $op_id = $dataProcess['data']['opId'];
                                    if ($tipo == 'fileupload') {
                                            $index = $arr_insert_relaciones['campo_id'];
                                            //Guardar en sesion todos los archivos a subir
                                            $arr_session_archivos = $this->session->userdata('archivos');
                                            $arr_session_archivos[$op_id][$instance_id][$index] = $arr_insert_archivos;
                                            $this->session->set_userdata('archivos', $arr_session_archivos);

                                            $arr_session_relaciones = $this->session->userdata('relaciones_archivos');
                                            $arr_session_relaciones[$op_id][$instance_id][$index] = $arr_insert_relaciones;
                                            $this->session->set_userdata('relaciones_archivos', $arr_session_relaciones);
                                    } else {
                                            //Guardar en sesion todos los archivos a subir
                                            $arr_session_archivos = $this->session->userdata('archivos');
                                            $arr_session_archivos[$op_id][$instance_id][] = $arr_insert_archivos;
                                            $this->session->set_userdata('archivos', $arr_session_archivos);

                                            $arr_session_relaciones = $this->session->userdata('relaciones_archivos');
                                            $arr_session_relaciones[$op_id][$instance_id][] = $arr_insert_relaciones;
                                            $this->session->set_userdata('relaciones_archivos', $arr_session_relaciones);
                                    }
                                    $result = TRUE;
                                    $msg = $this->lang->line('file_upload_success');
                            }
                    }
                    throwResponse("Subida de Archivo", $result, $msg);
            }
	}

	function ceate_video_thum($video, $direccion_upload) {
		$in = $video;
		$pedazos = explode("/", $in);
		$cant = count($pedazos);
		$arch_ext = $pedazos[$cant - 1];
		$nom_jpg_explo = explode(".", $arch_ext);
		$nom_jpg = $nom_jpg_explo[0];
		$out = $direccion_upload . "/" . $nom_jpg;
		$time = "00:00:10"; //momento desde donde tomar los cuadros ejm. 00:00:01
		$frames = "1"; //cantidad de cuadros q queremos sacar del video
		$tamanio = "130x98"; // tamanio del video ejm. 320x240
		$ffmpegPath = "/usr/bin/ffmpeg";
		$flvtool2Path = "/usr/bin/flvtool2";
		//sacar cuadros de un video de cualquier extencion
		$shell = $ffmpegPath;
		$shell .= " -i " . $in;
		if (empty($time)) {
			$shell .= " -an -ss 00:00:01";
		} else {
			$shell .= " -an -ss " . $time;
		}
		if (empty($frames)) {
			$shell .= " -an -r 1 -vframes 1 -y ";
		} else {
			$shell .= " -an -r 1 -vframes " . $frames . " -y ";
		}
		if (empty($tamanio)) {
			
		} else {
			$shell .= "-s " . $tamanio;
		}
		$shell .= " " . $out . "%d.jpg"; //varios cuadros
		shell_exec($shell);
		return $out;
	}

	function create_img($path, $image_width, $image_height, $file_name) {
		if ($image_width > $image_height) {
			$width = "130";
			$hight = floor($width / 1.3333);
		} else {
			$hight = "120";
			$width = floor($hight / 1.3333);
		}

		if ($image_width > $image_height) {
			$width_med = "800";
			$hight_med = floor($width_med / 1.3333);
		} else {
			$hight_med = "800";
			$width_med = floor($hight_med / 1.3333);
		}

		$config1['image_library'] = 'GD2';
		$config1['source_image'] = $path . '/' . $file_name;
		$config1['new_image'] = $path . '/' . 'med_' . $file_name;
		$config1['create_thumb'] = FALSE;
		$config1['maintain_ratio'] = FALSE;
		$config1['width'] = $width_med;
		$config1['height'] = $hight_med;
		$config1['quality'] = "90%";
		$this->load->library('image_lib', $config1);
		$this->image_lib->resize();
		$this->image_lib->clear();

		$config['image_library'] = 'GD2';
		$config['source_image'] = $path . '/' . $file_name;
		$config['new_image'] = $path . '/' . 'peq_' . $file_name;
		$config['create_thumb'] = FALSE;
		$config['maintain_ratio'] = FALSE;
		$config['width'] = $width;
		$config['height'] = $hight;
		$config['quality'] = "90%";
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		$this->image_lib->clear();
	}

	function makeFolder() {
		$dir_upload = "./uploads/" . $nom_tabla . "/";
		if (!file_exists($dir_upload)) {
			mkdir($dir_upload, 0755);
			chmod($dir_upload, 0755);
		}
	}

	function listAll() {

		$start = isset($_GET['start']) ? $_GET['start'] : 0;
		$limit = isset($_GET['limit']) ? $_GET['limit'] : $this->config->item('thumb_limit');
		if (isset($_GET['fieldId'])) {
			$campo_id = $this->input->get('fieldId');
			$this->session->set_userdata('campo_id', $campo_id);
		}else
			$campo_id = $this->session->userdata('campo_id');

		//Seleccionar el modulo al que pertenece el campo
		$module = $this->upload_model->getFolderPathByFieldId($campo_id);
		if ($module == 'cuerpos_policiales')
			$instancia_id = $this->session->userdata('cuerpo_policial_id');
		elseif ($module == 'funcionarios')
			$instancia_id = $this->session->userdata('funcionario_id');
		elseif ($module == 'personas')
			$instancia_id = $this->session->userdata('persona_id');

		$salida = $this->upload_model->listAll($limit, $start, $campo_id, $instancia_id);
		echo json_encode($salida);
	}

	function descargarArchivo() {
		$this->load->helper('download');
		$data = file_get_contents($this->inptu->post('vinc'));
		$name = $this->inptu->post('name');
		force_download($name, $data);
	}

	/*
	 * <b>Method:	deleteFile()</b>
	 * @method		Elimina Archivo, de formas binaria
	 * @author		Juan Carlos Lopez, Eliel Parra
	 * @version		v2.0 04/11/11 01:08 PM
	 * */

	function deleteFile() {

		if ($this->upload_model->createDeleteArray($this->input->post('id'))) {
			$salida = array(
				'success' => TRUE,
				'msg' => $this->lang->line('file_upload_delete_success'),
				'title' => 'Acci&oacute;n sobre Archivos'
			);
		} else {
			$salida = array(
				'success' => TRUE,
				'msg' => $this->lang->line('message_operation_error'),
				'title' => 'Acci&oacute;n sobre Archivos'
			);
		}
		echo json_encode($salida);
	}

	/*
	  <b>Method:	fileEdit()</b>
	 * @method		Muestra detalles de el archivo y permite su edicion
	 * @param		$params
	 * @return		return
	 * @author		Juan Carlos Lopez
	 * @version		v1.0 01/11/11 12:01 PM
	 * */

	function fileEdit($params) {
		$id = $this->input->post('id');
		if (!empty($params) && $params[0] == 'process') {
			$dataProcess = $this->dyna_views->processForm();
			if ($this->upload_model->createUpdateArray($id, $dataProcess['data'])) {
				$result = TRUE;
				$msg = $this->lang->line('file_upload_update_success');
			} else {
				$result = FALSE;
				$msg = $this->lang->line('message_operation_error');
			}
			throwResponse("Edición de Detalles de Archivo", $result, $msg);
		} else {
			$data = $this->upload_model->fileDetail($id);
			$titulo = $data['titulo'];
			$nombre = $data['nombre'];
			$extension = $data['extension'];
			$ruta = $data['ruta'];
			$observaciones = $data['observaciones'];
			switch ($extension) {
				case '.jpg':
				case '.png':
				case '.jpeg':
				case '.gif':
					$direccion = base_url() . $ruta . 'peq_' . $nombre . $extension;
					$direccion_download = base_url() . $ruta . $nombre . $extension;
					break;
				case '.doc':
				case '.odt':
				case '.rtf':
					$direccion = base_url() . 'assets/img/icon_file/MS-Office-2003-Word-icon.png';
					$direccion_download = base_url() . $ruta . $nombre . $extension;
					break;
				case '.ods':
				case '.xls':
					$direccion = base_url() . 'assets/img/icon_file/MS-Office-2003-Excel-icon.png';
					$direccion_download = base_url() . $ruta . $nombre . $extension;
					break;
				case '.pdf':
					$direccion = base_url() . 'assets/img/icon_file/PDF-icon.png';
					$direccion_download = base_url() . $ruta . $nombre . $extension;
					break;
				case '.txt':
					$direccion = base_url() . 'assets/img/icon_file/Document-Copy-icon.png';
					$direccion_download = base_url() . $ruta . $nombre . $extension;
					break;
				case '.pps':
				case '.ppt':
					$direccion = base_url() . 'assets/img/icon_file/MS-Office-2003-PowerPoint-icon.png';
					$direccion_download = base_url() . $ruta . $nombre . $extension;
					break;
			}

			$panelData['panelTipe'] = '2B';

			$datFile = "";
			$datFile .= '<div style="padding:5px; border:1px solid grey; margin-right:8px; background-color:#FFFFFF;"><div style=" margin-bottom:3px;"><a href="' . $direccion_download . '" target="_blank"><img width="100" height="100" src="' . $direccion . '" alt="' . $titulo . '" title="' . $titulo . '"></a></div><div align="center" class="bot_holder"><a href="' . $direccion_download . '" target="_blank" class="download_bot round_corner"><img src="' . base_url() . 'assets/img/icons/arrow_down.png" align="center" alt="Descargar"> Descargar</a></div></div>';

			$elHtml = $this->dyna_views->buildPanelHtml('Archivo', 'archivo', '', $datFile, $scriptTags = false, true, $extraOptions);
			$panelData['p1'] = $elHtml;
			$panelData['tipo1'] = 'PanelHtml_';

			$elForm = $this->dyna_views->buildForm('Editar de Archivo', 'editDetail', '', $data, false, $extraOptions);
			$panelData['p2'] = $elForm;
			$panelData['tipo2'] = 'form_';

			$this->dyna_views->buildPanel('', 'funcionario', 'window', $panelData, false, false);
		}
	}

	/*
	  <b>Method:	fileDetail()</b>
	 * @method		Permite la visulaizacion de los datos del archivo
	 * @param		
	 * @return		return
	 * @author		Juan Carlos Lopez
	 * @version		v1.0 02/11/11 03:26 PM
	 * */

	function fileDetail() {
		$id = $this->input->post('id');
		$data = $this->upload_model->fileDetail($id);
		$titulo = $data['titulo'];
		$nombre = $data['nombre'];
		$extension = $data['extension'];
		$ruta = $data['ruta'];
		$observaciones = $data['observaciones'];
		switch ($extension) {
			case '.jpg':
			case '.png':
			case '.jpeg':
			case '.gif':
				$direccion = base_url() . $ruta . 'peq_' . $nombre . $extension;
				$direccion_download = base_url() . $ruta . $nombre . $extension;
				break;
			case '.doc':
			case '.odt':
			case '.rtf':
				$direccion = base_url() . 'assets/img/icon_file/MS-Office-2003-Word-icon.png';
				$direccion_download = base_url() . $ruta . $nombre . $extension;
				break;
			case '.ods':
			case '.xls':
				$direccion = base_url() . 'assets/img/icon_file/MS-Office-2003-Excel-icon.png';
				$direccion_download = base_url() . $ruta . $nombre . $extension;
				break;
			case '.pdf':
				$direccion = base_url() . 'assets/img/icon_file/PDF-icon.png';
				$direccion_download = base_url() . $ruta . $nombre . $extension;
				break;
			case '.txt':
				$direccion = base_url() . 'assets/img/icon_file/Document-Copy-icon.png';
				$direccion_download = base_url() . $ruta . $nombre . $extension;
				break;
			case '.pps':
			case '.ppt':
				$direccion = base_url() . 'assets/img/icon_file/MS-Office-2003-PowerPoint-icon.png';
				$direccion_download = base_url() . $ruta . $nombre . $extension;
				break;
		}

		$panelData['panelTipe'] = '2B';
		$extraOptions['CancelButton'] = '0';

		$datFile = "";
		$datFile .= '<div style="padding:5px; border:1px solid grey; margin-right:8px; background-color:#FFFFFF;"><div style=" margin-bottom:3px;"><a href="' . $direccion_download . '" target="_blank"><img width="100" height="100" src="' . $direccion . '" alt="' . $titulo . '" title="' . $titulo . '"></a></div><div align="center" class="bot_holder"><a href="' . $direccion_download . '" target="_blank" class="download_bot round_corner"><img src="' . base_url() . 'assets/img/icons/arrow_down.png" align="center" alt="Descargar"> Descargar</a></div></div>';

		$elHtml = $this->dyna_views->buildPanelHtml('Archivo', 'archivo', '', $datFile, $scriptTags = false, true, $extraOptions);
		$panelData['p1'] = $elHtml;
		$panelData['tipo1'] = 'PanelHtml_';

		$elForm = $this->dyna_views->buildForm('Detalle de Archivo', 'Detail', '', $data, false, $extraOptions);
		$panelData['p2'] = $elForm;
		$panelData['tipo2'] = 'form_';

		$this->dyna_views->buildPanel('', 'funcionario', 'window', $panelData, false, $extraOptions);
	}

}

?>