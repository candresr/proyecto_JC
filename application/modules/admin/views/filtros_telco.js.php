<script type="text/javascript">
var form_filtros_maho = new Ext.FormPanel({
	<!--labelWidth:     110,-->
        labelAlign:     'top',
	buttonAlign:    'center',
	frame:		true,
        autoScroll:     true,
	//autoHeight:     true,
	autoWidth:     true,        
	width: 		'100%',
        //height:         500,
        border:         false,
        bodyStyle:      {paddingTop: '10px', paddingLeft: '35px' },
        style:          {margin: 'auto'},
        defaults:       {
                        labelStyle: 'font-weight: bold;',
                        style:      { margin: '0 0  5px 0', padding: '2px' }
                        },
                        
                        
                        
                        
                        
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
						width: 130,
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
                            },
                        select: function (combo, record){
                               Ext.Ajax.request({
                                       url: BASE_URL+'admin/asignacionComboStore',
                                       method: 'GET',
                                       params:  { a_consultar:record.get('valor') },
                                       success: function(response){
                                          var obj = Ext.util.JSON.decode(response.responseText);
                                          Ext.getCmp('combo_producto').store.loadData(obj);
                                          Ext.getCmp('combo_producto').clearValue();
                                          Ext.getCmp('combo_subproducto').clearValue();

                                       }
                                   })
                               }
                         },                                      
                        mode:           'local',
                        triggerAction:  'all',
                        blankText:      'El campo Cliente es obligatorio',
                        displayField:   'categoria',
                        valueField:     'valor',
                        store:          new Ext.data.JsonStore({
                                            fields: ['valor','categoria'],
                                            data: [
                                                {"valor":"cantv","categoria":"CANTV"},
                                                {"valor":"movilnet","categoria":"MOVILNET"},
                                                {"valor":"cantvnet","categoria":"CANTV.NET"}
                                            ]
                                        })
                        },{ 
                        xtype:          'combo', 
                        id:             'combo_producto',
                        name:           'combo_producto',
                        hiddenName:     'combo_producto',
                        fieldLabel:     'Producto',
                        autoWidth:      true,
                        editable:       false,
                        emptyText:      'Haga su selección',
                        disabled:       false,
                        hidden:         false,
						width: 130,
                        readOnly:       false,                               
                        allowBlank:     false,
                        listeners:      {
                                        render: function(c) {                                      
                                                new Ext.ToolTip({
                                                    target: c.getEl(),
                                                    anchor: 'left',
                                                    trackMouse: false,
                                                    html: 'Escoja Producto'
                                                });
                                            },
                                            select: function (combo, record){
                                                Ext.Ajax.request({
                                                        url: BASE_URL+'admin/asignacionComboStore',
                                                        method: 'GET',
                                                        params:  { a_consultar:record.get('valor') },
                                                        success: function(response){
                                                           var obj = Ext.util.JSON.decode(response.responseText);
                                                           Ext.getCmp('combo_subproducto').store.loadData(obj);
                                                           Ext.getCmp('combo_subproducto').clearValue();                                                                            
                                                        }
                                                    })
                                                }
                                        },                                      
                        mode:           'local',
                        triggerAction:  'all',
                        blankText:      'El campo Producto es obligatorio',
                        displayField:   'categoria',
                        valueField:     'valor',                        
                        store:          new Ext.data.JsonStore({
                                                 fields: ['valor','categoria']
                                        })                        
                        },{ 
                        xtype:          'combo', 
                        id:             'combo_subproducto',
                        name:           'combo_subproducto',
                        hiddenName:     'combo_subproducto',
                        fieldLabel:     'Sub-producto',
                        autoWidth:      true,
                        editable:       false,
						width: 130,
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
                                                    html: 'Escoja Sub-producto'
                                                });
                                            }
                                        },                                      
                        mode:           'local',
                        triggerAction:  'all',
                        blankText:      'El campo Sub-producto es obligatorio',
                        displayField:   'categoria',
                        valueField:     'valor',                
                        store:          new Ext.data.JsonStore({
                                                 fields: ['valor','categoria']
                                        })         
                        }, { 
						xtype:'datefield', 
						fieldLabel:'Fecha Inicio',
						name:'f_ini',
						width: 100,
						value: '2013-01-23',
						disabled:   false,
						hidden:     false,
						readOnly:  false,	                                                
                                                listeners: {
                                            render: function(c) {                                      
                                                    new Ext.ToolTip({
                                                        target: c.getEl(),
                                                        dismissDelay: 0,
                                                        showDelay: 0,
                                                        boxMinHeight: 400,
                                                        boxMinWidth: 400,
                                                        anchor: 'left',
                                                        trackMouse: false,
                                                        html: 'ayuda'
                                                    });
                                                }
                                              },
						format: 'Y-m-d'
                                                
					},{ 
						xtype:'datefield', 
						fieldLabel:'Fecha de Fin',
						name:'f_fin',
						width: 100,
						value: '2013-01-31',
						disabled:   false,
						hidden:     false,
						readOnly:  false,	                                                
                                                listeners: {
                                            render: function(c) {                                      
                                                    new Ext.ToolTip({
                                                        target: c.getEl(),
                                                        dismissDelay: 0,
                                                        showDelay: 0,
                                                        boxMinHeight: 400,
                                                        boxMinWidth: 400,
                                                        anchor: 'left',
                                                        trackMouse: false,
                                                        html: 'Fecha en que finaliza la Asignación, dependiendo del del tipo de cliente '
                                                    });
                                                }
                                              },
						format: 'Y-m-d'
                                                
					},{ 
                                            xtype:'textfield', 
                                            id:'monto_desde',
                                            fieldLabel:'Monto Desde',
                                            name:'monto_desde',
                                            width: 100,
                                            value: '0',
                                            disabled:   false,
                                            hidden:     false,
                                            readOnly:  false,    
                                            listeners: {
                                                render: function(c) {                                      
                                                        new Ext.ToolTip({
                                                            target: c.getEl(),
                                                            dismissDelay: 0,
                                                            showDelay: 0,
                                                            boxMinHeight: 400,
                                                            boxMinWidth: 400,
                                                            anchor: 'left',
                                                            trackMouse: false,
                                                            html: 'Ingrese un monto minimo, el valor por defecto es 0'
                                                        });
                                                    }
                                                  }                                                  
                                            },{ 
                                            xtype:'textfield', 
                                            id:'monto_hasta',
                                            fieldLabel:'Monto Hasta',
                                            name:'monto_hasta',
                                            width: 100,
                                            value: '1000000',
                                            disabled:   false,
                                            hidden:     false,
                                            readOnly:  false,
                                            listeners: {
                                                        render: function(c) {                                      
                                                                new Ext.ToolTip({
                                                                    target: c.getEl(),
                                                                    dismissDelay: 0,
                                                                    showDelay: 0,
                                                                    boxMinHeight: 400,
                                                                    boxMinWidth: 400,
                                                                    anchor: 'left',
                                                                    trackMouse: false,
                                                                    html: 'Ingrese el monto maximo, el valor por defecto 1000000'
                                                                });
                                                            }
                                                          }
                                                        }
        
        ],
	
        buttons: [{
                    id:'btnFiltrar_form_filtros_maho',
                    text:'Filtrar',
                    icon: BASE_ICONS + 'save.gif',
                    type:'submit',
                    standardSubmit:true,
                    handler:function (btn){
                        if(form_filtros_maho.getForm().isValid())
                        {
                            form_filtros_maho.getForm().submit({
                                url: BASE_URL + 'admin/procesaForm',
                                method:'POST',
                                success: function(form_filtros_maho, action)
                                {
                                    var obj = Ext.util.JSON.decode(action.response.responseText);
                                    //console.log(obj);
                                        Ext.Msg.show({
                                        title: obj.titulo,
                                        msg: obj.msj,
                                        buttons: Ext.Msg.OK,
                                        icon: Ext.MessageBox.INFO,
                                        minWidth: 300
                                    }),
                                    
                                    GridStore_maho_segmentacion.load();
                                    
                                    btn.enable();
                                },
                                failure: function(form_filtros_maho, action){
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
                           }else {
                    Ext.Msg.show({   
                        title: 'Error de Validaci&oacute;n',
                        msg: 'Debe llenar los campos que se indican como obligatorios',
                        buttons: Ext.Msg.OK,
                        icon: Ext.MessageBox.WARNING,
                        minWidth: 300
                    });
                    btn.enable();
                }
                     } 
                     
                },{
                    text: 'Exportar CSV',
                    disabled: false,
                    id: 'btnExporCsv',
                    icon: BASE_ICONS + 'page_green.png',
                    handler: exportCsv
                },{
                    text: 'Exportar XLS',
                    disabled: false,
                    id: 'btnExporXls',
                    icon: BASE_ICONS + 'page_red.png',
                    handler: exportXls
                },{
                    text: 'Generar Campaña',
                    disabled: false,
                    id: 'btnGenerCampaing',
                    icon: BASE_ICONS + 'page_white_stack.png',
                    handler: GenerateCampaing
                },{
                    id:'btnCancelar_form_filtros_maho',
                    text:'Limpiar',
                    formBind:true,
                    icon:BASE_ICONS + 'broom-minus-icon.png',
                    itemCls:'centrado',
                    handler:function(){ form_filtros_maho.getForm().reset();}
                }]
        });


        
        
    function exportCsv(){
        
    }
    
    function exportXls(){
        
    }
    
    function GenerateCampaing(){
        
    }


var w_filtros_maho = new Ext.Window({
	id: 'wf_filtros_maho',
	shadow: true,
	title: 'Filtros Segmetador MAHO',
	collapsible: true,
	maximizable: true,
        width: 500,
	height: 550,
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
	items: form_filtros_maho,
});
			
w_filtros_maho.show();