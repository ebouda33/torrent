/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


Ext.define('MyTorrent.view.main.Accueil',{
    extend: 'Ext.panel.Panel'
    ,xtype: 'panelAccueil'
    
//    tpl:[
//        'Ce site n\'est qu\'un moteur de recherche sur certains tracker bien connu.\n\
//            \n\<br>Actuellement gestion de {name}'
//    ]
    ,tpl : new Ext.XTemplate(
        'Ce site n\'est qu\'un moteur de recherche sur certains tracker bien connu.<br>',
        'Actuellement gestion de',
        '<tpl for=".">',
        '<div>{name}</div>',
        '</tpl>'
        
    )
    ,data : [   ]
    
    ,listeners :{
        initialize : function (panel,eOpts){
            console.log(eOpts);
//            panel.setHtml('before show');
            eric = panel;
            panel.data = [{
                    name :"T411"
                },{
                    name : 'nexTorrent'
                }
                ];
                var t = panel.getTpl();
                panel.setHtml(t.apply(panel.data));
//            panel.update(panel.data);
//            panel.doLayout();
        }
        ,click : function(){
            console.log('click panel');
        }
    }
    ,items : []
    
//    items:[{
//        html : 'Ce site n\'est qu\'un moteur de recherche sur certains tracker bien connu.\n\
//                <br>Actuellement gestion de Nextorrent et T411.'
//    }]
    
});
