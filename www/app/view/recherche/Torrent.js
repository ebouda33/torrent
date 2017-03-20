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
                    if (e.getKey() === e.ENTER && !Ext.isEmpty(value) && field.getPluginsUse() !== undefined && field.getPluginsUse().length > 0 ) {
                        var p = Ext.encode(field.getPluginsUse(field));
                        var url = Ext.String.format(field.searchUrl, value,p);
                        this.executeSearch(url,value,p);
//                        location.href = Ext.String.format(field.searchUrl, value);
                        field.setDisabled(true);
                    }
                },
                getPluginsUse : function(){
//                    var plugins = MyTorrent.getApplication().getPlugins();
                    var plugins = [];
                    var ancestor = this.getBubbleParent().getBubbleParent();
                    var enfants = ancestor.getItems().items;
                    //prise en compte que des checkbox
                    var plugSelect = enfants[0].getItems().items;
                    //demarre a 1 a cause du titre
                    for(var i =1;i < plugSelect.length;i++){
                        if(plugSelect[i].isChecked()){
                            plugins.push({name: plugSelect[i].getName(),id:plugSelect[i].getValue()});
                        }
                    }
                    return plugins;
                },
                executeSearch : function(url,value,p){
//                    console.log(url);
                    var me = this;
                    var urlFull = url.split("?");
                    
                    var store = Ext.create('Ext.data.JsonStore', {
                                fields: [
                                   {name: 'titre'},
                                   {name: 'size'},
                                   {name: 'url'},
                                   {name: 'leecher'},
                                   {name: 'seeder'}

                                ],
                                proxy : {
                                    type : 'ajax',
                                    url :  urlFull[0]
                                    ,method : 'GET'
                                    ,extraParams : {'search':value,'plugins':p}
                                    
                                    ,reader : {
                                        type : 'json',
                                        rootProperty : 'data'
                                    }
                                }
                                
                    });
                    var gridResultat = me.getBubbleParent().getBubbleParent().grid;
                    gridResultat.setStore(store);
                    store.load();
                    this.setDisabled(false);
//                    Ext.Ajax.request({
//                        url :  urlFull[0]
//                        ,method : 'GET'
//                        ,params : urlFull[1]
//                        ,success :function (response,opts){
//                            var obj = Ext.decode(response.responseText);
////                            console.log(response,opts);
//                            var store = Ext.create('Ext.data.JsonStore', {
//                                fields: [
//                                   {name: 'titre'},
//                                   {name: 'size'},
//                                   {name: 'url'},
//                                   {name: 'leecher'},
//                                   {name: 'seeder'}
//
//                                ],
//                                root : 'data',
//                                
//                                
//                            });
//                            eric = store;
//                            var gridResultat = me.getBubbleParent().getBubbleParent().grid;
//                            
//                            gridResultat.setStore(store);
//                            
//
//                        }
//                        ,failure : function(response,opts){
//                            console.log('failure plugins');
//                            console.log(response,opts);
//                        }
//
//                     });
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
                inputValue : plugin.id,
                id : plugin.name,
                labelTextAlign : 'left',
                labelWidth : '80%',
                value : plugin.id
            });
        });
        items[0].setItems(plugins);
        
    }
    ,setZoneResultat : function(grid){
        this.grid = grid;
    }
   
});