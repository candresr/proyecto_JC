<!--<script type="text/javascript">-->
var csv_generator = new Ext.FormPanel({
        labelAlign:     'top',
	buttonAlign:    'center',
	frame:		true,
        autoScroll:     true,
        title:          'Generador de CSV',
	autoWidth:     true,        
	width: 		'100%',
        border:         false,
        bodyStyle:      {paddingTop: '10px', paddingLeft: '15px' },
        style:          {margin: 'auto'},
        defaults:       {
                        labelStyle: 'font-weight: bold;',
                        style:      { margin: '0 0  5px 0', padding: '2px' }
                        },
                        items:[{            
                            xtype: 'checkboxgroup',
                            fieldLabel: 'Escoger campos para generar archivo CSV',
                            id: 'csVfieldgroup',
                            columns: 3,
                            vertical: true,
                            items: [<?=$checks?>]
                            }]
    });
        
    function exportXls(){};
    
    function exportCsv(){
        if(csv_generator.getForm().isValid())
        {
            csv_generator.getForm().submit({
                url: BASE_URL + 'admin/exportCsv',
                method:'POST',  
                success: function(csv_generator, action)
                        {
                            var obj = Ext.util.JSON.decode(action.response.responseText);
                                Ext.Msg.show({
                                title: 'Exportando Campaña en formato CSV',
                                msg: 'La campaña en formato CSV, se esta descargando',
                                buttons: Ext.Msg.OK,
                                icon: Ext.MessageBox.INFO,
                                minWidth: 300
                            });
                            var filename = obj.filename;
                            window.open(BASE_URL + 'admin/forcedownloadCsv?uri=' + encodeURIComponent(filename), '_top');
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
    };
    
    function GenerateCampaing(){};

var w_csv_generator = new Ext.Window({
	id: 'wf_csv_generator',
	shadow: true,
	title: 'Filtros Segmetador MAHO',
	collapsible: true,
	maximizable: true,
        width: 740,
	height: 450,
	minWidth: 300,
	minHeight: 200,
	layout: 'fit',
	modal:false,
	autoScroll: true,
	overflow:'auto',
	plain: true,
        buttons: [{
                    text: 'Exportar CSV',
                    disabled: false,
                    id: 'btnExporCsv',
                    standardSubmit:true,
                    icon: BASE_ICONS + 'page_green.png',
                    handler: exportCsv
                },{
                    text: 'Exportar XLS',
                    disabled: true,
                    id: 'btnExporXls',
                    standardSubmit:true,
                    icon: BASE_ICONS + 'page_red.png',
                    handler: exportXls
                },{
                    text: 'Generar Campaña',
                    disabled: true,
                    id: 'btnGenerCampaing',
                    standardSubmit:true,
                    icon: BASE_ICONS + 'page_white_stack.png',
                    handler: GenerateCampaing
                },{
                    id:'btnCancelar_form_filtros_maho',
                    text:'Limpiar',
                    formBind:true,
                    standardSubmit:true,
                    icon:BASE_ICONS + 'broom-minus-icon.png',
                    itemCls:'centrado',
                    handler:function(){ csv_generator.getForm().reset();}
                }],
	bodyStyle: 'padding:5px;',
	buttonAlign: 'center',
        closeAction:'destroy',
	items: csv_generator
});
			
w_csv_generator.show();