<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Upload_model Class
 * 
 * @package          Upload
 * @subpackage       Controllers
 * @author           Juan Carlos Lopez
 * @copyright        Por definir
 * @license          Por definir
 * @version          v1.0 26/10/11 04:25 PM
 *  * */
class Upload_model extends CI_Model {

	var $table = 'estatico.archivos';

	public function __construct() {
		parent::__construct();
	}

	/**
	 * <b>Method:	create()</b>
	 * @method		Crea el registro de un nuevo archivo
	 * @param		array $data Detalles del archivo a crear
	 * @return		integer $this->db->insert_id() Numero identificador del registro creado
	 * @author		Eliel Parra
	 * @version		v1.0 01/11/11 07:00 PM
	 * */
	function create($data) {
		if ($this->db->insert($this->table, $data))
			return $this->db->insert_id();
	}

	/**
	 * <b>Method:	createRelacionesArchivos()</b>
	 * @method		Crea la relacion entre archivos y campos
	 * @param		array $data Detalles de la relacion de archivo y campo a crear
	 * @return		boolean TRUE en caso de exito, FALSE en caso contrario
	 * @author		Eliel Parra
	 * @version		v1.0 01/11/11 07:05 PM
	 * */
	function createRelacionesArchivos($data) {
		return $this->db->insert('estatico.relaciones_archivos', $data);
	}
	
	/**
	 * <b>Method:	createDeleteArray()</b>
	 * @method		Genera el arreglo de session para borrar los archivos seleccionados
	 * @param		integer $archivo_id Identificador del archivo
	 * @return		boolean TRUE/FALSE
	 * @author		Eliel Parra
	 * @version		v1.0 04/11/11 12:44 PM
	 **/
	function createDeleteArray($archivo_id) {
		
		if($this->session->userdata('delete_archivos')){
			$arr_delete_archivos = $this->session->userdata('delete_archivos');	
			array_push($arr_delete_archivos, $archivo_id);
			$this->session->set_userdata('delete_archivos', $arr_delete_archivos);
		}else
			$this->session->set_userdata('delete_archivos', array($archivo_id));
		return TRUE;
	}
	
	/**
	 * <b>Method:	createUpdateArray()</b>
	 * @method		Genera el arreglo con los datos a modificar en un archivo
	 * @param		integer $archivo_id Identificador del archivo
	 * @param		array $data Arreglo con los datos a modificar
	 * @return		boolean TRUE/FALSE
	 * @author		Eliel Parra
	 * @version		v1.0 04/11/11 01:53 PM
	 **/
	function createUpdateArray($archivo_id, $data) {
		
		if($this->session->userdata('update_archivos')){
			$arr_update_archivos = $this->session->userdata('update_archivos');	
			$arr_update_archivos[$archivo_id] = $data;
			$this->session->set_userdata('update_archivos', $arr_update_archivos);
		}else{
			$this->session->set_userdata('update_archivos', array($archivo_id => $data));
		}
		return TRUE;
	}
	
	/**
	 * <b>Method:	getCountFilesByCampoId()</b>
	 * @method		Retorna la cantidad de imagenes asociadas a un campo y a una instancia
	 * @param		integer $campo_id Identificador del campo
	 * @param		integer $instancia_id Identificador de la instancia
	 * @param		char $eliminado Selector para campos eliminado ('1') o no ('0')
	 * @return		array $result Arreglo con los archivos asociados al campo, FALSE en caso de no tener
	 * @author		Eliel Parra
	 * @version		v1.0 04/11/11 04:02 PM
	 **/
	function getCountFilesByCampoId($campo_id, $instancia_id, $eliminado = '0') {
		
		$query = "	SELECT	COUNT (arch.*) AS cantidad
					FROM estatico.archivos arch
					JOIN estatico.relaciones_archivos rel
					ON arch.id = rel.archivo_id
					WHERE rel.campo_id = $campo_id
					AND rel.instancia_id = $instancia_id
					AND rel.eliminado = '$eliminado'
					AND arch.eliminado = '$eliminado'";
		
		$result = $this->db->query($query);
		if($result->num_rows() > 0){
			$row = $result->row();
			return $row->cantidad;
		}
			
	}

	/**
	 * <b>Method:	getFolderPathByFieldId($id)</b>
	 * @method		Permite obtener el directorio raiz donde debe guardarse un archivo especifico.
	 *                  Este directorio raiz por lo general esta relacionado a lo que se define como modulo 
	 * 			del sistema (Ej. Para el caso Sietpol los directorios raices son cuerpos_policiales, 
	 *                  funcionarios, entre otros). Es importante destacar que el directorio raiz cuerpos_policiales
	 *                  es un directorio que pertenece al file system de archivos, no confundir con el directorio
	 *                  cuerpos_policiales que se encuentra en el directorio modules de la aplicacion. 
	 * @param		Integer $id identificador del campo en la tabla campos al cual esta asociado el archivo
	 * @return		String nombre del directorio raiz
	 * @author		Eliel Parra, Reynaldo Rojas
	 * @version		v1.0 02/11/11 09:03 AM
	 * */
	function getFolderPathByFieldId($id) {

		$array_where = array(	'en.eliminado' => '0',
								'ca.eliminado' => '0',
								'ca.id' => $id);
		$this->db->select('en.chk_directorio_archivo');
		$this->db->from('dinamico.entidades en');
		$this->db->join('dinamico.campos ca', 'en.id = ca.entidad_id');
		$this->db->where($array_where);
		$query = $this->db->get();
		$result = $query->row();
		return $result->chk_directorio_archivo;
	}
	
	/**
	 * <b>Method:	getTipoCampo($id)</b>
	 * @method		Retorna el tipo de campo si es fileupload o fileuploadplus. 
	 * @param		Integer $id identificador del campo en la tabla campos al cual esta asociado el archivo
	 * @return		String nombre del directorio raiz
	 * @author		Eliel Parra, Reynaldo Rojas
	 * @version		v1.0 03/11/11 02:46 PM
	 * */
	function getTipoCampo($id) {
		
		$this->db->where('eliminado', '0');
		$this->db->where('id', $id);
		$this->db->select('tipo_campo');
		$query = $this->db->get('dinamico.campos');
		$result = $query->row();
		return $result->tipo_campo;
	}

	/**
	 * <b>Method:	listAll()</b>
	 * @method		Mustra todas las imagenes asociadas al campo y a la instancia seleccionada
	 * @param		integer $limit Valor del limit para el query
	 * @param		integer $start Offset para el query
	 * @param		integer $campo_id Identificador del campo
	 * @param		integer $instancia_id Identificador de la instancia
	 * @return		array $salida Arreglo con los datos asociados a las imagenes seleccionadas
	 * @author		Juan Carlos Lopez, Eliel Parra
	 * @version		v2.0 02/11/11 04:09 PM
	 * */
	function listAll($limit, $start, $campo_id, $instancia_id) {
		
		$queryTotal = "	SELECT	arch.*
						FROM estatico.archivos arch
						JOIN estatico.relaciones_archivos rel
						ON arch.id = rel.archivo_id
						WHERE rel.campo_id = $campo_id
						AND rel.instancia_id = $instancia_id
						AND rel.eliminado = '0'
						AND arch.eliminado = '0'";
		
		$resultTotal = $this->db->query($queryTotal);
//		$numRows = $resultTotal->num_rows();
		$numRows = $this->getCountFilesByCampoId($campo_id, $instancia_id);
		
		$query = "	SELECT	arch.*
					FROM estatico.archivos arch
					JOIN estatico.relaciones_archivos rel
					ON arch.id = rel.archivo_id
					WHERE rel.campo_id = $campo_id
					AND rel.instancia_id = $instancia_id
					AND rel.eliminado = '0'
					AND arch.eliminado = '0'
					LIMIT $limit
					OFFSET $start";

		$result = $this->db->query($query);
		if ($result->num_rows() > 0) {
			$lasImagenes = array();
			foreach ($result->result() as $row) {
				switch ($row->extension) {
					case '.jpg':
					case '.png':
					case '.jpeg':
					case '.gif':
						$arch = array(
							'name' => $row->titulo,
							'thumb_url' => base_url() . $row->ruta . 'peq_' . $row->nombre . $row->extension,
							'id' => $row->id
						);
						break;
					case '.doc':
					case '.odt':
					case '.rtf':
						$arch = array(
							'name' => $row->titulo,
							'thumb_url' => base_url() . 'assets/img/icon_file/MS-Office-2003-Word-icon.png',
							'id' => $row->id
						);
						break;
					case '.ods':
					case '.xls':
						$arch = array(
							'name' => $row->titulo,
							'thumb_url' => base_url() . 'assets/img/icon_file/MS-Office-2003-Excel-icon.png',
							'id' => $row->id
						);
						break;
					case '.pdf':
						$arch = array(
							'name' => $row->titulo,
							'thumb_url' => base_url() . 'assets/img/icon_file/PDF-icon.png',
							'id' => $row->id
						);
						break;
					case '.txt':
						$arch = array(
							'name' => $row->titulo,
							'thumb_url' => base_url() . 'assets/img/icon_file/Document-Copy-icon.png',
							'id' => $row->id
						);
						break;
					case '.pps':
					case '.ppt':
						$arch = array(
							'name' => $row->titulo,
							'thumb_url' => base_url() . 'assets/img/icon_file/MS-Office-2003-PowerPoint-icon.png',
							'id' => $row->id,
						);
						break;
				}
				$lasImagenes[] = $arch;
			}
			$salida = array('result' => $numRows, 'images' => $lasImagenes);
		} else {
			$salida = 'No hay archivos que mostrar';
		}
		return $salida;
	}
	
	/*
	 * <b>Method:   updateFileDetail()</b>
	 * @method		Guarda los cambios que se han hecho a los datos del archivo
	 * @param		$id, $data
	 * @return		return
	 * @author		Juan Carlos Lopez
	 * @version		v1.0 02/11/11 11:36 AM
	 * */

	function updateFilesDetails() {
		$this->db->trans_start();
		$arr_update_archivos = $this->session->userdata('update_archivos');
		foreach ($arr_update_archivos AS $key => $element){
			//Actualizar los registros en archivos
			$this->db->where('id', $key);
			$this->db->update($this->table, $element);
		}
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	/**
	 * <b>Method:	deleteFile()</b>
	 * @method		Elimina los archivos seleccionados
	 * @param		$param
	 * @return		boolean TRUE/FALSE
	 * @author		Eliel Parra
	 * @version		v1.0 04/11/11 01:09 PM
	 **/
	function deleteFiles() {
		
		$this->db->trans_start();
		$data = array('eliminado' => '1');
		$arr_delete_archivos = $this->session->userdata('delete_archivos');
		foreach ($arr_delete_archivos AS $element){
			//Eliminar los registros en relaciones archivos
			$this->db->where('archivo_id', $element);
			$this->db->update('estatico.relaciones_archivos', $data);
			//Eliminar los registros en archivos
			$this->db->where('id', $element);
			$this->db->update($this->table, $data);
		}
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	
	/**
	 * <b>Method:   generateRoute($rootDyrectory,$module,$register_id)</b>
	 * @method      Se encarga de generar la ruta donde debe ser almacenado un archivo. A su vez, verifica
	 *              que la estructura de directorios donde debe ser guardado el archivo existe, en caso contrario, se crea
	 *              la estructura de directorios
	 * @param       String $rootDyrectory directorio principal donde se guardan los archivos relacionados a la aplicacion
	 * @param       String $module modulo al que pertenece el campo tipo file
	 * @param       String $register_id identificador del registro del sistema al cual esta asociado el/los archivo(s)
	 * @return      String $path ruta donde debe ser guardado el archivo
	 * @author      Eliel Parra, Reynaldo Rojas 
	 * @version		v1.1 01/11/11 08:00 PM
	 * */
	function generatePath($rootDyrectory, $module, $register_id) {

		if ($register_id < 100) {
			if ($register_id > 10)
				$folder = '00' . $register_id;
			else
				$folder = '0' . $register_id;
		}else
			$folder = $register_id;

		$folder = substr($folder, -2);

		$subFolder = $register_id;

		$path = $rootDyrectory . '/' . $module . '/' . $folder . '/' . $subFolder . '/';

		return $path;
	}

	/*
	 * <b>Method:	fileDetail()</b>
	 * @method		Seleccionamos los datos de un archivo desde su id
	 * @param		$id
	 * @return		return
	 * @author		Juan Carlos Lopez
	 * @version		v1.0 01/11/11 02:06 PM
	 * */

	function fileDetail($id) {
		$this->db->from($this->table);
		$this->db->where('id', $id);
		$this->db->where('eliminado', '0');
		$query = $this->db->get();
		return $query->row_array();
	}

	/*
	 * <b>Method:	unsetSessionFile()</b>
	 * @method		Metodo para reiniciar los valores de las variables de sesion que manejan el proceso de archivo
	 * @author		Eliel Parra / Reynaldo Rojas
	 * @version		v1.0 03/11/11 10:10 AM
	 * */

	function unsetSessionFile() {
		$this->session->unset_userdata('archivos');
		$this->session->unset_userdata('relaciones_archivos');
		$this->session->unset_userdata('delete_archivos');
		$this->session->unset_userdata('update_archivos');
	}
	
	/**
	 * <b>Method:	saveFile()</b>
	 * @method		Permite guardar los archivos asociados a un formulario especifico
	 * @param		string $op_id identificador de la operacion
	 * @author		Eliel Parra, Reynaldo Rojas
	 * @version		v1.0 02/11/11 06:28 PM
	 * */
	function saveFile($op_id) {
		if(empty($op_id))
			return FALSE;
		// Definicion de variables auxiliares para el manejo de archivos
		$array_archivos = $this->session->userdata('archivos');
		$array_relaciones = $this->session->userdata('relaciones_archivos');
		$instancia_id = $array_relaciones['instancia_id'];

		// Verificar si se encuentra algun archivo que se deba agregar en el sistema
		if (!empty($array_archivos) && !empty($array_relaciones)) {
			foreach ($array_archivos[$op_id][$instancia_id] AS $key => $element) {

				// Agregar el archivo en la tabla archivos
				$file_id = $this->create($element);
				$array_relaciones[$op_id][$instancia_id][$key]['archivo_id'] = $file_id;

				// Agregar la relacion en la tabla relaciones_archivos
				$this->createRelacionesArchivos($array_relaciones[$op_id][$instancia_id][$key]);
			}
		}
		//Eliminar los archivos en el arreglo de eliminacion
		if($this->session->userdata('delete_archivos')){
			if(!$this->deleteFiles())
				return FALSE;
		}
		//Actualizar los archivos en el arreglo de actualizacion
		if($this->session->userdata('update_archivos')){
			if(!$this->updateFilesDetails())
				return FALSE;
		}
		return TRUE;
	}
}

?>