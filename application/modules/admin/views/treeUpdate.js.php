

	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/js/libraries/ExtJs/3.4/resources/css/ext-all-notheme.css"/>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/js/libraries/ExtJs/3.4/resources/css/xtheme-gray.css"/>
    <script type="text/javascript" src="<?=base_url()?>assets/js/libraries/ExtJs/3.4/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="<?=base_url()?>assets/js/libraries/ExtJs/3.4/ext-all.js"></script>
        
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/treeArchivos/checktree.css">
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/treeArchivos/Ext.ux.tree.CheckTreePanel.css">
	<script type="text/javascript" src="<?=base_url()?>assets/treeArchivos/Ext.ux.tree.CheckTreePanel.js"></script>
	<script type="text/javascript" src="<?=base_url()?>assets/treeArchivos/Ext.ux.tree.TreeFilterX.js"></script>

	
<script type="text/javascript">
     
    var childrens= [
                    {"id":"1","text":"Cuerpos de Policia","type":"titulo","checked":true,"children":[
                        {"id":"1-1","text":"Cuerpos de Policia numero 1","type":"cp","checked":false,"children":[
                            {"id":"1-1-1","text":"Funcionarios","type":"subtitulo","checked":false},
                            {"id":"1-1-2","text":"Instalaciones","type":"subtitulo","checked":false},
                            {"id":"1-1-3","text":"Departamentos","type":"subtitulo","checked":false},
                            {"id":"1-1-4","text":"Junta Directiva","type":"subtitulo","checked":false},
                            {"id":"1-1-5","text":"Parque de Armas","type":"subtitulo","checked":false},
                            {"id":"1-1-6","text":"Parque de Vehiculos","type":"subtitulo","checked":false}
                        ]},
                        {"id":"1-2","text":"Cuerpos de Policia numero 2","type":"cp","checked":false,"children":[
                            {"id":"1-2-1","text":"Funcionarios","type":"subtitulo","checked":false},
                            {"id":"1-2-2","text":"Instalaciones","type":"subtitulo","checked":false},
                            {"id":"1-2-3","text":"Departamentos","type":"subtitulo","checked":false},
                            {"id":"1-2-4","text":"Junta Directiva","type":"subtitulo","checked":false},
                            {"id":"1-2-5","text":"Parque de Armas","type":"subtitulo","checked":false},
                            {"id":"1-2-6","text":"Parque de Vehiculos","type":"subtitulo","checked":false}
                        ]},
                        {"id":"1-3","text":"Cuerpos de Policia numero 3","type":"cp","checked":true,"children":[
                            {"id":"1-3-1","text":"Funcionarios","type":"subtitulo","checked":true},
                            {"id":"1-3-2","text":"Instalaciones","type":"subtitulo","checked":false},
                            {"id":"1-3-3","text":"Departamentos","type":"subtitulo","checked":true},
                            {"id":"1-3-4","text":"Junta Directiva","type":"subtitulo","checked":false},
                            {"id":"1-3-5","text":"Parque de Armas","type":"subtitulo","checked":false},
                            {"id":"1-3-6","text":"Parque de Vehiculos","type":"subtitulo","checked":false}
                        ]}
                    ]},
                    {"id":"2","text":"Indicadores","type":"titulo","checked":false},
                    {"id":"3","text":"Estandares","type":"titulo","checked":true},
                    {"id":"4","text":"Ranquins","type":"titulo","checked":false},
                    {"id":"5","text":"Buenas Practicas","type":"titulo","checked":false},
                    {"id":"6","text":"Recursos de Mejoramiento","type":"titulo","checked":false}
                ];
    
    var tree = new Ext.ux.tree.CheckTreePanel({
        id: 'tree',
        name: 'tree',
        height: 500,
        width: 600,
        useArrows:true,
	expandOnCheck: true,
        autoScroll:true,
        animate:true,
        containerScroll: true,
        rootVisible: false,
        root: {"id":"0","text":"Raiz","type":"raiz","checked":true,"children":childrens},
        cascadeCheck: 'all',
	bubbleCheck: 'all'
    });
    
    var formInterTablesTree = new Ext.form.FormPanel({ 
	id: 'formInterTablesTree',
	name: 'formInterTablesTree',
	items: [tree],
        buttons: [{ id: 'button_aceptar',
                    text: 'Guardar',
                    icon: '<?=base_url()?>'+'assets/img/icons/save.gif',
                    type: 'submit',
                    standardSubmit:true,
                    handler: function() {
                            showMask();	
                            Ext.Ajax.request({
                               url: '<?=$url?>',
                               method: 'POST',
                               params: {tree: Ext.encode(Ext.getCmp('tree').getValue())},
                               success: function(response){

                                    hideMask();
                                    var icon = Ext.MessageBox.ERROR;
                                    var obj = Ext.util.JSON.decode(response.responseText);

                                    // Si el proceso de logica de negocio es exitoso
                                    if (obj.response.result) {
                                        icon = Ext.MessageBox.INFO;
                                        windowRoleTree.close();
                                    }
                                    Ext.Msg.show({   
                                            title: obj.response.title,
                                            msg: obj.response.msg,
                                            buttons: Ext.Msg.OK,
                                            icon: icon,
                                            minWidth: 300
                                    });
                               },
                               failure: function(){
                                    hideMask();
                                    Ext.Msg.show({
                                            title: 'Error',
                                            msg: 'Error en la Peticion al Servidor',
                                            buttons: Ext.Msg.OK,
                                            icon: Ext.MessageBox.ERROR,
                                            minWidth: 300
                                    });
                               }
                            })
                    }
                },{ 
                    id: 'button_limpiar',
                    text: 'Limpiar',
                    icon: '<?=base_url()?>'+'assets/img/icons/broom-minus-icon.png',
                    handler:function (){
//                            tree.getRootNode().cascade(function(n) {
//                            var ui = n.getUI();
//                            ui.toggleCheck(true);
//                        });
                        tree.getRootNode().removeAll();
                        tree.getRootNode().appendChild([
                    {"id":"1","text":"Cuerpos de Policia","type":"titulo","checked":true,"children":[
                        {"id":"1-1","text":"Cuerpos de Policia numero 1","type":"cp","checked":false,"children":[
                            {"id":"1-1-1","text":"Funcionarios","type":"subtitulo","checked":false},
                            {"id":"1-1-2","text":"Instalaciones","type":"subtitulo","checked":false},
                            {"id":"1-1-3","text":"Departamentos","type":"subtitulo","checked":false},
                            {"id":"1-1-4","text":"Junta Directiva","type":"subtitulo","checked":false},
                            {"id":"1-1-5","text":"Parque de Armas","type":"subtitulo","checked":false},
                            {"id":"1-1-6","text":"Parque de Vehiculos","type":"subtitulo","checked":false}
                        ]},
                        {"id":"1-2","text":"Cuerpos de Policia numero 2","type":"cp","checked":false,"children":[
                            {"id":"1-2-1","text":"Funcionarios","type":"subtitulo","checked":false},
                            {"id":"1-2-2","text":"Instalaciones","type":"subtitulo","checked":false},
                            {"id":"1-2-3","text":"Departamentos","type":"subtitulo","checked":false},
                            {"id":"1-2-4","text":"Junta Directiva","type":"subtitulo","checked":false},
                            {"id":"1-2-5","text":"Parque de Armas","type":"subtitulo","checked":false},
                            {"id":"1-2-6","text":"Parque de Vehiculos","type":"subtitulo","checked":false}
                        ]},
                        {"id":"1-3","text":"Cuerpos de Policia numero 3","type":"cp","checked":true,"children":[
                            {"id":"1-3-1","text":"Funcionarios","type":"subtitulo","checked":true},
                            {"id":"1-3-2","text":"Instalaciones","type":"subtitulo","checked":false},
                            {"id":"1-3-3","text":"Departamentos","type":"subtitulo","checked":true},
                            {"id":"1-3-4","text":"Junta Directiva","type":"subtitulo","checked":false},
                            {"id":"1-3-5","text":"Parque de Armas","type":"subtitulo","checked":false},
                            {"id":"1-3-6","text":"Parque de Vehiculos","type":"subtitulo","checked":false}
                        ]}
                    ]},
                    {"id":"2","text":"Indicadores","type":"titulo","checked":false},
                    {"id":"3","text":"Estandares","type":"titulo","checked":true},
                    {"id":"4","text":"Ranquins","type":"titulo","checked":false},
                    {"id":"5","text":"Buenas Practicas","type":"titulo","checked":false},
                    {"id":"6","text":"Recursos de Mejoramiento","type":"titulo","checked":false}
                ]);
                        
                    }
                }]
    });

    // Ventana donde se cargan los FieldSet
    var windowRoleTree = new Ext.Window({

        id: 'windowRoleTree',
        shadow: true,
        title: 'Habilitar áreas de estandarización, estandares e indicadores',
        collapsible: true,
        maximizable: true,
        minWidth: 500,
        width: 500,
        minHeight: 400,
        layout: 'auto',
        modal:true,
        autoScroll: true,
        overflow:'auto',
        plain: true,
        bodyStyle: 'padding:5px;',
        buttonAlign: 'center',
        items: [formInterTablesTree]        
    });    
    windowRoleTree.show();
    
//    replaceCenterContent(formInterTablesTree);
    
    tree.getRootNode().expand(false);
    
    // Creacion de la mascara
    mask = new Ext.LoadMask(Ext.getCmp('formInterTablesTree').body, { msg: 'Cargando' });
    
    // Funcion para mostrar la mascara
    function showMask() {
        mask.show();
        Ext.each(formInterTablesTree.buttons, function(button) {
            button.disable();
        });
    }
    
    // Funcion para ocultar la mascara
    function hideMask() {
        mask.hide();
        Ext.each(formInterTablesTree.buttons, function(button) {
            button.enable();
        });
    }
    
    
</script>