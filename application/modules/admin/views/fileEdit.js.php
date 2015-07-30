var PanelHtml_<?=$idFile?> = new Ext.Panel({
                    layout:     'fit',
                    id:         'panel_<?=$idFile?>',
                    frame:      true,
                    autoHeight: true,
                    title:      '<?=$thumTitle?>',
                    html:       ['<?=$thumFile?>']

                    }); 

var formFile_<?=$idFile?> = new Ext.FormPanel({
	labelWidth:     100,
	buttonAlign:    'center',
	frame:		true,
        title: 		'<?=$formTitle?>',
        autoScroll:     true,
	//autoHeight:     true,
	autoWidth:     true,        
	width: 		'100%',
        //height:         500,
	items: 		<?=$formFile?>,
	border:         false,
        bodyStyle:      {paddingTop: '10px', paddingLeft: '35px' },
        style:          {margin: 'auto'},
        defaults:       {
                        labelStyle: 'font-weight: bold;',
                        style:      { margin: '0 0  5px 0', padding: '2px' }
                        },
        buttons: [{
                    id:'editFile_<?=$idFile?>',
                    text:'Editar',
                    icon: BASE_ICONS + 'save.gif',
                    type:'submit',
                    standardSubmit:true,
                    handler:function (btn){
                        if(formFile_<?=$idFile?>.getForm().isValid())
                        {
                            formFile_<?=$idFile?>.getForm().submit({
                                url: BASE_URL + 'admin/fileEdit',
                                method:'POST',
                                success: function(formFile_<?=$idFile?>, action)
                                {
                                    var obj = Ext.util.JSON.decode(action.response.responseText);
                                    //console.log(obj);
                                        Ext.Msg.show({
                                        title: obj.titulo,
                                        msg: obj.msj,
                                        buttons: Ext.Msg.OK,
                                        icon: Ext.MessageBox.INFO,
                                        minWidth: 300
                                    })
                                    
                                    btn.enable();
                                },
                                failure: function(formFile_<?=$idFile?>, action){
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
                    id:'buttonCancelar_formFile_<?=$idFile?>',
                    text:'Limpiar',
                    formBind:true,
                    icon:BASE_ICONS + 'broom-minus-icon.png',
                    itemCls:'centrado',
                    handler:function(){ formFile_<?=$idFile?>.getForm().reset();}
                },{
                    id:'buttonCerrar_formFile_<?=$idFile?>',
                    text:'Cerrar Ventana',
                    formBind:true,
                    icon:BASE_ICONS + 'cancel.png',
                    itemCls:'centrado',
                    handler:function(){ w_fileEdit<?=$idFile?>.hide(); w_fileEdit<?=$idFile?>.destroy();}
                }]
        });

var paneles_<?=$idFile?> = new Ext.Panel({
                    layout: 'border',
                    id:     'PanelHtml_<?=$idFile?>',
                    frame:      true,
                    height: '99%',
                    items: [{
                                region: 'west',
                                xtype: 'panel',
                                autoHeight: true,
                                border: false,
                                margins: '0 0 5 0',
                                items: [PanelHtml_<?=$idFile?>]
                            },{            
                                region: 'center',
                                xtype: 'panel',
                                autoHeight: true,
                                border: false,
                                margins: '0 0 5 0',
                                items: [formFile_<?=$idFile?>]
                            }]
                    });
                    
var w_fileEdit<?=$idFile?> = new Ext.Window({
	id: 'w_fileEdit<?=$idFile?>',
	shadow: true,
	title: 'Editar Datos de Archivo',
	collapsible: true,
	maximizable: true,
        width: 650,
	height: 400,
	minWidth: 300,
	minHeight: 200,
	layout: 'fit',
	modal:true,
	autoScroll: true,
	overflow:'auto',
	plain: true,
	bodyStyle: 'padding:5px;',
	buttonAlign: 'center',
        closeAction:'destroy',
	items:paneles_<?=$idFile?>,
});
			
w_fileEdit<?=$idFile?>.show();