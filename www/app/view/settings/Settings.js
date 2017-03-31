/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


Ext = Ext || {};

Ext.define('MyTorrent.view.settings.Settings',{
    extend : 'Ext.panel.Panel',
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
                    placeHolder : 'http://maseedbox:9091/rpc/'
                },{
                    label : 'username',
                    xtype : 'textfield',
                    placeHolder : 'Username'
                },{
                    label : 'password',
                    xtype : 'passwordfield',
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
        
    ]
});