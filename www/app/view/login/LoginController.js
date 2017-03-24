Ext = Ext ||'';
Ext.define('MyTorrent.view.login.LoginController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.login',

    onLoginClick: function(button) {
        
        var form = button.up('formpanel');
        button.setDisabled(true);
        var config = form.getConfig();
        config.success = this.success;
        config.failure = this.failure;
        eric = config;
//        form.setController(this);
        form.submit(config);
        

    },
    success: function() { 
        
        localStorage.setItem("MyTorrentLoggedIn", true);
        Ext.Msg.alert("success"); 
        this.getParent().getController().refreshApp();
    },
    failure: function() { 
        localStorage.setItem("MyTorrentLoggedIn", false);
        Ext.Msg.alert("error"); 
        this.getParent().getController().refreshApp();
    },
    
    refreshApp : function(){
//        //fonctionne pas probleme pour charger la Vue principale
//        // Remove Login Window
        loggedIn = localStorage.getItem("MyTorrentLoggedIn");
        if(loggedIn === Ext.returnTrue().toString()){
            MyTorrent.getApplication().recherchePlugins();
            this.getView().destroy();
        }

    }
});