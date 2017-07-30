Ext = Ext || {};
Ext.define('MyTorrent.store.PlexFiles',{
	extend :'MyTorrent.store.GeneriqueTreeStore',
	constructor : function(config){
		var me = this;
		config = {
				fields : [
					{ name: 'id', type: 'string' },
					{ name: 'text', type: 'string' },
					{ name: 'name', type: 'string' },
					{ name: 'path', type: 'string' },
					{ name: 'leaf', type: 'boolean' }
				],
			action : 'PLEX_FILES'};
		
		
		me.callParent([config]);
	},
	
	alias : 'store.plexfiles',
	root: {
        text: 'Plex',
        expanded: false
    },
	listeners : {
		 load : function ( store, records, successful, operation, node, eOpts ){

			// console.log('Store PlexFiles');
		},
		
		nodeappend : function ( store, newChildNode, index, eOpts ){
			// console.log('node append',newChildNode,index);
			if( !newChildNode.isRoot() ) {
                if(newChildNode.get('leaf')){
					newChildNode.set('iconCls', 'x-fa fa-file-movie-o');
				}
            }
		}

	}
});