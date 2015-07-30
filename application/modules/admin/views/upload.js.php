
Ext.QuickTips.init();
var msg = function(title, msg){
        Ext.Msg.show({
            title: title,
            msg: msg,
            minWidth: 200,
            modal: true,
            icon: Ext.Msg.INFO,
            buttons: Ext.Msg.OK
        });
    };  

var store_<?=$galId?> = new Ext.data.Store({
            reader: new Ext.data.JsonReader({
                root:'images', 
                totalProperty: 'result'
                }, 
                [ 'name', 'thumb_url', 'id' ]
            ),	
            proxy: new Ext.data.HttpProxy({
                url: BASE_URL + 'admin/listAllFile',
                method: 'GET'
            })
        });

        store_<?=$galId?>.load();
        
        var tpl = new Ext.XTemplate(
            '<tpl for=".">',
                '<div class="thumb-wrap" id="{name}">',
                '<div class="thumb"><img src="{thumb_url}" title="{name}"></a></div>',
                    '<span class="x-editable">',
                        '{name} <br>',
                        '<img id="{id}" name="edi" src="<?=base_url()?>assets/img/icons/pencil.png" title="Editar">', 
                        '<img id="{id}" name="eli" src="<?=base_url()?>assets/img/icons/cancel.png" title="Eliminar">',
                    '</span></div>',
            '</tpl>',
            '<div class="x-clear"></div>'
        );
            
        
        

var pagingbar_<?=$galId?> = new Ext.PagingToolbar({
                style:          'border:1px solid #99BBE8;',
                store:          store_<?=$galId?>,
                pageSize:       10,
                displayInfo:    true
	});

           

var datav_<?=$galId?> = new Ext.DataView({
            autoScroll:     true, 
            store:          store_<?=$galId?>, 
            tpl:            tpl,
            id:             'datav_<?=$galId?>',
            autoHeight:     false, 
            height:         290, 
            multiSelect:    true,
            overClass:      'x-view-over', 
            itemSelector:   'div.thumb-wrap',
            emptyText:      'No hay imágenes que mostrar',
            style:          'border:1px solid #99BBE8; border-top-width: 0;',
            listeners:{
				click: function(dataView, index, node, e ){
					var target = e.getTarget();
					if(target.name == "det"){
                                                detalleArchivo(target.id);
					} 
                                        if(target.name == "edi"){
                                                editarArchivo(target.id);
                                        } 
                                        if(target.name == "eli"){
                                                Ext.MessageBox.buttonText.yes = "Si";
                                                Ext.Msg.show({
                                                        title: 'Confirmar Eliminar',
                                                        msg: 'Esta seguro de Eliminar este archivo',
                                                        buttons: Ext.Msg.YESNO,
                                                        fn: function(btn){
                                                                if(btn=='yes') eliminarArchivo(target.id);
                                                        },
                                                        minWidth: 300,
                                                        icon: Ext.MessageBox.QUESTION
                                                });
                                        }
				}
			}
        })        


 
function editarArchivo(id){
    Ext.Ajax.request({
                    url: BASE_URL + 'admin/fileEdit',
                    method: 'GET',
                    params: {id:id},
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
}

function eliminarArchivo(id){
    Ext.Ajax.request({
                    url: BASE_URL + 'admin/eliminarArchivo',
                    method: 'GET',
                    params: {id:id,id_gal:<?=$galId?>},
                    success: function(action, request) {
                    var obj = Ext.util.JSON.decode(action.responseText);
                            Ext.Msg.show({
                                            title: obj.title,
                                            msg: obj.msg,
                                            minWidth: 200,
                                            modal: true,
                                            icon: Ext.Msg.INFO,
                                            buttons: Ext.Msg.OK
                                        }); 
                            store_<?=$galId?>.reload();         
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
}
        
var pFile_<?=$galId?> = new Ext.Panel({
                id:         'images-view',
                frame:      true,
                height:     200,
                autoHeight: true,
                layout:     'auto',
		title:      'Listado de Archivos',
                items:      [pagingbar_<?=$galId?>,datav_<?=$galId?>]
	}); 


var fUpload_<?=$galId?> = new Ext.FormPanel({
            id: 'fUpload_<?=$galId?>',
            fileUpload: true,
            width: '100%',
            frame: true,
            title: 'Formulario de subir archivos',
            autoHeight: true,
            bodyStyle: 'padding: 10px 10px 0 10px;',
            labelWidth: 50,
            defaults: {
                anchor: '90%', 
                allowBlank: false,
                msgTarget: 'side'
            },
            items: [{
                xtype:'hidden', 
		id:'galId<?=$galId?>',
                name:'galId',
                value:'<?=$galId?>'
            }, {
                xtype: 'textfield',
                fieldLabel: 'Titulo',
                id: 'title_file_<?=$galId?>',
                name: 'title_file',
                allowBlank: false,
                blankText:  'El campo Titulo es obligatorio',
                emptyText:  'Titulo del Archivo',
                listeners: {
                            render: function(c) {                                      
                                    new Ext.ToolTip({
                                        target: c.getEl(),
                                        anchor: 'left',
                                        trackMouse: true,
                                        html: 'El campo Titulo es obligatorio, solo debe contener caracteres alfanumericos y espacios'
                                    });
                                },
                              },
                vtype:      ''
            },{
                xtype: 'fileuploadfield',
                id: 'userfile_<?=$galId?>',
                emptyText: 'Select an image',
                fieldLabel: 'Archivo',
                name: 'userfile',
                allowBlank: false,
                blankText:  'El campo Archivo es obligatorio',
                emptyText:  'Archivo a subir',
                listeners: {
                            render: function(c) {                                      
                                    new Ext.ToolTip({
                                        target: c.getEl(),
                                        anchor: 'left',
                                        trackMouse: true,
                                        html: 'El campo Archivo es obligatorio, solo debe contener caracteres alfanumericos, las extensiones de archivo permitida son .jpg, .png, ,gif, .doc, .odt, .xls, .ods, .pdf, .txt, .rtf.'
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
                icon: '<?=  base_url()?>assets/img/icons/arrow_up.png',
                handler: function(){                            
                    if(fUpload_<?=$galId?>.getForm().isValid()){
                        fUpload_<?=$galId?>.getForm().submit({
                            url:    BASE_URL + 'admin/do_upload',
                            method: 'POST',
                            waitMsg:'Subiendo Archivo ...',
                                success: function(fUpload_<?=$galId?>, action){                                 
                                var obj = Ext.util.JSON.decode(action.response.responseText);            
                                    Ext.Msg.show({   
                                                    title: obj.response.title,
                                                    msg: obj.response.msg,
                                                    buttons: Ext.Msg.OK,
                                                    icon: Ext.MessageBox.INFO,
                                                    minWidth: 300
                                                    });
                                                    store_<?=$galId?>.reload();
                                },
                                failure: function(fUpload_<?=$galId?>, action){                                
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
                    fUpload_<?=$galId?>.getForm().reset();
                }
            }]
    });

  
  var pUpload_<?=$galId?> = new Ext.Panel({
                layout: 'border',
                id:     'pUpload_<?=$galId?>',
                frame:      true,
                height: '100%',
		title:  '',
		items: [{
                            region: 'north',
                            xtype: 'panel',
                            layout: 'fit',
                            autoHeight:true,
                            border: false,
                            margins: '0 0 5 0',
                            items: [pFile_<?=$galId?>]
                        },{            
                           region: 'center',
                            xtype: 'panel',
                            border: false,
                            layout: 'fit',
                            //autoHeight:true,
                            margins: '0 0 5 0',
                            items: [fUpload_<?=$galId?> ]
                        }]
	});
  
var wUpload_<?=$galId?> = new Ext.Window({
                id: 'wUpload_<?=$galId?>',
                shadow: true,
                title: 'Ventana de Archivos',
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
                items: pUpload_<?=$galId?>
        });

        wUpload_<?=$galId?>.show();