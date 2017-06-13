
Ext = Ext ||'';
Ext.define('MyTorrent.view.login.Login', {
    extend: 'Ext.MessageBox',
//    extend: 'Ext.window.MessageBox',
    xtype: 'login',
    
    requires: [
        'MyTorrent.view.login.LoginController',
        'Ext.form.Panel'
    ],
    closable :true,
    modal : true,
    controller: 'login',
    bodyPadding: 10,
    title: 'Identification System',
//    closable: true,
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
            msgTarget: 'top',
            required: true,
//            formBind: true,
            listeners : {
                 change : function ( field, newValue , oldValue , eOpts ){
                    if(newValue !== oldValue){
                        field.getParent().getItems().items[2].setDisabled(false);
                    }
                },
                action : function(field,evt,eOpts){
                    var parent  = field.getParent();
                    var items = parent.getItems().items;
                    items[1].focus();
                }
            }
        }, {
            xtype: 'passwordfield',
//            xtype : 'textfield',
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
                },
                action : 'onLoginClick'
            }
        },{
            xtype : 'button',
            text : 'login',
//            formBind: true,
            disabled: true,

            handler : 'onLoginClick'
        },
         {
              xtype : 'textfield' ,
              name : 'login',
              value : 'login',
              hidden : true
            }
        ],
        listeners : {
             initialize : function( form, eOpts ){
                 var elements = form.getItems().items;
                 elements[0].focus();
                 
             }

        }

    }
});

