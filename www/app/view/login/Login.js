
Ext = Ext ||'';
Ext.define('MyTorrent.view.login.Login', {
    extend: 'Ext.MessageBox',
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
    items: {
        xtype: 'formpanel',
        standardSubmit : false,
        submitOnAction : true,
        url : 'torrentJson.php',
        method : 'get',
        items: [
            {
            xtype: 'emailfield',
            name: 'username',
            placeHolder : 'Email',
			label: '&nbsp;',
			labelWidth : 20,
			labelCls:"x-fa fa-envelope-o fa-fw input-group",
            msgTarget: 'top',
            required: true,
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
            name: 'password',
            placeHolder : 'Password',
            inputType: 'password',
            label: '&nbsp;',
			labelWidth : 20,
			labelCls:"x-fa fa-key fa-fw input-group",
            required: true,
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

