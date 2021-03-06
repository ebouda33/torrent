var Ext = Ext || {};

Ext.define('MyTorrent.view.grid.SeedBox',{
    extend : 'Ext.grid.Grid',
//    extend : 'Ext.grid.Panel',
    xtype : 'seedboxresult',
    title : '',
    requires : ['MyTorrent.store.SeedBox'],
    emptyText : '<h1 style="margin:20px">No matching results</h1>',
    layout: 'fit',
    height : '100%',
    minHeight : 800,
    
    listeners : {
      initialize : function(grid,eOpts){
          MyTorrent.getApplication().gridSeedBox = grid;
//            grid.setStore(Ext.create('MyTorrent.store.SeedBox',{}));
      } ,
      itemsingletap : function (grid , row , target , record , e , eOpts ){
         
      },
      itemmouseenter : function (grid , row , target , record , e , eOpts ){
         
      },
      itemmouseleave : function (grid , row , target , record , e , eOpts ){
          
      }
    },
    scrollable : true,
    items :[{
            xtype : 'toolbar',
            docked: 'top',
            items:[{
              text : 'SeedBox perso'      
            },
              '->'      
            ,{
                    
                    text:'Refresh',
                    textAlign : 'bottom',
                    iconCls : 'x-fa fa-refresh',
                    handler : function(){
                        MyTorrent.getApplication().loadStoreSeedBox();
                    }
            }]
        }],
    columns: [
        {
          text : 'Date',
          dataIndex: 'doneDate',
          width: 100,
          resizable : true,
          align : 'left',
          renderer : function(value,record){
              
              return Ext.Date.format(Ext.Date.parse(value,'U'),'d/m/Y');;
          }
          
        },
        {
            text: 'Torrent',
            dataIndex: 'name',
//                    sortable: false,  // column cannot be sorted
            width: 250,
            flex : 1.5,
            resizable : true,
            align : 'left'
        },
        {
            text: 'Total Size',
            dataIndex: 'totalSize',
//                    hidden: true  // column is initially hidden
            resizable : true,
            width : 100,
            align : 'left',
            renderer : function(value,record){
                return MyTorrent.util.Util.getSizeLitteral(value);
            }

        },
        {
            text: 'Size State',
            dataIndex: 'haveValid',
            width: 100,
            resizable : true,
            align : 'left',
            renderer : function(value,record){
                return MyTorrent.util.Util.getSizeLitteral(value);
            }
        },
        {
            text: 'Status',
            dataIndex: 'status',
            width: 70,
            resizable : true,
            align : 'left',
            flex:1,
            renderer : function(value,record){
                return MyTorrent.util.Util.statusSeedBox(value);
            }
        },
        {
            text : '',
            dataIndex: 'id',
            width: 150,
            hidden : true,
            resizable : false,
            align : 'left'
            

        },{
            text : 'ratio',
            dataIndex : 'uploadRatio',
            width: 50,
            resizable : false,
            align : 'left',
            renderer : function(value,record){
                if(!isNaN(value)){
                    return Math.floor((value*10))/10;
                }
                
                return 0; 
            }
        }
    ]
});