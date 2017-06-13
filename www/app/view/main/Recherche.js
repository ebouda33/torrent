/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


Ext.define('MyTorrent.view.main.Recherche',{
    extend: 'Ext.panel.Panel'
    ,xtype: 'panelRecherche'
   // ,scrollable : true
    ,requires :[
        'MyTorrent.view.recherche.Torrent',
        'MyTorrent.view.grid.GridResult'
    ]
//    ,layout : 'hbox'
    
    
    
    ,align : 'stretch'
    ,height : '100%'

    ,items : [
        {
            xtype : 'rechercheTorrent',
            width : '30%',
            height : '100%',
            
        },
        {
            xtype : 'torrentresult',
            minHeight : 500,
//            height : 400,
            height : '100%',
            store : null ,
            plugins : [{type:'gridcolumnresizing'},{type:'gridviewoptions'},{type:'gridpagingtoolbar'},{type:'responsive'}],
            scrollable : true,
            responsiveConfig: {
                wide: {
                    width : '70%',
                    height : '100%'
                },
                tall: {
                    width : '100%',
                    height : 400
                }
            },
            scrollable : true,
            style : {textAlign : 'left'}
        }
    ]
    
});
