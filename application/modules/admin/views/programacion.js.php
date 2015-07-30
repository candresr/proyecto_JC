
    Ext.QuickTips.init();

    var Employee = Ext.data.Record.create([
    {
        name: 'id',
        type: 'int'
    },{
        name: 'id_prog',
        type: 'int'
    },
    {
        name: 'titulo',
        type: 'string'
    }, {
        name: 'enlace',
        type: 'string'
    }
    ]);


              
                
  var store = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            fields: Employee
            }),
            
            data: <?=$data?>,
            sortInfo: {field: 'titulo', direction: 'ASC'}
    });

    var editor = new Ext.ux.grid.RowEditor({
                            saveText:   'Actualizar',
                            cancelText: 'Cancelar',
                            listeners: {
                                    afteredit: function(object, changes, r, rowIndex){
                                       var registro= Ext.encode(r.data);
                                       Ext.Ajax.request({
						url: BASE_URL + 'admin/updateGrid',
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

    var winCapGrid = new Ext.grid.GridPanel({
        store: store,
        width: 600,
        region:'center',
        margins: '0 5 5 5',

        plugins: [editor],
        view: new Ext.grid.GroupingView({
            markDirty: false
        }),
        tbar: [{
            iconCls: 'icon_insertar',
            text: 'Agregar Capitulo',
            handler: function(){
                var e = new Employee({
                    id: '',
                    titulo: 'Nuevo Capitulo',
                    enlace: 'www.enlace.com',
                    id_prog: <?=$progId?>
                });
                editor.stopEditing();
                store.insert(0, e);
                winCapGrid.getView().refresh();
                winCapGrid.getSelectionModel().selectRow(0);
                editor.startEditing(0);
            }
        },{
            ref: '../removeBtn',
            iconCls: 'icon_borrar',
            text: 'Borrar Capitulo',
            disabled: true,
            handler: function(){
                editor.stopEditing();
                var s = grid.getSelectionModel().getSelections();
                for(var i = 0, r; r = s[i]; i++){
                    store.remove(r);
                }
            }
        }],

        columns: [
        new Ext.grid.RowNumberer(),
        {
            id: 'titulo',
            header: 'Titulo',
            dataIndex: 'titulo',
            width: 250,
            sortable: true,
            editor: {
                xtype: 'textfield',
                allowBlank: false
            }
        },{
            header: 'Enlace',
            dataIndex: 'enlace',
            width: 300,
            sortable: true,
            editor: {
                xtype: 'textfield',
                allowBlank: false
            }
         }
        ]
    });

    winCapGrid.getSelectionModel().on('selectionchange', function(sm){
        winCapGrid.removeBtn.setDisabled(sm.getCount() < 1);
    });

   
    var wGrid = new Ext.Window({
                id: 'wGrid',
                shadow: true,
                title: 'Ventana de Enlaces',
                collapsible: true,
                maximizable: true,
                width: 660,
                height: 545,
                layout: 'fit',
                modal:true,
                autoScroll: true,
                overflow:'auto',
                plain: true,
                bodyStyle: 'padding:3px;',
                buttonAlign: 'center',
                closeAction:'destroy',
		frame:true,
                items: winCapGrid
        });

        wGrid.show();


