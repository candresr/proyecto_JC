<?php if($searchType=='S'){ ?>
var search_<?=$nom_tabla?> = new Ext.app.SearchField({
			store: <?=$storeName.$nom_tabla?>,
			params: {start: 0, limit: LIMITE },
			width: 180,
			vtype: 'alpha_numeric',
			id: 'fieldSearch_<?=$nom_tabla?>',
            name: 'searchfield'
		});
<?php } ?>