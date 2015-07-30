<script type="text/javascript">
Ext.onReady(function() {

    var formLogin = new Ext.FormPanel({
		frame: false, 
        border: false, 
        buttonAlign: 'center',
        url: BASE_URL + 'index.php/admin/verifica_login', 
        method: 'POST', 
        id: 'frmLogin',
		bodyStyle: 'padding: 8px 8px 8px 8px; background:#FFF;',
		width: 210, 
        labelAlign: 'top',
        labelWidth: 60,
		items: [{
			xtype: 'textfield',
			fieldLabel: 'Usuario',
            labelStyle: 'font-weight:bold; color:#666',
			name: 'login',
			id: 'login',
            blankText:  'El campo Usuario es obligatorio',
//          emptyText:   'Usuario',
			allowBlank: false,
            width: 175,
            height: 25,
            listeners: {
                        render: function(c) {                                      
                                new Ext.ToolTip({
                                    target: c.getEl(),
                                    anchor: 'left',
                                    trackMouse: false,
                                    html: 'Debe colocar en este espacio su usuario, debe contener solo caracteres alfanumericos'
                                });
                            }
                          }
		}, {
			xtype: 'textfield',
//          emptyText:   'Contrase&ntilde;a',
			fieldLabel: 'Contrase&ntilde;a',
            labelStyle: 'font-weight:bold; color:#666',
			name: 'password',
            id: 'logPassword',
            width: 175,
            height: 25,
			allowBlank: false,
            hideLabel : false,
            blankText:  'El campo Contrase&ntilde;a es obligatorio',
			inputType: 'password',
            listeners: {
                        render: function(c) {                                      
                                new Ext.ToolTip({
                                    target: c.getEl(),
                                    anchor: 'left',
                                    trackMouse: false,
                                    html: 'Debe colocar en este espacio su contrase&ntilde;a, debe contener solo caracteres alfanumericos'
                                });
                            }
                          }
		}],
		buttons: [{ 
                    text: 'Entrar', 
                    width: 80,
                    height:25,
                    icon: BASE_ICONS + 'accept.png', 
                    handler: fnLogin 
                },{ 
                    text: 'Borrar', 
                    width: 80,
                    height:25, 
                    icon: BASE_ICONS + 'minus-circle.png', 
                    handler: function() {
                                    formLogin.getForm().reset();
                            }
                }],
        keys: [{ key: [Ext.EventObject.ENTER], handler: fnLogin}]
	});

    function fnLogin() {
        Ext.getCmp('frmLogin').on({
            beforeaction: function() {
                if (formLogin.getForm().isValid()) {
                    Ext.getCmp('winLogin').body.mask();
                    Ext.getCmp('sbWinLogin').showBusy();
                } else {
                    Ext.Msg.alert('Errores de Validacion', 'Debe completar los campos remarcados en rojo')
                }
            }
        });
        formLogin.getForm().submit({
           success: function(form, action) {
               Ext.getCmp('winLogin').body.unmask();			   
			   var obj = Ext.util.JSON.decode(action.response.responseText);
			  			   
			    if (obj.situacion.de_error == 'no_valido') {
                                 Ext.getCmp('sbWinLogin').setStatus({
                                    text: obj.errors.reason,
                                    iconCls: 'x-status-error'
                                });
                                Ext.Msg.alert('Errores de Validacion', obj.errors.reason)
			    } else if(obj.situacion.de_error == 'directo'){					
					window.location = BASE_URL + 'admin';
           		}
		   },
           failure: function(form, action) {
               Ext.getCmp('winLogin').body.unmask();
               if (action.failureType == 'server') {
                    obj = Ext.util.JSON.decode(action.response.responseText);
                    Ext.getCmp('sbWinLogin').setStatus({
                        text: obj.errors.reason,
                        iconCls: 'x-status-error'
                    });
                } else {
                    if (formLogin.getForm().isValid()) {
                        Ext.getCmp('sbWinLogin').setStatus({
                            text: 'Imposible contactar al servidor',
                            iconCls: 'x-status-error'
                        });
                    } else {
						
                        Ext.getCmp('sbWinLogin').setStatus({
                            text: 'Error en el formulario !',
                            iconCls: 'x-status-error'
                        });
                    }
                }
           }
        });
    }

	var aux = new Ext.Panel({
//		layout:'column',
		border:false,
		bodyStyle: 'background:#FFFFFF;',
		items: [{
    			 border:false,
    			 html:'<div align="center" style="background-color:#FFF; padding-top:10px; padding-bottom:10px;"><img src="'+BASE_URL+'assets/img/login_logo.png"/></div>'
                }, formLogin
                ]
	});


	var winLogin = new Ext.Window({
		title: NOM_SITIO,
        shadow: true,
        layout: 'fit',
        id: 'winLogin',
		width: 220,
		height: 305,
		y: 150,
		resizable: false,
		closable: false,
		items: [aux],
                bbar:   new Ext.ux.StatusBar({
                            id: 'sbWinLogin'
                        })
	});

	winLogin.show();
	
});
</script>