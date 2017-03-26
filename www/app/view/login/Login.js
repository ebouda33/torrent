
Ext = Ext ||'';
Ext.define('MyTorrent.view.login.Login', {
    extend: 'Ext.MessageBox',
    xtype: 'login',
    
    requires: [
        'MyTorrent.view.login.LoginController',
        'Ext.form.Panel'
    ],

    controller: 'login',
    bodyPadding: 10,
    title: 'Identification System',
    closable: true,
    items: {
        xtype: 'formpanel',
//        reference: 'formLogin',
        standardSubmit : false,
        submitOnAction : true,
        url : 'torrentJson.php',
        method : 'get',
        items: [
            {
            xtype: 'emailfield',
            name: 'username',
            placeHolder : 'Email',
            fieldLabel: 'Username',
            msgTarget: 'under',
            required: true,
//            formBind: true,
            listeners : {
                 change : function ( field, newValue , oldValue , eOpts ){
                    if(newValue !== oldValue){
                        field.getParent().getItems().items[2].setDisabled(false);
                    }
                }
            }
        }, {
            xtype: 'passwordfield',
            name: 'password',
            placeHolder : 'Password',
            inputType: 'password',
            fieldLabel: 'Password',
            required: true,
//            formBind: true,
            listeners : {
                 change : function ( field, newValue , oldValue , eOpts ){
                    if(newValue !== oldValue){
                        field.getParent().getItems().items[2].setDisabled(false);
                    }
                }
            }
        },{
            xtype : 'button',
            text : 'login',
            formBind: true,
            disabled: true,

            handler : 'onLoginClick'
        },
         {
              xtype : 'textfield' ,
              name : 'login',
              value : 'login',
              hidden : true
            }
        ]

    }
});

