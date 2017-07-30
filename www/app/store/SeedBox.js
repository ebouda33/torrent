Ext = Ext || {};
Ext.define('MyTorrent.store.SeedBox',{
	extend : 'MyTorrent.store.GeneriqueStore',
	constructor : function(config){
		var me = this;
		config = {
				fields : [
					{name: 'doneDate'},
					{name: 'name'},
					{name: 'totalSize'},
					{name: 'haveValid'},
					{name: 'status'},
					{name: 'id'},
					{name: 'uploadRatio'}
				],
			action : 'seedbox'};
		
		
		me.callParent([config]);
	}
	
}
);