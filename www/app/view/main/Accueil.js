/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


Ext.define('MyTorrent.view.main.Accueil',{
    extend: 'Ext.panel.Panel'
    ,xtype: 'panelAccueil'
    ,padding : 30
    
    ,tpl : new Ext.XTemplate(
        'Ce site n\'est qu\'un moteur de recherche sur certains tracker bien connu.<br>',
        'Actuellement gestion de',
        '<tpl for=".">',
        '<div><img src="data:image/jpg;base64,{icone}" alt="{name}"/>{name}</div>',
        '<div>{description}</div>',
        '</tpl>',
        '<p><br></p>',
        '<p>L\'idée est de pouvoir téléchargé rapidement des torrents sur un serveur spécialisé</p>',
        '<p>Dans la zone recherche vous chercher les torrents qui vous interresse, vous cliquez dessus et c\'est parti.</p>',
        '<br><br>',
        '<p>SeedBox vous permets de voir les torrents en cours ou déjà DL sur votre SeedBox</p>',
        '<p>Settings pour gérer des paramétres ....',
        '<pre><span style="height:100%;"><br><br><br> Il faut un compte pour l\'utiliser , les inscriptions sont fermées</span></pre>'
        
        
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
