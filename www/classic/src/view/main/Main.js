///**
// * This class is the main view for the application. It is specified in app.js as the
// * "mainView" property. That setting automatically applies the "viewport"
// * plugin causing this view to become the body element (i.e., the viewport).
// *
// * TODO - Replace this content of this view to suite the needs of your application.
// */
//Ext.define('MyTorrent.view.main.Main', {
//    extend: 'Ext.tab.Panel',
//    xtype: 'app-main',
//
//    requires: [
//        'Ext.plugin.Viewport',
//        'Ext.window.MessageBox',
//
//        'MyTorrent.view.main.MainController',
//        'MyTorrent.view.main.MainModel',
//        'MyTorrent.view.main.List'
//    ],
//
//    controller: 'main',
//    viewModel: 'main',
//
//    ui: 'navigation',
//
//    tabBarHeaderPosition: 1,
//    titleRotation: 0,
//    tabRotation: 0,
//
//    header: {
//        layout: {
//            align: 'stretchmax'
//        },
//        title: {
//            bind: {
//                text: '{name}'
//            },
//            flex: 0
//        },
//        iconCls: 'fa-th-list'
//    },
//
//    tabBar: {
//        flex: 1,
//        layout: {
//            align: 'stretch',
//            overflowHandler: 'none'
//        }
//    },
//
//    responsiveConfig: {
//        tall: {
//            headerPosition: 'top'
//        },
//        wide: {
//            headerPosition: 'left'
//        }
//    },
//
//    defaults: {
//        bodyPadding: 20,
//        tabConfig: {
//            plugins: 'responsive',
//            responsiveConfig: {
//                wide: {
//                    iconAlign: 'left',
//                    textAlign: 'left'
//                },
//                tall: {
//                    iconAlign: 'top',
//                    textAlign: 'center',
//                    width: 120
//                }
//            }
//        }
//    },
//
//    items: [{
//        title: 'Home',
//        iconCls: 'fa-home',
//        // The following grid shares a store with the classic version's grid as well!
//        items: [{
//            xtype: 'mainlist'
//        }]
//    }, {
//        title: 'Users',
//        iconCls: 'fa-user',
//        bind: {
//            html: '{loremIpsum}'
//        }
//    }, {
//        title: 'Groups',
//        iconCls: 'fa-users',
//        bind: {
//            html: '{loremIpsum}'
//        }
//    }, {
//        title: 'Settings',
//        iconCls: 'fa-cog',
//        bind: {
//            html: '{loremIpsum}'
//        }
//    }]
//});
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

    plugins: 'responsive',
    controller: 'main',
    viewModel: 'main',
    activeItem : 2,
    requires : [
//        'Ext.window.MessageBox'
          'Ext.MessageBox'
    ],
    tabBarPosition: 'bottom',
    defaults:{
        plugins: 'responsive',
        responsiveConfig: {
            'width < 500': {
                title: ''
            }
            
        },
        scrollable : true
    },
//    tabBarPosition: 'bottom',
    items: [
        {
            responsiveConfig: {
                'width < 500': {
                    title: '',
                    tooltip : 'Accueil'
                },
                'width >= 500': {
                    title: 'Accueil'
                }    
            },
            iconCls: 'x-fa fa-home',
            layout: 'fit',
            // The following grid shares a store with the classic version's grid as well!
            items: [{
                xtype: 'panelAccueil'
            }]
        },{
            responsiveConfig: {
                'width < 500': {
                    title: '',
                    tooltip : 'Recherche'
                },
                'width >= 500': {
                    title: 'Recherche'
                }    
            },
            iconCls: 'x-fa fa-search',
//            layout: 'fit',
//            height : 500,
            items: [{
                xtype: 'panelRecherche',
                plugins: 'responsive',
                responsiveConfig: {
                    'width >= height': {
//                        layout : 'hbox'
                        layout: {
                            type: 'box',
                            vertical: false,
                            align: 'stretch'
                        }
                    },
                    'width < height': {
//                        layout : 'vbox'
                        layout: {
                            type: 'box',
                            align: 'stretch',
                            vertical: true
                       }
                    }
                }
            }]
        },{
            responsiveConfig: {
                'width < 500': {
                    title: '',
                    tooltip : 'SeedBox'
                },
                'width >= 500': {
                    title: 'SeedBox'
                }    
            },
            iconCls: 'x-fa fa-cloud',
            items: [{
                xtype: 'seedbox'
                
            }]
        

        },{
            responsiveConfig: {
                'width < 500': {
                    title: '',
                    tooltip : 'Settings'
                },
                'width >= 500': {
                    title: 'Settings'
                }    
            },
            iconCls: 'x-fa fa-wrench',
            items: [{
                xtype: 'panelProfil'
            }]
        },
        {
//            xtype : 'button',
            responsiveConfig: {
                'width < 500': {
                    title: '',
                    tooltip : 'Logout'
                },
                'width >= 500': {
                    title: 'Logout'
                }    
            },
            handler: 'onLogoutButton',
            iconCls: 'x-fa fa-toggle-off ',
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
