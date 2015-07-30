var topBar_<?=$nom_tabla?>    = false;
var bottomBar_<?=$nom_tabla?> = false;

<?php if(!empty($tbar) || !empty($searchType)): ?>
	var topBar_<?=$nom_tabla?>  = new Ext.Toolbar({
            id:     'topBar_<?=$nom_tabla?>',
            items:  <?=$tbar?>
	});   
<?php endif ?>

        
	
<?php if(!empty($bbar)): ?>
	var bottomBar_<?=$nom_tabla?>  = new Ext.Toolbar({
            id:     'bottomBar_<?=$nom_tabla?>',
            items:  <?=$bbar?>
	});  
<?php endif ?>
        
     
        