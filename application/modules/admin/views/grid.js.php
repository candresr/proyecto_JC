<?php
$tableRols  = $this->session->userdata('tableRols');
$permisos_t = $tableRols[$nom_tabla];
$borrar     = $permisos_t->borrar;
$editar     = $permisos_t->editar;
$crear      = $permisos_t->crear;
$ver        = $permisos_t->ver;
$permiso    = $this->session->userdata('permiso');

//if(empty($scriptTags)){ '<script type="text/javascript">'; } else { FALSE; }
if(false):?><script languaje="javascript"><?php endif;
?>
    
var  GridStore_<?=$nom_tabla?>   = new Ext.data.JsonStore({
                totalProperty:  'totalRows',
                root:           'rowset',
                fields:         <?=$fields?>,
                data:           <?=$data?>,
                remoteSort:     true,
                proxy: new Ext.data.HttpProxy({
	            url:    BASE_URL+'admin/listAll',
                    method: 'POST',
                    params: { start: 0, limit: LIMITE }
                    })
                });
				
Ext.StoreMgr.register( GridStore_<?=$nom_tabla?>);


 <?php    
//    $dataToolBar = array('tbar'=>$tbar, 'bbar'=>$bbar, 'nom_tabla'=>$nom_tabla, 'searchType'=>$searchType);
//    $this->load->view('generals/toolbar.js.php', $dataToolBar); 

    $dataSearch = array('nom_tabla'=>$nom_tabla, 'searchType' => $searchType, 'elem' => 'Grid_', 'storeName'=>'GridStore_');
    $this->load->view('search.js.php', $dataSearch);
?> 

var paginBar_<?=$nom_tabla?> = new Ext.PagingToolbar({
                pageSize:   LIMITE, 
                store:      GridStore_<?=$nom_tabla?>,
                displayInfo: true,
                displayMsg: 'Mostrando Registros {0} - {1} de un total de {2}',
                emptyMsg: "No hay registros que mostrar",
                id : 'paginBar_<?=$nom_tabla?>'
                }); 

var cbGrid_<?=$nom_tabla?> = new Ext.grid.CheckboxSelectionModel({
        listeners: {
            selectionchange: function(sm) {
                if (sm.getCount()) {
                    <?php if($borrar=='true'){ ?> Grid_<?=$nom_tabla?>.removeButton.enable(); <?php } ?>
                    <?php if($editar=='true'){ ?> Grid_<?=$nom_tabla?>.editButton.enable(); <?php } ?>
                    <?php if($crear=='true'){ ?> Grid_<?=$nom_tabla?>.newButton.enable(); <?php } ?>
                    <?php if($ver=='true'){ ?> Grid_<?=$nom_tabla?>.verButton.enable(); <?php } ?>
                    <?php if($nom_tabla=='usuarios' && $permiso<3){ ?> Grid_<?=$nom_tabla?>.cloneButton.enable(); <?php } ?>
                } else {
                    <?php if($borrar=='true'){ ?> Grid_<?=$nom_tabla?>.removeButton.disable(); <?php } ?>
                    <?php if($editar=='true'){ ?> Grid_<?=$nom_tabla?>.editButton.disable(); <?php } ?>
                    <?php if($crear=='true'){ ?> Grid_<?=$nom_tabla?>.newButton.disable(); <?php } ?>
                    <?php if($ver=='true'){ ?> Grid_<?=$nom_tabla?>.verButton.disable(); <?php } ?>
                    <?php if($nom_tabla=='usuarios' && $permiso<3){ ?> Grid_<?=$nom_tabla?>.cloneButton.disable(); <?php } ?>
                }
            }
        }
    });
    
var myColumns = <?=$columns?>;
myColumns.unshift(cbGrid_<?=$nom_tabla?>);

var Grid_<?=$nom_tabla?> = new Ext.grid.GridPanel({
		id:         'Grid_<?=$nom_tabla?>',
                layout:     'anchor',
		frame:      true, 
		border:     true, 
		stripeRows: true, 
		sm:         cbGrid_<?=$nom_tabla?>,
                autoScroll: true,
                colModel: new Ext.grid.ColumnModel({
                    defaults: { width: 120 },
                    columns:        myColumns
                }),
                store:      GridStore_<?=$nom_tabla?>, 
		loadMask:   true,
		title:      '<?=$gridTitle?>',
		style:      'margin:0 auto;', 
                //autoWidth:  true,
                //autoHeight: true,
		height:     '100%',
		//height:     500,                                
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
                                id: 'btnEdit',
                                icon: BASE_ICONS + 'save.gif',
                                ref: '../editButton',
                                disabled: true,
                                handler: editarFila
                            }, 
                            <?php } if($crear=='true'){ ?>
                            '-', {
                                text: 'Nuevo',
                                id: 'btnNew',
                                icon: BASE_ICONS + 'pencil_add.png',
                                ref: '../newButton',
                                //disabled: true,
                                handler: newFila
                            },  
                            <?php } if($ver=='true'){ ?>
                            '-', {
                                text: 'Ver',
                                id: 'btnView',
                                icon: BASE_ICONS + 'zoom.png',
                                ref: '../verButton',
                                disabled: true,
                                handler: verFila
                            },   
                            <?php } if($nom_tabla=='usuarios' && $permiso<3){ ?>
                            '-', {
                                text: 'Clonar Usuario',
                                id: 'btnClone',
                                icon: BASE_ICONS + 'icon_clone.png',
                                ref: '../cloneButton',
                                disabled: true,
                                handler: clonaUser
                            },                           
                            
                            <?php } if(preg_match('/^rep_/',$nom_tabla) === 1){ ?>
                            '-',{
                                title: 'Fecha Desde',
                                columns: 3,
                                id: 'gReportFechaDesde',
                                xtype: 'buttongroup',
                                //icon: BASE_ICONS + 'table_go.png',
                                disabled: false,
                                items: [
                                    {
                                        xtype: 'datefield',
                                        id: 'inpFechaDesde',
                                        format: 'd-m-Y'
                                    }, 
                                    {
                                        xtype: 'timefield',
                                        id: 'inpTiempoDesde',
                                        increment: 30,
                                        width: 80,
                                        triggerAction: 'all',
                                            format: 'g:i A'
                                    },
                                    {
                                        icon: BASE_ICONS + 'broom-minus-icon.png',
                                        tooltip: 'Limpiar',
                                        handler: function (){
                                            Ext.getCmp('inpFechaDesde').reset();
                                            Ext.getCmp('inpTiempoDesde').clearValue();
                                            exportCsvReport('<?php echo $nom_tabla; ?>', false);
                                            //Ext.Msg.alert('Información','Se ha limpiado el filtro, ejecute "Limpiar" para ver los cambios.');
                                        }
                                    }
                                ]
//                                handler: function() {
//                                    exportCsvReport('<?php echo $nom_tabla; ?>');
//                                }
                            },
                            '-', {
                                title: 'Fecha Hasta',
                                columns: 3,
                                id: 'gReportFechaHasta',
                                xtype: 'buttongroup',
                                //icon: BASE_ICONS + 'table_go.png',
                                disabled: false,
                                items: [
                                    {
                                        xtype: 'datefield',
                                        id: 'inpFechaHasta',
                                        format: 'd-m-Y'
                                    }, 
                                    {
                                        xtype: 'timefield',
                                        id: 'inpTiempoHasta',
                                        increment: 30,
                                        width: 80,
                                        triggerAction: 'all',
                                            format: 'g:i A'
                                    },
                                    {
                                        icon: BASE_ICONS + 'broom-minus-icon.png',
                                        tooltip: 'Limpiar',
                                        handler: function (){
                                            Ext.getCmp('inpFechaHasta').reset();
                                            Ext.getCmp('inpTiempoHasta').clearValue();
                                            exportCsvReport('<?php echo $nom_tabla; ?>', false);
                                            //Ext.Msg.alert('Información','Se ha limpiado el filtro, ejecute "Limpiar" para ver los cambios.');
                                        }
                                    }
                                ]
//                                handler: function() {
//                                    exportCsvReport('<?php echo $nom_tabla; ?>');
//                                }
                            },'-', {
                                title: 'Acciones',
                                xtype: 'buttongroup',
                                columns: 2,
                                items: [                                  
                                    {
                                        text: 'Filtrar',
                                        id: 'btnFiltrarCsvReport',
                                        icon: BASE_ICONS + 'group_go.png',
                                        disabled: false,
                                        handler: function() {
                                            exportCsvReport('<?php echo $nom_tabla; ?>', false);
                                        }
                                    },
                                    {
                                        text: 'Exportar CSV',
                                        id: 'btnExportCsvReport',
                                        icon: BASE_ICONS + 'table_go.png',
                                        disabled: false,
                                        handler: function() {
                                            exportCsvReport('<?php echo $nom_tabla; ?>', true);
                                        }
                                    }  
                                ]
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
                            <?php } elseif($nom_tabla!=='') { ?>
                                '->',
                                search_<?=$nom_tabla?>    
                            <?php } else {} ?>    
                            ],
                                                        
                            bbar: [ paginBar_<?=$nom_tabla?> ],     
                      <?php if($editar=='true'){ ?>
                            listeners: {
                                rowdblclick: editarFila				
                                }
                      <?php } ?>
          })
  
  
  function mostrarPublicados(){
        Grid_<?=$nom_tabla?>.store.load({params:{estatus:'publicado'}});
  }
 
  function mostrarBorradores(){
        Grid_<?=$nom_tabla?>.store.load({params:{estatus:'borrador'}});
  }
  
  function mostrarTodos(){
        Grid_<?=$nom_tabla?>.store.load();
  }


  function validaDataFilter(fCmp, tCmp) {
      var fdatevalue = '';
      if (typeof fCmp.value !== "undefined") {
          fdatevalue = fCmp.value;
      }
      
      if (typeof tCmp.value !== "undefined") {
          if (typeof fCmp.value === "undefined") {
              alert('Debe Especificar una Fecha');
              return -1;
          } else if (fdatevalue != '') {
              fdatevalue += ' ' + tCmp.value;
          }
      }
      return fdatevalue;
  }
  
    function exportCsvReport (table_name, exportar) {
    //alert('exportar' + table_name);
    var fechaDesde = Ext.getCmp('inpFechaDesde');
    var tiempoDesde = Ext.getCmp('inpTiempoDesde');
    var fechaHasta = Ext.getCmp('inpFechaHasta');
    var tiempoHasta = Ext.getCmp('inpTiempoHasta');
   // alert(tiempoDesde.value);
    var vstart_date = validaDataFilter(fechaDesde, tiempoDesde);
    var vend_date = validaDataFilter(fechaHasta, tiempoHasta);
    
    if (vstart_date != -1) {
        var fgrid = Ext.getCmp('Grid_' + table_name);
        var fgrid_store = fgrid.store;
        var fgrid_bar = Ext.getCmp('paginBar_' + table_name);
//        Ext.Ajax.request({
//            url : BASE_URL + 'admin/listAll',
//            method: 'post',
//            params: {
//                start_date : vstart_date,
//                end_date : vend_date,
//                gexport: exportar
//            },
//            success: function (data) {
//                var obj = Ext.util.JSON.decode(data.responseText);
////                var fgrid_store = window['GridStore_' + table_name];
////                var fgrid_var = window['paginBar_' + table_name];
//                fgrid.store.loadData(obj);
                fgrid_store.reload({params:{start_date: vstart_date, end_date : vend_date}});
                Ext.apply(fgrid_bar.store.baseParams, fgrid_store.lastOptions.params);
//            }
//        });
        if (exportar) {
            Ext.Ajax.request({
                url : BASE_URL + 'admin/listAll',
                method: 'post',
                params: {
                    start_date : vstart_date,
                    end_date : vend_date,
                    rexport: '1'
                },
                success: function (data) {
                    window.open(BASE_URL + 'admin/forcedownloadCsv?uri=' + decodeURIComponent(data.responseText), '_blank');
                }
            });
            //window.open(BASE_URL+'admin/listAll?csv=1&','_blank');
        }
        
//            fgrid.store.loadData(obj);
//            fgrid_store.reload({params:{start_date: vstart_date}});
//            Ext.apply(fgrid_bar.store.baseParams, fgrid_store.lastOptions.params);
    }
  }

  
  function editarFila(grid, rowIndex, e){
      
      var sm = Grid_<?=$nom_tabla?>.getSelectionModel();
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
      
            var ilId = Grid_<?=$nom_tabla?>.getSelectionModel().getSelected().data.id;
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
        
        
        function verFila(grid, rowIndex, e){
      
      var sm = Grid_<?=$nom_tabla?>.getSelectionModel();
            var sel = sm.getSelections();

            if(sel.length > 1){
                Ext.Msg.show({   
                                title: 'Edición de Registro',
                                msg: 'Solo puede escoger un registro para ver',
                                buttons: Ext.Msg.OK,
                                icon: Ext.MessageBox.ERROR,
                                minWidth: 300
                            }); 
            } else {
      
            var ilId = Grid_<?=$nom_tabla?>.getSelectionModel().getSelected().data.id;
            //console.log(ilId);
            Ext.Ajax.request({
                url: BASE_URL+'admin/form',
                method: 'GET',
                params:{id:ilId, action:'ver'},
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
        
  
       function clonaUser(){
              
              var sm = Grid_<?=$nom_tabla?>.getSelectionModel();
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
                                                        GridStore_<?=$nom_tabla?>.load();
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
					var sm = Grid_<?=$nom_tabla?>.getSelectionModel();
					var sel = sm.getSelections();
					var data = '';
					for (i = 0; i<sel.length; i++) {
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
                                                    GridStore_<?=$nom_tabla?>.load();
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

        function newFila(){
        //console.log(ilId);
         Ext.Ajax.request({
             url: BASE_URL+'admin/form',
             method: 'GET',
             params:{tabla:'<?=$nom_tabla?>'},
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
    
<?php
    if($replace == 'window'){
        $wdata['w_item'] = 'Grid_'.$nom_tabla;
        $this->load->view('generals/window.js.php', $wdata);
    } else {
        echo $replace;
    }
?>
	
<?php (empty($scriptTags)) ? '</script>' : FALSE ?>