/**
 * This class is the main view for the application. It is specified in app.js as the
 * "mainView" property. That setting causes an instance of this class to be created and
 * added to the Viewport container.
 *
 * TODO - Replace the content of this view to suit the needs of your application.
 */
Ext.define('MyTorrent.view.main.Main', {
    extend: 'Ext.tab.Panel',
    xtype: 'app-main',

    requires: [
        'Ext.MessageBox',
        'MyTorrent.view.main.MainController',
        'MyTorrent.view.main.MainModel',
        'MyTorrent.view.liste.ListeResultat',
        'MyTorrent.view.main.Accueil',
        'MyTorrent.view.main.Profil',
        'MyTorrent.view.main.Recherche'
    ],
    plugins: 'responsive',
    controller: 'main',
    viewModel: 'main',
    activeItem : 1,
    defaults: {
        tab: {
            iconAlign: 'top'
        },
        styleHtmlContent: true,
        bodyPadding: 20,
        tabConfig: {
            plugins: 'responsive',
            responsiveConfig: {
                wide: {
                    iconAlign: 'left',
                    textAlign: 'left'
                },
                tall : {
                    iconAlign: 'top',
                    textAlign: 'center',
                    width: 120
                }
            }
        }
    },
     

    
    titleRotation: 0,
    tabRotation: 0,

    

    responsiveConfig: {
        tall: {
            headerPosition: 'top'
        },
        wide: {
            headerPosition: 'left'
        }
    },

    
    tabBarPosition: 'bottom',
    items: [
        {
            title: 'Accueil',
            iconCls: 'x-fa fa-home',
            layout: 'fit',
            // The following grid shares a store with the classic version's grid as well!
            items: [{
                xtype: 'panelAccueil'
            }]
        },{
            title: 'Recherche',
            iconCls: 'x-fa fa-search',
//            layout: 'fit',
            height : 500,
            items: [{
                xtype: 'panelRecherche'
            }]
        },{
            title: 'Torrents',
            iconCls: 'x-fa fa-cloud',
            items: [{
                xtype: 'panel'
                
            }]
//            bind: {
//                html: '{loremIpsum}'
//            }
        },{
            title: 'Settings',
            iconCls: 'x-fa fa-wrench',
            items: [{
                xtype: 'panelProfil'
            }]
        },
        {
//            xtype : 'button',
            title: 'Logout',
            handler: 'onLogoutButton',
            iconCls: 'fa-th-list',
            items: [{
                xtype: 'panel',
                html : 'GoodBye',
                height: '100%',
                bodyPadding: '60%'
                
            }],
            listeners :{
                activate : 'onLogoutButton'
            }
        }
    ]
});
