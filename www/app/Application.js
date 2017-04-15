/**
 * The main application class. An instance of this class is created by app.js when it
 * calls Ext.application(). This is the ideal place to handle application launch and
 * initialization details.
 */
Ext = Ext ||{};
Ext.define('MyTorrent.Application', {
    extend: 'Ext.app.Application',
    requires : [
        'MyTorrent.util.Util',
        'MyTorrent.view.main.MainController',
        'MyTorrent.view.main.Accueil',
        'MyTorrent.view.main.Profil',
        'MyTorrent.view.main.Recherche',
        'MyTorrent.view.main.SeedBox',
        
        'MyTorrent.view.login.Login',
        'MyTorrent.view.main.Main'
    ],
    name: 'MyTorrent',
    views: [
        'MyTorrent.view.login.Login',
        'MyTorrent.view.main.Main'
    ],
    stores: [
        // TODO: add global / shared stores here
    ],
    quickTips : true,
    listenersPlugins : [],
    listenersSettings : [],
    storeSeedBox : null,
    plugin : null,
    configPanel : null,
    launch: function () {
        var node = Ext.getDom('loader_mask');
        
        // TODO - Launch the applicatio
        var loggedIn;

        // Check to see the current value of the localStorage key
        loggedIn = localStorage.getItem("MyTorrentLoggedIn");

        // This ternary operator determines the value of the TutorialLoggedIn key.
        // If TutorialLoggedIn isn't true, we display the login window,
        // otherwise, we display the main view
        if(loggedIn === Ext.returnTrue().toString()){
            this.logged();
            
        }else{
            Ext.create({
                xtype:'login'
            }).show();
        }
        
        Ext.Anim.run(node,'fade',
        {
            out : true,
            duration: 1000,
            to : {
                opacity : 0
            }
            ,after : function(){
                Ext.removeNode(node);
            }
        });
        
    },

    onAppUpdate: function () {
        Ext.Msg.confirm('Application Update', 'This application has an update, reload?',
            function (choice) {
                if (choice === 'yes') {
                    window.location.reload();
                }
            }
        );
    },
    
    setPlugins : function (data){
        this.plugin = data;
    }
    ,getPlugins : function(){
        return this.plugin;
    }
    ,setConfigPanel : function(panel){
        this.configPanel = panel;
    },
    logged : function(){
        //construction de la seedbox affecter le store au grid
            MyTorrent.getApplication().gridSeedBox.setStore(Ext.create('MyTorrent.store.SeedBox',{}));
            MyTorrent.getApplication().setStoreSeedBox(MyTorrent.getApplication().gridSeedBox.getStore());    
            //determine si seedbox autorise ou non
            localStorage.setItem("MyTorrentSeebBox",false);
            
            this.recherchePlugins();
    },
    recherchePlugins : function (){
        var me = this;
        var token = localStorage.getItem("MyTorrentToken");
        Ext.Ajax.request({
               url :  'torrentJson.php'
               ,method : 'GET'
               ,params : {'action':'plugin','token':token}
               ,success :function (response,opts){
                   var obj = Ext.decode(response.responseText);
                   if(obj.success){
                        me.setPlugins(obj.data);
                        me.pushListenersPlugins();
                        me.loadSettings();
                   }else{
                       localStorage.removeItem("MyTorrentLoggedIn");
                       me.launch();
                   }
                   
                   
               }
               ,failure : function(response,opts){
                   console.log('failure plugins');
//                   console.log(response,opts);
               }
                   
            });
    },
    setListenersPlugins : function(elem){
        this.listenersPlugins.push(elem);
    },
    pushListenersPlugins : function(){
        var me = this;
        Ext.each(this.listenersPlugins,function(elem){
            elem.setPlugins(me.getPlugins());
        }
        );
    },
    setListenersSettings : function(elem){
      this.listenersSettings.push(elem);  
    },
    pushListenersSettings : function(data){
        Ext.each(this.listenersSettings,function(elem){
            elem.setSettings(data);
        }
        );
    },
    loadSettings : function(){
        var me = this;
        var token = localStorage.getItem("MyTorrentToken");
        Ext.Ajax.request({
               url :  'torrentJson.php'
               ,method : 'GET'
               ,params : {action:'settings',config:'load', token:token}
               ,success :function (response,opts){
                   var obj = Ext.decode(response.responseText);
                   if(obj.success){
                       me.configPanel.setSettings(obj.data);
                       //doit determiner affichage ou non de la seedbox; 
                        var main = MyTorrent.app.viewport.getItems().items[0];
                        var appmain = main.getItems().items[1];
                        var bar = appmain.getTabBar();
                        var seed = bar.getItems().items[2];
                        if(obj.data['transmission_url'] !== undefined){
                            seed.setHidden(false);
                            MyTorrent.getApplication().loadStoreSeedBox();
                        }else{
                            seed.setHidden(true);
                            bar.setActiveTab(0);
                            
                        }
                        me.pushListenersSettings(obj.data);
                       localStorage.setItem("MyTorrentSeebBox",!seed.isHidden());
                   }
                   
                   
               }
               ,failure : function(response,opts){
                   console.log('failure load settings');
//                   console.log(response,opts);
               }
                   
            });
    },
    setStoreSeedBox : function (store){
        this.storeSeedBox = store;
    },
    loadStoreSeedBox :function(){
        
        MyTorrent.getApplication().storeSeedBox.load({
            scope: this,
            callback: function(records, operation, success) {
                // the operation object
                // contains all of the details of the load operation
                if(!success){
                    Ext.Msg.show({
                        title : 'Recherche Seedbox',
                        message : operation.error,
                        buttons : Ext.MessageBox.OK,
                        iconCls :  'x-fa fa-error' ,
                        closable : true,
                        height : 200
                    });
                }

            }
        });
    }
});
