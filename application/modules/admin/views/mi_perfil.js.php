var form_<?=$nom_tabla?>= new Ext.FormPanel({
	labelWidth:     140,
	buttonAlign:    'center',
	frame:		true,
        title: 		'<?=$formTitle?>',
        autoScroll:     true,
	//autoHeight:     true,
	autoWidth:     true,        
	width: 		'100%',
        //height:         500,
	items: 		<?=$fields?>,
	border:         false,
        bodyStyle:      {paddingTop: '10px', paddingLeft: '35px' },
        style:          {margin: 'auto'},
        defaults:       {
                        labelStyle: 'font-weight: bold;',
                        style:      { margin: '0 0  5px 0', padding: '2px' }
                        },
        buttons: [{
                    id:'<?=$nom_tabla?>',
                    text:'Editar',
                    icon: BASE_ICONS + 'save.gif',
                    type:'submit',
                    standardSubmit:true,
                    handler:function (btn){
                        if(form_<?=$nom_tabla?>.getForm().isValid())
                        {
                            form_<?=$nom_tabla?>.getForm().submit({
                                url: BASE_URL + 'admin/procesaForm',
                                method:'POST',
                                success: function(form_<?=$nom_tabla?>, action)
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
                                failure: function(form_<?=$nom_tabla?>, action){
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
                    id:'buttonCancelar_form_<?=$nom_tabla?>',
                    text:'Limpiar',
                    formBind:true,
                    icon:BASE_ICONS + 'broom-minus-icon.png',
                    itemCls:'centrado',
                    handler:function(){ form_<?=$nom_tabla?>.getForm().reset();}
                }]
        });

        
    Ext.QuickTips.init(); 