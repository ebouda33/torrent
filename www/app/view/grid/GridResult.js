

Ext.define('MyTorrent.view.grid.GridResult', {
    extend: 'Ext.grid.Panel',
    xtype : 'erictest',
    title : 'Résultat',
    collapsible : true,
//            layout : 'fit',
    minHeight : 400,
    height : '100%',
    width : '64%',
    scrollable : true,
    deferEmptyText : 'Aucune recherche exécutée.',
    store : null ,
    
    viewConfig: {
                trackOver: false,
                emptyText: '<h1 style="margin:20px">No matching results</h1>'
            },
    style : {textAlign : 'left'},
    columns: [
        {
            text: 'Torrent',
            dataIndex: 'titre',
//                    sortable: false,  // column cannot be sorted
            width: 250,
            flex : 1,
            resizable : true,
            align : 'left'
        },
        {
            text: 'Size',
            dataIndex: 'size',
//                    hidden: true  // column is initially hidden
            resizable : true,
            width : 100,
            align : 'left'

        },
        {
            text: 'Seeder',
            dataIndex: 'seeder',
            width: 100,
            resizable : false,
            align : 'left'
        },
        {
            text: 'Leecher',
            dataIndex: 'leecher',
            width: 100,
            resizable : false,
            align : 'left'
        },
        {
            text : 'Use Transmission',
            dataIndex: 'url',
            width: 200,
            resizable : true,
            align : 'left',
            renderer : function (container,position){
//                        console.log(container,position);
                var message = 'Goto MyTransmision';
                return message;
            }

        }
    ]  
});