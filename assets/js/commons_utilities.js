
	function do_logout() {
		Ext.Ajax.request({
			url: BASE_URL + 'admin/logout',
			method: 'POST',
			success: function(xhr) {
				window.location = BASE_URL + 'admin/index';
			}
		});
	}
        
        function replaceCenterContent(centerElement){
            CENTER_CONTENT.removeAll();
            CENTER_CONTENT.add(centerElement);
            CENTER_CONTENT.doLayout();
        }
        
        function replaceHelpContent(centerElement){
            HELP_CONTENT.removeAll();
            HELP_CONTENT.add(centerElement);
            HELP_CONTENT.doLayout();
        }
        
       
       function mi_perfil(elid, latabla){
           Ext.Ajax.request({
			url: BASE_URL + 'admin/form',
			method: 'GET',
                        params:{id:elid, tabla:latabla, posicion:'center'},
			success: function(response){
				eval(response.responseText);
			},
                        failure: function(response){
					Ext.Msg.show({   
						title: 'Error!',
						msg: 'Error en la Peticion al Servidor',
						buttons: Ext.Msg.OK,
						icon: Ext.MessageBox.ERROR,
						minWidth: 300
					});
				}
		});
       }
        

       function ayuda(){
              Ext.Ajax.request({
                    url: BASE_URL + 'ayuda/index',
                    method: 'GET',                    
                    success: function(response){
                            eval(response.responseText);
                            //console.log(response.responseText)
                    },
                    failure: function(response){
                                    Ext.Msg.show({   
                                            title: 'Error!',
                                            msg: 'Error en la Peticion al Servidor',
                                            buttons: Ext.Msg.OK,
                                            icon: Ext.MessageBox.ERROR,
                                            minWidth: 300
                                        });
                                    }
            });    
       }


	function getCenterContent(operation, id, triggerObj){
                var tempOnClick=false;
                if(!empty(triggerObj)){
                    tempOnClick=triggerObj.target.onclick;
                    triggerObj.target.onclick=null;
                }           
            
		try {
			Ext.StoreMgr.clear();
		} catch (exception) { 
				//do nothing
		}	
		if(empty(operation)) return;
		var url=BASE_URL;
			url+=(typeof(operation)=='object')?operation.url:operation;
		var params=(empty(id))?false:({id:id});			
		var ops={
			url: url,
			params: params,
			method: 'GET',
			success: function(response) {
                            eval(response.responseText);
                            if(!empty(tempOnClick)){
                                triggerObj.target.onclick=tempOnClick;
                            }
                           
                        }
		}
		Ext.Ajax.request(ops);
	}
        
    function evalTabContent(){
		eval(arguments[2].responseText);		
		return false;
	}		
		
		
   function empty(val){	   
		if(val==null) return true;
		switch(typeof(val)){
			case 'number':return (val==0);
            case 'boolean':return (val==false);
			case 'string':
                if (val=="" || val.match(/^\s*$/) ) return true;
                else return false;
			case 'object':return (val.length==0 );
            case 'function':return false;
			case 'undefined':return true;
		}
	}
	

	function loadChildCombo(combo, record){
		var childCombo=Ext.getCmp(combo.child);
		clearChildCombos(childCombo); 
		childCombo.store.load({params:{id:combo.value}});
	}
       
	
	function clearChildCombos(combo){
		combo.clearValue();
		combo.store.removeAll(); 
		combo.disable();		
		if(!empty(combo.child)){
			var childCombo=Ext.getCmp(combo.child);
			clearChildCombos(childCombo);
		}
		
	}
	
	   
	   
   	function confirmDelete(url, recordId, gridId, subject, disabling){
		var title = message = '';		
		if(!empty(disabling)){
			title='Confirmar Desactivacion';
			message='Esta seguro(a) de Desactivar '+subject+'?';
		}else{
			title='Confirmar Eliminacion';
			message='Esta seguro(a) de Eliminar '+subject+'?';
		} 
		Ext.MessageBox.buttonText.yes = "Si";
		Ext.Msg.show({
			title: title,
			msg: message,
			buttons: Ext.Msg.YESNO,
			fn: function(btn){
				if(btn=='yes') deleteDBRecord(url, recordId, gridId);
			},
			minWidth: 300,
			icon: Ext.MessageBox.QUESTION
		});
	}	
	
	function deleteDBRecord(url, recordId, gridId){
		var deleteDBRecordConn = new Ext.data.Connection();
			deleteDBRecordConn.request({
				url: url, 
				method: 'POST',
				params:{id:recordId},
				//success: function(resp,opt){
				success: function(resp,opt){
					var icon = Ext.MessageBox.ERROR;
					var obj = Ext.util.JSON.decode(resp.responseText);
					if (obj.response.result){
						icon = Ext.MessageBox.INFO;
						Ext.getCmp(gridId).store.reload();
						//console.log(Ext.getCmp(gridId).store);
						
					} 
					Ext.Msg.show({   
						title: obj.response.title,
						msg: obj.response.msg,
						buttons: Ext.Msg.OK,
						icon: icon,
						minWidth: 300
					});			
					
				},
				failure: function(resp,opt){
					Ext.Msg.show({   
						title: 'Error!',
						msg: 'Error en la Peticion al Servidor',
						buttons: Ext.Msg.OK,
						icon: Ext.MessageBox.ERROR,
						minWidth: 300
					});
				}				
			});
	}
	
	function renderCountry(value, metaData, record, rowIndex, colIndex, store){
		if(value=='1') return 'Venezuela';
		else return value;
	}	
	
	
function deleteOption(index){
		Ext.getCmp('optionListGrid').store.removeAt(index);
		return;
	}



Ext.Ajax.on('requestexception', function(c,r,o)
{
   if(r.status == 300) {
//        Ext.Msg.show({   
//            title: 'Sistema',
//            msg: 'Sesión Finalizada',
//            buttons: Ext.Msg.OK,
//            icon: Ext.MessageBox.INFO,
//            minWidth: 300
//        });
       window.location = BASE_URL;
   }
});