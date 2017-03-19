/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if(Ext === undefined){
    Ext = {};
}

Ext.define('MyTorrent.view.recherche.Torrent',{
    extend : 'Ext.form.Panel' 
    ,xtype : 'rechercheTorrent'
    ,requires : [
         'Ext.form.FieldSet'
    ]
    ,style : {
                border: 'none'
    }
    ,bodyPadding : 10
    ,items :[
       {
            xtype: 'fieldset',
            title: 'Plugins',
            defaultType : 'checkboxfield',
            width : 150,
            items : [
                
            ]
        },
        {
            xtype : 'panel',
            layout : 'hbox',
            items : [
                {
                xtype : 'textfield',
                name: 'search',
                allowBlank: false,  // requires a non-empty value,
                
                placeHolder : 'Entrez vos mots clés',
                width : '50%',
                inputType: 'search',

                // Config defining the search URL
                searchUrl: 'torrentJson.php?search={0}&plugins={1}',
                 // Add specialkey listener
                listeners : {
                    initialize : function(textfield,eOpts){
                       
                    },
                    keyup : function ( field , e , eOpts){
                        
//                        console.log(plugins);
                        field.checkEnterKey(field,e);
                    }
                 },
                
                // Handle enter key presses, execute the search if the field has a value
                checkEnterKey: function(field, e) {
                    var value = field.getValue();
                    if (e.getKey() === e.ENTER && !Ext.isEmpty(value)) {
                        var p = Ext.encode(field.getPluginsUse(field));
                        console.log(Ext.String.format(field.searchUrl, value,p));
//                        location.href = Ext.String.format(field.searchUrl, value);
                    }
                },
                getPluginsUse : function(field){
                    var ancestor = field.getBubbleParent().getBubbleParent().getItems();
                    var plugins = ancestor.items[0].items.items;
                    
                    plugins = [{id:2323},{id:98988}];
                    
                    return plugins;
                }
            }
            ]
        }
        
    ]
  ,listeners :{
        initialize : function (panel,eOpts){
            MyTorrent.getApplication().setListenersPlugins(panel);

        }
        
    }     
    ,setPlugins : function(data)  {
        var items = this.getItems().items;
        var plugins = [];
        Ext.each(data,function(plugin){
            plugins.push({
                label : '<div><img src="data:image/jpg;base64,'+plugin.icone+'" alt="'+plugin.name+'" />'+plugin.name+'</div>',
                name : plugin.name,
                inputValue : 654446,
                id : ''+plugin.name,
                labelTextAlign : 'left',
                labelWidth : '80%',
                value : plugin.id
            });
        });
        items[0].setItems(plugins);
        
    }
   
});