<? if(!empty($scriptTags)):?>
    <script type="text/javascript">
<? endif; ?>

var form_<?=$nom_tabla?>= new Ext.FormPanel({
	labelWidth:     140,
	buttonAlign:    'center',
	frame:		true,
        <?php if($nom_tabla=='web_galerias' && $operacion=='Editar' || $nom_tabla=='web_sono' && $operacion=='Editar'){ ?>
        tbar:       ['->',{
                        text: 'Agregar Archivos(s)',
                        id: 'btnAdFile',
                        icon: BASE_ICONS + 'photos.png',
                        handler:  function(btn){
                                 btn.disable();
                                Ext.Ajax.request({
                                    url: BASE_URL + 'admin/winUpload',
                                    method: 'GET',
                                    params: { galId:<?=$rowId?> , nom_tabla:'<?=$nom_tabla?>'},
                                    success: function(action, request) {
                                    eval(action.responseText);
                                    },
                                    failure: function(action, request) {
                                    var obj = Ext.util.JSON.decode(action.responseText);
                                               Ext.Msg.show({
                                                            title: 'Error',
                                                            msg: 'Ha ocurrido un error en la conexi&oacute;n con el servidor',
                                                            minWidth: 200,
                                                            modal: true,
                                                            icon: Ext.Msg.INFO,
                                                            buttons: Ext.Msg.OK
                                                        });         
                                    } 
                                });                      
                                btn.enable();
                            }        
                    }],
        
        <?php } ?>

        <?php if($nom_tabla=='web_programacion' && $operacion=='Editar'){ ?>
        tbar:       ['->',{
                        text: 'Agregar Capitulo(s)',
                        id: 'btnAdFile',
                        icon: BASE_ICONS + 'film.png',
                        handler:  function(btn){
                                 btn.disable();
                                Ext.Ajax.request({
                                    url: BASE_URL + 'admin/winGrid',
                                    method: 'GET',
                                    params: { progId:<?=$rowId?> },
                                    success: function(action, request) {
                                    eval(action.responseText);
                                    },
                                    failure: function(action, request) {
                                    var obj = Ext.util.JSON.decode(action.responseText);
                                               Ext.Msg.show({
                                                            title: 'Error',
                                                            msg: 'Ha ocurrido un error en la conexi&oacute;n con el servidor',
                                                            minWidth: 200,
                                                            modal: true,
                                                            icon: Ext.Msg.INFO,
                                                            buttons: Ext.Msg.OK
                                                        });         
                                    } 
                                });                      
                                btn.enable();
                            }        
                    }],
        
        <?php } ?>
	<? if($replace != 'window'): ?>	
            title: 		'<?=$formTitle?>',
	<? endif ?>
        autoScroll:     true,
	//autoHeight:     true,
	autoWidth:     true,        
	width: 		'100%',
        //height:         500,
	items: 		<?=$fields?>,
	border:         false,
        bodyStyle:      {paddingTop: '10px', paddingLeft: '35px' },
        style:          {margin: 'auto'},
        defaults:       {
                        labelStyle: 'font-weight: bold;',
                        style:      { margin: '0 0  5px 0', padding: '2px' }
                        },
        <?php if($buttons == 0){}else{  ?>                
        buttons: [{
                    id:'<?=$nom_tabla?>',
                    text:'<?=$operacion?>',
                    icon: BASE_ICONS + 'save.gif',
                    type:'submit',
                    standardSubmit:true,
                    handler:function (btn){
                        if(form_<?=$nom_tabla?>.getForm().isValid())
                        {
                            form_<?=$nom_tabla?>.getForm().submit({
                                url: BASE_URL + 'admin/procesaForm',
                                method:'POST',
                                success: function(form_<?=$nom_tabla?>, action)
                                {
                                    var obj = Ext.util.JSON.decode(action.response.responseText);
                                    //console.log(obj);
                                        Ext.Msg.show({
                                        title: obj.titulo,
                                        msg: obj.msj,
                                        buttons: Ext.Msg.OK,
                                        icon: Ext.MessageBox.INFO,
                                        minWidth: 300
                                    })
                                    if(obj.resultado=='Editar')
                                    {
                                        w_<?=$nom_tabla?>.hide();
                                        w_<?=$nom_tabla?>.destroy();
                                        GridStore_<?=$nom_tabla?>.load();
                                    }
                                    if(obj.resultado=='Crear')
                                    {
                                       Ext.Ajax.request({
                                           url: BASE_URL+'admin/listAll',
                                           method: 'GET',
                                           success: function(response){ eval(response.responseText); },                          
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
                                    btn.enable();
                                },
                                failure: function(form_<?=$nom_tabla?>, action){
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
                    id:'buttonCancelar_form_<?=$nom_tabla?>',
                    text:'Limpiar',
                    formBind:true,
                    icon:BASE_ICONS + 'broom-minus-icon.png',
                    itemCls:'centrado',
                    handler:function(){ form_<?=$nom_tabla?>.getForm().reset();}
                }]
            <?php } ?>    
        });
        

<?php
    if($replace == 'window'){
        $wdata['w_item'] = 'form_'.$nom_tabla;
        $wdata['nom_tabla'] = $nom_tabla;        
        if(empty($win_width)){ $wdata['win_width'] = '600'; } else { $wdata['win_width'] = $win_width; }
        $this->load->view('window.js.php', $wdata);
    } else {
        echo $replace;
    }
?>
Ext.QuickTips.init();  
Ext.form.Field.prototype.msgTarget = 'side';
<? if(!empty($scriptTags)):?>
	</script>
<? endif; ?>