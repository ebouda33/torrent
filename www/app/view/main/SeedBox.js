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
                MyTorrent.getApplication().setStoreSeedBox(store);

//           }
        }
   },
   height:'100%',
   items:[
       {
           xtype : 'seedboxresult',
           minHeight : 200,
		   height:'100%',
           layout : 'fit',
		   width : '100%',
           scrollable : true,
           style : {textAlign : 'left'},
           plugins : [{type:'gridcolumnresizing'},{type:'gridviewoptions'},{type:'gridpagingtoolbar'},{type:'responsive'}]
       }
   ]
});

