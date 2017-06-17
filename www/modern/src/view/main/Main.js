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
    activeItem : 1,

    requires : [
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
           layout: 'fit',
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
                    tooltip : 'Souhait'
                },
                'width >= 500': {
                    title: 'Souhait'
                }    
            },
            iconCls: 'x-fa  fa-paper-plane',
//            layout: 'fit',
//            height : 500,
            items: [{
                xtype: 'panelSouhait',
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
			scrollable : false,
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
    ],
    
    setMenu : function(index,visible){
        
    }
});
