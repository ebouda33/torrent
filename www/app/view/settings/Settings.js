/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


Ext = Ext || {};

Ext.define('MyTorrent.view.settings.Settings',{
    extend : 'Ext.form.Panel',
    xtype : 'settingpanel',
    requires: [
        'MyTorrent.view.settings.SettingsController'
    ],
    controller :'settings',
    items : [
        {
            xtype : 'fieldset',
            title : 'Seedbox Transmission',
            items : [
                {
                    xtype : 'textfield',
                    label : 'Serveur Transmission',
                    name : 'transmission_url',
                    id : 'transmission_url',
                    
                    placeHolder : 'http://maseedbox:9091/rpc/'
                },{
                    label : 'username',
                    xtype : 'textfield',
                    name : 'transmission_user',
                    id : 'transmission_user',
                    placeHolder : 'Username'
                },{
                    label : 'password',
                    xtype : 'passwordfield',
                    name : 'transmission_password',
                    id : 'transmission_password',
                    placeHolder : 'Password'
                }
            ]
        },
        {
            xtype : 'fieldset',
            title : 'Proxy',
            items : [
                {
                    xtype : 'textfield',
                    label : 'Serveur Proxy http',
                    name : 'proxy_url',
                    id : 'proxy_url',
                    placeHolder : 'monproxy:port'
                }
            ]
        },
        {
            xtype : 'fieldset',
            title : 'Plugin Options',
            items : [
                
            ]
        },{
            xtype : 'button',
            text : 'Enregistrer',
            handler : 'onSettingsClick'
        }
        
    ],
    listeners : {
        initialize : function(cmp,eOpts){
            MyTorrent.getApplication().setListenersPlugins(cmp);
            MyTorrent.getApplication().setConfigPanel(cmp);
        }
    },
    setPlugins : function(plugins){
        var me = this;
        var fieldsetPlugin = me.getItems().items[2];
        var items = [];
        Ext.each(plugins,function(plugin,index){
            if(plugin.options !== null){
                var sitems = [];
                Ext.each(plugin.options,function(opt,index){
                    for(var option in opt){
                        sitems.push({
                        xtype : 'passwordfield',
                        label : option,
                        name : option,
                        id : option,
                        placeHolder : option

                    });
                    }
                    
                });
                
                items.push({
                    xtype : 'fieldset',
                    title : plugin.name,
                    items : sitems
                    });
            }
        });
        fieldsetPlugin.setItems(items);
    },
    setSettings : function(data){
        for(var opt in data){
            Ext.getCmp(opt).setValue(data[opt]);
        }
    }
});