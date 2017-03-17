/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


Ext.define('MyTorrent.view.recherche.Torrent',{
   extend : 'Ext.form.Panel' 
   ,xtype : 'rechercheTorrent'
    ,requires : [
         'Ext.form.FieldSet'
     ]
   ,items :[
       {
            xtype: 'fieldset',
            title: 'Plugins',
            defaultType : 'checkboxfield',
            width : 150,
            items : [
                {
                    label : 'plugin1',
                    name : 'plugin1',
                    inputValue : 654446,
                    id : 'plugin1',
                    labelAlign : 'right',

                },
                {
                    label : 'plugin2',
                    name : 'plugin2',
                    inputValue : 654446,
                    id : 'plugin2',
                    labelAlign : 'right',

                }
            ]
        }
    ]
//       
       
   
});