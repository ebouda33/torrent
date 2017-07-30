Ext.define('MyTorrent.view.main.Souhait',{
    extend: 'Ext.container.Container'
    ,xtype: 'panelSouhait'
	,requires: [
      'MyTorrent.store.PlexFiles',
	]
	,height : '100%'
	,scrollable : true
	,defaults :{
		minHeight : 400,
		xtype : 'panel',
		// layout : 'fit',
		height : '100%',
		width : '50%',
		scrollable : true
	}
	,plugins: 'responsive'
	,split : true
	,items : [
		{
			title : 'Les envies',
			
			html : 'liste envies',
			plugins: 'responsive',
			responsiveConfig: {
                wide: {
                    
                },
                tall: {
                    width : '100%'
                }
            },
			
		},
		{
			plugins: 'responsive',
			title : {
				xtype : 'paneltitle',
				text : 'Déjà présent dans seedbox',
				textAlign : 'right'
			},
			responsiveConfig: {
                wide: {
                    
                },
                tall: {
                    width : '100%',
					title:{
						textAlign : 'left'
					}
                }
            },
			
			//html : 'Eric Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'
			items:[
			{
				xtype: 'treelist',
				store : null,
				reserveScrollbar: true,
				collapsible: true,
				loadMask: true,
				useArrows: true,
				rootVisible: true,
				animate: true,
				
			}
			],
			listeners :{
				initialize : function(panel,eOpts){
					var tree = panel.getItems().items[1];
					MyTorrent.getApplication().treePlexFiles = tree;
				}
			}
		}
	]
});