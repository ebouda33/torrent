Ext = Ext ||'';
Ext.define('MyTorrent.view.login.LoginController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.login',

    onLoginClick: function(button) {
        var me = this;
        var form = button.up('formpanel');
        button.setDisabled(true);
        var config = form.getConfig();
        
        var cmps = config.items.items;
        var params = {
            login : ''
        };
        params[cmps[0].getName()] = cmps[0].getValue();
        params[cmps[1].getName()] = cmps[1].getValue();
        
        Ext.Ajax.request({
            url :  'torrentJson.php'
               ,method : 'GET'
               ,params : params
//               ,extraParams : {'user':'','password':''}
               ,success :function (response,opts){
                   var obj = Ext.decode(response.responseText);
                   if(obj.success){
                       me.success(me,obj.name,obj.data);
                   }else{
                       me.failure(me);
                   }
                   
               }
               ,failure : function(response,opts){
                   me.failure(me);
               }
               
        });
        

    },
    success: function(controller,name,token) { 
        
        localStorage.setItem("MyTorrentLoggedIn", true);
        localStorage.setItem("MyTorrentToken", token);
        Ext.Msg.alert("Bienvenue "+name); 
        controller.refreshApp();
    },
    failure: function(controller) { 
        localStorage.removeItem("MyTorrentLoggedIn");
        localStorage.removeItem("MyTorrentToken");
        
    },
    
    refreshApp : function(){
//        //fonctionne pas probleme pour charger la Vue principale
//        // Remove Login Window
        loggedIn = localStorage.getItem("MyTorrentLoggedIn");
        token = localStorage.getItem("MyTorrentToken");
        
        if(loggedIn === Ext.returnTrue().toString() && token !== undefined){
            MyTorrent.getApplication().recherchePlugins();
            this.getView().destroy();
        }

    }
});