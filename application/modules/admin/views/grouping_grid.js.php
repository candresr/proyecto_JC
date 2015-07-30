<?php
$tableRols  = $this->session->userdata('tableRols');
$permisos_t = $tableRols[$nom_tabla];
$borrar     = $permisos_t->borrar;
$editar     = $permisos_t->editar;
$permiso    = $this->session->userdata('permiso');

echo $estatus;
if(empty($scriptTags)){ '<script type="text/javascript">'; } else { FALSE; }
?>




var myReader_<?=$nom_tabla?> = new Ext.data.JsonReader({
                    totalProperty:  'totalRows',
                    root: 'rowset',
                    fields:     <?=$fields?>       
                });



var groupingGridStore_<?=$nom_tabla?>   = new Ext.data.GroupingStore({
                    reader:     myReader_<?=$nom_tabla?>,
                    data:       <?=$data?>,
                    groupField: '<?=$groupField?>',
                    id: 'groupingGridStore_<?=$nom_tabla?>',
                    autoDestroy:true,
                    remoteSort:true,
                    proxy:  new Ext.data.HttpProxy({
                                url:    BASE_URL+'admin/listAll',
                                method: 'POST',
                                params: { start: 0, limit: LIMITE }
                            })
                });

 Ext.StoreMgr.register(groupingGridStore_<?=$nom_tabla?>);


<?php    
    $dataSearch = array('nom_tabla'=>$nom_tabla, 'searchType' => $searchType, 'elem' => 'groupingGrid_', 'storeName'=>'groupingGridStore_');
    $this->load->view('search.js.php', $dataSearch);
?> 

var paginBar_<?=$nom_tabla?> = new Ext.PagingToolbar({
                pageSize:   LIMITE, 
                store:      groupingGridStore_<?=$nom_tabla?>,
                displayInfo: true,
                displayMsg: 'Mostrando Registros {0} - {1} de un total de {2}',
                emptyMsg: "No hay registros que mostrar",
                }); 

var cbgroupingGrid_<?=$nom_tabla?> = new Ext.grid.CheckboxSelectionModel({
        listeners: {
            selectionchange: function(sm) {
                if (sm.getCount()) {
                    <?php if($borrar=='true'){ ?> groupingGrid_<?=$nom_tabla?>.removeButton.enable(); <?php } ?>
                    <?php if($editar=='true'){ ?> groupingGrid_<?=$nom_tabla?>.editButton.enable(); <?php } ?>
                    <?php if($nom_tabla=='usuarios' && $permiso<3){ ?> groupingGrid_<?=$nom_tabla?>.cloneButton.enable(); <?php } ?>
                    <?php if($nom_tabla=='proyectos' && $permiso<3){ ?> groupingGrid_<?=$nom_tabla?>.DownloadChangeLog.enable(); <?php } ?>
                } else {
                    <?php if($borrar=='true'){ ?> groupingGrid_<?=$nom_tabla?>.removeButton.disable(); <?php } ?>
                    <?php if($editar=='true'){ ?> groupingGrid_<?=$nom_tabla?>.editButton.disable(); <?php } ?>
                    <?php if($nom_tabla=='usuarios' && $permiso<3){ ?> groupingGrid_<?=$nom_tabla?>.cloneButton.disable(); <?php } ?>
                    <?php if($nom_tabla=='proyectos' && $permiso<3){ ?> groupingGrid_<?=$nom_tabla?>.DownloadChangeLog.disable(); <?php } ?>
                }
            }
        }
    });
    
var myColumns = <?=$columns?>;
myColumns.unshift(cbgroupingGrid_<?=$nom_tabla?>);

var groupingGrid_<?=$nom_tabla?> = new Ext.grid.GridPanel({
		id:         'groupingGrid_<?=$nom_tabla?>',
                layout:     'anchor',
		frame:      true, 
		border:     true, 
		stripeRows: true, 
		sm:         cbgroupingGrid_<?=$nom_tabla?>,
                autoScroll: true,
                colModel: new Ext.grid.ColumnModel({
                    defaults: { width: 120 },
                    columns:        myColumns
                }),
                store:      groupingGridStore_<?=$nom_tabla?>, 
		loadMask:   true,
		title:      '<?=$gridTitle?>',
		style:      'margin:0 auto;', 
		height:     '100%',
                tbar:       [
                            <?php if($borrar=='true'){ ?>
                                {
                                text: 'Eliminar(s)',
                                id: 'btnDel',
                                icon: BASE_ICONS + 'pencil_delete.png',
                                ref: '../removeButton',
                                disabled: true,
                                handler: eliminarFilas
                            }, 
                            <?php } if($editar=='true'){ ?>
                            '-', {
                                text: 'Editar',
                                disabled: false,
                                id: 'btnEdit',
                                icon: BASE_ICONS + 'save.gif',
                                ref: '../editButton',
                                disabled: true,
                                handler: editarFila
                            }, 
                            <?php } if($nom_tabla=='usuarios' && $permiso<3){ ?>
                            '-', {
                                text: 'Clonar Usuario',
                                disabled: false,
                                id: 'btnClone',
                                icon: BASE_ICONS + 'icon_clone.png',
                                ref: '../cloneButton',
                                disabled: true,
                                handler: clonaUser
                            },        
                            <?php } if($nom_tabla=='versiones' && $permiso<3){ ?>
                            '-', {
                                text: 'Generar Version',
                                disabled: false,
                                id: 'btnClone',
                                icon: BASE_ICONS + 'group_go.png',
                                //ref: '../cloneButton',
                                disabled: false,
                                handler: generaVersion
                            },  
                            <?php } if($nom_tabla=='proyectos' && $permiso<3){ ?>
                            '-', {
                                text: 'Descargar Registros de Cambios',
                                disabled: false,
                                id: 'btnClone',
                                icon: BASE_ICONS + 'group_go.png',
                                ref: '../DownloadChangeLog',
                                disabled: true,
                                handler: DescargaChangeLog
                            },  
                            <?php } if($nom_tabla=='detalle_version' && $permiso<3){ ?>
                            '-', {
                                text: 'Importar Datos',
                                disabled: false,
                                id: 'btnClone',
                                icon: BASE_ICONS + 'group_go.png',
                                //ref: '../cloneButton',
                                disabled: false,
                                handler: importaData
                            },     
                            <?php } if($estatus == '1'){ ?>
                            '->', {
                                text: 'Publicados',
                                disabled: false,
                                id: 'btnPublicados',
                                icon: BASE_ICONS + 'page_green.png',
                                handler: mostrarPublicados
                            }, '-', {
                                text: 'Borradores',
                                disabled: false,
                                id: 'btnBorradores',
                                icon: BASE_ICONS + 'page_red.png',
                                handler: mostrarBorradores
                            }, '-', {
                                text: 'Todos',
                                disabled: false,
                                id: 'btnTodos',
                                icon: BASE_ICONS + 'page_white_stack.png',
                                handler: mostrarTodos
                            },          
                            '-',
                                search_<?=$nom_tabla?>
                            <?php } else { ?>
                                '->',
                                search_<?=$nom_tabla?>
                            <?php } ?>    
                            ],
							<?php if($pagination):?>
								bbar: paginBar_<?=$nom_tabla?>,
							<?endif?>
							<?php if($editar=='true'){ ?>
								listeners: {
                                    rowdblclick: editarFila								
                                },
							<?php } ?>
                        view: new Ext.grid.GroupingView({
                        forceFit: <?=(isset ($forceFit))?$forceFit:true?> ,
                        enableGroupingMenu:false,
                        enableNoGroups:false,
                        hideGroupedColumn : true,
                        showGroupName : false,
                        groupTextTpl: '{text} ({[values.rs.length]})'
                    })
          })
  
  function mostrarPublicados(){
        groupingGrid_<?=$nom_tabla?>.store.load({params:{estatus:'publicado'}});
  }
 
  function mostrarBorradores(){
        groupingGrid_<?=$nom_tabla?>.store.load({params:{estatus:'borrador'}});
  }
  
  function mostrarTodos(){
        groupingGrid_<?=$nom_tabla?>.store.load();
  }

  function editarFila(grid, rowIndex, e){
      
      var sm = groupingGrid_<?=$nom_tabla?>.getSelectionModel();
            var sel = sm.getSelections();

            if(sel.length > 1){
                Ext.Msg.show({   
                                title: 'Edición de Registro',
                                msg: 'Solo puede escoger un registro a editar',
                                buttons: Ext.Msg.OK,
                                icon: Ext.MessageBox.ERROR,
                                minWidth: 300
                            }); 
            } else {
      
            var ilId = groupingGrid_<?=$nom_tabla?>.getSelectionModel().getSelected().data.id;
            //console.log(ilId);
            Ext.Ajax.request({
                url: BASE_URL+'admin/form',
                method: 'GET',
                params:{id:ilId},
                    success: function(response){ eval(response.responseText); },
                    failure: function(){ 
                    Ext.Msg.show({   
                            title: 'Error de peticion al servidor',
                            msg: 'No se pudo procesar la peticion de edicion',
                            buttons: Ext.Msg.OK,
                            icon: Ext.MessageBox.ERROR,
                            minWidth: 300
                        });              
                    }
                });
            }
        }
  
        //Metodo que genera la version, prepara datos y muestra un formulario donde se guardan los datos de la version
        function generaVersion(){
         Ext.Ajax.request({
            url: BASE_URL+'admin/proyectoComboStore',
            method: 'GET',
            params:  { a_vista:'win_genera_version.js.php' }, 
                success: function(response){ eval(response.responseText); },
                failure: function(){ 
                Ext.Msg.show({   
                        title: 'Error de peticion al servidor',
                        msg: 'No se pudo procesar la peticion de edicion',
                        buttons: Ext.Msg.OK,
                        icon: Ext.MessageBox.ERROR,
                        minWidth: 300
                    })
                }
            })
        }
  
  
      //metodo que muestra la ventana que permite seleccionar el archivo que se va a importar (csv)
      function importaData(){
          Ext.Ajax.request({
                url: BASE_URL+'admin/proyectoComboStore',
                method: 'GET',
                params:  { a_vista:'win_import_data.js.php' }, 
                success: function(response){ 
                     eval(response.responseText);    //                    
                },
                failure: function(){ 
                Ext.Msg.show({   
                        title: 'Error de peticion al servidor',
                        msg: 'No se pudo procesar la peticion de edicion',
                        buttons: Ext.Msg.OK,
                        icon: Ext.MessageBox.ERROR,
                        minWidth: 300
                    })
                }
            })
        }
  
  
        function DescargaChangeLog()
        {
            var sm  = groupingGrid_<?=$nom_tabla?>.getSelectionModel();
            var sel = sm.getSelections();

            if(sel.length > 1){
                Ext.Msg.show({   
                                title: 'Descargar ChangeLog',
                                msg: 'Solo puede escoger un proyecto',
                                buttons: Ext.Msg.OK,
                                icon: Ext.MessageBox.ERROR,
                                minWidth: 300
                            }); 
            } else {
                var ilId = groupingGrid_<?=$nom_tabla?>.getSelectionModel().getSelected().data.id;
                
                Ext.Ajax.request({
                url: BASE_URL+'admin/download_changlog',
                method: 'GET',
                params:{id:ilId},
                    success: function(response){ 
                         eval(response.responseText);
                    },
                    failure: function(){ 
                    Ext.Msg.show({   
                            title: 'Error de peticion al servidor',
                            msg: 'No se pudo procesar la peticion de edicion',
                            buttons: Ext.Msg.OK,
                            icon: Ext.MessageBox.ERROR,
                            minWidth: 300
                        })
                    }
                })

            }
        }
  
  
          function clonaUser(){
              
              var sm = groupingGrid_<?=$nom_tabla?>.getSelectionModel();
					var sel = sm.getSelections();
                                        
                                        if(sel.length > 1){
                                            Ext.Msg.show({   
                                                            title: 'Clonación de Usuario',
                                                            msg: 'Solo puede escoger un usuario para clonar',
                                                            buttons: Ext.Msg.OK,
                                                            icon: Ext.MessageBox.ERROR,
                                                            minWidth: 300
                                                        }); 
                                        } else {
              
                          Ext.Msg.show({
                                    title: 'Confirm',
                                    msg: 'Desea Clonar el usuario seleccionado?',
                                    buttons: Ext.Msg.YESNO,
                                    icon: Ext.MessageBox.ERROR,
                                    fn: function(btn) {
                                            if (btn == 'yes'){                                            
                                                var data = '';
                                                    data = sel[0].get('id');
                                            Ext.Ajax.request({
                                            url: BASE_URL + 'admin/clonarUser/',
                                            method: 'GET',
                                            params: { postdata: data },
                                            success:function(response){
                                                        var obj = Ext.util.JSON.decode(response.responseText);
                                                        Ext.Msg.show({   
                                                            title: 'Clonación de Usuario',
                                                            msg: obj.msj,
                                                            buttons: Ext.Msg.OK,
                                                            icon: Ext.MessageBox.ERROR,
                                                            minWidth: 300
                                                        });                                                        
                                                        groupingGridStore_<?=$nom_tabla?>.load();
                                                    },						
                                            failure: function(response){
                                                        var obj = Ext.util.JSON.decode(response.responseText);
                                                        Ext.Msg.show({   
                                                            title: 'Clonación de Usuario',
                                                            msg: obj.msj,
                                                            buttons: Ext.Msg.OK,
                                                            icon: Ext.MessageBox.ERROR,
                                                            minWidth: 300
                                                        });
                                                    }
                                                })                                            
                                        }					
                                }
                       })
                 }
          }
  
  
  
  
  	function eliminarFilas() {
        
		Ext.Msg.show({
			title: 'Confirm',
			msg: 'Desea Eliminar los siguientes registros?',
			buttons: Ext.Msg.YESNO,
			icon: Ext.MessageBox.ERROR,
			fn: function(btn) {
				if (btn == 'yes') {
					var sm = groupingGrid_<?=$nom_tabla?>.getSelectionModel();
					var sel = sm.getSelections();
					var data = '';
					for (var i = 0; i< sel.length; i++) {
						data = data + sel[i].get('id') + ';';
					}
					Ext.Ajax.request({
                                            url: BASE_URL + 'admin/eliminar/',
                                            method: 'GET',
                                            params: { postdata: data },
                                            success:
                                                function(response){
                                                        var obj = Ext.util.JSON.decode(response.responseText);
                                                        Ext.Msg.show({   
                                                            title: 'Eliminacion de Registros',
                                                            msg: obj.msj,
                                                            buttons: Ext.Msg.OK,
                                                            icon: Ext.MessageBox.ERROR,
                                                            minWidth: 300
                                                        });                                                        
                                                    groupingGridStore_<?=$nom_tabla?>.load();
                                                    },						
                                            failure: 
                                                function(response){
                                                        var obj = Ext.util.JSON.decode(response.responseText);
                                                        Ext.Msg.show({   
                                                            title: 'Eliminacion de Registros',
                                                            msg: obj.msj,
                                                            buttons: Ext.Msg.OK,
                                                            icon: Ext.MessageBox.ERROR,
                                                            minWidth: 300
                                                            });
                                                    }
                                        })
                                }
                        }
                })
        }
    
<?php
    if($replace == 'window'){
        $wdata['w_item'] = 'groupingGrid_'.$nom_tabla;
        $this->load->view('generals/window.js.php', $wdata);
    } else {
        echo $replace;
    }
 ?>
 <?php (empty($scriptTags)) ? '</script>'; : FALSE; ?>