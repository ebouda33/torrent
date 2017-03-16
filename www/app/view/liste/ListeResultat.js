
Ext.define('MyTorrent.view.liste.ListeResultat', {
    extend: 'Ext.grid.Grid',
    xtype: 'listeresultat',
    
    requires: [
        'MyTorrent.store.Personnel'
    ],

    title: 'Résultat',

    store: {
        type: 'personnel'
    },

    columns: [
        { text: 'Name',  dataIndex: 'name', width: 100 },
        { text: 'Email', dataIndex: 'email', width: 230 },
        { text: 'Phone', dataIndex: 'phone', width: 150 }
    ],

    listeners: {
        select: 'onItemSelected'
    }
});