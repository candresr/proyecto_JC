<!--<script type="text/javascript">-->
var form_filtros_maho = new Ext.FormPanel({
	//labelWidth:     110,
	//autoHeight:     true,
        //height:         500,
        labelAlign:     'top',
	buttonAlign:    'center',
	frame:		true,
        autoScroll:     true,
        title:          'Filtros de Segmentación',
	autoWidth:     true,        
	width: 		'100%',
        border:         false,
        bodyStyle:      {paddingTop: '10px', paddingLeft: '15px' },
        style:          {margin: 'auto'},
        defaults:       {
                        labelStyle: 'font-weight: bold;',
                        style:      { margin: '0 0  5px 0', padding: '2px' }
                        },
        items: [{                
                layout:'column',
                items:  [{
                        columnWidth:.4,
                        layout: 'form',
                        items: [
                                { 
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
			width:          170,
                        readOnly:       false,                               
                        allowBlank:     true,
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
			width:          170,
                        readOnly:       false,                               
                        allowBlank:     true,
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
                        emptyText:      'Haga su selección',
                        disabled:       false,
                        hidden:         false,
			width:          170,
                        readOnly:       false,                               
                        allowBlank:     true,
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
                        },{ 
						xtype:'datefield', 
						fieldLabel:'Fecha Asignación Desde',
						name:'f_desde',
						width: 100,
						value: '',
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
						fieldLabel:'Fecha Asignación Hasta',
						name:'f_hasta',
						width: 100,
						value: '',
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
                                            value: '',
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
                                            value: '',
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
                                ]
                        },{
                        columnWidth:.6,
                        layout: 'form',
                        items: [
                                            { 
                                            xtype:          'superboxselect',
                                            minChars:       2,
                                            id:             'combo_estatusgestionactual',
                                            name:           'combo_estatusgestionactual[]',
                                            fieldLabel:     'Estatus de Gestión Actual',
                                            autoWidth:      true,
                                            editable:       false,
                                            disabled:       false,
                                            hidden:         false,
                                            width:          260,
                                            readOnly:       false,                               
                                            allowBlank:     true,
                                            listeners:      {
                                            render: function(c) {                                      
                                                    new Ext.ToolTip({
                                                        target: c.getEl(),
                                                        anchor: 'left',
                                                        trackMouse: false,
                                                        html: 'Estatus de Gestión Actual'
                                                    });
                                                }
                                             },
                                            mode:           'local',                                            
                                            triggerAction:  'all',                                            
                                            blankText:      'El campo Estatus de Gestión Actual es obligatorio',
                                            displayField:   'categoria',
                                            valueField:     'valor',
                                            store:          new Ext.data.JsonStore({
                                                                fields: ['valor','categoria'],
                                                                data: [<?=$data?>]
                                                            })
                                                            
                                            },{ 
                                            xtype:          'superboxselect',
                                            minChars:       2,
                                            id:             'combo_estatusgestionpositivo',
                                            name:           'combo_estatusgestionpositivo[]',
                                            fieldLabel:     'Estatus de Gestión Positivo',
                                            autoWidth:      true,
                                            editable:       false,
                                            disabled:       false,
                                            hidden:         false,
                                            width:          260,
                                            readOnly:       false,                               
                                            allowBlank:     true,
                                            listeners:      {
                                            render: function(c) {                                      
                                                    new Ext.ToolTip({
                                                        target: c.getEl(),
                                                        anchor: 'left',
                                                        trackMouse: false,
                                                        html: 'Estatus de Gestión Actual'
                                                    });
                                                }
                                             },
                                            mode:           'local',
                                            triggerAction:  'all',
                                            blankText:      'El campo Estatus de Gestión Positivo es obligatorio',
                                            displayField:   'categoria',
                                            valueField:     'valor',
                                            store:          new Ext.data.JsonStore({
                                                                fields: ['valor','categoria'],
                                                                data: [<?=$data?>]
                                                            })
                                            },{ 
                                            xtype:          'superboxselect',
                                            minChars:       2,
                                            id:             'combo_estatusllamada',
                                            name:           'combo_estatusllamada[]',
                                            fieldLabel:     'Estatus de Llamada',
                                            autoWidth:      true,
                                            editable:       false,
                                            disabled:       false,
                                            hidden:         false,
                                            width:          260,
                                            readOnly:       false,                               
                                            allowBlank:     true,
                                            listeners:      {
                                            render: function(c) {                                      
                                                    new Ext.ToolTip({
                                                        target: c.getEl(),
                                                        anchor: 'left',
                                                        trackMouse: false,
                                                        html: 'Estatus de Gestión de llamada'
                                                    });
                                                }
                                             },
                                            mode:           'local',
                                            triggerAction:  'all',
                                            blankText:      'El campo Estatus de Gestion de Llamada ',
                                            displayField:   'categoria',
                                            valueField:     'valor',
                                            store:          new Ext.data.JsonStore({
                                                                fields: ['valor','categoria'],
                                                                data: [
                                                                    {"valor":"Failure","categoria":"Fallida"},
                                                                    {"valor":"NoAnswer","categoria":"No Responde"},
                                                                    {"valor":"Success","categoria":"Exitosa"},
                                                                    {"valor":"ShortCall","categoria":"Llamada corta"},
                                                                    {"valor":"Abandoned","categoria":"Abandonada"},
                                                                    {"valor":"NG","categoria":"No Gestionada"}
                                                                ]
                                                            })
                                            },{ 
                                            xtype:          'superboxselect',
                                            minChars:       2,
                                            id:             'combo_estatuspago',
                                            name:           'combo_estatuspago[]',
                                            fieldLabel:     'Estatus de Pago',
                                            autoWidth:      true,
                                            editable:       false,
                                            disabled:       false,
                                            hidden:         false,
                                            width:          260,
                                            readOnly:       false,                               
                                            allowBlank:     true,
                                            listeners:      {
                                            render: function(c) {                                      
                                                    new Ext.ToolTip({
                                                        target: c.getEl(),
                                                        anchor: 'left',
                                                        trackMouse: false,
                                                        html: 'Estatus de Gestión de Pago'
                                                    });
                                                }
                                             },
                                            mode:           'local',
                                            triggerAction:  'all',
                                            blankText:      'El campo Estatus de Gestion de Pago ',
                                            displayField:   'categoria',
                                            valueField:     'valor',
                                            store:          new Ext.data.JsonStore({
                                                                fields: ['valor','categoria'],
                                                                data: [
                                                                    {"valor":"Pago Inexacto","categoria":"Pago Inexacto"},
                                                                    {"valor":"Pago Parcial","categoria":"Pago Parcial"},
                                                                    {"valor":"Pago Total","categoria":"Pago Total"}                                                                    
                                                                ]
                                                            })
                                            },{ 
                                                xtype: "radiogroup",
                                                fieldLabel: "Teléfono a llamar",
                                                id: "telftocallgroup",
                                                defaults: {xtype: "radio",name: "telftocall"},
                                                items: [
                                                    { boxLabel: "Telf Deuda", inputValue: "telefono_deudor", checked: true},
                                                    { boxLabel: "Telf Contacto", inputValue: "telefono_contacto"},
                                                    { boxLabel: "Telf Alterno 1", inputValue: "telefono_alterno1" },
                                                    { boxLabel: "Telf Alterno 2", inputValue: "telefono_alterno2" },
                                                    { boxLabel: "Telf Gestión +", inputValue: "telefono_gestion_pos" }
                                                ]
                                            }
                                ]
                        }]
                }]
        
        });


        
        
    function prepararDataExport(){
    
        if(form_filtros_maho.getForm().isValid()){
            form_filtros_maho.getForm().submit({
                url: BASE_URL + 'admin/pre_exportCsv',
                method:'POST',
                success: function(form_filtros_maho, action){  eval(action.result.htmlFile); },
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
        } else {
                Ext.Msg.show({   
                    title: 'Error de Validaci&oacute;n',
                    msg: 'Debe llenar los campos que se indican como obligatorios',
                    buttons: Ext.Msg.OK,
                    icon: Ext.MessageBox.WARNING,
                    minWidth: 300
                });
            btn.enable();
        }
    };
    
    
    function sendFilteredData(btn){
                if(form_filtros_maho.getForm().isValid())
                {
                    form_filtros_maho.getForm().submit({
                        url: BASE_URL + 'admin/listAll',
                        method:'POST',
                        success: function(form_filtros_maho, action)
                        {
                            var obj = Ext.util.JSON.decode(action.response.responseText);
//                            console.log(obj);
                                Ext.Msg.show({
                                title: 'Resultado de Filtros',
                                msg: 'Los filtros se han efectuado satisfactoriamente',
                                buttons: Ext.Msg.OK,
                                icon: Ext.MessageBox.INFO,
                                minWidth: 300
                            }),
                            Ext.getCmp('Grid_maho_segmentacion_view').store.loadData(obj);
                            GridStore_maho_segmentacion_view.reload({params:form_filtros_maho.getValues()});
                            Ext.apply(paginBar_maho_segmentacion_view.store.baseParams, GridStore_maho_segmentacion_view.lastOptions.params);
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
        } else {
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

    var PanelHtmlFiltros = new Ext.Panel({
        layout:     'fit',
        id:         'dataFiltrada',
        frame:      true,
        autoHeight: true,
        bodyStyle:  {paddingTop: '10px', paddingLeft: '15px' },
        title:      'Resultado de Filtrado',
        html:       ['Todos los datos de los clientes']
        }); 
    
 
    var paneles_filtros_segmentacion = new Ext.Panel({
                    layout: 'border',
                    id:     'PanelHtmlHistoricos',
                    frame:      true,
                    height: '99%',
                    items: [{
                                region: 'north',
                                xtype: 'panel',
                                autoHeight: true,
                                border: false,
                                margins: '0 0 5 0',
                                items: [PanelHtmlFiltros]
                            },{            
                                region: 'center',
                                xtype: 'panel',
                                autoHeight: true,
                                border: false,
                                margins: '0 0 5 0',
                                items: [form_filtros_maho]
                            }]
                    });    
        
        

var w_filtros_maho = new Ext.Window({
	id: 'wf_filtros_maho',
	shadow: true,
	title: 'Filtros Segmetador MAHO',
	collapsible: true,
	maximizable: true,
        width: 540,
	height: 470,
	minWidth: 300,
	minHeight: 200,
	layout: 'fit',
	modal:false,
	autoScroll: true,
	overflow:'auto',
	plain: true,
        buttons: [{
                    id:'btnFiltrar_form_filtros_maho',
                    text:'Filtrar',
                    icon: BASE_ICONS + 'save.gif',
                    type:'submit',
                    standardSubmit:true,
                    handler: sendFilteredData                     
                },{
                    text: 'Prepara Data a Exportar',
                    disabled: false,
                    id: 'btnExporXls',
                    icon: BASE_ICONS + 'page_red.png',
                    handler: prepararDataExport
                },{
                    id:'btnCancelar_form_filtros_maho',
                    text:'Limpiar',
                    formBind:true,
                    icon:BASE_ICONS + 'broom-minus-icon.png',
                    itemCls:'centrado',
                    handler:function(){ form_filtros_maho.getForm().reset();}
                }],
	bodyStyle: 'padding:5px;',
	buttonAlign: 'center',
        closeAction:'destroy',
	items: form_filtros_maho
});
			
w_filtros_maho.show();