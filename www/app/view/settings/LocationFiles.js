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
    readOnly : false,
    initialize: function() {
        var me = this;
 
        me.callParent();
        me.getComponent().parent = me;
        
        var items = me.getComponent().getItems().items ;
        items[0].setPlaceHolder( me.placeHolder || 'Chemin vers une zone de DL, return pour ajouter');
        if(me.readOnly){
            me.setLabel('');
        }
        items[0].setHidden(me.readOnly);
        
        items[1].setHideHeaders(me.readOnly);
        
    },
    
    addLibrary : function(value){
        var me = this;
        if(value !== null){
            if(Array.isArray(value)){
                Ext.Array.each(value,function(lib,index){
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
		if(value != undefined){
			var me = this;
			me.libraries = [];
			if(!Array.isArray(value)){
				value = value.split('|');
			}
			me.addLibrary(value);
			var items = me.getComponent().getItems().items ;
			var data = [];
			Ext.Array.each(me.libraries,function(location){
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
        }
    },
    getSelectedValue : function(){
        var me = this;
        var items = me.getComponent().getItems().items ;
        var grid = items[1];
        if(grid.getSelection() !== null){
            return grid.getSelection().data.location;
        }
        return null;
    },
    component :
        
        {
            xtype : 'panel',
//            padding : -10,
            label : null,
            labelWidth : 0,
            margin : 0,
            layout : 'vbox',
			scrollable:true,
            width: '100%',
			height:'100%',
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
                            component.setValue(component.libraries);
                            field.setValue('');
                            field.focus();
                            
                        }
                    }

                },{
                    xtype : 'grid',
                    minHeight : 200,
                    tbar : 'bottom',
					scrollable : true,
                    listeners :{
                        
                      itemtap : function(grid , index , target , record , e , eOpts){
                          var store = grid.getStore();
                          grid.deleteLocation(store,index);
                      } 
                    },
                    height : '100%',
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
                                var me = this;
                                var parent  = me.getParent().getParent().getParent();
                                var component = parent.getParent();
                                if(!component.readOnly){
                                    cell.setEncodeHtml (false);
                                    return "<div class='x-fa fa-trash' style='cursor:pointer;'></div>";

                                }
                                return '';
                            }
          
                       }
                    ],
                    deleteLocation : function(store,ligne){
                        var me = this;
                        var parent  = me.getParent();
                        component = parent.getParent();
                        if(!component.readOnly){
                            store.removeAt(ligne);
                            var elements = [];
                            Ext.Array.each(store.data.items,function(row){
                                elements.push(row.data.location);
                            });
                            component.setValue(elements);
                        }
                    }
                }
            ]
        }
        
    
});
