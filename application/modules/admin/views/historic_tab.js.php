var historic_tabs = new Ext.TabPanel({
        renderTo: document.body,
        activeTab: 0,
        width:460,
        height:350,
        //autoHeight: false,
        plain:true,
        defaults:{autoScroll: true},
        items:[{
                title: 'Historico de Gestion',
                style: {
                    padding: '5px 5px 5px 5px',
                    backgroundColor: '#E9F0FF'
                },
                autoLoad: {url: 'admin/historic_segement', params: 'cuenta_contrato=<?=$cuenta_contrato?>'}
            }
            //,{
            //    title: 'Historico de Asignaciones',
            //    autoLoad: {url: 'admin/historic_segement', params: 'id=bar&cliente=1'}
            //},{
            //    title: 'Historico de Segmentación',
            //    autoLoad: {url: 'admin/historic_segement', params: 'id=bar&cliente=1'}
            //}
        ]
    });


var PanelHtmlCliente = new Ext.Panel({
        layout:     'fit',
        id:         'dataCliente',
        frame:      true,
        autoHeight: true,
        title:      'Datos de Cliente',
        html:       ['<?=$cliente?>']
        }); 

var paneles_historicos = new Ext.Panel({
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
                                items: [PanelHtmlCliente]
                            },{            
                                region: 'center',
                                xtype: 'panel',
                                autoHeight: true,
                                border: false,
                                margins: '0 0 5 0',
                                items: [historic_tabs]
                            }]
                    });
    

var w_historic_tabs = new Ext.Window({
	id: 'w_historic_tabs',
	shadow: true,
	title: 'Historico de Clientes',
	collapsible: true,
	maximizable: true,
        width: 500,
	height: 570,
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
	items:paneles_historicos,
});
			
w_historic_tabs.show();