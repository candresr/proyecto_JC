<script type="text/javascript">
    
	var layout_main = new Ext.Viewport({
	layout: 	'border', 
	renderTo: 	Ext.getBody(),
	items: [
		{ 
			region: 	'north', 
			autoHeight: 	true, 
			height: 	100, 
			border: 	false,
			html: 		'<?=$this->load->view('header.js.php')?>',
			collapseMode: 	'mini',
            <?php if(isset($collapsed)){echo $collapsed;}else{} ?>
			split: 		true,
			margins: 	'0 0 5 0' /*, style: 'border-bottom: 4px solid #4c72a4;' */
		}, {			
			region: 	'west', 
			baseCls: 	'x-plain', 
			xtype: 		'panel', 
			autoScroll: 	true,
			width: 		200, 
			border: 	true, 
			margins:	'0 0 0 5', 
			collapseMode: 	'mini',
			split: 		true,
			items: 		[accordion]
		},{
			id: 		'center_content', 
			region: 	'center', 
			layout: 	'fit',
			margins: 	'0 5 5 0',
			//tbar: 	tbCenter,
			//items:	[center],
			border: 	true
			}
		]
	});
        CENTER_CONTENT=Ext.getCmp('center_content');
	layout_main.show();
</script>
