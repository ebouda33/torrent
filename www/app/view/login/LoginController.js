Ext = Ext ||'';
Ext.define('MyTorrent.view.login.LoginController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.login',

    onLoginClick: function(button) {
        var me = this;
        controller = me;
        var form = button.up('formpanel');
        button.setDisabled(true);
        var config = form.getConfig();
        eric = config;
//        form.setController(this);
//        form.submit(config);
        var cmps = config.items.items; 
        Ext.Ajax.request({
            url :  'torrentJson.php'
               ,method : 'GET'
               ,params : {'login':'','user':'','password':''}
//               ,extraParams : {'user':'','password':''}
               ,success :function (response,opts){
                   var obj = Ext.decode(response.responseText);
                   if(obj.success){
                       me.success(me);
                   }else{
                       me.failure(me);
                   }
                   
               }
               ,failure : function(response,opts){
                   me.failure(me);
               }
               
        });
        

    },
    success: function(controller) { 
        
        localStorage.setItem("MyTorrentLoggedIn", true);
        Ext.Msg.alert("success"); 
        controller.getParent().getController().refreshApp();
    },
    failure: function(controller) { 
        localStorage.setItem("MyTorrentLoggedIn", false);
        Ext.Msg.alert("error"); 
        controller.getParent().getController().refreshApp();
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