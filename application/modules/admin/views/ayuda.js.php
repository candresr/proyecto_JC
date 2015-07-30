Ext.QuickTips.init();

var tree = new Ext.tree.TreePanel({  
        title:          'Modulos del Sistema',
        border:         true,  
        frame:          true,
        height:         350,
        
        animCollapse:   true,
        animate:        true,
        useArrows:      true, 
        autoScroll:     true,  
        closable:       true,
        collapseFirst:  true,
        containerScroll:true,
        ddScroll:       true,
        
        rootVisible:    false,  
        root:           <?=$root?>  
    });  
    

var el_centro = new Ext.Panel({
        title:      'El Contenido',
        id:         'ayuda_conten',
        height:     350,
        frame:      true,
        border:     true        
    });
    
    
var paneles_ayuda = new Ext.Panel({
                    layout: 'border',
                    id:     'paneles_ayuda',
                    frame:  true,
                    border: true,
                    items: [{
                                region:     'west',
                                id:         'elarbolayuda',
                                xtype:      'panel',
                                width:      220,
                                margins:    '0 0 5 0',
                                items:      [tree]                                
                            },{            
                                region:     'center',
                                id:         'elcentroayuda',
                                xtype:      'panel',
                                width:      330,
                                margins:    '0 0 5 0',
                                items:      [el_centro]
                            }]
                    });    
                    
                                   
        
var win_ayuda = new Ext.Window({
                            id:         'winAyuda',
                            shadow:     true,
                            title:      'Ayuda Personalizada del Sistema',
                            collapsible:true,
                            maximizable:false,
                            width:      550,
                            height:     405,
                            layout:     'fit',
                            modal:      true,
                            autoScroll: true,
                            overflow:   'auto',
                            plain:      true,
                            bodyStyle:  'padding:2px;',
                            buttonAlign:'center',
                            closeAction:'destroy',
                            items:      paneles_ayuda
                        });
			
win_ayuda.show();
HELP_CONTENT=Ext.getCmp('elcentroayuda');