Ext = Ext || {};
Ext.define('MyTorrent.store.Torrent',{
    extend :'Ext.data.JsonStore',
    alias : 'store.torrent',
    autoLoad: false,
    requires: [
        'Ext.data.proxy.Ajax',
        'Ext.data.reader.Json',
        'Ext.data.writer.Json'
    ],
    constructor : function(config){
        var me = this;
        config.url = config.url || '';
        config.search = config.search || '';
        config.plugins = config.plugins ||[];
        config.fields = config.fields || [
            {name: 'title'},
            {name: 'size'},
            {name: 'magnet'},
            {name: 'leecher'},
            {name: 'seeder'},
            {name: 'category'}

         ];
        config = Ext.apply({
            url : config.url,
            search : config.search,
            plugins : config.plugins,
            proxy : {
                type : 'ajax',
                url :  config.url
                ,method : 'GET'
                ,extraParams : {'search':config.search,'plugins':config.plugins,token:localStorage.getItem("MyTorrentToken")}
                ,reader : {
                    type : 'json',
                    rootProperty : 'data',
                    totalProperty : 'totalCount',
                    successProperty : 'success',
                    messageProperty: 'message'
                }
            }
        },config);
        me.callParent([config]);
    },
    getParams : function(){ return this.proxy.extraParams;  }
    
    


}
);