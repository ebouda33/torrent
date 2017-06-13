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
            action : 'login'
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
                       me.failure(me,button);
                   }
                   
               }
               ,failure : function(response,opts){
                   me.failure(me,button);
               }
               
        });
        

    },
    success: function(controller,name,token) { 
        
        localStorage.setItem("MyTorrentLoggedIn", true);
        localStorage.setItem("MyTorrentToken", token);
        //TODO charger fichier config
        Ext.Msg.alert("Bienvenue "+name); 
        controller.refreshApp();
    },
    failure: function(controller,button) { 
        localStorage.removeItem("MyTorrentLoggedIn");
        localStorage.removeItem("MyTorrentToken");
        button.setDisabled(false);
        button.setText('Incorrect identification');
        button.setStyle({color:'red'});
        
    },
    
    refreshApp : function(){
//        //fonctionne pas probleme pour charger la Vue principale
//        // Remove Login Window
        loggedIn = localStorage.getItem("MyTorrentLoggedIn");
        token = localStorage.getItem("MyTorrentToken");
        
        if(loggedIn === Ext.returnTrue().toString() && token !== undefined){
            MyTorrent.getApplication().logged();
            this.getView().destroy();
        }

    }
});