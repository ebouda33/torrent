/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Ext = Ext || {};

Ext.define('MyTorrent.view.main.SeedBox',{
   extend : 'Ext.panel.Panel',
   xtype : 'seedbox',
   requires : ['MyTorrent.view.grid.SeedBox'],
   listeners : {
       initialize : function(cmp,ots){
//           console.log(localStorage.getItem("MyTorrentSeebBox"));
//           if(localStorage.getItem("MyTorrentSeebBox") === 'true'){
                var grid = cmp.getItems().items[0];
                var store = grid.getStore();
                eric = store;
                console.log(eric);
                MyTorrent.getApplication().setStoreSeedBox(store);
//                store.load({
//                            scope: this,
//                            callback: function(records, operation, success) {
//                                // the operation object
//                                // contains all of the details of the load operation
//                                if(!success){
//                                    Ext.Msg.show({
//                                        title : 'Recherche Seedbox',
//                                        message : operation.error,
//                                        buttons : Ext.MessageBox.OK,
//                                        iconCls :  'x-fa fa-error' ,
//                                        closable : true,
//                                        height : 200
//                                    });
//                                }
//
//                            }
//                        });

//           }
        }
   },
   layout : 'fit',
   items:[
       {
           xtype : 'seedboxresult',
           minHeight : 200,
           responsiveConfig: {
                wide: {
                    width : '100%',
                    height : '100%'
                },
                tall: {
                    width : '100%'
                }
            },
           scrollable : true,
           style : {textAlign : 'left'},
           plugins : [{type:'gridcolumnresizing'},{type:'gridviewoptions'},{type:'gridpagingtoolbar'},{type:'responsive'}]
       }
   ]
});

