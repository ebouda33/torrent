/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


Ext.define('MyTorrent.view.recherche.Torrent',{
   extend : 'Ext.form.Panel' 
   ,xtype : 'rechercheTorrent'
    ,requires : [
         'Ext.form.FieldSet'
     ]
     ,style : {
                border: 'none'
            }
   ,items :[
       {
            xtype: 'fieldset',
            title: 'Plugins',
            defaultType : 'checkboxfield',
            width : 150,
            items : [
                
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
                label : plugin.name,
                name : plugin.name,
                inputValue : 654446,
                id : plugin.name,
                labelTextAlign : 'left',
                labelWidth : '80%'
            });
        });
        items[0].setItems(plugins);
        
    }
   
});