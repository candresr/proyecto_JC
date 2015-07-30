<?if(!$error):?>
	var download_json = new Ext.Panel({
		autoHeight: true, 
		height: 	100, 
		border: 	false,
		html: 	'<?=$this->load->view('download_json.js.php', $data)?>',
		style:      'border-bottom: 4px solid #4c72a4; padding: 7px; background-color:#FFFFFF'
	});


	var win_download_json = new Ext.Window({
		id:         'exportdata',
		shadow:     true,
		title:      'Exportar Data de Versión',
		collapsible:true,
		maximizable:false,
		width:      320,
		height:     120,
		layout:     'fit',
		modal:      true,
		autoScroll: true,
		overflow:   'auto',
		plain:      true,
		closeAction:'destroy',
		items:      download_json
	});

	win_download_json.show();
<?else:?>
	Ext.Msg.show({   
		title: 'Advertencia',
		msg: '<?=$msg_error?>',
		buttons: Ext.Msg.OK,
		icon: Ext.MessageBox.INFO,
		minWidth: 300
	});
<?endif?>