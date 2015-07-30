Ext.QuickTips.init();

var fSubir_<?=$nom_tabla?> = new Ext.FormPanel({
            id: 'fUpload_<?=$nom_tabla?>',
            fileUpload: true,
            width: '100%',
            frame: true,
            //title: 'Formulario de subir archivos',
            autoHeight: true,
            bodyStyle: 'padding: 10px 10px 0 10px;',
            labelWidth: 50,
            defaults: {
                anchor: '90%', 
                allowBlank: false,
                msgTarget: 'side'
            },
            items: [{
                xtype: 'fileuploadfield',
                id: 'userfile_<?=$nom_tabla?>',
                emptyText: 'Select an image',
                fieldLabel: 'Archivo',
                name: 'userfile',
                allowBlank: false,
                blankText:  'El campo Archivo es obligatorio',
                emptyText:  'Archivo a subir',
                vtype:'alpha_dash',
                listeners: {
                            render: function(c) {                                      
                                    new Ext.ToolTip({
                                        target: c.getEl(),
                                        anchor: 'left',
                                        trackMouse: true,
                                        html: 'El campo Archivo es obligatorio, solo debe contener caracteres alfanumericos, las extensiones de archivo permitida son .jpg, .png, ,gif'
                                    });
                                },
                              },
                vtype:      '',	
                buttonText: '',
                buttonCfg: {
                    iconCls: 'upload-icon'
                }
            }
        ],
            buttons: [{
                text: 'Subir Archivo',
                icon: BASE_ICONS + 'add.png',
                handler: function(){                            
                    if(fSubir_<?=$nom_tabla?>.getForm().isValid()){
                        fSubir_<?=$nom_tabla?>.getForm().submit({
                            url:    BASE_URL + 'admin/subeArch',
                            method: 'POST',
                            waitMsg:'Subiendo Archivo ...',
                                success: function(fSubir_<?=$nom_tabla?>, action){                                 
                                var obj = Ext.util.JSON.decode(action.response.responseText);            
                                    Ext.Msg.show({   
                                                    title: obj.response.title,
                                                    msg: obj.response.msg,
                                                    buttons: Ext.Msg.OK,
                                                    icon: Ext.MessageBox.INFO,
                                                    minWidth: 300
                                                    });
                                    wSubir_<?=$nom_tabla?>.hide();
                                    wSubir_<?=$nom_tabla?>.destroy();
                                    Ext.getCmp('<?=$nom_tabla?>.<?=$campo?>').setValue(obj.response.file_name);
                                    
                                    
                                },
                                failure: function(fSubir_<?=$nom_tabla?>, action){     
                                var obj = Ext.util.JSON.decode(action.response.responseText);
                                    Ext.Msg.show({   
                                                    title: 'Error!',
                                                    msg: 'Error en la Peticion al Servidor',
                                                    buttons: Ext.Msg.OK,
                                                    icon: Ext.MessageBox.ERROR,
                                                    minWidth: 300
                                                    });					
                                }
                        });
                    }
                }
            },{
                text: 'Limpiar',
                icon: BASE_ICONS + 'broom-minus-icon.png',
                handler: function(){
                    fSubir_<?=$nom_tabla?>.getForm().reset();
                }
            }]
    });
  
var wSubir_<?=$nom_tabla?> = new Ext.Window({
                id: 'wSubirArchivo_<?=$nom_tabla?>',
                shadow: true,
                title: 'Ventana de Archivos',
                collapsible: true,
                maximizable: true,
                width: 440,
                height: 125,
                layout: 'fit',
                modal:true,
                autoScroll: true,
                overflow:'auto',
                plain: true,
                bodyStyle: 'padding:3px;',
                buttonAlign: 'center',
                closeAction:'destroy',
		frame:true,
                items: fSubir_<?=$nom_tabla?>
        });

        wSubir_<?=$nom_tabla?>.show();