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
    
    cmpPlugins : null,
    items :[{
            xtype : 'panel',
            layout : 'hbox',
            items:[
                {
                     xtype: 'fieldset',
                     title: 'Plugins',
                     defaultType : 'radiofield',
                     width : 150,
                     items : [

                     ]
                 },{
                     xtype : 'selectfield',
                     showAnimation : 'fadeIn'
//                     label:'Catégories'
                 }
                
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
                    //recuperer la location avant
                    var value = field.getValue();
                    if (e.getKey() === e.ENTER && !Ext.isEmpty(value) && field.getPluginsUse() !== undefined && field.getPluginsUse().length > 0 ) {
                            var p = Ext.encode(field.getPluginsUse());
                            var url = Ext.String.format(field.searchUrl, value,p);
                            field.setDisabled(true);
                            this.executeSearch(url,value,p);
                        
//                        location.href = Ext.String.format(field.searchUrl, value);
                    }else if(e.getKey() === e.ENTER){
                        Ext.Msg.alert('Choose Plugin');
                    }
                },
                getPluginsUse : function(){
                    var me = this;
//                    var plugins = MyTorrent.getApplication().getPlugins();
                    var plugins = [];
                    var ancestor = this.getBubbleParent().getBubbleParent();
//                    var enfants = ancestor.getItems().items;
                    var cmp = ancestor.cmpPlugins;
                    //prise en compte que des checkbox
                    var plugSelect = cmp.getItems().items;
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
                    var ancestor = me.getBubbleParent().getBubbleParent();
                    var categorie = ancestor.getCategorie();
                    var store = Ext.create('MyTorrent.store.Torrent',{url:urlFull[0],search:value,plugins:p,categorie:categorie});
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
        },
        {
            xtype:'fieldset',
            title : 'Location for Download',
            // height:200,
            items:[
                {
                    xtype:'panel',
                    html : 'Aucune seedBox Configurer voir les settings'
                    
                },{
                    xtype : 'libraries',
                    layout : 'fit',
                    name : 'bibliothequeSearch',
                    id : 'bibliothequeSearch',
                    readOnly : true
                }
            ],
            listeners : {
                    initialize : function(panel,eopts){
                    MyTorrent.getApplication().setListenersSettings(panel);

                }
            },
            setSettings : function(data){
                var me = this;
                var items = me.getItems().items;
                items[1].setValue(data.bibliotheque);
                if(data.bibliotheque.trim().length > 1){
                    items[0].setHidden(true);
                }
            }
        }
        
    ]
  ,listeners :{
        initialize : function (panel,eOpts){
            var me = this;
            var plugins = panel.getItems().items[0];
            me.cmpPlugins = plugins.getItems().items[0];
            MyTorrent.getApplication().setListenersPlugins(panel);

        }
        
        
    }   
    ,setPlugins : function(data)  {
        var cmp = this.cmpPlugins;
        if(cmp !== null){
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
            cmp.setItems(plugins);
            //remplir les categories;
            var cat = cmp.getParent().getItems().items[1];
            Ext.Ajax.request({
                url: 'torrentJson.php',
                method : 'GET',
                params : {action:'categories',token:localStorage.getItem("MyTorrentToken")},
                async : false,
                success: function(response, opts) {
                    var obj = Ext.decode(response.responseText);
//                    console.dir(obj);
                    if(obj.success){
                        var options = [];
                        Ext.Array.each(obj.data,function(cat){
                            options.push({text:cat.text,value:cat.value});
                        });
                        cat.setOptions(options);
                    }
                },

                failure: function(response, opts) {
                    console.log('server-side failure with status code ' + response.status);
                }
            });
           
        }
        
    }
    ,setZoneResultat : function(grid){
        this.grid = grid;
        grid.setSearchZone(this);
    }
   , getSelectedLocation : function(){
        var enfants = this.getItems().items;
        //prise en compte que des checkbox
        var component = enfants[2].getItems().items[1];
        return component.getSelectedValue();
    }
    ,getCategorie : function(){
        var enfants = this.getItems().items;
        var component = enfants[0].getItems().items[1];
        return component.getSelection().data.value;
    },
    getPluginUse : function(){
        var enfants = this.getItems().items;
        var component = enfants[1].getItems().items[0];
        return component.getPluginsUse();
    }
});