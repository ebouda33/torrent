/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if(Ext === undefined){
    Ext = {};
}

Ext.define('MyTorrent.view.recherche.Torrent',{
    extend : 'Ext.panel.Panel' 
    ,xtype : 'rechercheTorrent'
    ,requires : [
         'Ext.form.FieldSet'
    ]
    ,style : {
        border: 'none'
    },
//    plugins: 'responsive',
//    responsiveConfig: {
//        wide: {
//            width : '35%'
//        },
//        tall: {
//            width : '100%'
//        }
//    },
    bodyPadding: 20,
//    region : 'center',
    items :[
       {
            xtype: 'fieldset',
            title: 'Plugins',
            defaultType : 'radiofield',
            width : 150,
            items : [
                
            ]
        },
        {
            xtype : 'panel',
            
//            layout : 'hbox',
            items : [
                {
                xtype : 'textfield',
                name: 'search',
                allowBlank: false,  // requires a non-empty value,
                
                placeHolder : 'Entrez vos mots clés',
//                width : '50%',
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
                        field.setDisabled(true);
                        this.executeSearch(url,value,p);
//                        location.href = Ext.String.format(field.searchUrl, value);
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
                    
                    var store = Ext.create('MyTorrent.store.Torrent',{url:urlFull[0],search:value,plugins:p});
                    var gridResultat = me.getBubbleParent().getBubbleParent().grid;
                    gridResultat.setStore(store);
                    //rafraichir le grid
                    gridResultat.refreshGrid();
                    store.load({
                        scope: this,
                        callback: function(records, operation, success) {
                            // the operation object
                            // contains all of the details of the load operation
                            me.setDisabled(false);
                            if(!success){
                                Ext.Msg.show({
                                    title : 'Recherche Plugin',
                                    message : operation.error,
                                    buttons : Ext.MessageBox.OK,
                                    iconCls :  'x-fa fa-error' ,
                                    closable : true,
                                    height : 200
                                });
                            }
//                            console.log(records,operation,success);
                        }
                    });
                    

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
        if(items !== null){
            var plugins = [];
            Ext.each(data,function(plugin){
                plugins.push({
                    label : '<div><img src="'+plugin.icone+'" alt="'+plugin.name+'" />'+plugin.name+'</div>',
                    name : 'plugin',
                    inputValue : plugin.id,
                    id : plugin.name,
                    labelTextAlign : 'left',
                    labelWidth : '80%',
                    value : plugin.id
                });
            });
            items[0].setItems(plugins);
        }
        
    }
    ,setZoneResultat : function(grid){
        this.grid = grid;
    }
   
});