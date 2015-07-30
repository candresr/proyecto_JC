<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo meta(array('name' => 'Content-type', 'content' => 'text/html; charset=utf-8', 'type' => 'equiv')) ?>
    <link rel="shortcut icon" href="<?=base_url()?>/assets/img/favicon.ico" />
    <script type="text/javascript" src="<?=base_url()?>assets/js/libraries/ExtJs/3.4/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/js/libraries/ExtJs/3.4/ext-all.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/js/libraries/ExtJs/3.4/plugins/statusbar/StatusBar.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/js/searchfield.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/js/form_validation.js"></script>    
    <script type="text/javascript" src="<?=base_url()?>assets/js/FileUploadField.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/js/commons_utilities.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/js/RowEditor.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/js/SuperBoxSelect.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/js/MultiSelect.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/js/ItemSelector.js"></script>
       
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/js/libraries/ExtJs/3.4/resources/css/ext-all-notheme.css"/>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/js/libraries/ExtJs/3.4/resources/css/yourtheme.css"/>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/style.css"/>		
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/RowEditor.css"/>	
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/fileuploadfield.css"/>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/MultiSelect.css"/>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/superboxselect.css"/>
    
    
    <script type="text/javascript">
        var BASE_URL 	= '<?=base_url()?>';
        var NOM_SITIO 	= '<?=$conf_titulo?>';
        var BASE_PATH 	= '<?=base_url()?>';
        var BASE_ICONS 	= '<?=base_url()?>' + 'assets/img/icons/';
        var LIMITE		= 25;
        Ext.onReady(function() {
            Ext.QuickTips.init();
            Ext.BLANK_IMAGE_URL = BASE_PATH + 'assets/js/libraries/ExtJs/3.4/resources/images/default/s.gif';
            Ext.form.Field.prototype.msgTarget = 'side';
            
            //verificamos si tenemos que cargar por defecto desde consola de agentes:            
            <?php if(isset($cs)){
                echo "Ext.Ajax.timeout = 120000; myMask.show();";
//                echo "Ext.Ajax.request({
//                    url: BASE_URL + '/leclub/leclub/listAll/',
//                    method: 'GET',
//                    params:{tabla:'leclub_segmentacion'},
//                    success: function(response){
//                        eval(response.responseText);
//                        myMask.hide();
//                    },
//                    failure: function(){ 
//                        Ext.Msg.alert('Falla','fallo la respuesta axaj'); 
//                        myMask.hide();
//                   }
//                });";
                echo "Ext.Ajax.request({
                            url: BASE_URL + '/leclub/leclub/form/',
                            method: 'GET',
                            params:{tabla:'leclub_segmentacion',id:'{$cs}'},
                            success: function(response){
                                eval(response.responseText);
                                Ext.getCmp('leclub_segmentacion.estatus_pago').setValue('{$estatus_pago}');
                                Ext.getCmp('leclub_segmentacion.telefono_gestion_pos').setValue('{$telefono_gestion_pos}');
                                myMask.hide();
                            },
                            failure: function(){ 
                                Ext.Msg.alert('Falla','fallo la respuesta axaj'); 
                                myMask.hide();
                           }
                        });";
            } ?>
            
        });        
	</script>    
    <title><?=$conf_titulo?></title>
</head>
<body>
<?php
    $this->load->view('west_menu.js.php');
    $this->load->view('viewport.js.php');
?>
    </body>
</html>