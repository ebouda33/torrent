/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


Ext.define('MyTorrent.view.main.Recherche',{
    extend: 'Ext.panel.Panel'
    ,xtype: 'panelRecherche'
    ,scrollable : true
    ,requires :[
        'MyTorrent.view.recherche.Torrent',
        'MyTorrent.view.grid.GridResult'
    ]
//    ,layout : 'hbox'
    ,plugins: 'responsive',
    responsiveConfig: {
        'width >= height': {
            layout: {
                       type: 'box',
                       vertical: false,
                       align: 'stretch'
                }
        },
        'width < height': {
            layout: {
                    type: 'box',
                    align: 'stretch',
                    vertical: true
               }
        }
    }
    
    
//    ,align : 'stretch'
    ,height : '100%'
    ,items : [
        {
            xtype : 'rechercheTorrent',
            width : '35%'
        },
        {
            xtype : 'torrentresult',
            minHeight : 400,
//            height : '100%',
            plugins: 'responsive',
            responsiveConfig: {
                wide: {
                    width : '64%'
                },
                tall: {
                    width : '100%'
                }
            },
            scrollable : true,
            style : {textAlign : 'left'}
        }
    ]
    
});
