Ext = Ext || {};


Ext.define('MyTorrent.view.settings.LocationFiles',{
    extend : 'Ext.field.Field',
    xtype : 'locationFiles',
    alias:['widget.locationFiles','widget.libraries'],
    label : 'Bibliotheques',
    name : 'library',
    id : 'library',
//    requires : ['Ext.ux.form.MultiSelect'],
    libraries : [],
    initialize: function() {
        var me = this;
 
        me.callParent();
        me.getComponent().parent = me;
        
        var items = me.getComponent().getItems().items ;
        items[0].setPlaceHolder( me.placeHolder || 'Chemin vers une zone de DL, return pour ajouter');
    },
    addLibrary : function(value){
        var me = this;
        if(value !== null){
            if(Array.isArray(value)){
                Array.forEach(value,function(lib,index){
                    me.libraries.push(lib);  

                });
            }else{
                me.libraries.push(value.trim());  
            
            }
        }
    },
    getValue : function(){
        var libraries = this.libraries.join('|');
        return libraries;
    },
    setValue : function(value){
        var me = this;
        me.libraries = [];
        if(!Array.isArray(value)){
            value = value.split('|');
        }
        me.addLibrary(value);
        var items = me.getComponent().getItems().items ;
        var data = [];
        Array.forEach(me.libraries,function(location){
            data.push({'location':location});
        });
        var store = Ext.create('Ext.data.Store', {
                    fields: ['location'],
                    data: data
                });
        if(items[1].getStore() !== null){        
            items[1].getStore().removeAll(); 
        }       
        items[1].setStore(store);
        
    },
    component :
        
        {
            xtype : 'panel',
//            padding : -10,
            label : null,
            labelWidth : 0,
            margin : 0,
            layout : 'vbox',
            width: '100%',
            items:[
                {
                    xtype: 'textfield',
//                    placeHolder : 'Chemin vers une zone de DL, return pour ajouter',
//                  width: '100%',
                    label : null,
                    labelWidth : 0,
                    listeners :{
                        action : function(field,evt,eOpts){
                            evt.stopPropagation();
                            var parent  = field.getParent();
                            var component = parent.getParent();
                            var value = field.getValue() !== null?field.getValue().trim():null;
                            component.addLibrary(value);
                            component.setValue(component.librairies);
                            field.setValue('');
                            field.focus();
                            
                        }
                    }

                },{
                    xtype : 'grid',
                    height : 200,
                    tbar : 'bottom',
                    listeners :{
                      itemtap : function(grid , index , target , record , e , eOpts){
                          var store = grid.getStore();
                          eric =grid;
                          grid.deleteLocation(store,index);
                      }  
                    },
                    plugins: {
                        type: 'multiselection',
                        triggerText: 'My Select Button',
                        cancelText: 'Forget about it',
                        deleteText: 'Get outta here'
                    },
                    layout : 'fit',
                    
                    columns: [
                            {
                         text : 'Destination',
                         dataIndex: 'location',
                         flex : 1,   
                       align : 'left'
                    
                       },
                       {
                           width:30,
                           renderer : function(value,record,index,cell){
//                               e = this;
//                               var grid = this.getParent().getParent();
//                               console.log(this);
                               cell.setEncodeHtml (false);
                                return "<div class='x-fa fa-trash' style='cursor:pointer;'></div>";
                            }
                            
          
                       }
                    ],
                    deleteLocation : function(store,ligne){
                        var me = this;
                        var parent  = me.getParent();
                        component = parent.getParent();
                        store.removeAt(ligne);
                        var elements = [];
                        Array.forEach(store.data.items,function(row){
                            elements.push(row.data.location);
                        });
                        component.setValue(elements);
                    }
                }
            ]
        }
        
    
});
