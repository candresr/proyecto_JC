<?php

class Admin_model extends CI_Model {   

    function __construct() {
        parent::__construct();
        // $this->db = $this->load->database('condo', TRUE);
        // $this->db2 = $this->load->database('call_center', TRUE);
        $this->load->database();
        $this->load->dbutil();
    }
    
    
    function dataConfig(){
        $this->db->select('*');
        $query = $this->db->get('sys_siteconfig');
        foreach($query->row_array() as $key => $value){
            $newdata['conf_'.$key] = $value;
        }
        
        $this->session->set_userdata($newdata);
    }

    /**
     * <b>Method: verifica_login</b>
     * //@method	Primero consulta si el usuario existe y luego si el usuarioy la contraseña son validos, 
     * @param	$username, $password
     * @return	returna 0 = No existe el usuario, 1 = El usuario existe pero la contraeña no coincide, 2 = Usuario y contraseña validos
     * @author	Juan Carlos Lopez Guillot
     * */
    function verifica_login($username, $password) {
        $this->db->select('*');
        $this->db->from('sys_usuarios');
        //$this->db->where('eliminado !=', 'si');
        $this->db->where('login', $username);
        $result = $this->db->count_all_results();
        if ($result != 1) {
            $verifica_login = "0";
            return $verifica_login;
        } else {
            $this->db->select('*');
            $this->db->from('sys_usuarios');
            //$this->db->where('eliminado !=', 'si');
            $this->db->where('login', $username);
            $this->db->where('pass', $password);
            $result = $this->db->count_all_results();
            if ($result != 1) {
                $verifica_login = "1";
                return $verifica_login;
            } else {
                $verifica_login = "2";
                return $verifica_login;
            }
        }
    }

    /**
     * <b>Method: data_user</b>
     * //@method	Se consultan por los datos del usuario para crear su session
     * @param	$username, $password
     * @return	returna todos los datos de usuario
     * @author	Juan Carlos Lopez Guillot
     * */
    function data_user($username, $password) {
        $this->db->select('*');
        $this->db->from('sys_usuarios');
        //$this->db->where('eliminado !=', 'si');
        $this->db->where('login', $username);
        $this->db->where('pass', $password);
        $query = $this->db->get();
        $row = $query->row();
        return $row;
    }

    /**
     * <b>Method: data_user_reload</b>
     * //@method	Se consultan por los datos del usuario para re-crear su session 
     * @param	$idUser
     * @return	returna todos los datos de usuario
     * @author	Juan Carlos Lopez Guillot
     * */
    function data_user_reload($idUser) {
        $this->db->select('*');
        $this->db->from('sys_usuarios');
        //$this->db->where('eliminado !=', 'si');
        $this->db->where('id', $idUser);
        $query = $this->db->get();
        $row = $query->row();
        return $row;
    }

    /**
     * <b>Method: rols_user</b>
     * //@method	Se consultan por los roles del usuario para definir sus permisos y ponerlos en session
     * @param	$id_user
     * @return	returna todos los roles permitidos del usuario
     * @author	Juan Carlos Lopez Guillot
     * */
    function rols_user($id_user) {
        $this->db->select('nom_tabla,visible,ver,crear,editar,borrar,publicar');
        $this->db->from('sys_roles');
        $this->db->where('id_user', $id_user);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $salida[$row->nom_tabla] = $row;
        }
        return $salida;
    }

    /**
     * <b>Method: get_tablas</b>
     * @method	Se consultan todos los esquemas de tabla con el objeto de crear el menu principal de la aplicacion
     * @param	
     * @return	returna un arreglo de datos de todas las tablas o modulos del sistema
     * @author	Juan Carlos Lopez Guillot
     * */
    function get_tablas() {
        $this->db->from('sys_categorias');
        $this->db->where('clave', 'agrupador');
        $this->db->order_by('orden', 'asc');
        $salida = $this->db->get();
        $colu = array();
        $n = 0;
        foreach ($salida->result() as $obj) {
            $ntables = $this->get_nt_by_agrup($obj->valor);
            if ($ntables == TRUE) {
                $colu[$n]['valor'] = $obj->valor;
                $colu[$n]['categoria'] = $obj->categoria;
                $colu[$n]['id'] = $obj->id;
                $colu[$n]['lasTablas'] = $this->get_tables_by_agrupador($obj->valor);
                $n = $n + 1;
            }
        }
        return $colu;
    }

    function get_nt_by_agrup($agrupador) {
        $this->db->from('sys_esq_tablas');
        $this->db->where('agrupador', $agrupador);
        $query = $this->db->get();
        $total = $query->num_rows();
        $tableRols = $this->session->userdata('tableRols');
        $x = 0;
        $n = 0;
        if ($total > 0) {
            foreach ($query->result() as $obj) {
                $permisos_t = $tableRols[$obj->nom_tabla];
                if ($permisos_t->visible == 'true') {
                    $n = 1;
                } else {
                    $n = 0;
                }
                $x = $x + $n;
            }
            if ($x > 0) {
                $salida = TRUE;
            } else {
                $salida = FALSE;
            }
        } else {
            $salida = FALSE;
        }
        return $salida;
    }

    function get_tables_by_agrupador($agrupador) {

        $permiso = $this->session->userdata('permiso');

        $this->db->from('sys_esq_tablas');
        $this->db->where('agrupador', $agrupador);
        $this->db->order_by('orden', 'asc');
        $salida = $this->db->get();
        $colu = array();
        $n = 0;
        $tableRols = $this->session->userdata('tableRols');
        foreach ($salida->result() as $obj) {
            $permisos_t = $tableRols[$obj->nom_tabla];
            if ($permisos_t->visible == 'true') {
                $colu[$n]['id'] = $obj->id;
                $colu[$n]['etiqueta'] = $obj->etiqueta;
                $colu[$n]['icon'] = $obj->icon;
                $colu[$n]['nom_tabla'] = $obj->nom_tabla;
                $colu[$n]['agrupador'] = $obj->agrupador;
                $colu[$n]['modulo'] = $obj->modulo;
                $colu[$n]['crear'] = $permisos_t->crear;
                $n = $n + 1;
            }
        }
        return $colu;
    }

    /**
     * <b>Method: get_latabla</b>
     * @method	Se consulta un esquema de una tabla en particular con el objeto traernos todos sus datos  
     * @param	$nom_tabla
     * @return	returna la fila de datos asociada a esta tabla
     * @author	Juan Carlos Lopez Guillot
     * */
    function get_latabla($nom_tabla) {
        $this->db->from('sys_esq_tablas');
        $this->db->where('nom_tabla', $nom_tabla);
        $query = $this->db->get();
        $row = $query->row();
        return $row;
    }

    /**
     * <b>Method: get_campos</b>
     * //@method	Se consulta todos los detalles de los campos asociados a una tabla en particular
     * @param	$nom_tabla
     * @return	returna un arreglo de datos asociados a los campos de una tabla en particular 
     * @author	Juan Carlos Lopez Guillot
     * */
    function get_campos($nom_tabla, $arrayWhere) {
        $this->db->from('sys_esq_campos');
        $this->db->where('nom_tabla', $nom_tabla);
        if(count($arrayWhere)>0){
            $this->db->where($arrayWhere);
        }
        $this->db->order_by('f_orden', 'asc');
        $query = $this->db->get();

        $salida = array();
        foreach ($query->result() as $row) {
            $salida[] = $row;
        }
        return $salida;
    }

    /**
     * <b>Method: get_data</b>
     * //@method	Se consulta todas las filas de una tabla en particular permitiendo la paginacion y resultados de busqueda
     * @param	$nom_tabla, $limit, $start, $searchField
     * @return	returna todas las filas de una tabla filtradas por paginado y palabras a buscar
     * @author	Juan Carlos Lopez Guillot
     * */
    function get_data($nom_tabla, $limit, $start, $searchField, $estatus, $filtro, $csv = FALSE) {
        
        $this->db->where('nom_tabla', $nom_tabla);
        $this->db->order_by('f_orden', 'asc');
        $fcampos = $this->db->get('sys_esq_campos');

        $sel = 1;
        
        foreach ($fcampos->result() as $cam) {
            if ($cam->f_campo == 'combobox' OR $cam->f_campo == 'combo_lect') {
                
                $part = explode('|', $cam->f_clave);
                $tabl = $part[0];
                $clav = $part[1];
                if ($tabl == 'sys_categorias') {
                    $select = "cat$sel.categoria AS $cam->f_name";
                    $this->db->select($select, FALSE);                        
                    $table_alias = "$tabl AS cat$sel";
                    $cond = "$nom_tabla.$cam->f_name = cat$sel.valor";
                    $this->db->join($table_alias, $cond, '');
                } elseif ($tabl == 'numeros') {
                    $select = "$nom_tabla.$cam->f_name";
                    $this->db->select($select);
                } else {
                    $select = "cat$sel.$clav AS $cam->f_name";
                    $this->db->select($select, FALSE);
                    $table_alias = "$tabl AS cat$sel";
                    $cond = "$nom_tabla.$cam->f_name = cat$sel.id";
                    $this->db->join($table_alias, $cond, 'LEFT');
                }
            } else {
                $select = "$nom_tabla.$cam->f_name";
                $this->db->select($select);
            }
            $sel = ($sel + 1);
        }

        if (!empty($searchField) && $filtro == FALSE) {
            $query2 = $this->buscarSearchField($nom_tabla);
            $n = 0;
            $losOR = "";
            foreach ($query2->result() as $row) {
                if ($n == 0) {
                    $losOR .= " $row->f_name LIKE '%$searchField%'  ESCAPE '!' ";
                } else {
                    $losOR .= "OR $row->f_name LIKE '%$searchField%'  ESCAPE '!' ";
                }
                $n = $n + 1;
            }
            $this->db->where("($losOR)");
        } 
        
        $this->db->order_by($nom_tabla.'.id', 'desc');        
        $this->db->limit($limit, $start);
        $query = $this->db->get($nom_tabla);
//       die($this->db->last_query());
        return $query->result();
    }

    /**
     * <b>Method: buscarSearchField</b>
     * //@method	Se consultan por los campos de una tabla en particular que son suseptibles de hacer busquedas en ellos
     * @param	$nom_tabla
     * @return	returna un arreglo campos asociados a una tabla en particular que permiten buscar en ellos
     * @author	Juan Carlos Lopez Guillot
     * */
    function buscarSearchField($nom_tabla) {
        $query = "SELECT f_name FROM sys_esq_campos WHERE nom_tabla = '$nom_tabla' AND f_buscar = 'si'";
        $result = $this->db->query($query);
        return $result;
    }

    /**
     * <b>Method: get_total_data</b>
     * //@method	Se consultan todas las filas de una tabla en particular permitiendo la paginacion y resultados de busqueda
     * @param	$nom_tabla, $searchField
     * @return	returna la cantidad de registros totales incluyendo resultado de busqueda
     * @author	Juan Carlos Lopez Guillot
     * */
    function get_total_data($nom_tabla, $searchField, $estatus, $filtro) {
        
        if (!empty($searchField) && $filtro == FALSE) {
            $query2 = $this->buscarSearchField($nom_tabla);
            $n = 0;
            $losOR = "";
            foreach ($query2->result() as $row) {
                if ($n == 0) {
                    $losOR .= " $row->f_name LIKE '%$searchField%'  ESCAPE '!' ";
                } else {
                    $losOR .= "OR $row->f_name LIKE '%$searchField%'  ESCAPE '!' ";
                }
                $n = $n + 1;
            }
            $this->db->where("($losOR)");
        }
        $query = $this->db->get($nom_tabla);
        return $query->num_rows();
    }

    /**
     * <b>Method: get_data_byId</b>
     * //@method	Se consultan por los datos de un id en particular de una tabla en particular
     * @param	$id, $nom_tabla
     * @return	returna un arreglo de datos o fila de la tabla al que hace referencia el id
     * @author	Juan Carlos Lopez Guillot
     * */
    function get_data_byId($id, $nom_tabla) {
        $this->db->where('id', $id);
        //$this->db->where('eliminado !=', 'si');
        $query = $this->db->get($nom_tabla);

        return $query->row_array();
    }

    /**
     * <b>Method: eliminar</b>
     * @method	Se actualizan una fila o varias filas en una tabla particulas pasandolo estos id a eliminado = si
     * @param	$id, $nom_tabla
     * @return	returna true o false dependiendo del exito o fracaso de la operacion
     * @author	Juan Carlos Lopez Guillot
     * */
    function eliminar($id, $nom_tabla) {
//        die($id." ".$nom_tabla);
        $losId = explode(';', $id);
        $cant = count($losId);
        $salida = '';
        for ($i = 0; $i < ($cant - 1); $i++) {
            $this->db->where('id', $losId[$i]);
            $query = $this->db->delete($nom_tabla);
            if ($nom_tabla == 'sys_usuarios') {
                $this->db->where('id_user', $losId[$i]);
                $this->db->delete('sys_roles');
            }
            if($nom_tabla == 'web_galerias'){
                $this->db->where('id_gal', $losId[$i]);
                $this->db->delete('web_archivos');
               
            }
        }
        return $query;
    }

    /**
     * <b>Method: categoriasStore</b>
     * //@method	Se consultan las filas asociadas a una clave para la contruccion de combobox
     * @param	$campo
     * @return	returna una estructura de datos tipo json para ser interpretada por el combobox
     * @author	Juan Carlos Lopez Guillot
     * */
    function categoriasStore($campo) {
        $this->db->select('valor,categoria');
        $this->db->from('sys_categorias');
        $this->db->where('clave', $campo);
        $this->db->order_by('orden', 'asc');
        $query = $this->db->get();
        $total = $query->num_rows();
        return json_encode($query->result());
    }

    /**
     * <b>Method: categoriasStore</b>
     * //@method	Se consultan las filas asociadas a una clave para la contruccion de combobox
     * @param	$campo
     * @return	returna una estructura de datos tipo json para ser interpretada por el combobox
     * @author	Juan Carlos Lopez Guillot
     * */
    function categoriasStoreCombo($campo) {
        $this->db->select('valor,categoria');
        $this->db->from('sys_categorias');
        $this->db->where('clave', $campo);
        $this->db->order_by('orden', 'asc');
        $query = $this->db->get();
        $total = $query->num_rows();
        //$arr = "[['','Haga su Selección'],";
        $arr = "[";
        $n = 1;
        foreach ($query->result() as $row) {
            $arr .= "['$row->valor','$row->categoria']";
            if ($n == $total) {
                
            } else {
                $arr .=",";
            }
            $n = $n + 1;
        }
        $arr .= "]";
        return $arr;
    }

    /**
     * <b>Method: tablaStore</b>
     * //@method	Se consultan las filas asociadas a un campo para la contruccion de combobox
     * @param	$tabla, $campo
     * @return	returna una estructura de datos tipo json para ser interpretada por el combobox
     * @author	Juan Carlos Lopez Guillot
     * */
    function tablaStore($laClave) {
        $arrayWhere = array();
        $fieldsDb = $this->get_campos($tabla, $arrayWhere);
        $this->db->select("id,$laClave[1]");
        $can = count($laClave);
        if($can > 2){
            $w = explode(',', $laClave[2]);
            $w_campo = $w[0];
            $w_valor = $w[1];
            $this->db->where($w_campo, $w_valor);
        }
        $this->db->from($laClave[0]);
        //$this->db->where("eliminado !=", "si");
        foreach ($fieldsDb as $dat) {
            $f_name = $dat->f_name;
            if ($f_name == 'estatus') {
                $this->db->where("estatus", "publicado");
            }
        }
        $query = $this->db->get();

        $total = $query->num_rows();
        if ($total > 0) {
            $arr = "[['0','Deseleccionar'],";
            //$arr = "[";
            $n = 1;
            foreach ($query->result() as $row) {
                $elcampo = $row->$laClave[1];
                $arr .= "['$row->id','$elcampo']";
                if ($n == $total) {
                    
                } else {
                    $arr .=",";
                }
                $n = $n + 1;
            }
            $arr .= "]";
        } else {
            $arr = "[['0','Deseleccionar'],]";
        }
        return $arr;
    }

    /**
     * <b>Method: updateData</b>
     * //@method	Se actualizan los datos de una tabla en particular usando el id como condicion
     * @param	$data, $nom_tabla
     * @return	returna true o false dependiendo del exito de la operacion
     * @author	Juan Carlos Lopez Guillot
     * */
    function updateData($data, $nom_tabla) {
        $this->db->where('id', $data['id']);
        return $this->db->update($nom_tabla, $data);
    }

    /**
     * <b>Method: createData</b>
     * @method	Se inserta nuevo registro en una tabla en particular, las excepciones se manejan dentro del switch
     * @param	$data, $nom_tabla
     * @return	returna true o false dependiendo del exito de la operacion
     * @author	Juan Carlos Lopez Guillot
     * */
    function createData($data, $nom_tabla) {
        //array_shift($data);
        if ($this->db->insert($nom_tabla, $data)) {
            switch ($nom_tabla) {
                case 'sys_usuarios':
                    $this->db->select('id');
                    //$this->db->where('eliminado !=', 'si');
                    $this->db->order_by('id', 'desc');
                    $result = $this->db->get('sys_usuarios');
                    $row = $result->row();
                    $id_user = $row->id;

                    $this->db->from('sys_esq_tablas');
                    //$this->db->where('eliminado !=', 'si');
                    $query = $this->db->get();
                    foreach ($query->result() as $row) {
                        $dataRol = array(
                            'id_user' => $id_user,
                            'titulo' => $row->etiqueta,
                            'nom_tabla' => $row->nom_tabla,
                        );
                        $this->db->insert('sys_roles', $dataRol);
                    }
                    break;
                case 'sys_esq_tablas':
                    $this->db->select('id');
                    //$this->db->where('eliminado !=', 'si');
                    $result = $this->db->get('sys_usuarios');

                    //$this->db->where('eliminado !=', 'si');
                    $this->db->order_by('id', 'desc');
                    $query = $this->db->get('sys_esq_tablas');
                    $fila = $query->row();

                    foreach ($result->result() as $row) {
                        $dataRol = array(
                            'id_user' => $row->id,
                            'titulo' => $fila->etiqueta,
                            'nom_tabla' => $fila->nom_tabla,
                        );
                        $this->db->insert('sys_roles', $dataRol);
                    }
                    break;
            }
            return true;
        }
    }


    /**
     * <b>Method: admin_log</b>
     * //@method	Se inserta nuevo registro en una tabla de logs, 
     * @param	$trace_ins
     * @return	returna true
     * @author	Juan Carlos Lopez Guillot
     * */
    function admin_log($trace_ins) {
        array_shift($trace_ins);
        $this->db->insert('sys_admin_log', $trace_ins);
        return "Hecho";
    }

    /**
     * <b>Method: cambiaPass</b>
     * @method	Actualiza contraseña de usuario
     * @param	$data, $nom_tabla, $id
     * @return	returna true o false dependiendo del exito de la operacion
     * @author	Juan Carlos Lopez Guillot
     * */
    function cambiaPass($data, $nom_tabla, $id) {
        $this->db->where('id', $id);
        return $this->db->update($nom_tabla, $data);
    }

    /**
     * <b>Method: buscaRol</b>
     * //@method	Metodo que consulta la tabla categorias para identificar el rol asociado al permiso de un usuario
     * @param	$permiso
     * @return	returna la categoria asociada al usuario
     * @author	Juan Carlos Lopez Guillot
     * */
    function buscaRol($permiso) {
        $this->db->from('sys_categorias');
        $this->db->where('clave', 'usuarios_permiso');
        $this->db->where('valor', $permiso);
        $query = $this->db->get();
        $row = $query->row();
        return $row->categoria;
    }

    /**
     * <b>Method: get_rolData</b>
     * @method	Metodo que consulta los permisos sobre una tabla en particular para un usuario en particular
     * @param	$nom_tabla, $id
     * @return	returna arrglo de datos con los permisos sobre las tablas para el usuario en partuicular
     * @author	Juan Carlos Lopez Guillot
     * */
    function get_rolData($nom_tabla, $id) {
        $this->db->from($nom_tabla);
        $this->db->where('id_user', $id);
        $query = $this->db->get();
        $data['rowset'] = $query->result();
        $data['totalRows'] = $query->num_rows();
        return $data;
    }

    /**
     * <b>Method: updateRol</b>
     * //@method	Metodo que permite la actualizacion o modificacion de roles de ususarios
     * @param	$data_ins, $id
     * @return	returna true o false dependiendo del exito de la operacion
     * @author	Juan Carlos Lopez Guillot
     * */
    function updateRol($data_ins, $id) {
   
        $this->db->where('id', $id);
        return $this->db->update('sys_roles', $data_ins);

    }
    function updateGrid($data_ins,$id) {
  
        if(!empty($id)){
            $this->db->where('id', $id);
            return $this->db->update('web_capitulos', $data_ins);
        }  else {
           return $this->db->insert('web_capitulos', $data_ins); 
       }
        
    }

//     /**
//     * <b>Method: listAllFile</b>
//     * @method	Metodo que permite la construccion de los thumnaild de archivos y permite paginar los mismos
//     * @param	$limit, $start, $galId
//     * @return	returna arreglo de datos para ser recorrido en el controlador
//     * @author	Juan Carlos Lopez Guillot
//     * */      
//    function listAllFile($limit, $start, $galId)
//    {
//        $this->db->from('archivos');
//        $this->db->where('id_gal', $galId);
//        //$this->db->where('eliminado !=', 'si');
//        $result = $this->db->get();
//        $numRows = $result->num_rows();
//        
//        $this->db->from('archivos');
//        $this->db->where('id_gal', $galId);
//        //$this->db->where('eliminado !=', 'si');
//        $this->db->order_by('id', 'desc');
//        $this->db->limit($limit, $start);
//        $result = $this->db->get();
//        if ($result->num_rows() > 0)
//        {
//            $lasImagenes = array();
//            foreach ($result->result() as $row)
//            {
//                switch ($row->ext)
//                {
//                        case '.jpg':
//                        case '.png':
//                        case '.jpeg':
//                        case '.gif':
//                                $arch = array(
//                                        'name' => $row->titulo,
//                                        'thumb_url' => base_url().'uploads/galerias/'.$row->id_gal.'/peq_'.$row->archivo,
//                                        'id' => $row->id
//                                );
//                                break;
//                        case '.doc':
//                        case '.odt':
//                        case '.rtf':
//                                $arch = array(
//                                        'name' => $row->titulo,
//                                        'thumb_url' => base_url().'assets/img/icon_file/MS-Office-2003-Word-icon.png',
//                                        'id' => $row->id
//                                );
//                                break;
//                        case '.ods':
//                        case '.xls':
//                                $arch = array(
//                                        'name' => $row->titulo,
//                                        'thumb_url' => base_url().'assets/img/icon_file/MS-Office-2003-Excel-icon.png',
//                                        'id' => $row->id
//                                );
//                                break;
//                        case '.pdf':
//                                $arch = array(
//                                        'name' => $row->titulo,
//                                        'thumb_url' => base_url().'assets/img/icon_file/PDF-icon.png',
//                                        'id' => $row->id
//                                );
//                                break;
//                        case '.txt':
//                                $arch = array(
//                                        'name' => $row->titulo,
//                                        'thumb_url' => base_url().'assets/img/icon_file/Document-Copy-icon.png',
//                                        'id' => $row->id
//                                );
//                                break;
//                        case '.pps':
//                        case '.ppt':
//                                $arch = array(
//                                        'name' => $row->titulo,
//                                        'thumb_url' => base_url().'assets/img/icon_file/MS-Office-2003-PowerPoint-icon.png',
//                                        'id' => $row->id,
//                                );
//                                break;
//                }
//                $lasImagenes[] = $arch;
//            }
//            $salida = array('result' => $numRows, 'images' => $lasImagenes);
//        } else {
//            $salida = 'No hay archivos que mostrar';
//        }
//    return $salida;
//    }
//    
//     /**
//     * <b>Method: insertArchivo</b>
//     * @method	Metodo que inserta los datos de un archivo en la tabla archivos
//     * @param	$insertArchivos
//     * @return	
//     * @author	Juan Carlos Lopez Guillot
//     * */       
//    function insertArchivo($insertArchivos)
//    {
//        $this->db->insert('archivos', $insertArchivos);
//    }
//     /**
//     * <b>Method: get_rolData</b>
//     * @method	Consulta los datos particulares de un archivo asociado al id del archivo
//     * @param	$idFile
//     * @return	returna la fila de datos del archivo en cuestion
//     * @author	Juan Carlos Lopez Guillot
//     * */      
//    function fileEdit($idFile)
//    {
//        $query = "SELECT galerias.titulo AS gal_titulo, 
//                    archivos.id_gal AS id_gal, 
//                    archivos.titulo AS titulo, 
//                    archivos.archivo AS archivo,
//                    archivos.ext AS ext,
//                    archivos.descripcion AS descripcion
//                    FROM archivos
//                    JOIN galerias ON archivos.id_gal = galerias.id
//                    WHERE archivos.eliminado != 'si' AND 
//                    galerias.eliminado != 'si' 
//                    AND archivos.id = $idFile";
//        
//        $result = $this->db->query($query);
//        return $result->row_array();
//    }
//     /**
//     * <b>Method: eliminarArchivo</b>
//     * @method	Actualiza la tabla archivos y particularmente el campo eliminado pasa a si, 
//     * @param	$idFile
//     * @return	retorna true o false dependiendo del exito de la operacion
//     * @author	Juan Carlos Lopez Guillot
//     * */       
//    function eliminarArchivo($idFile)
//    {
//        $data = array('eliminado' => 'si');
//        $this->db->where('id', $idFile);
//        return $this->db->update('archivos', $data);
//    }

    /**
     * <b>Method: clonarUser</b>
     * @method	Crea un nuevo usuario en la tabla usuarios y sus respectivos permisos en tabla rol, 
     * @param	$id
     * @return	retorna true o false dependiendo del exito de la operacion
     * @author	Juan Carlos Lopez Guillot
     * */
    function clonarUser($id) {
        $this->db->select('permiso');
        $this->db->where('id', $id);
        $query = $this->db->get('sys_usuarios');
        $fila = $query->row();

        $silabas = array("a", "e", "i", "o", "u", "wa", "we", "wi", "wo", "wu", "sa", "se", "si", "so", "su", "za", "ze", "ax", "ex", "ca", "ce", "ci", "co", "cu", "da", "de", "di", "do", "du", "fa", "fe", "fi", "fo", "fu", "ra", "re", "ri", "ro", "ru", "va", "ve", "vi", "vo", "vu", "ba", "be", "bi", "bo", "bu", "na", "ne", "ni", "no", "nu", "ma", "me", "mi", "mo", "mu", "ha", "he", "hi", "ho", "hu", "yo", "ja", "je", "ji", "jo", "ju", "la", "le", "li", "lo", "lu", "pa", "pe", "pi", "po", "pu", "al", "el", "tha", "l", "l", "l", "l", "r", "r", "r", "s", "s", "s", "a", "e", "i", "o", "a", "e", "i", "o");
        $nombre = "";
        for ($i = 0; $i <= rand(2, 4); $i++) {
            $nombre = $nombre . $silabas[rand(0, 124)];
        }
        $nombre = ucfirst($nombre);
        $newUser = array(
            'login' => $nombre,
            'pass' => md5('123456'),
            'nombre' => 'Nombre de Usuario',
            'mail' => $nombre . '@mail.com',
            'permiso' => $fila->permiso,
        );
        $this->db->insert('sys_usuarios', $newUser);

        $this->db->order_by('id', 'desc');
        $query = $this->db->get('sys_usuarios');
        $fila = $query->row();
        $idNewUser = $fila->id;

        $this->db->where('id_user', $id);
        $query = $this->db->get('sys_roles');

        foreach ($query->result() as $row) {
            $newTableRol = array(
                'id_user' => $idNewUser,
                'titulo' => $row->titulo,
                'nom_tabla' => $row->nom_tabla,
                'visible' => $row->visible,
                'ver' => $row->ver,
                'crear' => $row->crear,
                'editar' => $row->editar,
                'borrar' => $row->borrar,
                'publicar' => $row->publicar,
            );
            $this->db->insert('sys_roles', $newTableRol);
        }
        return true;
    }

    function parse($data) {
        return utf8_encode(trim($data));
    }

   
    function exportCsv($searchField, $campos) {
        $sql = '';
        $n = 1;
        $menorIgual = array('f_hasta', 'monto_hasta');
        $mayorIgual = array('f_desde', 'monto_desde');
        $nom_tabla = 'maho_segmentacion_view';
        
        $telf_to_call = $searchField['telf_to_call'];
        $llamar = "maho_segmentacion_view.$telf_to_call AS llamar, "; 
        $excep = 1;
        foreach($searchField as $key => $value){
            
            if(!empty($value) && !is_array($value)){
               if($n==1){ $consulta = 'WHERE'; } else { $consulta = 'AND'; }
               if(in_array($key, $menorIgual)){ $comp = '<='; } 
               elseif(in_array($key, $mayorIgual)){ $comp = '>='; }                
               else { $comp = '='; }
                        
               if( $key=='monto_desde' || $key == 'monto_hasta'){ $key = 'monto_resto'; }
               if( $key=='f_desde' || $key == 'f_hasta'){ $key = 'f_ini'; }
               
               if($key == 'telf_to_call'){
                   $sql .= "$consulta $nom_tabla.$value IS NOT NULL ";
                   $sql .= "AND LENGTH($nom_tabla.$value) > 0";
               } else {
                   $sql .= "$consulta $nom_tabla.$key $comp '$value' ";
               }
               
               $n++;
            } 
            if(is_array($value)){
                if(count($value)>0){
                    if($n==1){ $consulta = 'WHERE'; } else { $consulta = 'AND'; }
                    $valuecoma = implode("','", $value);
                    $comp = 'IN';                    
                    $sql .= "$consulta $nom_tabla.$key $comp ('$valuecoma') ";
                    $n++;
                }
            }
        }
        
        $selects = '';
        foreach($campos as $valor){
            $selects .= "maho_segmentacion_view.$valor as $valor, ";
        }
        
        $query = "SELECT $llamar $selects 
                    maho_segmentacion_view.id as CS 
                    FROM maho_segmentacion_view 
                    $sql
                    ";
        
        $result = $this->db->query($query);
//        die($this->db->last_query());
        $list = $result->result_array();    
        
        $camposComa = implode("','", $campos);        
        $sql = "SELECT f_titulo FROM sys_esq_campos 
                WHERE f_name IN ('$camposComa') 
                AND nom_tabla = 'maho_segmentacion_view' 
                ORDER BY f_orden asc";
        $result = $this->db->query($sql);
        $csv_header = array();
        foreach($result->result() as $row){
            $csv_header[] = $row->f_titulo;
        }
        array_unshift($csv_header, "");
        array_push($csv_header, "CS");
//        die(print_r($csv_header));
//        $csv_header = array("", "Nombre", "Cédula", "Teléfono Deuda", "Teléfono Contacto", "Teléfono Alterno 1", "Teléfono Alterno 2", "Total Deuda", "Estatus Gestión Actual", "Estatus Gestión Positivo", "Estatus Pago", "Nivel", "Estado", "Central", "Fecha Ejecución", "Cuenta Contrato", "CS");
        $file_name = 'Campania-MAHO-' . date('Ymdhis') . '.csv';
        $fp = fopen('/var/www/crm/tmpfiles/' . $file_name, 'w');
        fputcsv($fp, $csv_header);
        foreach ($list as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
        return $file_name;
    }

    public function getRecordInfo($id) {
        $this->db->select('filename');
        $this->db->where('id', $id);
        $query = $this->db->get('grabaciones');
        return $query->row_array();
    }

    
    function formList(){
        $sql    = "SELECT ff.`value` AS valores
                FROM call_center.form_field AS ff
                JOIN call_center.form AS f ON f.id = ff.id_form
                WHERE lower( ff.etiqueta ) LIKE '%estatus%'
                AND lower( f.nombre ) LIKE '%maho%'";
        $query  = $this->db2->query($sql);
        $fila   = $query->row();
        $val    = $fila->valores;
        $valores= $val.',Tono Ocupado, No Contesta, Ocupado, NG';
        $p = explode(',', utf8_decode($valores));
        $n = count($p);
        $i = 1;
        $salida = '';
        
        for($i = 0; $i<$n; $i++){
            if($i<($n-1)){$coma = ',';}else{$coma='';}
            $salida .= '{"valor":"'.$p[$i].'","categoria":"'.$p[$i].'"}'.$coma;
        }
        return $salida;
    }

    
    public function generateCSVFile($data, $extra = null) {
        set_time_limit(0);
        $headers = array_keys($data[0]);
        foreach ($headers as $key => $value) {
            $headers[$key] = str_replace('_',' ', $value);
        }
        $extradata = '';
        //verifica fechas: 
        
//        if (! empty($extra['start_date'])) {
//            $extradata = 'fi_' . str_replace(' ', '_', $extra['start_date']);
//        }
//        if (! empty($extra['end_date'])) {
//            $extradata .= '_ff_' . str_replace(' ', '_', $extra['end_date']);
//        }
//        
        $file_name = 'report_' . $extradata . '_' . date('Ymdhis') . '.csv';
        $fp = fopen(PATH_GENERATE_FILES . $file_name, 'w');
        fputcsv($fp, $headers);
        foreach ($data as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
        return $file_name;
    }
    
    function insertFile($insertArchivos)
    {
        $this->db->insert('web_archivos', $insertArchivos);
    }
     /**
     * <b>Method: eliminarArchivo</b>
     * @method	Actualiza la tabla archivos y particularmente el campo eliminado pasa a si, 
     * @param	$idFile
     * @return	retorna true o false dependiendo del exito de la operacion
     * @author	Juan Carlos Lopez Guillot
     * */       
    function eliminarArchivo($idFile,$id_gal)
    {
        $data = array('eliminado' => 'si');
        $this->borrarArchivo($idFile,$id_gal);
        $this->db->where('id', $idFile);
        return $this->db->update('web_archivos', $data);
    }
    
    function borrarArchivo($id,$id_gal) {
        $this->db->from('web_archivos');
        $this->db->where('id', $id);
        $this->db->where('id_gal', $id_gal);
        $this->db->where('eliminado !=', 'si');
        $result = $this->db->get();
        $get = $result->row();
        $dir = getcwd().'/uploads/web_galerias/'.$id_gal.'/'.$get->archivo;
        unlink($dir);
    }
    
        /**
     * <b>Method: get_rolData</b>
     * @method	Metodo que permite la construccion de los thumnaild de archivos y permite paginar los mismos
     * @param	$limit, $start, $galId
     * @return	returna arreglo de datos para ser recorrido en el controlador
     * @author	Juan Carlos Lopez Guillot
     * */      
    function listAllFile($limit, $start, $galId,$nom_tabla)
    {  
        $this->db->from($nom_tabla);
        $this->db->where('id', $galId);
        $this->db->where('eliminado !=', 'si');
        $result = $this->db->get();
        $getipo = $result->row();
        
        
        $this->db->from('web_archivos');
        $this->db->where('id_gal', $galId);
        $this->db->where('eliminado !=', 'si');
        $result = $this->db->get();
        $numRows = $result->num_rows();

        
        $this->db->from('web_archivos');
        $this->db->where('id_gal', $galId);
        $this->db->where('tipo', $getipo->tipo);
        $this->db->where('eliminado !=', 'si');
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $start);
        $result = $this->db->get();
        if ($result->num_rows() > 0)
        {
            $lasImagenes = array();
            foreach ($result->result() as $row)
            {
                switch ($row->ext)
                {
                        case '.jpg':
                        case '.png':
                        case '.jpeg':
                        case '.gif':
                                $arch = array(
                                        'name' => $row->titulo,
                                        'thumb_url' => base_url().'uploads/web_galerias/'.$row->id_gal.'/peq_'.$row->archivo,
                                        'id' => $row->id
                                );
                                break;
                        case '.doc':
                        case '.odt':
                        case '.rtf':
                                $arch = array(
                                        'name' => $row->titulo,
                                        'thumb_url' => base_url().'assets/img/icons/MS-Office-2003-Word-icon.png',
                                        'id' => $row->id
                                );
                                break;
                        case '.ods':
                        case '.xls':
                                $arch = array(
                                        'name' => $row->titulo,
                                        'thumb_url' => base_url().'assets/img/icons/MS-Office-2003-Excel-icon.png',
                                        'id' => $row->id
                                );
                                break;
                        case '.pdf':
                                $arch = array(
                                        'name' => $row->titulo,
                                        'thumb_url' => base_url().'assets/img/icons/PDF-icon.png',
                                        'id' => $row->id
                                );
                                break;
                        case '.txt':
                                $arch = array(
                                        'name' => $row->titulo,
                                        'thumb_url' => base_url().'assets/img/icons/Document-Copy-icon.png',
                                        'id' => $row->id
                                );
                                break;
                        case '.pps':
                        case '.ppt':
                                $arch = array(
                                        'name' => $row->titulo,
                                        'thumb_url' => base_url().'assets/img/icons/MS-Office-2003-PowerPoint-icon.png',
                                        'id' => $row->id,
                                );
                                break;
                        case '.mp3':
                            $arch = array(
                                        'name' => $row->titulo,
                                        'thumb_url' => base_url().'assets/img/musical.png',
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
    function listCapitulos($progId) {
        $this->db->from('web_capitulos');
        $this->db->where('id_prog', $progId);
        $this->db->order_by('id', 'desc');
        $result = $this->db->get();
        return $result->result();
        
    }
}

?>
