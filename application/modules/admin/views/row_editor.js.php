<script type="text/javascript">
Ext.onReady(function(){
    Ext.QuickTips.init();

    var Employee = Ext.data.Record.create([{
        name: 'nom_tabla',
        type: 'string'
    }, {
        name: 'visible',
        type: 'bool'
    }, {
        name: 'listar',
        type: 'bool'
    },{
        name: 'crear',
        type: 'bool'
    },{
        name: 'editar',
        type: 'bool'
    },{
        name: 'borrar',
        type: 'bool'
    }]);



    var store = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            fields: Employee
            }),
            data: genData(),
            sortInfo: {field: 'nom_tabla', direction: 'ASC'}
    });

    var editor = new Ext.ux.grid.RowEditor({
        saveText: 'Actualizar'
    });

    var grid = new Ext.grid.GridPanel({
        store: store,
        width: 600,
        region:'center',
        margins: '0 5 5 5',
        autoExpandColumn: 'nom_tabla',
        plugins: [editor],
        view: new Ext.grid.GroupingView({
            markDirty: false
        }),
        

        columns: [
        new Ext.grid.RowNumberer(),
        {
            id: 'name',
            header: 'First Name',
            dataIndex: 'name',
            width: 220,
            sortable: true,
            editor: {
                xtype: 'textfield',
                allowBlank: false
            }
        },{
            header: 'Email',
            dataIndex: 'email',
            width: 150,
            sortable: true,
            editor: {
                xtype: 'textfield',
                allowBlank: false,
                vtype: 'email'
            }
        },{
            xtype: 'datecolumn',
            header: 'Start Date',
            dataIndex: 'start',
            format: 'm/d/Y',
            width: 100,
            sortable: true,
            groupRenderer: Ext.util.Format.dateRenderer('M y'),
            editor: {
                xtype: 'datefield',
                allowBlank: false,
                minValue: '01/01/2006',
                minText: 'Can\'t have a start date before the company existed!',
                maxValue: (new Date()).format('m/d/Y')
            }
        },{
            xtype: 'numbercolumn',
            header: 'Salary',
            dataIndex: 'salary',
            format: '$0,0.00',
            width: 100,
            sortable: true,
            editor: {
                xtype: 'numberfield',
                allowBlank: false,
                minValue: 1,
                maxValue: 150000
            }
        },{
            xtype: 'booleancolumn',
            header: 'Active',
            dataIndex: 'active',
            align: 'center',
            width: 50,
            trueText: 'Yes',
            falseText: 'No',
            editor: {
                xtype: 'checkbox'
            }
        }]
    });

    

    var layout = new Ext.Panel({
        title: 'Employee Salary by Month',
        layout: 'border',
        layoutConfig: {
            columns: 1
        },
        width:600,
        height: 600,
        items: [grid]
    });
    layout.render(Ext.getBody());

    grid.getSelectionModel().on('selectionchange', function(sm){
        grid.removeBtn.setDisabled(sm.getCount() < 1);
    });
});
</script>