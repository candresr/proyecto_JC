<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller
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
        $this->load->library('table');
        // $this->load->helper('my_pdf');
        // $this->load->library('jpgraph');
        // $this->load->helper('pdf_merge');
        $this->entity = get_Class($this);
        $this->entityModel = get_Class($this).'_model';
        $this->load->model($this->entityModel, 'model_class');
    }


    /**
     * Función que detecta si se ha finalizado sesión para redireccionar a login
     * @param string $method Método
     * @param array $params Paŕámetros
     * @return mixed
     */
    public function _remap($method, $params = array())
    {
        // excluye el método encargado de login
        if ($method != 'verifica_login') {
            //verifica si se está logueado
            if (! $this->isLoged()) {
                //verifica si es una petición AJAX
                if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    show_error('Inicie Sesión', 300);
                } else {
                    $data["title"] = $this->session->userdata('conf_titulo');
                    $data["advertencia"] = "Debe autenticarse para ingresar al sistema";
                    $this->load->view('login.js.php', $data);
                }
            }
        }
        if (method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $params);
        }
        show_404();        
    }
    
    /**
     * verifica si se está logueado
     * @return bool
     */
    public function isLoged() {
        return ($this->session->userdata("logged_in") == '1');
    }

    /**
     * <b>Method: index</b>
     * @method	Metodo al que se invoca por defecto al entrar en la admnistracion del sistema
     * @param	$param
     * @return	return
     * @author	Juan Carlos Lopez Guillot
     * */
    function index() {
            $site_config                = $this->model_class->dataConfig();
            $data["conf_titulo"]        = $this->session->userdata('conf_titulo');
            $data["conf_paginado"]      = $this->session->userdata('conf_paginado');
            $data["conf_base"]          = $this->session->userdata('conf_base');
            $data["conf_telefono"]      = $this->session->userdata('conf_telefono');
            $data["conf_mail"]          = $this->session->userdata('conf_mail');
            $data["conf_palabra_clave"] = $this->session->userdata('conf_palabra_clave');
            $data["conf_descripcion"]   = $this->session->userdata('conf_descripcion');
            
        if ($this->session->userdata("logged_in") !== '1') {                                
            $data["advertencia"]    = "Debe autenticarse para ingresar al sistema";
            $this->load->view('login.js.php', $data);
        } else {
            $data["advertencia"]    = "";
//            $data["collapsed"]      = 'collapsed:false,'; 
//            $cs = $this->input->get('CS');
//            $telefono_gestion_pos = $this->input->get('phone');
//            if (!empty($cs)) {
//                $data['cs']         = $cs;
//                $data['collapsed']  = 'collapsed:true,';
//                $data['telefono_gestion_pos'] = $telefono_gestion_pos;
//                $data['estatus_pago']= $this->input->get('ESTATUS_PAGO');
//            }
//            print_r($data);
            $this->load->view('main_layout.js.php', $data);
        }
    }

    /**
     * <b>Method: verifica_login</b>
     * //@method	Metodo que verifica los datos de usuario para entrar a la administracion del sistema
     * @param	$param
     * @return	return
     * @author	Juan Carlos Lopez Guillot
     * */
    function verifica_login() {
        $username = $this->input->post('login');
        $password = $this->input->post('password');

        $this->form_validation->set_rules('login', 'Usuario', 'trim|required|alpha_dash|max_length[24]|xss_clean');
        $this->form_validation->set_rules('password', 'Contrase&ntilde;a', 'trim|required|alpha_dash|max_length[16]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            //Prepara accion de log
            $accion = "no pasa la validacion de campos";
            $this->admin_log($accion);
            //Finaliza accion de log
            $error_validacion = validation_errors();
            $error_validacion = preg_replace("[\n|\r|\n\r]", "", $error_validacion);
            echo "{
                                success:	true,
                                errors: 	{ reason: '$error_validacion' },
                                situacion: 	{ de_error:'no_valido'}	
                                }";
        } else {

            $verifica_login = $this->model_class->verifica_login($username, md5($password));
            if ($verifica_login == "2") {
                $session_id = $this->session->userdata('session_id');
                $data_user = $this->model_class->data_user($username, md5($password));
                $rols_user = $this->model_class->rols_user($data_user->id);
                //print_r($rols_user);
                //echo json_encode($rols_user);
                $newdata = array(
                    'permiso' => $data_user->permiso,
                    'rol' => $this->model_class->buscaRol($data_user->permiso),
                    'login' => $username,
                    'mail' => $data_user->mail,
                    'nombre' => $data_user->nombre,
                    'userId' => $data_user->id,
                    'tableRols' => $rols_user,
                    'logged_in' => '1'
                );
                $this->session->set_userdata($newdata);
                echo "{	
                        success:	true,
                        situacion: 	{ de_error:'directo'},
                        errors:		{ reason:'Usuario Autenticado Satisfactoriamente'},
                        }";
                $accion = "usuario valido hace session";
            } else {
                echo "{	
                        success:	true,
                        situacion: 	{ de_error:'no_valido'},
                        errors:         { reason:'Usuario o Contrasenia incorrecta'},
                        }";
                $accion = "error de autenticacion";
            }
            $this->admin_log($accion);
        }
    }
    
    

    /**
     * <b>Method: logout</b>
     * @method	Metodo para destruir la session y salirse del sistema
     * @param	$param
     * @return	return
     * @author	Juan Carlos Lopez Guillot
     * */
    public function logout() {
        $this->session->unset_userdata("user_id");
        $this->session->sess_destroy();
        $accion = 'Se ha cerrado la sessión';
        $this->admin_log($accion);
        echo "{success:true}";
    }

    /**
     * <b>Method: get_tablas</b>
     * //@method	Metodo que devuelve los datos necesarios para crear el menu del sistema de administracion
     * @param	$param
     * @return	return
     * @author	Juan Carlos Lopez Guillot
     * */
    public function get_tablas() {
        $menuItems = $this->model_class->get_tablas();
//            echo '<pre>'; 
//            print_r($menuItems); 
//            echo '</pre>';
//            die();
        echo '{ success:true, menuItems:' . json_encode($menuItems) . ', }';
    }



    /**
     * <b>Method: listAll</b>
     * //@method	Metodo que genera listados, paginado y busqueda en cualquier tabla del sistema
     * @param	$param
     * @return	return
     * @author	Juan Carlos Lopez Guillot
     * */
    public function listAll() {
        if ($this->input->get('tabla')) {
            $nom_tabla = $this->input->get('tabla');
            $this->session->set_userdata('nom_tabla', '');
            $this->session->set_userdata('nom_tabla', $nom_tabla);
        } else {
            $nom_tabla = $this->session->userdata('nom_tabla');
        }

        $filtro = FALSE;
        $searchField = $this->input->post('searchfield');
        if (!empty($searchField)) {
            //Lipiar el searchfield
            $searchField = preg_replace("/[^a-zA-Z0-9ñÑ_-]/", "", $searchField);
        }

        $start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
        $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : LXP;
        $estatus = isset($_REQUEST['estatus']) ? $_REQUEST['estatus'] : FALSE;
        $arrayWhere = array();
        $losCampos = $this->model_class->get_campos($nom_tabla, $arrayWhere);
        $laTabla = $this->model_class->get_latabla($nom_tabla);
        $data['rowset'] = $this->model_class->get_data($nom_tabla, $limit, $start, $searchField, $estatus, $filtro);
        $data['totalRows'] = $this->model_class->get_total_data($nom_tabla, $searchField, $estatus, $filtro);
        $data['success'] = true;

        if (isset($_POST['searchfield']))
            die(json_encode($data));
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
            die(json_encode($data));

        $storeFields = array();
        $columns = "[";
        $coma = "";
        $estatus = '0';
        foreach ($losCampos as $field) {
            if ($field->f_name == 'pass') {
                
            } else {
                $storeFields[]['name'] = $field->f_name;
                if ($field->f_name == 'estatus') {
                    $estatus = '1';
                }
                if ($field->f_campo != "oculto") {
                    if ($field->f_name == 'id') {
                        $width = '50';
                    } else {
                        $width = '130';
                    }
                    $colum = $coma . "{ header:'$field->f_titulo', dataIndex:'$field->f_name', sorteable:true, width:$width}";
                    $coma = ",";
                    $columns .=$colum;
                }
            }
        }
        $columns .= "]";
        $searchType = 'S';
        $tbarButton = TRUE;
        $bbarButton = TRUE;
        $scriptTags = FALSE;
        if ($nom_tabla == 'detalle_version') {
            $replace = "replaceCenterContent(groupingGrid_$nom_tabla);";
            $pagination = FALSE;
        } else {
            $replace = "replaceCenterContent(Grid_$nom_tabla);";
            $pagination = TRUE;
        }

        $viewData = array(
            "gridTitle" => 'Listado de ' . $laTabla->etiqueta,
            "fields" => json_encode($storeFields),
            "data" => json_encode($data),
            "columns" => $columns,
            "tbar" => $tbarButton,
            "bbar" => $bbarButton,
            "pagination" => $pagination,
            "searchType" => $searchType,
            "nom_tabla" => $nom_tabla,
            "scriptTags" => $scriptTags,
            "estatus" => $estatus,
            "replace" => $replace,
            "groupField" => 'id_version'
        );

        $accion = 'Listado del Módulo ' . $nom_tabla;
        $this->admin_log($accion);
        if ($nom_tabla == 'detalle_version') {
            $this->load->view("grouping_grid.js.php", $viewData);
        } else {
            $this->load->view("grid.js.php", $viewData);
        }
    }

    /**
     * <b>Method: get_all_tables</b>
     * @method	Metodo con el recogemos todas las tablas que se usan en el sistema con sus datos
     * @param	$param
     * @return	return
     * @author	Juan Carlos Lopez Guillot
     * */
    function get_all_tables() {
        $tablas = $this->model_class->get_lasTablas();
        echo '{ 
                        success:true,
                        tablas:' . json_encode($tablas) . ',
                }';
    }

    /**
     * <b>Method: eliminar</b>
     * //@method	Metodo que elimina cualquier registro del sistema de forma booliana
     * @param	$param
     * @return	return
     * @author	Juan Carlos Lopez Guillot
     * */
    function eliminar() {
        $id = $this->input->get('postdata');
        $nom_tabla = $this->session->userdata('nom_tabla');
        if ($this->model_class->eliminar($id, $nom_tabla)) {
            $dataSend = array(
                'success' => true,
                'msj' => 'Registro(s) eliminado(s) satisfactoriamente',
            );
            $accion = 'Se ha Eliminado el registro ' . $id . ' de la tabla ' . $nom_tabla;
            $this->admin_log($accion);
            echo json_encode($dataSend);
        } else {
            $dataSend = array(
                'success' => true,
                'msj' => 'Error al intentar eliminar registro(s)',
            );

            $accion = 'Error al intentar eliminar registro ' . $id . ' de la tabla ' . $nom_tabla;
            $this->admin_log($accion);
            echo json_encode($dataSend);
        }
    }

    /**
     * <b>Method: form</b>
     * //@method	Metodo que genera todos los formularios del sistema de administracion
     * @param	$params arreglo de datos para generar los items de un formulario
     * @return	return
     * @author	Juan Carlos Lopez Guillot
     * */
    function form() {

//        if ($params['returnFields'] == TRUE) {
//            $operacion = 'Editar';
//            $nom_tabla = $params['nom_tabla'];
//            $id = $params['idFile'];
//            $data = $this->model_class->get_data_byId($id, $nom_tabla);
//        } else {
            if ($this->input->get('tabla')) {
                $nom_tabla = $this->input->get('tabla');
                $this->session->set_userdata('nom_tabla', $nom_tabla);
            } else {
                $nom_tabla = $this->session->userdata('nom_tabla');
            }

            if ($this->input->get('id')) {
                $id = $this->input->get('id');
                $operacion = 'Editar';
                $this->session->set_userdata('operacion', $operacion);
                $data = $this->model_class->get_data_byId($id, $nom_tabla);
            } else {
                $id = FALSE;
                $operacion = 'Crear';
                $this->session->set_userdata('operacion', $operacion);
            }

            $posicion = $this->input->get('posicion');
            if ($operacion == 'Crear' OR $posicion == 'center') {
                $replace = "replaceCenterContent(form_$nom_tabla);";
            } else {
                $replace = 'window';
            }
//        }

        $permiso = $this->session->userdata('permiso');
        $tableRols = $this->session->userdata('tableRols');
        $permisos_t = $tableRols[$nom_tabla];
        $publicar = $permisos_t->publicar;
        $arrayWhere = array();
        $fieldsDb = $this->model_class->get_campos($nom_tabla, $arrayWhere);
        $laTabla = $this->model_class->get_latabla($nom_tabla);

        $fields = "[";
        $coma = $campo = "";
        foreach ($fieldsDb as $field) {

            $f_name = $field->f_name;
            if (!empty($data[$f_name])) {
                $elValue = "value: '$data[$f_name]',";
            } else {
                $elValue = "";
            }

            $validation = $field->f_validar;
            $validaArray = explode('|', $validation);
            if (in_array("required", $validaArray)) {
                $elAllowBlank = "allowBlank:false,";
                $elBlankText = "blankText:  'El campo $field->f_titulo es obligatorio',";
            } else {
                $elAllowBlank = "";
                $elBlankText = "";
            }

            $validVType = array('numeric', 'integer', 'alpha', 'alpha_numeric', 'alpha_dash', 'valid_email');
            $coinciden = array_intersect($validaArray, $validVType);
            $vType = array_shift($coinciden);

            if (!empty($vType)) {
                $elVtype = "vtype:'$vType'";
            } else {
                $elVtype = "";
            }

            if (!empty($field->f_titulo)) {
                //$elEmptyText = "emptyText:  '$field->f_titulo',"; //No esta funcionando apropiadamente
                $elEmptyText = "";
            } else {
                $elEmptyText = "";
            }

            if (!empty($field->f_ayuda)) {
                $elTooltip = "listeners: {
                                            render: function(c) {                                      
                                                    new Ext.ToolTip({
                                                        target: c.getEl(),
                                                        dismissDelay: 0,
                                                        showDelay: 0,
                                                        boxMinHeight: 400,
                                                        boxMinWidth: 400,
                                                        anchor: 'left',
                                                        trackMouse: false,
                                                        html: '$field->f_ayuda'
                                                    });
                                                },
                                              },";
            } else {
                $elTooltip = "";
            }

            $elMaxLenght = "";
            $elMaxLenghtText = "";
            $elMinLenght = "";
            $elMinLenghtText = "";

            foreach ($validaArray as $key => $value) {
                $toSearch = substr($value, 0, 10);
                if ($toSearch == 'max_length') {
                    $num = preg_replace('/\D/', '', $value);
                    $elMaxLenght = "maxLength:$num,";
                    $elMaxLenghtText = "maxLengthText:  'El campo $field->f_titulo no puede exceder los $num caracteres',";
                }
                if ($toSearch == 'min_length') {
                    $num2 = preg_replace('/\D/', '', $value);
                    $elMinLenght = "minLength:$num2,";
                    $elMinLenghtText = "minLengthText:  'El campo $field->f_titulo debe tener al menos $num2 caracteres',";
                }
            }

            $elcampo = $field->f_campo;

            if ($field->f_name == 'estatus' AND $publicar == 'false') {
                $elcampo = 'lectura';
                $elValue = "value: 'borrador',";
            }
            
            $win_width = FALSE;
            $scriptTags = FALSE;
            
            switch ($elcampo) {
                case 'htmleditor':
                    $campo = "{ 
						xtype:      'htmleditor', 
						id:         '$nom_tabla.$field->f_name',
						fieldLabel: '$field->f_titulo',
						name:       '$field->f_name',                                        
                                                labelAlign: 'top',
						width:      520,
						height:     200,
						$elValue
						disabled:   false,
						hidden:     false,
						readOnly:  false,                                  
                                                $elAllowBlank
                                                $elBlankText
                                                $elEmptyText
                                                $elTooltip
                                                $elVtype

					}";
                    $win_width = '760';
                    break;

                case 'checkbox':
                    if (!empty($data[$f_name])) {
                        $elCheck = "checked: true,";
                        $elInputValue = "value: $data[$f_name],";
                    } else {
                        $elCheck = "";
                        $elInputValue = "value: 1,";
                    }
                    $campo = "{ 
						xtype:      'checkbox', 
						id:         '$nom_tabla.$field->f_name',
						//boxLabel: '$field->f_titulo',
                                                fieldLabel: '$field->f_titulo',
						name:       '$field->f_name',
						$elCheck
                                                $elInputValue
						disabled:   false,
						hidden:     false,
						readOnly:  false,
                                                $elAllowBlank
                                                $elBlankText
                                                $elTooltip
                                                validateField:      true
					}";
                    break;

                case 'input':
                    $campo = "{ 
						xtype:'textfield', 
						id:'$nom_tabla.$field->f_name',
						fieldLabel:'$field->f_titulo',
						name:'$field->f_name',
						width: 300,
						$elValue
						disabled:   false,
						hidden:     false,
						readOnly:  false,                               
                                                $elAllowBlank
                                                $elBlankText
                                                $elEmptyText
                                                $elTooltip
                                                $elMaxLenght
                                                $elMaxLenghtText
                                                $elMinLenght
                                                $elMinLenghtText
                                                $elVtype
					}";
                    break;
                case 'imagen':

                    $campo = "{ layout: 'hbox',
                                                    fieldLabel:'$field->f_titulo',
                                                    flex: 1,
                                                    align: 'stretch',
                                                    pack: 'start',
                                                    items: [{
                                                            xtype:'textfield', 
                                                            id:'$nom_tabla.$field->f_name',
                                                            name:'$field->f_name',
                                                            width: 200,
                                                            margins: '0 5 0 0',
                                                            $elValue
                                                            disabled:   false,
                                                            hidden:     false,
                                                            readOnly:  true,
                                                            $elAllowBlank
                                                            $elBlankText
                                                            $elEmptyText
                                                            $elTooltip
                                                            $elVtype
                                                            },{                                        
                                                            xtype:      'button',
                                                            text:       'Subir archivo',
                                                            icon:       BASE_ICONS + 'photos.png',
                                                            handler:function(btn){
                                                                    btn.disable();
                                                                    Ext.Ajax.request({
                                                                            url: BASE_URL + 'admin/subirArchivo',
                                                                            method: 'GET',
                                                                            params: { nom_tabla:'$nom_tabla', campo:'$field->f_name' },
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
                                                                    btn.enable();
                                                                    }
                                                           }]
                                                }";
                    break;
                case 'passw':
                    if ($operacion == 'Editar') {
                        $elId = $data['id'];
                        $campo = "{ 
                                                xtype:      'button',
                                                fieldLabel: '$field->f_titulo',
                                                id:         '$nom_tabla.$field->f_name',
                                                text:       'Cambiar Contrase&ntilde;a',
                                                icon:           BASE_ICONS + 'user_edit.png',
                                                handler: 
                                                              function(btn){
//                                                                btn.disable();
                                                
                                                Ext.Ajax.request({
                                                    url: BASE_URL + 'admin/winPass',
                                                    method: 'GET',
                                                    params: { userId:$elId },
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
        //                                                                btn.enable();
                                                                        }                  
                                                }";
                        if ($permiso <= 1) {
                            $campo .= ",{ 
                                                    xtype:      'button',
                                                    fieldLabel: 'Permisos sobre tablas',
                                                    id:         '$nom_tabla.rols',
                                                    text:       'Permisos sobre tablas',
                                                    icon:           BASE_ICONS + 'page_white_edit.png',
                                                    handler: 
                                                                  function(btn){
    //                                                                btn.disable();

                                                    Ext.Ajax.request({
                                                        url: BASE_URL + 'admin/winRols',
                                                        method: 'GET',
                                                        params: { userId:$elId },
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
            //                                                                btn.enable();
                                                                            }                  
                                                    }";
                        }
                    } else {
                        $campo = "{ 
                                                    xtype:'textfield', 
                                                    id:'$nom_tabla.$field->f_name',
                                                    fieldLabel:'$field->f_titulo',
                                                    name:'$field->f_name',
                                                    width: 300,
                                                    $elValue
                                                    disabled:   false,
                                                    hidden:     false,
                                                    readOnly:  false,   
                                                    inputType: 'password',                            
                                                    $elAllowBlank
                                                    $elBlankText
                                                    $elTooltip
                                                    $elVtype
                                            },{ 
                                                    xtype:'textfield', 
                                                    id:'$nom_tabla.passconf',
                                                    fieldLabel:'Confirmar Contrase&ntilde;a',
                                                    name:'passconf',
                                                    width: 300,
                                                    $elValue
                                                    disabled:   false,
                                                    hidden:     false,
                                                    readOnly:  false,                               
                                                    allowBlank:false,
                                                    blankText:  'El campo Confirmar Contrase&ntilde;a es obligatorio',
                                                    inputType: 'password',
                                                    $elTooltip
                                                    $elVtype
                                            }";
                    }
                    break;
                case 'lectura':
                    $campo = "{ 
                                                    xtype:'textfield', 
                                                    id:'$nom_tabla.$field->f_name',
                                                    fieldLabel:'$field->f_titulo',
                                                    name:'$field->f_name',
                                                    width: 300,
                                                    $elValue
                                                    disabled:   false,
                                                    hidden:     false,
                                                    readOnly:  true,                               
                                                    $elAllowBlank
                                                    $elBlankText
                                                    $elEmptyText
                                                    $elTooltip
                                                    $elVtype
                                            }";
                    break;
                case 'textarea':
                    $campo = "{ 
						xtype:'textarea', 
						id:'$nom_tabla.$field->f_name',
						fieldLabel:'$field->f_titulo',
						name:'$field->f_name',
						width: 300,
						height: 100,
						autoScroll:true,                    
						$elValue
						disabled:   false,
						hidden:     false,
						readOnly:  false,
                                                $elAllowBlank
                                                $elBlankText
                                                $elEmptyText
                                                $elTooltip
                                                $elVtype
					}";
                    break;

                case 'oculto':
                    $campo = "{ 
						xtype:'hidden', 
						id:'$nom_tabla._$field->f_name',
						$elValue
						name:'$field->f_name'
					}";
                    break;

                case 'fecha':
                    $campo = "{ 
						xtype:'datefield', 
						fieldLabel:'$field->f_titulo',
						name:'$field->f_name',
						width: 100,
						$elValue
						disabled:   false,
						hidden:     false,
						readOnly:  false,	
                                                $elAllowBlank
                                                $elBlankText
                                                $elEmptyText
                                                $elTooltip
						format: 'Y/m/d',
                                                $elVtype
					}";
                    break;

                case 'combobox':
                    unset($store);
                    $store = $this->crearComboStore($field->f_clave);
                    $ladata = $store['ladata'];
                    $losfield = $store['losfields'];
                    if ($nom_tabla == 'sys_usuarios' && $permiso != 1) {
                        $elDisable = 'disabled: true,';
                    } else {
                        $elDisable = 'disabled: false,';
                    }
                    $campo = "{ 
                                                    xtype:          'combo', 
                                                    id:             'combo_$nom_tabla.$field->f_name',
                                                    name:           '$field->f_name',
                                                    hiddenName:     '$field->f_name',
                                                    fieldLabel:     '$field->f_titulo',
                                                    autoWidth:      true,
                                                    editable:       false,
                                                    emptyText:      'Haga su selección',
                                                    $elAllowBlank
                                                    $elBlankText
                                                    $elTooltip
                                                    $elValue
                                                    mode:           'local',
                                                    triggerAction:  'all',
                                                    displayField:   'categoria',
                                                    valueField:     'valor',
                                                    $elDisable
                                                    hidden:         false,";
                    if ($permiso > 2 && $nom_tabla == 'sys_usuarios') {
                        $campo .="readOnly:  true,";
                    } else {
                        $campo .="readOnly:  false,";
                    }
                    $campo .= "store:     new Ext.data.SimpleStore({
                                                                    fields: $losfield,
                                                                    data:   $ladata
                                                                })
                                                    }";

                    break;
                case 'combo_lect':
                    unset($store);
                    $store = $this->crearComboStore($field->f_clave);
                    $ladata = $store['ladata'];
                    $losfield = $store['losfields'];

                    $campo = "{ 
                                                    xtype:          'combo', 
                                                    id:             'combo_$nom_tabla.$field->f_name',
                                                    name:           '$field->f_name',
                                                    hiddenName:     '$field->f_name',
                                                    fieldLabel:     '$field->f_titulo',
                                                    autoWidth:      true,
                                                    editable:       false,
                                                    emptyText:      'Haga su selección',
                                                    $elAllowBlank
                                                    $elBlankText
                                                    $elTooltip
                                                    $elValue
                                                    mode:           'local',
                                                    triggerAction:  'all',
                                                    displayField:   'categoria',
                                                    valueField:     'valor',
                                                    disabled:       false,
													readOnly:		true,
                                                    hidden:         false,";
                    $campo .= "store:     new Ext.data.SimpleStore({
                                                                    fields: $losfield,
                                                                    data:   $ladata
                                                                })
                                                    }";

                    break;
                case 'combo_ani':
                    unset($store);
                    $store = $this->crearComboStore($field->f_clave);
                    $ladata = $store['ladata'];
                    $losfield = $store['losfields'];

                    $campo = "{ 
                                                        xtype:          'combo', 
                                                        id:             'combo_$nom_tabla.$field->f_name',
                                                        name:           '$field->f_name',
                                                        hiddenName:     '$field->f_name',
                                                        fieldLabel:     '$field->f_titulo',
                                                        autoWidth:      true,
                                                        editable:       false,
                                                        emptyText:      'Haga su selección',
                                                        $elAllowBlank
                                                        $elBlankText
                                                        $elTooltip
                                                        $elValue
                                                        mode:           'local',
                                                        triggerAction:  'all',
                                                        displayField:   'categoria',
                                                        valueField:     'valor',
                                                        disabled:       false,
                                                        hidden:         false,";
                    if ($permiso > 2 && $nom_tabla == 'sys_usuarios') {
                        $campo .="readOnly:  true,";
                    } else {
                        $campo .="readOnly:  false,";
                    }
                    $campo .= "store:     new Ext.data.SimpleStore({
                                                                        fields: $losfield,
                                                                        data:   $ladata
                                                                    })
                                                        }";

                    break;
            }
            if (empty($campo)) {
                
            } else {
                $fields .=$coma . $campo;
                $coma = ",";
            }
        }
        $fields.="]";
                
        $partes = explode('_', $nom_tabla);
        $canti = count($partes);
        if($partes[$canti-1] == 'view'){ 
            $buttons = 0; 
        } elseif($this->input->get('action') == 'ver'){ 
            $buttons = 0; 
        } else { 
            $buttons = 1;
        }
        $viewData = array(
            "formTitle" => $operacion . " datos en " . $laTabla->etiqueta,
            "rowId" => $id,
            "fields" => $fields,
            "nom_tabla" => $nom_tabla,
            "buttons" => $buttons,
            "replace" => $replace,
            "scriptTags" => $scriptTags,
            "operacion" => $operacion,
            "win_width" => $win_width,
        );

        $accion = "Se ha creado un formulario de $operacion para la tabla $nom_tabla";
        $this->admin_log($accion);

//        if ($params['returnFields'] == TRUE) {
//            return $fields;
//        } else {
            $this->load->view("form.js.php", $viewData);
//        }
    }

    /**
     * <b>Method: crearComboStore</b>
     * //@method	Metodo que genera los posibles combobox del sistema de administracion
     * @param	$param
     * @return	return
     * @author	Juan Carlos Lopez Guillot
     * */
    function crearComboStore($clave) {
        $laClave = explode('|', $clave);
        $tabla = $laClave[0];
        $campo = $laClave[1];        
        switch ($tabla) {
            case 'sys_categorias':
                $store['ladata'] = $this->model_class->categoriasStoreCombo($campo);
                $store['losfields'] = "['valor','categoria']";
                break;
            case 'numeros':
                $limites = explode('-', $campo);
                $ini = $limites[0];
                $fin = $limites[1];
                $store['ladata'] = '[';
                for ($i = $ini; $i <= $fin; $i++) {
                    $store['ladata'] .= "['$i','$i'],";
                }
                $store['ladata'] .= ']';
                $store['losfields'] = "['valor','categoria']";
                break;
            default :
                $store['ladata'] = $this->model_class->tablaStore($laClave);
                $store['losfields'] = "['valor','categoria']";
                break;
        }
        return $store;
    }

    /**
     * <b>Method: procesaForm</b>
     * //@method	Metodo que procesa en envio de cualquier formulario de edicion o creacion
     * @param	$param
     * @return	return
     * @author	Juan Carlos Lopez Guillot
     * */
    function procesaForm() {
        if ($this->input->get('tabla')) {
            $nom_tabla = $this->input->get('tabla');
            $this->session->set_userdata('nom_tabla', $nom_tabla);
        } else {
            $nom_tabla = $this->session->userdata('nom_tabla');
        }
        $operacion = $this->session->userdata('operacion');
        $arrayWhere = array();
        $fieldsDb = $this->model_class->get_campos($nom_tabla, $arrayWhere);

        $tableRols = $this->session->userdata('tableRols');
        $permisos_t = $tableRols[$nom_tabla];
        $publicar = $permisos_t->publicar;

        $data_ins = array();
//print_r($fieldsDb); die;
        foreach ($fieldsDb as $dat) {
            if ($operacion == 'Editar' && $dat->f_name == 'pass') {
                
            } else {
                switch ($dat->f_name) {
                    case 'pass':
                        $data_ins[$dat->f_name] = md5($this->input->post($dat->f_name));
                        break;
                    case 'fecha':
                    case 'fecha_ini':
                    case 'fecha_fin':
                        $fech_sep = explode('/', $this->input->post($dat->f_name));
                        $data_ins[$dat->f_name] = $fech_sep[0] . '-' . $fech_sep[1] . '-' . $fech_sep[2];
                        break;
                    case 'eliminado':
                        $data_ins[$dat->f_name] = 'no';
                        break;
                    case 'estatus':
                        if ($publicar == 'true') {
                            $data_ins[$dat->f_name] = $this->input->post($dat->f_name);
                        } else {
                            $data_ins[$dat->f_name] = 'borrador';
                        }
                        break;
                    default :
                        $val = $this->input->post($dat->f_name);
                        $f_campo = $dat->f_campo;
                        if ($f_campo == 'textarea' || $f_campo == 'htmleditor') {
                            $data_ins[$dat->f_name] = preg_replace("[\n|\r|\n\r]", " ", $val);
                        } else {
                            $data_ins[$dat->f_name] = $val;
                        }
                        break;
                }
                $this->form_validation->set_rules($dat->f_name, $dat->f_titulo, $dat->f_validar);
            }
        }
//print_r($data_ins);die;
        $output = array();
        if ($this->form_validation->run() == FALSE) {
            $error_validacion = validation_errors();
            $error_validacion = preg_replace("[\n|\r|\n\r]", " ", $error_validacion);
            $output['success'] = true;
            $output['resultado'] = 'error_validacion';
            $output['titulo'] = 'Error de Validacion';
            $output['msj'] = $error_validacion;
            $accion = "Error de validación para la $operacion en la tabla $nom_tabla";
        } else {
            $output['success'] = true;
            $output['titulo'] = 'Exito';
            if ($operacion == 'Editar') {
                if ($this->model_class->updateData($data_ins, $nom_tabla)) {
                    $output['msj'] = 'Los datos se editaron satisfactoriamente';
                    $output['resultado'] = $operacion;
                    $accion = "Datos se editados satisfactoriamente en la tabla $nom_tabla";
                } else {
                    $output['msj'] = 'Ha ocurrido un error editando sus datos, intentelo una vez mas';
                    $output['resultado'] = 'error_bd';
                    $accion = "Ha ocurrido un error editando sus datos en la tabla $nom_tabla";
                }
            } else {
                array_shift($data_ins);
                if ($this->model_class->createData($data_ins, $nom_tabla)) {
                    $output['msj'] = 'Los datos se insertaron satisfactoriamente';
                    $output['resultado'] = $operacion;
                    $accion = "Datos se insertaron satisfactoriamente en la tabla $nom_tabla";
                } else {
                    $output['msj'] = 'Ha ocurrido un error insertando sus datos, intentelo una vez mas';
                    $output['resultado'] = 'error_bd';
                    $accion = "Ha ocurrido un error insertando datos en la tabla $nom_tabla";
                }
            }
        }
        $this->admin_log($accion);
        echo json_encode($output);
    }

    /**
     * <b>Method: winPass</b>
     * //@method	Metodo que genera ventana para cambiar contrasenia
     * @param	$param
     * @return	return
     * @author	Juan Carlos Lopez Guillot
     * */
    function winPass() {
        $id = $this->input->get('userId');
        $this->session->set_userdata('userId', $id);
        $this->load->view('win_pass.js.php');
    }

    /**
     * <b>Method: eliminarArchivo</b>
     * //@method    Metodo para el borrado de archivos de forma binaria
     * @param   $param
     * @return  return
     * @author  Cesar Andres Ramirez
     * */
    function eliminarArchivo() {
        $idFile = $this->input->get('id');
        $id_gal = $this->input->get('id_gal');
        
        if ($this->model_class->eliminarArchivo($idFile,$id_gal)) {
            
            $salida = array(
                'success' => TRUE,
                'msg' => 'Archivo eliminado satisfactoriamente',
                'title' => 'Acci&oacute;n sobre Archivos'
            );
        } else {
            $salida = array(
                'success' => TRUE,
                'msg' => 'No se puedo eliminar el archivo, intentolo nuevamente',
                'title' => 'Acci&oacute;n sobre Archivos'
            );
        }
        echo json_encode($salida);
    }

    /**
     * <b>Method: fileEdit</b>
     * //@method    Muestra detalles de el archivo y permite su edicion
     * @param   $param
     * @return  return
     * @author  Cesar Andres Ramirez
     * */
    function fileEdit() {
        $idFile = $this->input->get('id');
        if ($this->input->post()) {
            $operacion = 'Editar';
            $arrayWhere = array();
            $fieldsDb = $this->model_class->get_campos('archivos', $arrayWhere);
            $data_ins = array();
            foreach ($fieldsDb as $dat) {
                $data_ins[$dat->f_name] = $this->input->post($dat->f_name);
                $this->form_validation->set_rules($dat->f_name, $dat->f_titulo, $dat->f_validar);
            }
            // print_r($data_ins);
            $output = array();
            if ($this->form_validation->run() == FALSE) {
                $error_validacion = validation_errors();
                $error_validacion = preg_replace("[\n|\r|\n\r]", " ", $error_validacion);
                $output['success'] = true;
                $output['resultado'] = 'error_validacion';
                $output['titulo'] = 'Error de Validacion';
                $output['msj'] = $error_validacion;
            } else {
                $output['success'] = true;
                $output['titulo'] = 'Exito';
                if ($this->model_class->updateData($data_ins, 'archivos')) {
                    $output['msj'] = 'Los datos se editaron satisfactoriamente';
                    $output['resultado'] = 'Editar';
                } else {
                    $output['msj'] = 'Ha ocurrido un error editando sus datos, intentelo una vez mas';
                    $output['resultado'] = 'error_bd';
                }
            }
            echo json_encode($output);
        } else {

            $data = $this->model_class->fileEdit($idFile);
            $titulo = $data['titulo'];
            $nombre = $data['archivo'];
            $extension = $data['ext'];
            $ruta = 'uploads/galerias/' . $data['id_gal'] . '/';
            $observaciones = $data['descripcion'];
            switch ($extension) {
                case '.jpg':
                case '.png':
                case '.jpeg':
                case '.gif':
                    $direccion = base_url() . $ruta . 'peq_' . $nombre;
                    $direccion_download = base_url() . $ruta . $nombre;
                    break;
                case '.doc':
                case '.odt':
                case '.rtf':
                    $direccion = base_url() . 'assets/img/icon_file/MS-Office-2003-Word-icon.png';
                    $direccion_download = base_url() . $ruta . $nombre;
                    break;
                case '.ods':
                case '.xls':
                    $direccion = base_url() . 'assets/img/icon_file/MS-Office-2003-Excel-icon.png';
                    $direccion_download = base_url() . $ruta . $nombre;
                    break;
                case '.pdf':
                    $direccion = base_url() . 'assets/img/icon_file/PDF-icon.png';
                    $direccion_download = base_url() . $ruta . $nombre;
                    break;
                case '.txt':
                    $direccion = base_url() . 'assets/img/icon_file/Document-Copy-icon.png';
                    $direccion_download = base_url() . $ruta . $nombre;
                    break;
                case '.pps':
                case '.ppt':
                    $direccion = base_url() . 'assets/img/icon_file/MS-Office-2003-PowerPoint-icon.png';
                    $direccion_download = base_url() . $ruta . $nombre;
                    break;
            }

            $datFile = "";
            $datFile .= '<div style="padding:5px; border:1px solid grey; margin-right:8px; background-color:#FFFFFF;"><div style=" margin-bottom:3px;"><a href="' . $direccion_download . '" target="_blank"><img width="100" height="100" src="' . $direccion . '" alt="' . $titulo . '" title="' . $titulo . '"></a></div><div align="center" class="bot_holder"><a href="' . $direccion_download . '" target="_blank" class="download_bot round_corner"><img src="' . base_url() . 'assets/img/icons/arrow_down.png" align="center" alt="Descargar"> Descargar</a></div></div>';

            $params = array(
                'returnFields' => TRUE,
                'nom_tabla' => 'archivos',
                'idFile' => $idFile
            );
            $formFile = $this->form($params);
            $dataFile = array(
                'idFile' => $idFile,
                'thumTitle' => 'Miniatura de Archivo',
                'thumFile' => $datFile,
                'formTitle' => 'Datos de Archivo',
                'formFile' => $formFile
            );
            $this->load->view('fileEdit.js.php', $dataFile);
        }
    }

    /**
     * <b>Method: cambiaPass</b>
     * //@method	Metodo que permite el cambio de contraseña
     * @param	$accion
     * @return	return
     * @author	Juan Carlos Lopez Guillot
     * */
    function cambiaPass() {
        $pass = $this->input->post('pass');
        $passconf = $this->input->post('passconf');
        $nom_tabla = $this->session->userdata('nom_tabla');
        $id = $this->session->userdata('userId');

        $data_ins = array('pass' => md5($pass));

        $this->form_validation->set_rules('pass', 'Contrase&ntilde;a', 'trim|required|min_length[6]|max_length[24]|matches[passconf]|xss_clean');
        $this->form_validation->set_rules('passconf', 'Confirmar Contrase&ntilde;a', 'trim|required|min_length[6]|max_length[24]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            //Prepara accion de log
            $accion = "no pasa la validacion de campos";
            $this->admin_log($accion);
            //Finaliza accion de log
            $error_validacion = validation_errors();
            $error_validacion = preg_replace("[\n|\r|\n\r]", "", $error_validacion);

            $output['success'] = true;
            $output['resultado'] = 'error_validacion';
            $output['titulo'] = 'Error de Validacion';
            $output['msj'] = $error_validacion;
            $accion = "Ha ocurrido un error de validación cambiando la constraseña del usuario de id $id";
        } else {

            $output['success'] = true;
            $output['titulo'] = 'Exito';
            if ($this->model_class->cambiaPass($data_ins, $nom_tabla, $id)) {
                $output['msj'] = 'Los datos se insertaron satisfactoriamente';
                $output['resultado'] = 'Editar';
                $accion = "La constraseña del usuario de id $id se ha cambiado satisfactoriamente";
            } else {
                $output['msj'] = 'Ha ocurrido un error insertando sus datos, intentelo una vez mas';
                $output['resultado'] = 'error_bd';
                $accion = "Ha ocurrido un error cambiando la constraseña del usuario de id $id";
            }
        }
        $this->admin_log($accion);
        echo json_encode($output);
    }

    /**
     * <b>Method: winRols</b>
     * //@method	Metodo que permite la edicion de los permisos de ejecucion sobre las tablas del sistema
     * @param	usar parametro de session
     * @return	return
     * @author	Juan Carlos Lopez Guillot
     * */
    function winRols() {
        $id = $this->input->get('userId');
        $this->session->set_userdata('userId', $id);
        $arrayWhere = array();
        $losCampos = $this->model_class->get_campos('sys_roles', $arrayWhere);
        $data = $this->model_class->get_rolData('sys_roles', $id);

        $storeFields = array();
        $columns = "[ ";
        $coma = "";

        foreach ($losCampos as $field) {
            if ($field->f_name == 'pass') {
                
            } else {
                if ($field->f_campo != "oculto") {
                    if ($field->f_campo == "checkbox") {
                        $storeFields[] = array('name' => $field->f_name, 'type' => 'bool');
                        $colum = $coma . "{
                                            xtype: 'booleancolumn',
                                            header:'$field->f_titulo', 
                                            dataIndex:'$field->f_name',
                                            align: 'center',
                                            width: 50,
                                            trueText: 'Si',
                                            falseText: 'No',
                                            editor: {
                                                xtype: 'checkbox'
                                                }
                                            }";
                    } else {
                        $storeFields[]['name'] = $field->f_name;
                        $colum = $coma . "{ 
                                            header:'$field->f_titulo',";
                        if ($field->f_name == 'id') {
                            $colum .= "width: 40,";
                        } else {
                            $colum .= "width: 120,";
                        }
                        $colum .= "dataIndex:'$field->f_name'
                                            }";
                    }
                    $coma = ",";
                    $columns .=$colum;
                }
            }
        }
        $columns .= "]";

        $viewData = array(
            "gridTitle" => 'Roles de usuario por tabla',
            "fields" => json_encode($storeFields),
            "data" => json_encode($data),
            "columns" => $columns
        );

        $this->load->view('win_gridrols.js.php', $viewData);
    }

    /**
     * <b>Method: updateRols</b>
     * //@method	Metodo que permite la actualización de los sys_roles sobre tablas
     * @param	usar parametro de session
     * @return	return
     * @author	Juan Carlos Lopez Guillot
     * */
    function updateRols() {
        $data = json_decode($this->input->post('registro'));
        $id = $data->id;
        $idUser = $data->id_user;
       
        if (empty($data->visible)) {
            $visible = 'false';
        } else {
            $visible = 'true';
        }
        if (empty($data->crear)) {
            $crear = 'false';
        } else {
            $crear = 'true';
        }
        if (empty($data->editar)) {
            $editar = 'false';
        } else {
            $editar = 'true';
        }
        if (empty($data->ver)) {
            $ver = 'false';
        } else {
            $ver = 'true';
        }
        if (empty($data->borrar)) {
            $borrar = 'false';
        } else {
            $borrar = 'true';
        }
        if (empty($data->publicar)) {
            $publicar = 'false';
        } else {
            $publicar = 'true';
        }
        $data_ins = array(
            'visible' => $visible,
            'crear' => $crear,
            'editar' => $editar,
            'ver' => $ver,
            'borrar' => $borrar,
            'publicar' => $publicar
        );
 
        $output['success'] = true;
        $output['titulo'] = 'Exito';
        if ($this->model_class->updateRol($data_ins, $id)) {
            if ($this->updateSession($idUser)) {
                $output['msj'] = 'Los datos de sessionse actualizaron satisfactoriamente';
            } else {
                $output['msj'] = 'Los datos se actualizaron satisfactoriamente';
            }
            $output['resultado'] = 'Editar';
            $accion = "Se han actualizando los roles del usuario de id $idUser satisfactoriamente";
        } else {
            $output['msj'] = 'Ha ocurrido un error actualizando los datos, intentelo una vez mas';
            $output['resultado'] = 'error_bd';
            $accion = "Ha ocurrido un error actualizando los roles del usuario de id $idUser";
        }
        $this->admin_log($accion);
        echo json_encode($output);
    }

    /**
     * <b>Method: updateSession</b>
     * //@method	Metodo que actualiza varibles puntuales de la session de usuario
     * @param	
     * @return	returna actualizacion de session y reload de pagina para hacer visibles los cambios de session
     * @author	Juan Carlos Lopez Guillot
     * */
    function updateSession($id) {
        $userId = $this->session->userdata('userId');
        if ($userId == $id) {
            $rols_user = $this->model_class->rols_user($userId);
            $data_user = $this->model_class->data_user_reload($userId);
            $newdata = array(
                'permiso' => $data_user->permiso,
                'rol' => $this->model_class->buscaRol($data_user->permiso),
                'login' => $data_user->login,
                'mail' => $data_user->mail,
                'nombre' => $data_user->nombre,
                'userId' => $userId,
                'tableRols' => $rols_user,
                'logged_in' => '1'
            );
            return $this->session->set_userdata($newdata);
        }
    }

    /**
     * <b>Method: admin_log</b>
     * @method	Metodo que guarda las acciones que ser realizan en los metodos
     * @param	$accion
     * @return	return
     * @author	Juan Carlos Lopez Guillot
     * */
    function admin_log($accion) {
        $trace_ins = array(
            'id' => "",
            'fecha' => date('Y-m-d H:i:s', time()),
            'usuario' => $this->session->userdata("login"),
            'accion' => $accion,
            'ip' => $this->input->ip_address(),
        );
        return $this->model_class->admin_log($trace_ins);
    }

    /**
     * <b>Method: clonarUser</b>
     * //@method	Metodo que clona usuarios
     * @param	
     * @return	retorna mensaje de exito o fallo
     * @author	Juan Carlos Lopez Guillot
     * */
    function clonarUser() {
        $id = $this->input->get('postdata');
        $output['success'] = true;
        $output['titulo'] = 'Exito';
        if ($this->model_class->clonarUser($id)) {
            $output['msj'] = 'El usuario ha sido clonado satisfactoriamente';
            $output['resultado'] = '';
            $accion = "Se ha clonado el usuario de id $id satisfactoriamente";
        } else {
            $output['msj'] = 'Ha ocurrido un error clonando el usuario, intentelo una vez mas';
            $output['resultado'] = 'error_bd';
            $accion = "Ha ocurrido un error clonando el usuario de id $id satisfactoriamente";
        }
        $this->admin_log($accion);
        echo json_encode($output);
    }


    /**
     * <b>Method: import_data</b>
     * @method	Metodo importa (upload) y procesa archivo csv de datos
     * @param	recibe formulario via POST del id del proyecto y el archivo csv a ser procesado
     * @return	retorna mensaje de exito o fallo
     * @author	Juan Carlos Lopez Guillot, Eliel Parra
     * */
    function import_data() {
        $nom_tabla = $this->input->post('nom_tabla');
        if ($nom_tabla == 'maho_pagos') {
            $processData = array(
                'operacion' => $nom_tabla,
                'combo_cliente' => $this->input->post('combo_cliente'),
                'f_ini' => $this->input->post('f_ini'),
                'path' => 'uploads/pagos/',
            );
        } else {
            $processData = array(
                'operacion' => $nom_tabla,
                'combo_cliente' => $this->input->post('combo_cliente'),
                'combo_producto' => $this->input->post('combo_producto'),
                'combo_subproducto' => $this->input->post('combo_subproducto'),
                'f_ini' => $this->input->post('f_ini'),
                'f_fin' => $this->input->post('f_fin'),
                'path' => 'uploads/asignacion/',
            );
        }

        if (!file_exists($path)) {
            mkdir($path, 0777, TRUE);
        }

        $config['upload_path'] = $processData['path'];
        $config['allowed_types'] = 'csv|txt|xls';
        $config['max_size'] = 1024 * 2;
        $config['remove_spaces'] = TRUE;
        $config['overwrite'] = TRUE;

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload()) {
            $result = TRUE;
            $error = $this->upload->display_errors();
            $msg = $this->lang->line('error_file_upload');
            $arrayResponse = array(
                "title" => 'Carga de datos',
                "result" => $result,
                "msg" => $error
            );
            echo json_encode(array('success' => true, 'response' => $arrayResponse));
        } else {
            $upload_data = $this->upload->data();
            $processData['file_name'] = $upload_data['file_name'];
            if ($nom_tabla == 'maho_pagos') {
                $test = $this->model_class->procesarDataPagos($processData);
            } else {
                $test = $this->model_class->procesarDataAsignacion($processData);
            }
            $arrayResponse = array(
                "valid" => $test['valid'],
                "title" => 'Carga de datos',
                "msg" => $test['msg'],
            );
            echo json_encode(array('success' => true, 'response' => $arrayResponse));
        }
    }

    /**
     * <b>Method: forcedownload</b>
     * @method  Metodo para las cabeceras del archivo a descargar
     * @param   $param
     * @return  return
     * @author  Cesar Andres Ramirez
     * */  
    public function forcedownload() {
        $uri = $this->input->get('uri');
        $info = explode('/', $uri);
        $path = getcwd();
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$info[1]");
        header("Content-Type: audio/x-wav");
        header("Content-Type: text/csv");
        header("Content-Transfer-Encoding: binary");
        header('Content-Length: ' . filesize($path . $uri));
        readfile($path . $uri);
        exit;
    }
   /**
     * <b>Method: subirArchivo</b>
     * @method  Metodo para invocar el popoup de subida de archivos 
     * @param   $param
     * @return  return
     * @author  Cesar Andres Ramirez
     * */  
    function subirArchivo()
    {
        $nom_tabla  = $this->input->get('nom_tabla');
        $campo      = $this->input->get('campo');
        $this->session->set_userdata('nom_tabla', $nom_tabla);
        $dataUpload = array('nom_tabla' => $nom_tabla, 'campo' => $campo);        
        $this->load->view('subirArchivo.js.php', $dataUpload);               
    }
    
    /**
     * <b>Method: subeArch</b>
     * @method  Metodo para la subida de archivos al servidor de tipo imagen
     * redimencionando la medida dependiendo de la seccion del menu
     * @param   $param
     * @return  return
     * @author  Cesar Andres Ramirez
     * */ 
    function subeArch()
    {
        
        $folder = $this->session->userdata('nom_tabla');
        $nombre_archivo =  $this->input->post('filename');
        
        $path = getcwd()."/uploads/".$folder."/";
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'jpg|jpeg|png|pdf|ppt|doc|docx|zip';
        $config['max_size'] = 1024 * 5;
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
            
            $arrayResponse=array(
			"title"=>'Error en la validaci&oacute;n',
			"result"=>$result,
			"msg"=>$msg,
		);
            echo json_encode(array('success'=>true, 'response'=>$arrayResponse));
        } else {

            $upload_data = $this->upload->data();
            $file_name = $upload_data['file_name'];
            $file_type = $upload_data['file_type'];
            $file_ext = $upload_data['file_ext'];
            $file_size = $upload_data['file_size'];
            $is_image = $upload_data['is_image'];
            $image_width = $upload_data['image_width'];
            $image_height = $upload_data['image_height'];
            
            switch ($folder) {
                case 'web_contenido':
                    $width_med = "230";
                    $hight_med = "165";
                    break;
                case 'web_programacion':
                    $width_med = "279";
                    $hight_med = "279";
                    break;
                case 'web_descargas':
                    $width_med = "360";
                    $hight_med = "285";
                    break;
                case 'web_noticias';
                    $width_med = "292";
                    $hight_med = "165";
                    break;
            }
            
//            if ($image_width > $image_height) {
//                $proporcion = $image_width / $image_height;
//                $height = "281";
//                $width = floor($height * $proporcion);
//            } else {            
//                $proporcion = $image_height / $image_width;                
//                $height = "281";
//                $width = floor($height / $proporcion);
//            }
//
//            if ($image_width > $image_height) {
//                $proporcion = $image_width / $image_height;
//                $width_med = "800";
//                $hight_med = floor($width_med / $proporcion);
//            } else {
//                $proporcion = $image_height / $image_width;
//                $hight_med = "800";
//                $width_med = floor($hight_med / $proporcion);
//            }

            $config1['image_library'] = 'GD2';
            $config1['source_image'] = $path . $file_name;
            $config1['new_image'] = $path . 'med_' . $file_name;
            $config1['create_thumb'] = FALSE;
            $config1['maintain_ratio'] = FALSE;
            $config1['width'] = $width_med;
            $config1['height'] = $hight_med;
            $config1['quality'] = "90%";
            $this->load->library('image_lib', $config1);
            $this->image_lib->resize();
            $this->image_lib->clear();

//            $config['image_library'] = 'GD2';
//            $config['source_image'] = $path . $file_name;
//            $config['new_image'] = $path . 'peq_' . $file_name;
//            $config['create_thumb'] = FALSE;
//            $config['maintain_ratio'] = FALSE;
//            $config['width'] = $width;
//            $config['height'] = $height;
//            $config['quality'] = "90%";
//            $this->image_lib->initialize($config);
//            $this->image_lib->resize();
//            $this->image_lib->clear();
//            
//            $config2['image_library'] = 'GD2';
//            $config2['source_image'] = $path . 'peq_' . $file_name;
//            $config2['new_image'] = $path . 'crop_' . $file_name;
//            $config2['x_axis'] = '0';
//            $config2['y_axis'] = '0';
//            $config2['maintain_ratio'] = FALSE;
//            $config2['width'] = '170';
//            $config2['height'] = '281';
//            $this->load->library('image_lib', $config2);            
//            $this->image_lib->initialize($config2);
//            $this->image_lib->crop();
//            $this->image_lib->clear();
            
            $result = TRUE;
            $msg = 'El archivo se ha subido al servidor satisfactoriamente';
            $arrayResponse=array(
			"title"=>'Satisfactorio',
			"result"=>$result,
			"msg"=>$msg,
                        "file_name"=>$file_name
		);
            echo json_encode(array('success'=>true, 'response'=>$arrayResponse));
        }
    }
    /**
     * <b>Method: winUpload</b>
     * @method	Metodo que genera ventana para subir archivos
     * @param	$param
     * @return	return
     * @author	Juan Carlos Lopez Guillot
     * */    
    function winUpload()
    {
        $galId = $this->input->get('galId');
        $nom_tabla = $this->input->get('nom_tabla');
        $this->session->set_userdata('galId', $galId);
        $this->session->set_userdata('nom_tabla', $nom_tabla);
        $dataUpload = array(
                'title' => 'Manejador de Archivos',
                'name' => 'wUpload',
                'galId' => $galId,
                'nom_tabla' => $nom_tabla
        );        
        $this->load->view('upload.js.php', $dataUpload);        
    }
    
    /**
     * <b>Method: winGrid</b>
     * @method  Metodo que genera ventana para cargar los capitulos pertenecientes
     * a un programa de TV en especifico
     * @param   $param
     * @return  return
     * @author  Cesar Andres Ramirez
     * */   
    function winGrid() {
        
        $progId = $this->input->get('progId');  
        $data = $this->model_class->listCapitulos($progId);
        $this->session->set_userdata('progId', $progId);
  
        $dataProgramacion = array(
                'title' => 'Manejador de Programas',
                'name' => 'wGrid',
                'progId' => $progId,
                'data' => json_encode($data)
        );        
        $this->load->view('programacion.js.php', $dataProgramacion);
    }

    /**
     * <b>Method: updateGrid</b>
     * @method  Metodo para actualizar la informacion enviada por la funcion winGrid
     * @param   $param
     * @return  return
     * @author  Cesar Andres Ramirez
     * */ 
    function updateGrid() {
        $data = json_decode($this->input->post('registro'));
        $id = $data->id;
        $data_ins = array(
            'id' => $id,
            'id_prog' => $data->id_prog,
            'titulo' => $data->titulo,
            'enlace' => $data->enlace
        );
        $output['success'] = true;
        $output['titulo'] = 'Exito';
        if ($this->model_class->updateGrid($data_ins,$id)) {
            
            $output['msj'] = 'Los datos se actualizaron satisfactoriamente';
            $output['resultado'] = 'Editar';
            
        } else {
            $output['msj'] = 'Ha ocurrido un error actualizando los datos, intentelo una vez mas';
            $output['resultado'] = 'error_bd';
           
        }
       
        echo json_encode($output);
        
    }
    /**
     * <b>Method: do_upload</b>
     * @method	Metodo que sube los archivos al servidor
     * @param	$accion
     * @return	return
     * @author	Juan Carlos Lopez Guillot
     * */ 
    function do_upload()
    {
        $galId = $this->input->post('galId');
        $title_file = $this->input->post('title_file');
        $this->makeFolder($galId);
        //echo $galId.$title_file;
        $path = 'uploads/web_galerias/'.$galId.'/';
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'jpg|jpeg|png|gif|doc|txt|xls|ods|pps|ppt|odt|odp|pdf|rtf|mp3';
        $config['max_size'] = 1024 * 9;
        $config['file_name'] = $title_file;
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
            
            $arrayResponse=array(
			"title"=>'Error en la validaci&oacute;n',
			"result"=>$result,
			"msg"=>$msg,
		);
            echo json_encode(array('success'=>true, 'response'=>$arrayResponse));
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
            if($file_ext == '.mp3') $tipo = 'sonido'; else $tipo = 'imagen';
            $insertArchivos = array(
                    'id_gal' => $galId,
                    'archivo' => $file_name, 
                    'ext' => $file_ext,
                    'tipo' => $tipo,
                    'titulo' => $title_file,
                    'descripcion' => 'Descripcion de Archivo',
                    'eliminado' => 'no'
            );
            $this->model_class->insertFile($insertArchivos);
            
            $result = TRUE;
            $msg = 'El archivo se ha subido al servidor satisfactoriamente';
            $arrayResponse=array(
			"title"=>'Satisfactorio',
			"result"=>$result,
			"msg"=>$msg,
		);
            echo json_encode(array('success'=>true, 'response'=>$arrayResponse));
        }
    }
    
    function create_img($path, $image_width, $image_height, $file_name)
    {        
        /*if ($image_width > $image_height) {
            $proporcion = $image_width / $image_height;
            $width = "85";
            floor($width / $proporcion);
        } else {*/            
            $proporcion = $image_height / $image_width;
            $hight = "50";
            $width = floor($hight / $proporcion);
        //}

        if ($image_width > $image_height) {
            $proporcion = $image_width / $image_height;
            $width_med = "800";
            $hight_med = floor($width_med / $proporcion);
        } else {
            $proporcion = $image_height / $image_width;
            $hight_med = "800";
            $width_med = floor($hight_med / $proporcion);
        }

        $config1['image_library'] = 'GD2';
        $config1['source_image'] = $path . $file_name;
        $config1['new_image'] = $path . 'med_' . $file_name;
        $config1['create_thumb'] = FALSE;
        $config1['maintain_ratio'] = FALSE;
        $config1['width'] = $width_med;
        $config1['height'] = $hight_med;
        $config1['quality'] = "90%";
        $this->load->library('image_lib', $config1);
        $this->image_lib->resize();
        $this->image_lib->clear();

        $config['image_library'] = 'GD2';
        $config['source_image'] = $path . $file_name;
        $config['new_image'] = $path . 'peq_' . $file_name;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = FALSE;
        $config['width'] = $width;
        $config['height'] = $hight;
        $config['quality'] = "90%";
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        $this->image_lib->clear();
    }
     /**
     * <b>Method: listAllFile</b>
     * @method	Metodo que lista todos los archivos asociados a una galeria
     * @param	null
     * @return	return
     * @author	Juan Carlos Lopez Guillot
     * */ 
    function listAllFile()
    {
        $start = isset($_GET['start']) ? $_GET['start'] : 0;
        $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        $galId = $this->session->userdata('galId');
        $nom_tabla = $this->session->userdata('nom_tabla');

        //echo $start.' '.$limit.' '.$galId;
        
        $salida = $this->model_class->listAllFile($limit, $start, $galId,$nom_tabla );
        echo json_encode($salida);
    }

    /**
     * <b>Method: listCapitulos</b>
     * @method  Metodo que retorna la data para visualizar en la funcion winGrid
     * @param   null
     * @return  return
     * @author  Cesar Andres Ramirez
     * */ 
    function listCapitulos() {
         $progId = $this->session->userdata('progId');

        //echo $start.' '.$limit.' '.$galId;
        
        $salida = $this->model_class->listCapitulos($progId);
        $viewData = array(
            'data' => json_encode($salida)
        );
                
        $this->load->view('programacion.js.php', $viewData);
        
        
    }
    /**
     * <b>Method: makeFolder</b>
     * @method  Metodo para la creacion de directorios correspondientes a las nuevas galerias
     * @param   id galeria
     * @return  return
     * @author  Cesar Andres Ramirez
     * */ 
    function makeFolder($galId) {
        $dir_upload = "./uploads/web_galerias/$galId/";

        if (!file_exists($dir_upload)) {
                mkdir($dir_upload, 0777);
                chmod($dir_upload, 0777);
        }
    }
  
}
?>