var Ext = Ext || {};

Ext.define('MyTorrent.view.grid.GridResult',{
    extend : 'Ext.grid.Grid',
    xtype : 'torrentresult',
    title : 'Résultat',
    collapsible : true,
//            layout : 'fit',
    
    deferEmptyText : 'Aucune recherche exécutée.',
    store : null ,
//            fullscreen : true,
//            columnLines: true,
    plugins : [{type:'gridcolumnresizing'},{type:'gridviewoptions'},{type:'gridpagingtoolbar'}],
    emptyText : '<h1 style="margin:20px">No matching results</h1>',
    listeners : {
      initialize : function(grid,eOpts){
          ancestor = grid.getBubbleParent().getItems().items;
          ancestor[0].setZoneResultat(grid);
      } ,
      itemsingletap : function (grid , row , target , record , e , eOpts ){
          //ask question
          grid.gotoTransmission(record.data.url);
          target.setStyle({
             color : '#e1dede' 
          });
      },
      itemmouseenter : function (grid , row , target , record , e , eOpts ){
           target.setStyle({
               cursor : 'pointer'
           });
      },
      itemmouseleave : function (grid , row , target , record , e , eOpts ){
          target.setStyle({
               cursor : 'default'
           });
      }
    },
    
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
    ],
    refreshGrid : function (){
      var me = this;
      var items = me.items.items[0].items.items;
      Ext.each(items,function(item,index){
         item.setStyle({
          color : 'grey' 
      }); 
      });
      
    },
    gotoTransmission : function (torrent){
//        console.log('prise en compte de ...'+torrent);
//        console.log('encode '+Ext.encode(torrent));
        if(torrent.indexOf('&tr')>0){
            torrent = torrent.replace(/&tr/g,'@');
        }
        Ext.Ajax.request({
            url :  'torrentJson.php'
            ,method : 'GET'
            ,params : 'transmission='+torrent
            ,success :function (response,opts){
                var obj = Ext.decode(response.responseText);
                var icon = 'x-fa fa-info' ;
                var message = 'Lien envoyer au serveur Transmission, dans quelques temps le fichier sera dispo';
                if(!obj.success){
                    message = obj.message;
                    icon = 'x-fa fa-warning' ;
                }
                if(obj.duplicate !== undefined && obj.duplicate){
                    message = 'Torrent existant';
                }
                Ext.Msg.show({
                    title : 'Envoi Transmission',
                    message : message,
                    buttons : Ext.MessageBox.OK,
                    iconCls :  icon ,
                    closable : true,
                    height : 200
                });


            }
            ,failure : function(response,opts){
                icon = 'x-fa fa-error' ;
                Ext.Msg.show({
                    title : 'Envoi Transmission',
                    message : response.statusText,
                    buttons : Ext.MessageBox.OK,
                    iconCls :  icon ,
                    closable : true
                });
            }
        });

    }
});