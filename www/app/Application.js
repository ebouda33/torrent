/**
 * The main application class. An instance of this class is created by app.js when it
 * calls Ext.application(). This is the ideal place to handle application launch and
 * initialization details.
 */
Ext.define('MyTorrent.Application', {
    extend: 'Ext.app.Application',
    
    name: 'MyTorrent',

    stores: [
        // TODO: add global / shared stores here
    ],
    
    launch: function () {
        // TODO - Launch the application
        this.recherchePlugins();
        //defini plugin au composant qui ont en besoin
        
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
        Ext.Ajax.request({
               url :  'torrentJson.php'
               ,method : 'GET'
               ,params : 'plugin'
               ,success :function (response,opts){
                   var obj = Ext.decode(response.responseText);
                   me.setPlugins(obj.data);
                   me.pushListenersPlugins();
                   
                   
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
