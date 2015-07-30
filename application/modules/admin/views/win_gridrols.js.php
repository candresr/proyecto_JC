Ext.QuickTips.init();

var RolGridStore  = new Ext.data.JsonStore({
                            totalProperty:  'totalRows',
                            root:           'rowset',
                            fields:         <?=$fields?>,
                            data:           <?=$data?>,
                            sortInfo:       {field: 'nom_tabla', direction: 'ASC'},
                            proxy: new Ext.data.HttpProxy({
                                    url:    BASE_URL+'admin/winRols',
                                    method: 'POST'
                                })
                        });
              
                
                
Ext.StoreMgr.register(RolGridStore);

var editor = new Ext.ux.grid.RowEditor({
                            saveText:   'Actualizar',
                            cancelText: 'Cancelar',
                            listeners: {
                                    afteredit: function(object, changes, r, rowIndex){
                                       var registro = Ext.encode(r.data);
                                       Ext.Ajax.request({
						url: BASE_URL + 'admin/updateRols',
                                                method: 'POST',
						params: {
                                                    registro:registro
                                                },
						success: function(response)
                                                {
//                                                    var obj = Ext.util.JSON.decode(response.responseText);
//                                                    Ext.Msg.show({
//                                                        title: obj.titulo,
//                                                        msg: obj.msj,
//                                                        buttons: Ext.Msg.OK,
//                                                        icon: Ext.MessageBox.INFO,
//                                                        minWidth: 300
//                                                    })
                                                    myMenuItemsData.reload();
                                                },
                                                failure: function(response){
                                                    var obj = Ext.util.JSON.decode(response.responseText);
                                                    Ext.Msg.show({   
                                                        title: 'Error!',
                                                        msg: 'Error en la Peticion al Servidor',
                                                        buttons: Ext.Msg.OK,
                                                        icon: Ext.MessageBox.ERROR,
                                                        minWidth: 300
                                                    });
                                                }
					})
                                    }
                                }
                        });


var RolGrid = new Ext.grid.GridPanel({
                            id:         'RolGrid',
                            layout:     'anchor',
                            autoScroll: true,
                            store:      RolGridStore, 
                            loadMask:   true,
                            plugins:    [editor],                            
                            columns:    <?=$columns?>
                        })     
          
var winrol = new Ext.Window({
                            id:         'winrol',
                            shadow:     true,
                            title:      'Definir Roles sobre tablas',
                            collapsible:true,
                            maximizable:true,
                            width:      600,
                            height:     300,
                            layout:     'fit',
                            modal:      true,
                            autoScroll: true,
                            overflow:   'auto',
                            plain:      true,
                            bodyStyle:  'padding:5px;',
                            buttonAlign:'center',
                            closeAction:'destroy',
                            items:      RolGrid
                        });
			
winrol.show();