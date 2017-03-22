/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Ext.define('MyTorrent.view.main.MainView',{
    extend: 'Ext.panel.Panel',
    
    requires: [
//        'Ext.MessageBox',
//        'Ext.layout.Fit',
        'MyTorrent.view.main.Main',
        'MyTorrent.view.main.MainController',
        'MyTorrent.view.main.MainModel',
        'MyTorrent.view.liste.ListeResultat',
        'MyTorrent.view.main.Accueil'
    ],
    
    controller: 'main',
    viewModel: 'main',
    layout : 'fit',

    style: '{text-align:center;}',
    title : '<center>MyTorrent</center>',
    
    items: [
        {
            xtype : 'app-main'
        }],
//    fullscreen: true
});
