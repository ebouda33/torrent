Ext = Ext ||'';
Ext.define('MyTorrent.view.settings.SettingsController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.settings',

    onSettingsClick: function(button) {
        var me = this;
        var form = button.up('formpanel');
        button.setDisabled(true);
        var config = form.getConfig();
//        eric = form;
        var cmps = config.items.items;
        eric = form;
        form.setMasked(true);
        var params = {
            action : 'settings',
            token : localStorage.getItem("MyTorrentToken")
        };
        Ext.each(cmps,function(fieldset,index){
            if(fieldset.xtype === 'fieldset'){
                var cmp = fieldset.getItems().items;
                Ext.each(cmp,function(c,index){
                    if(c.xtype === 'fieldset'){
                        var cmp = c.getItems().items;
                        Ext.each(cmp,function(c1,index){
                            if(c1.xtype !== 'title'){
                                params[c1.getName()] = c1.getValue();
                            }
                        });
                    }else if(c.xtype !== 'title'){
                        params[c.getName()] = c.getValue();
                    }
                });

            }
        });
        
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
                   button.setDisabled(false);
                   form.setMasked(false);
               }
               ,failure : function(response,opts){
                   me.failure(me,button);
                   button.setDisabled(false);
                   form.setMasked(false);
               }
               
        });
        

    },
    success: function(controller,name,token) { 
        
    },
    failure: function(controller,button) { 
        
        
    }
    
    
});