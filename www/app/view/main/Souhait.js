Ext.define('MyTorrent.view.main.Souhait',{
    extend: 'Ext.container.Container'
    ,xtype: 'panelSouhait'
	,requires: [
      
	]
	,height : '100%'
	,scrollable : true
	,defaults :{
		minWidth : 200,
		minHeight : 200,
		xtype : 'panel',
		height : '100%',
		width : '50%',
	}
	,split : true
	,items : [
		{
			title : 'Les envies',
			
			html : 'liste envies',
			
			
		},
		{
			
			title : {
				xtype : 'paneltitle',
				text : 'Deja present dans seedbox',
				textAlign : 'right'
			},
			html : 'Eric Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'
			
			
		},
	]
});