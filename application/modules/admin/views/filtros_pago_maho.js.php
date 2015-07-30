<!--<script type="text/javascript">-->
var form_filtros_pago_cantv = new Ext.FormPanel({
    labelAlign: 'top',
    buttonAlign: 'center',
    frame: true,
    autoScroll: true,
    title: 'Filtros de Pagos',
    autoWidth: true,
    width: '100%',
    border: false,
    bodyStyle: {
        paddingTop: '10px',
        paddingLeft: '15px'
    },
    style: {
        margin: 'auto'
    },
    defaults: {
        labelStyle: 'font-weight: bold;',
        style: {
            margin: '0 0  5px 0',
            padding: '2px'
        }
    },
    items: [{
        layout: 'column',
        items: [{
            columnWidth: .5,
            layout: 'form',
            items: [{
                xtype: 'combo',
                id: 'combo_cliente',
                name: 'combo_cliente',
                hiddenName: 'combo_cliente',
                fieldLabel: 'Cliente',
                autoWidth: true,
                editable: false,
                emptyText: 'Haga su selección',
                disabled: false,
                hidden: false,
                width: 170,
                readOnly: false,
                allowBlank: true,
                listeners: {
                    render: function (c) {
                        new Ext.ToolTip({
                            target: c.getEl(),
                            anchor: 'left',
                            trackMouse: false,
                            html: 'Escoja Cliente'
                        });
                    },
                    select: function (combo, record) {
                        Ext.Ajax.request({
                            url: BASE_URL + 'admin/asignacionComboStore',
                            method: 'GET',
                            params: {
                                a_consultar: record.get('valor')
                            },
                            success: function (response) {
                                var obj = Ext.util.JSON.decode(response.responseText);
                                Ext.getCmp('combo_producto').store.loadData(obj);
                              //  Ext.getCmp('combo_producto').clearValue();
                              //  Ext.getCmp('combo_subproducto').clearValue();
                            }
                        })
                    }
                },
                mode: 'local',
                triggerAction: 'all',
                blankText: 'El campo Cliente es obligatorio',
                displayField: 'categoria',
                valueField: 'valor',
                store: new Ext.data.JsonStore({
                    fields: ['valor', 'categoria'],
                    data: [{
                        "valor": "cantv",
                        "categoria": "CANTV"
                    }]
                })
            }, {
                xtype: 'combo',
                id: 'combo_producto',
                name: 'combo_producto',
                hiddenName: 'combo_producto',
                fieldLabel: 'Producto',
                autoWidth: true,
                editable: false,
                emptyText: 'Haga su selección',
                disabled: false,
                hidden: false,
                width: 170,
                readOnly: false,
                allowBlank: true,
                listeners: {
                    render: function (c) {
                        new Ext.ToolTip({
                            target: c.getEl(),
                            anchor: 'left',
                            trackMouse: false,
                            html: 'Escoja Producto'
                        });
                    },
                    select: function (combo, record) {
                        Ext.Ajax.request({
                            url: BASE_URL + 'admin/asignacionComboStore',
                            method: 'GET',
                            params: {
                                a_consultar: record.get('valor')
                            },
                            success: function (response) {
                                var obj = Ext.util.JSON.decode(response.responseText);
                                Ext.getCmp('combo_subproducto').store.loadData(obj);
                             //   Ext.getCmp('combo_subproducto').clearValue();
                            }
                        })
                    }
                },
                mode: 'local',
                triggerAction: 'all',
                blankText: 'El campo Producto es obligatorio',
                displayField: 'categoria',
                valueField: 'valor',
                store: new Ext.data.JsonStore({
                    fields: ['valor', 'categoria']
                })
            }, {
                xtype: 'textfield',
                id: 'num_doc_pago',
                fieldLabel: 'N&uacute;mero Documento de Pago',
                name: 'num_doc_pago',
                width: 100,
                value: '',
                disabled: false,
                hidden: false,
                readOnly: false,
                listeners: {
                    render: function (c) {
                        new Ext.ToolTip({
                            target: c.getEl(),
                            dismissDelay: 0,
                            showDelay: 0,
                            boxMinHeight: 400,
                            boxMinWidth: 400,
                            anchor: 'left',
                            trackMouse: false,
                            html: 'Ingrese el n&uacute;mero de documento de pago.'
                        });
                    }
                }
            }, {
                xtype: 'textfield',
                id: 'cuenta_contrato',
                fieldLabel: 'Cuenta Contrato',
                name: 'cuenta_contrato',
                width: 100,
                value: '',
                disabled: false,
                hidden: false,
                readOnly: false,
                listeners: {
                    render: function (c) {
                        new Ext.ToolTip({
                            target: c.getEl(),
                            dismissDelay: 0,
                            showDelay: 0,
                            boxMinHeight: 400,
                            boxMinWidth: 400,
                            anchor: 'left',
                            trackMouse: false,
                            html: 'Ingrese la cuenta contrato'
                        });
                    }
                }
            }, {
                xtype: 'combo',
                id: 'finalizada',
                name: 'finalizada',
                fieldLabel: 'Estatus del Pago',
                autoWidth: true,
                editable: false,
                disabled: false,
                hidden: false,
                width: 170,
                readOnly: false,
                allowBlank: true,
                listeners: {
                    render: function (c) {
                        new Ext.ToolTip({
                            target: c.getEl(),
                            anchor: 'left',
                            trackMouse: false,
                            html: 'Estatus de Gestión de Pago'
                        });
                    }
                },
                mode: 'local',
                triggerAction: 'all',
                blankText: 'El campo Estatus de Gestion de Pago ',
                displayField: 'categoria',
                valueField: 'valor',
                store: new Ext.data.JsonStore({
                    fields: ['valor', 'categoria'],
                    data: [{
                        "valor": "Cancelado",
                        "categoria": "Cancelado"
                    }, {
                        "valor": "Pendiente",
                        "categoria": "Pendiente"
                    }]
                })
            }]
        }, {
            columnWidth: .5,
            layout: 'form',
            items: [{
                xtype: 'datefield',
                fieldLabel: 'Fecha Recaudaci&oacute;n (Desde)',
                name: 'f_desde_recaudacion',
                width: 100,
                value: '',
                disabled: false,
                hidden: false,
                readOnly: false,
                listeners: {
                    render: function (c) {
                        new Ext.ToolTip({
                            target: c.getEl(),
                            dismissDelay: 0,
                            showDelay: 0,
                            boxMinHeight: 400,
                            boxMinWidth: 400,
                            anchor: 'left',
                            trackMouse: false,
                            html: 'Ingrese la fecha de inicio de recaudaci&oacute;n del pago.'
                        });
                    }
                },
                format: 'Y-m-d'

            }, {
                xtype: 'datefield',
                fieldLabel: 'Fecha Recaudaci&oacute;n (Hasta)',
                name: 'f_hasta_recaudacion',
                width: 100,
                value: '',
                disabled: false,
                hidden: false,
                readOnly: false,
                listeners: {
                    render: function (c) {
                        new Ext.ToolTip({
                            target: c.getEl(),
                            dismissDelay: 0,
                            showDelay: 0,
                            boxMinHeight: 400,
                            boxMinWidth: 400,
                            anchor: 'left',
                            trackMouse: false,
                            html: 'Ingrese la fecha de finalizaci&oacute;n de la recaudaci&oacute;n del pago.'
                        });
                    }
                },
                format: 'Y-m-d'

            },{
                xtype: 'datefield',
                fieldLabel: 'Fecha Asignación (Desde)',
                name: 'f_desde_asignacion',
                width: 100,
                value: '',
                disabled: false,
                hidden: false,
                readOnly: false,
                listeners: {
                    render: function (c) {
                        new Ext.ToolTip({
                            target: c.getEl(),
                            dismissDelay: 0,
                            showDelay: 0,
                            boxMinHeight: 400,
                            boxMinWidth: 400,
                            anchor: 'left',
                            trackMouse: false,
                            html: 'Ingrese la fecha de inicio de asignaci&oacute;n.'
                        });
                    }
                },
                format: 'Y-m-d'

            }, {
                xtype: 'datefield',
                fieldLabel: 'Fecha Asignación (Hasta)',
                name: 'f_hasta_asignacion',
                width: 100,
                value: '',
                disabled: false,
                hidden: false,
                readOnly: false,
                listeners: {
                    render: function (c) {
                        new Ext.ToolTip({
                            target: c.getEl(),
                            dismissDelay: 0,
                            showDelay: 0,
                            boxMinHeight: 400,
                            boxMinWidth: 400,
                            anchor: 'left',
                            trackMouse: false,
                            html: 'Ingrese la fecha de finalizaci&oacute;n de la asignaci&oacute;n.'
                        });
                    }
                },
                format: 'Y-m-d'

            }]
        }]
    }]

});

function exportCsv() {
    if (form_filtros_pago_cantv.getForm().isValid()) {
        form_filtros_pago_cantv.getForm().submit({
            url: BASE_URL + 'admin/exportPagosCsv',
            method: 'POST',
            success: function (form_filtros_pago_cantv, action) {
                var obj = Ext.util.JSON.decode(action.response.responseText);
                //                            console.log(obj);
                Ext.Msg.show({
                    title: 'Exportando Pagos en formato CSV',
                    msg: 'Exportando Pagos en formato CSV, se esta descargando',
                    buttons: Ext.Msg.OK,
                    icon: Ext.MessageBox.INFO,
                    minWidth: 300
                });
                var filename = obj.filename;
                window.open(BASE_URL + 'admin/forcedownloadCsv?uri=' + encodeURIComponent(filename), '_top');
                btn.enable();
            },
            failure: function (form_filtros_pago_cantv, action) {
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
};

function sendFilteredData(btn) {
    if (form_filtros_pago_cantv.getForm().isValid()) {
        form_filtros_pago_cantv.getForm().submit({
            url: BASE_URL + 'admin/listAll',
            method: 'POST',
            success: function (form_filtros_pago_cantv, action) {
                var obj = Ext.util.JSON.decode(action.response.responseText);
                                            console.log(obj);
                Ext.Msg.show({
                    title: 'Resultado de Filtros',
                    msg: 'Los filtros se han efectuado satisfactoriamente',
                    buttons: Ext.Msg.OK,
                    icon: Ext.MessageBox.INFO,
                    minWidth: 300
                }),
                Ext.getCmp('Grid_pagos_cantv').store.loadData(obj);
                GridStore_pagos_cantv.reload({
                    params: form_filtros_pago_cantv.getValues()
                });
                Ext.apply(paginBar_pagos_cantv.store.baseParams, GridStore_pagos_cantv.lastOptions.params);
                btn.enable();
            },
            failure: function (form_filtros_pago_cantv, action) {
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
    layout: 'fit',
    id: 'dataFiltrada',
    frame: true,
    autoHeight: true,
    bodyStyle: {
        paddingTop: '10px',
        paddingLeft: '15px'
    },
    title: 'Resultado de Filtrado',
    html: ['Todos los datos de los clientes']
});


var paneles_filtros_segmentacion = new Ext.Panel({
    layout: 'border',
    id: 'PanelHtmlHistoricos',
    frame: true,
    height: '99%',
    items: [{
        region: 'north',
        xtype: 'panel',
        autoHeight: true,
        border: false,
        margins: '0 0 5 0',
        items: [PanelHtmlFiltros]
    }, {
        region: 'center',
        xtype: 'panel',
        autoHeight: true,
        border: false,
        margins: '0 0 5 0',
        items: [form_filtros_pago_cantv]
    }]
});



var w_filtros_maho = new Ext.Window({
    id: 'wf_filtros_maho',
    shadow: true,
    title: 'Filtros de Pagos CANTV',
    collapsible: true,
    maximizable: true,
    width: 540,
    height: 400,
    minWidth: 300,
    minHeight: 200,
    layout: 'fit',
    modal: false,
    autoScroll: true,
    overflow: 'auto',
    plain: true,
    buttons: [{
        id: 'btnFiltrar_form_filtros_pago_cantv',
        text: 'Filtrar',
        icon: BASE_ICONS + 'save.gif',
        type: 'submit',
        standardSubmit: true,
        handler: sendFilteredData
    }, {
        text: 'Exportar CSV',
        disabled: false,
        id: 'btnExporCsv',
        icon: BASE_ICONS + 'page_green.png',
        handler: exportCsv
    }, {
        id: 'btnCancelar_form_filtros_pago_cantv',
        text: 'Limpiar',
        formBind: true,
        icon: BASE_ICONS + 'broom-minus-icon.png',
        itemCls: 'centrado',
        handler: function () {
            form_filtros_pago_cantv.getForm().reset();
        }
    }],
    bodyStyle: 'padding:5px;',
    buttonAlign: 'center',
    closeAction: 'destroy',
    items: form_filtros_pago_cantv
});

w_filtros_maho.show();