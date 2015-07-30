var form_combo_proyect = new Ext.FormPanel({
	labelWidth:     '500',
	buttonAlign:    'center',
	frame:		true,
        autoScroll:     true,
	autoWidth:      true,        
	width: 		'100%',
	items: 		[{ 
                        xtype:          'combo', 
                        id:             'combo_proyect',
                        name:           'combo_proyect',
                        hiddenName:     'combo_proyect',
                        fieldLabel:     'Proyectos',
                        autoWidth:      true,
                        editable:       false,
                        emptyText:      'Haga su selección',
                        disabled:       false,
                        hidden:         false,
                        readOnly:       false,                               
                        allowBlank:     false,

                        listeners: {
                                    render: function(c) {                                      
                                            new Ext.ToolTip({
                                                target: c.getEl(),
                                                anchor: 'left',
                                                trackMouse: false,
                                                html: 'Escoja proyecto versionar'
                                            });
                                        }
                                   },                                      
                        mode:           'local',
                        triggerAction:  'all',
                        displayField:   'categoria',
                        valueField:     'valor',
                        store:     new Ext.data.SimpleStore({
                                        fields: <?=$losfields?>,
                                        data:   <?=$ladata?>
                                    })
                        }],
	border:         false,
        bodyStyle:      {paddingTop: '10px', paddingLeft: '35px' },
        style:          {margin: 'auto'},
        defaults:       {
                            labelStyle: 'font-weight: bold;',
                            style:      { margin: '0 0  5px 0', padding: '2px' }
                        },
        buttons: [{
                        id:'bot_combo_proyect',
                        text:'Seleccionar Proyecto',
                        icon: BASE_ICONS + 'save.gif',
                        type:'submit',
                        standardSubmit:true,
                        handler:function (btn){
                            if(form_combo_proyect.getForm().isValid())
                            {
                                form_combo_proyect.getForm().submit({
                                    url: BASE_URL + 'admin/genera_version',
                                    method:'POST',
                                    success: function(form_combo_proyect, action)
                                    {
                                        var obj = Ext.util.JSON.decode(action.response.responseText);
                                        if(obj.id == false){
                                            Ext.Msg.show({   
                                                        title: 'Imposible completar operación',
                                                        msg: obj.msg,
                                                        buttons: Ext.Msg.OK,
                                                        icon: Ext.MessageBox.ERROR,
                                                        minWidth: 300
                                                        }); 
                                        } else {                                        
                                            Ext.Ajax.request({
                                               url: BASE_URL+'admin/form',
                                               params:  { tabla:obj.tabla, id:obj.id }, 
                                               method: 'GET',
                                               success: function(response){eval(response.responseText); },
                                               failure: function(){ 
                                                        Ext.Msg.show({   
                                                                title: 'Error de peticion al servidor',
                                                                msg: 'No se pudo procesar la peticion de edicion',
                                                                buttons: Ext.Msg.OK,
                                                                icon: Ext.MessageBox.ERROR,
                                                                minWidth: 300
                                                            });              
                                                    }
                                            })
                                        }
                                        win_combo_proyect.hide();
                                        win_combo_proyect.destroy();
                                        btn.enable();
                                    },
                                    failure: function(form_combo_proyect, action){
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
                    id:'buttonCancelar_form_combo_proyect',
                    text:'Limpiar',
                    formBind:true,
                    icon:BASE_ICONS + 'broom-minus-icon.png',
                    itemCls:'centrado',
                    handler:function(){ form_combo_proyect.getForm().reset();}
                }]
        });



var win_combo_proyect = new Ext.Window({
                        id:         'winversion',
                        shadow:     true,
                        title:      'Escoja Proyecto a Versionar',
                        collapsible:true,
                        maximizable:true,
                        width:      450,
                        height:     140,
                        layout:     'fit',
                        modal:      true,
                        autoScroll: true,
                        overflow:   'auto',
                        plain:      true,
                        bodyStyle:  'padding:5px;',
                        buttonAlign:'center',
                        closeAction:'destroy',
                        items:      form_combo_proyect
                });
			
win_combo_proyect.show();