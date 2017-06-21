/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if(Ext === undefined){
    Ext = {};
}

Ext.define('Mytorent.util.Ajax',{
   extend : 'Ext.Ajax' 
   ,xtype : 'utilajax'
   
   ,callbackSuccess:null
           
   ,callbackFailure:null
   ,setSuccessCallback : function(callback){
       this.callbackSuccess = callback;
   }
   ,setFailureCallback : function(callback){
       this.callbackFailure = callback;
   }
});