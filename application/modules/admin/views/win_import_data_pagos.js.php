var productoStore = '';
var form_import_data = new Ext.FormPanel({
	labelWidth:     '50',
        fileUpload:     true,
	buttonAlign:    'center',
	frame:		true,
        autoScroll:     true,
	autoWidth:      true,        
	width: 		'100%',            
	items: 		[{ 
                        xtype:          'combo', 
                        id:             'combo_cliente',
                        name:           'combo_cliente',
                        hiddenName:     'combo_cliente',
                        fieldLabel:     'Cliente',
                        autoWidth:      true,
                        editable:       false,
                        emptyText:      'Haga su selección',
                        disabled:       false,
                        hidden:         false,
                        readOnly:       false,                               
                        allowBlank:     false,
                        listeners:      {
                                        render: function(c) {                                      
                                                new Ext.ToolTip({
                                                    target: c.getEl(),
                                                    anchor: 'left',
                                                    trackMouse: false,
                                                    html: 'Escoja Cliente'
                                                });
                                            }
                                        },                                      
                        mode:           'local',
                        triggerAction:  'all',
                        blankText:      'El campo Cliente es obligatorio',
                        displayField:   'categoria',
                        valueField:     'valor',
                        store:          new Ext.data.JsonStore({
                                            fields: <?=$losfields?>,
                                            data:   <?=$ladata?>
                                        })
                        },{
                            xtype:'hidden', 
                            id:'operacion',
                            name:'nom_tabla',
                            value:'pagos'
                        },{ 
                        xtype:'datefield', 
                        fieldLabel:'Fecha',
                        name:'f_ini',
                        id:'f_ini',
                        width: 100,
                        disabled:   false,
                        hidden:     false,
                        readOnly:  false,	
                        allowBlank: false,
                        blankText: 'La fecha es obligatoria',
                        value: new Date(),
                        format:         'Y-m-d',
                        listeners:      {
                                        render: function(c) {                                      
                                                new Ext.ToolTip({
                                                    target: c.getEl(),
                                                    anchor: 'left',
                                                    trackMouse: false,
                                                    html: 'Fecha del Pago'
                                                });
                                            }
                                            }
                        },{
                        xtype:          'fileuploadfield',
                        height:         '30px',
                        width:          '210px',
                        id:             'userfile',
                        emptyText:      'Seleccione un archivo csv',
                        fieldLabel:     'Archivo',
                        name:           'userfile',
                        allowBlank:     false,
                        blankText:      'El campo Archivo es obligatorio',
                        emptyText:      'Archivo a importar',
                        listeners:      {
                                        render: function(c) {                                      
                                                new Ext.ToolTip({
                                                    target: c.getEl(),
                                                    anchor: 'left',
                                                    trackMouse: false,
                                                    html: 'El campo Archivo es obligatorio.'
                                                });
                                            }
                                          },
                        vtype:          '',	
                        buttonText:     '',
                        buttonCfg:      { iconCls: 'upload-icon'}
                        }],
	border:         false,
        bodyStyle:      {paddingTop: '10px', paddingLeft: '10px', paddingRight: '60px' },
        style:          {margin: 'auto'},
        defaults:       {
                            labelStyle: 'font-weight: bold;',
                            style:      { margin: '0 0  5px 0', padding: '2px' }
                        },
        buttons:        [{
                        id:'bot_combo_proyect',
                        text:'Importar Data',
                        icon: BASE_ICONS + 'save.gif',
                        type:'submit',
                        standardSubmit:true,
                        handler:function (btn){
                            if(form_import_data.getForm().isValid())
                            {
                                form_import_data.getForm().submit({
                                    url: BASE_URL + 'admin/import_data',
                                    method:'POST',
                                    waitMsg:'Subiendo Archivo ...',
                                    success: function(form_import_data, action)
                                    {
                                        var obj = Ext.util.JSON.decode(action.response.responseText);   
                                        if(obj.response.valid == true){
                                            Ext.Msg.show({
                                                title:      obj.response.title,
                                                msg:        obj.response.msg,
                                                buttons:    Ext.Msg.OK,
                                                icon:       Ext.MessageBox.INFO,
                                                minWidth:   300
                                            });
                                            win_import_data_pagos.hide();
                                            win_import_data_pagos.destroy();   
                                            GridStore_pagos.load();
                                        } else {
                                            Ext.Msg.show({   
                                                title:      'Error!',
                                                msg:        obj.response.msg,
                                                buttons:    Ext.Msg.OK,
                                                icon:       Ext.MessageBox.ERROR,
                                                minWidth:   300
                                            });
                                        }
                                        btn.enable();
                                    },
                                    failure: function(form_import_data, action){
                                        Ext.Msg.show({   
                                            title: 'Error!',
                                            msg: 'Error en la Peticion al Servidor',
                                            buttons: Ext.Msg.OK,
                                            icon: Ext.MessageBox.ERROR,
                                            minWidth: 300
                                        });
                                        btn.enable();
                                    }
                                 })
                             }
                        } 
                },{
                    id:'buttonCancelar_form_import_data',
                    text:'Limpiar',
                    formBind:true,
                    icon:BASE_ICONS + 'broom-minus-icon.png',
                    itemCls:'centrado',
                    handler:function(){ form_import_data.getForm().reset();}
                }]
        });



var win_import_data_pagos = new Ext.Window({
                        id:         'importdatapagos',
                        shadow:     true,
                        title:      'Importar Data a version de Proyecto',
                        collapsible:true,
                        maximizable:true,
                        width:      450,
                        height:     230,
                        layout:     'fit',
                        modal:      true,
                        autoScroll: true,
                        overflow:   'auto',
                        plain:      true,
                        bodyStyle:  'padding:5px;',
                        buttonAlign:'center',
                        closeAction:'destroy',
                        items:      form_import_data
                });
			
win_import_data_pagos.show();