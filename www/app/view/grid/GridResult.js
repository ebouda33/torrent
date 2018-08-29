var Ext = Ext || {};

Ext.define('MyTorrent.view.grid.GridResult',{
    extend : 'Ext.grid.Grid',
    xtype : 'torrentresult',
    title : 'Résultat',
    collapsible : true,

    requires : ['MyTorrent.store.Torrent'],
    emptyText : '<h1 style="margin:20px">No matching results</h1>',
    zoneSearch : null,
    listeners : {
      initialize : function(grid,eOpts){
          ancestor = grid.getBubbleParent().getItems().items;
          ancestor[0].setZoneResultat(grid);
      } ,
      itemsingletap : function (grid , row , target , record , e , eOpts ){
          //ask question
          var magnet = record.data.magnet;
          var txt = '';
          
          if(magnet !== undefined && magnet.indexOf('magnet')>=0){
              txt = magnet;
          }
          Ext.Msg.show({
                    title : 'DL?',
                    itemId : 'msgDL',
                    message : (txt === '')? 'Vous voulez vous dl le torrent ou envoyer à la seedbox?':'Copiez le Magnet ou envoyer à la seedbox?<br><textarea  rows="4" cols"50">'+txt+'</textarea>',
                    width : '80%',
                    height : 200,
                    buttons : [{
                            text : 'Magnet',
                            itemId : 'torrentMagnet',
                            hidden : (txt === ''),
                            handler : function(){
                                window.open(txt);
                            }
                            
                    },{
                        text : 'seedbox',
                        itemId : 'torrentSeedbox',
                        handler : function(){
                            var torrent = (record.data.magnet === undefined)?record.data.id:record.data.magnet;
                            grid.gotoTransmission(torrent);
                        }
                    },{
                      text : 'DL',
                      itemId : 'torrentDL',
                      hidden : (txt === '')?false:true,
                      handler : function(){
                          grid.downloadTorrent(record.data.id);
                      }
                    },
                    {
                        text : 'annuler',
                        itemId : 'cancel'
                    }
                    ],
                    iconCls :  'x-fa fa-question' ,
                    closable : false
                });
          target.setStyle({
             color : '#e1dede' 
          });
      },
      itemmouseenter : function (grid , row , target , record , e , eOpts ){
           target.setStyle({
               cursor : 'pointer'
           });
      },
      itemmouseleave : function (grid , row , target , record , e , eOpts ){
          target.setStyle({
               cursor : 'default'
           });
      }
    },
    
    columns: [
        {
          text : '',
          dataIndex: 'category',
          width: 50,
          resizable : true,
          align : 'left',
          
           renderer : function (value,record,index,cell){
               //console.log(container,position);
               if(value.indexOf('data:image')>-1){
                    cell.setEncodeHtml (false);
                    return '<img src="'+value+'" alt=\''+record.data.categoryLabel+'\' title=\''+record.data.categoryLabel+'\' />';
                }
                return record.data.categoryLabel;
                
            }
        },
        {
            text: 'Torrent',
            dataIndex: 'title',
//                    sortable: false,  // column cannot be sorted
            width: 250,
            flex : 1,
            resizable : true,
            align : 'left'
        },
        {
            text: 'Size',
            dataIndex: 'size',
//                    hidden: true  // column is initially hidden
            resizable : true,
            width : 70,
            align : 'left',
            renderer : function(value,record){
                return MyTorrent.util.Util.getSizeLitteral(value);
            }

        },
        {
            text: 'Seeders',
            dataIndex: 'seeder',
            width: 70,
            resizable : true,
            align : 'left'
        },
        {
            text: 'Leechers',
            dataIndex: 'leecher',
            width: 70,
            resizable : true,
            align : 'left'
        },
        {
            text : 'Use Transmission',
            dataIndex: 'magnet',
            width: 150,
            resizable : false,
            align : 'left',
            renderer : function (value,record){
                var message = 'Goto MySeedBox';
                return message;
            }

        }
    ],
    refreshGrid : function (){
      var me = this;
      var items = me.items.items[0].items.items;
      Ext.each(items,function(item,index){
         item.setStyle({
          color : '#000' 
      }); 
      });
      
    },
    gotoTransmission : function (torrent){
        var me  =this;
//        console.log('prise en compte de ...'+torrent);
//        console.log('encode '+Ext.encode(torrent));
        if(torrent.indexOf('&tr')>0){
            torrent = torrent.replace(/&tr/g,'@');
        }
        //recuperer la location avant
        var location = me.zoneSearch.getSelectedLocation();
        if(location !== null){
            var plugin = me.zoneSearch.getPluginUse();
            Ext.Ajax.request({
                url :  'torrentJson.php'
                ,method : 'GET'
                ,params : 'action=transmission&token='+localStorage.getItem("MyTorrentToken")+'&transmission='+torrent+'&location='+location+'&plugin='+Ext.JSON.encode(plugin)
                ,success :function (response,opts){
                    var obj = Ext.decode(response.responseText);
                    var icon = 'x-fa fa-info' ;
                    var message = 'Lien envoyer au serveur Transmission, dans quelques temps le fichier sera dispo';
                    if(!obj.success){
                        message = obj.message;
                        icon = 'x-fa fa-warning' ;
                    }
                    if(obj.duplicate !== undefined && obj.duplicate){
                        message = 'Torrent existant';
                    }
                    Ext.Msg.show({
                        title : 'Envoi Transmission',
                        itemId : 'msgTransmission',
                        message : message,
                        buttons : Ext.MessageBox.OK,
                        iconCls :  icon ,
                        closable : true,
                        height : 200
                    });


                }
                ,failure : function(response,opts){
                    icon = 'x-fa fa-error' ;
                    Ext.Msg.show({
                        title : 'Envoi Transmission',
                        message : response.statusText,
                        buttons : Ext.MessageBox.OK,
                        iconCls :  icon ,
                        closable : true
                    });
                }
            });
        }else{
            Ext.Msg.alert('Vous avez oublie','Selectionnez la location pour envoyer vers la SeedBox.');
        }

    },
    downloadTorrent : function(id){
        var me = this;
        //recuperer la location avant
        var plugin = me.zoneSearch.getPluginUse();
        plugin = Ext.JSON.encode(plugin);
//        console.log('torrentJson.php?action=download&id='+id+'&plugin='+plugin+'&token='+localStorage.getItem("MyTorrentToken"));
        window.location = 'torrentJson.php?action=download&id='+id+'&plugin='+plugin+'&token='+localStorage.getItem("MyTorrentToken");
        
        
    },
    setSearchZone : function (zone){
        var me = this;
        me.zoneSearch = zone;
    },
    preventStore : function (store) {
        const plugins = this.getPlugins();
        const plugin = plugins.filter(plugin => {
            return plugin.type === "gridpagingtoolbar";
        })[0];
        plugin.setPageSize(store.pageSize);
        plugin.setTotalPages(Math.ceil(store.getTotalCount()/plugin.getPageSize()));

    }

});