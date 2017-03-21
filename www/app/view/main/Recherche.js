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
        'MyTorrent.view.grid.GridResult',
    ]
    ,listeners :{
        initialize : function (panel,eOpts){
            
            

        }
        
    }
    ,layout : 'hbox'
//    ,align : 'stretch'
    ,height : '100%'
    ,items : [
        {
            xtype : 'rechercheTorrent',
            width : '35%'
        },
        {
            xtype : 'torrentresult',
            minHeight : 400,
            height : '100%',
            width : '64%',
            scrollable : true,
            style : {textAlign : 'left'}
        }
    ]
    
//    items:[{
//        html : 'Ce site n\'est qu\'un moteur de recherche sur certains tracker bien connu.\n\
//                <br>Actuellement gestion de Nextorrent et T411.'
//    }]
    
});
