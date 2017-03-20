/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


Ext.define('MyTorrent.view.main.Recherche',{
    extend: 'Ext.panel.Panel'
    ,xtype: 'panelRecherche'
    ,scrollable : true
    ,requires :[
        'MyTorrent.view.recherche.Torrent'
    ]
    ,listeners :{
        initialize : function (panel,eOpts){
            
            

        }
        
    }
    ,layout : 'hbox'
    ,align : 'stretch'
    ,items : [
        {
            xtype : 'rechercheTorrent',
            width : '35%'
        },
        {
            xtype : 'grid',
            title : 'Résultat',
            collapsible : true,
            layout : 'fit',
            minHeight : 100,
            height : 400,
            width : '64%',
            scrollable : true,
            deferEmptyText : 'Aucune recherche exécutée.',
            store : null ,
//            fullscreen : true,
//            columnLines: true,
            plugins : [{type:'gridcolumnresizing'},{type:'gridviewoptions'},{type:'gridpagingtoolbar'}],
            
            viewConfig: {
                trackOver: false,
                emptyText: '<h1 style="margin:20px">No matching results</h1>'
            },
            listeners : {
              initialize : function(grid,eOpts){
                  ancestor = grid.getBubbleParent().getItems().items;
                  ancestor[0].setZoneResultat(grid);
              }  
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
                    align : 'left',
                },
                {
                    text: 'Size',
                    dataIndex: 'size',
//                    hidden: true  // column is initially hidden
                    resizable : true,
                    width : 100,
                    align : 'left',
                    
                },
                {
                    text: 'Seeder',
                    dataIndex: 'seeder',
                    width: 100,
                    resizable : false,
                    align : 'left',
                },
                {
                    text: 'Leecher',
                    dataIndex: 'leecher',
                    width: 100,
                    resizable : false,
                    align : 'left',
                },
                {
                    text : 'Use Transmission',
                    dataIndex: 'url',
                    width: 200,
                    resizable : true,
                    align : 'left',
                    renderer : function (container,position){
//                        console.log(container,position);
                        return 'MAgnet';
                    }
                }
            ]
        }
    ]
    
//    items:[{
//        html : 'Ce site n\'est qu\'un moteur de recherche sur certains tracker bien connu.\n\
//                <br>Actuellement gestion de Nextorrent et T411.'
//    }]
    
});
