Ext = Ext || {};
Ext.define('MyTorrent.store.SeedBox',{
    extend :'Ext.data.JsonStore',
    alias : 'store.seedbox',
    autoLoad: false,
    requires: [
        'Ext.data.proxy.Ajax',
        'Ext.data.reader.Json',
        'Ext.data.writer.Json'
    ],
    constructor : function(config){
        var me = this;
        config.url = config.url || 'torrentJson.php';
        config.fields = config.fields || [
            {name: 'doneDate'},
            {name: 'name'},
            {name: 'totalSize'},
            {name: 'haveValid'},
            {name: 'status'},
            {name: 'id'},
            {name: 'uploadRatio'}

         ];
        config = Ext.apply({
            url : config.url,
            proxy : {
                type : 'ajax',
                url :  config.url
                ,method : 'GET'
                ,extraParams : {action:'seedbox',token:localStorage.getItem("MyTorrentToken")}
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