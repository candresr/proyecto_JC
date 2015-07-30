var el_contenido = new Ext.Panel({
        title:      '<?=$titulo?>',
        id:         'ayuda_conten',
        height:     350,
        frame:      true,
        border:     true,
        autoScroll: true,
        html:       '<?=$elhtml?>'
    });

replaceHelpContent(el_contenido);