/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


Ext.define('MyTorrent.view.main.Recherche',{
    extend: 'Ext.panel.Panel'
    ,xtype: 'panelRecherche'
    
    ,requires :[
        'MyTorrent.view.recherche.Torrent'
    ]
//    tpl:[
//        'Ce site n\'est qu\'un moteur de recherche sur certains tracker bien connu.\n\
//            \n\<br>Actuellement gestion de {name}'
//    ]
    
    ,listeners :{
        initialize : function (panel,eOpts){
            
            

        }
        
    }
    ,layout : 'hbox'
    ,items : [
        {
            xtype : 'rechercheTorrent',
            width : '50%'
        },
        {
            xtype : 'panel',
            title : 'resultat',
            layout : 'fit'
        }
    ]
    
//    items:[{
//        html : 'Ce site n\'est qu\'un moteur de recherche sur certains tracker bien connu.\n\
//                <br>Actuellement gestion de Nextorrent et T411.'
//    }]
    
});
