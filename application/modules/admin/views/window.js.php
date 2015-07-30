var w_<?=$nom_tabla?> = new Ext.Window({
	id: 'w_<?=$nom_tabla?>',
	shadow: true,
	title: '<?=$formTitle?>',
	collapsible: true,
	maximizable: true,
    width: <?=$win_width?>,
	height: 500,
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
	items:<?=$w_item?>,
});
			
w_<?=$nom_tabla?>.show();