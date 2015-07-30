<script type="text/javascript">
        var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Espere por favor..."});    
	var myMenuItemsData = null;
	var myMenuItemsData = new Ext.data.JsonStore({
		//autoDestroy:	true,
		url: 		BASE_URL + 'admin/get_tablas/',
		fields: 	["id","etiqueta"],
		root: 		'menuItems',
		totalProperty:	'results',
		method: 	'POST',
		autoLoad:	true,
		listeners:{
		load:function(){
				menuItems();
			}
		}
	});
	
	
	function menuItems()
	{
		var a;
		var myItems = [];
		accordion.removeAll();
                var tbar=false;
		for(a=0; a<myMenuItemsData.data.items.length; a++)
		{		
                    var prefijo = myMenuItemsData.data.items[a].json;
                    var this_item = {};
                    var crear = prefijo.crear;                    
                    var b;
                    var botones_menu = '';
                    var mis_buttons = [];
                    var cant = prefijo.lasTablas.length;
                    var altura = 26*cant; 
                    for(b=0; b<cant; b++)
                    {                    
//                      
                        var subprefijo = prefijo.lasTablas[b];
                        if(b==0){ var cm = ''; } else { var cm = ','; }
                        
                        botones_menu = {
                            xtype: 'button',
                            text: 'Listar ' + subprefijo.etiqueta,
                            icon: BASE_ICONS + 'table.png',
                            width: '100%',
                            tableName: subprefijo.nom_tabla,
                            moduleName: subprefijo.modulo,
                            handler:  function(btn){
                                    btn.disable();
                                    Ext.Ajax.timeout = 120000;
                                    myMask.show();
                                    Ext.Ajax.request({
                                        url: BASE_URL + arguments[0].moduleName + '/listAll/',
                                        method: 'GET',
                                        params:{tabla:arguments[0].tableName},
                                        success: function(response){
                                            btn.enable();
                                            eval(response.responseText);
                                            myMask.hide();
                                        },
                                        failure: function(){ 
                                            btn.enable();
                                            Ext.Msg.alert('Falla','fallo la respuesta axaj'); 
                                            myMask.hide();
                                       }
                                    })
                                }
                           }
                           mis_buttons.push(botones_menu);
                    }
                    this_item['title'] 	= prefijo.categoria;
                    this_item['id'] 	= prefijo.id;
                    this_item['iconCls']= prefijo.icon;
                    this_item['tbar']   = new Ext.Toolbar({
                             autoWidth: true,
                             layout:	'vbox',
                             height:altura,
                             border: false,
                             items:[mis_buttons]
                    });
                    myItems.push(this_item);
                   
		}
                accordion.add(myItems);
		accordion.doLayout();
	};

	
	var accordion = new Ext.Panel({
		title: 'Modulos',
		layout:'accordion',
		defaults: {
			autoHeight: true
		},
		layoutConfig: {
			titleCollapse: true,
			animate: true,
			activeOnTop: false
		}
	});	
</script>