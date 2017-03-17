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
            MyTorrent.getApplication().setListenersPlugins(panel);
            

        }
        
    }
    ,majTpl : function(data){
        var panel = this;
        var t = panel.getTpl();
        panel.data = data;
        panel.setHtml(t.apply(panel.data));
    }
    ,items : []
    
    ,setPlugins : function(data){
        this.majTpl(data);
    }
    
});
