/**
 * The main application class. An instance of this class is created by app.js when it
 * calls Ext.application(). This is the ideal place to handle application launch and
 * initialization details.
 */
Ext = Ext ||{};
Ext.define('MyTorrent.Application', {
    extend: 'Ext.app.Application',
    requires : [
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
    launch: function () {
        var node = Ext.getDom('loader_mask');
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
        // TODO - Launch the applicatio
        var loggedIn;

        // Check to see the current value of the localStorage key
        loggedIn = localStorage.getItem("MyTorrentLoggedIn");

        // This ternary operator determines the value of the TutorialLoggedIn key.
        // If TutorialLoggedIn isn't true, we display the login window,
        // otherwise, we display the main view
        if(loggedIn === Ext.returnTrue().toString()){
//            Ext.create({
//                xtype: 'mytorrent-main'
//            });  
             //defini plugin au composant qui ont en besoin
            this.recherchePlugins();
        }else{
            Ext.create({
                xtype:'login'
            }).show();
        }
        
       
        
        
        
//        eric = Ext.create('MyTorrent.store.Torrent',{url:'torrent.php',search:'eric'});
        
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
    listenersPlugins : []
    ,setPlugins : function (data){
        this.plugin = data;
    }
    ,getPlugins : function(){
        return this.plugin;
    },
    recherchePlugins : function (){
        var me = this;
        var token = localStorage.getItem("MyTorrentToken");
        Ext.Ajax.request({
               url :  'torrentJson.php'
               ,method : 'GET'
               ,params : {'plugin':'','token':token}
               ,success :function (response,opts){
                   var obj = Ext.decode(response.responseText);
                   if(obj.success){
                        me.setPlugins(obj.data);
                        me.pushListenersPlugins();
                   }else{
                       localStorage.removeItem("MyTorrentLoggedIn");
                       me.launch();
                   }
                   
                   
               }
               ,failure : function(response,opts){
                   console.log('failure plugins');
                   console.log(response,opts);
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
    }
});
