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
        'MyTorrent.view.recherche.Torrent',
        'MyTorrent.view.grid.GridResult'
    ]
//    ,layout : 'hbox'
    
    
    
//    ,align : 'stretch'
    ,height : '100%'
    ,items : [
        {
            xtype : 'rechercheTorrent',
            width : '30%'
            
        },
        {
            xtype : 'torrentresult',
            minHeight : 200,
//            height : 400,
            store : null ,
            plugins : [{type:'gridcolumnresizing'},{type:'gridviewoptions'},{type:'gridpagingtoolbar'},{type:'responsive'}],
            
            responsiveConfig: {
                wide: {
                    width : '70%',
                    height : 400
                },
                tall: {
                    width : '100%'
                }
            },
            scrollable : true,
            style : {textAlign : 'left'}
        }
    ]
    
});
