var form_cambia_pass = new Ext.FormPanel({
	labelWidth:     '500',
	buttonAlign:    'center',
	frame:		true,
        autoScroll:     true,
	autoWidth:      true,        
	width: 		'100%',
	items: 		[{ 
                        xtype:'textfield', 
                        id:'pass',
                        fieldLabel:'Contrase&ntilde;a',
                        name:'pass',
                        width: 200,
                        disabled:   false,
                        hidden:     false,
                        readOnly:  false,                               
                        allowBlank:false,
                        blankText:  'El campo Contrase&ntilde;a es obligatorio',
                        inputType: 'password',
                        listeners: {
                                    render: function(c) {                                      
                                            new Ext.ToolTip({
                                                target: c.getEl(),
                                                anchor: 'left',
                                                trackMouse: false,
                                                html: 'Ingrese su nueva contrase&ntilde;a'
                                            });
                                        },
                                      }
                        },{ 
                        xtype:'textfield', 
                        id:'passconf',
                        fieldLabel:'Confirmar Contrase&ntilde;a',
                        name:'passconf',
                        width: 200,
                        disabled:   false,
                        hidden:     false,
                        readOnly:  false,                               
                        allowBlank:false,
                        blankText:  'El campo Confirmar Contrase&ntilde;a es obligatorio',
                        inputType: 'password',
                        listeners: {
                                    render: function(c) {                                      
                                            new Ext.ToolTip({
                                                target: c.getEl(),
                                                anchor: 'left',
                                                trackMouse: false,
                                                html: 'Repita la nueva contrase&ntilde;a'
                                            });
                                        },
                                      }
                        }],
	border:         false,
        bodyStyle:      {paddingTop: '10px', paddingLeft: '35px' },
        style:          {margin: 'auto'},
        defaults:       {
                        labelStyle: 'font-weight: bold;',
                        style:      { margin: '0 0  5px 0', padding: '2px' }
                        },
        buttons: [{
                        id:'bot_cambia_pass',
                        text:'Cambiar Contrase&ntilde;a',
                        icon: BASE_ICONS + 'save.gif',
                        type:'submit',
                        standardSubmit:true,
                        handler:function (btn){
                        if(form_cambia_pass.getForm().isValid())
                        {
                            form_cambia_pass.getForm().submit({
                                url: BASE_URL + 'admin/cambiaPass',
                                method:'POST',
                                success: function(form_cambia_pass, action)
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
                                    winpass.hide();
                                    winpass.destroy();
                                    btn.enable();
                                },
                                failure: function(form_cambia_pass, action){
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
                    id:'buttonCancelar_form_cambia_pass',
                    text:'Limpiar',
                    formBind:true,
                    icon:BASE_ICONS + 'broom-minus-icon.png',
                    itemCls:'centrado',
                    handler:function(){ form_cambia_pass.getForm().reset();}
                }]
        });



var winpass = new Ext.Window({
                        id:         'winpass',
                        shadow:     true,
                        title:      'Cambiar Contrase&ntilde;a',
                        collapsible:true,
                        maximizable:true,
                        width:      450,
                        height:     180,
                        layout:     'fit',
                        modal:      true,
                        autoScroll: true,
                        overflow:   'auto',
                        plain:      true,
                        bodyStyle:  'padding:5px;',
                        buttonAlign:'center',
                        closeAction:'destroy',
                        items:      form_cambia_pass
                });
			
winpass.show();