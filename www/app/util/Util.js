/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Ext = Ext || {};
//MyTorrent = MyTorrent || {};

Ext.define('MyTorrent.util.Util',{
    extend :'Object',
    statics : {
        tabSize : ['octets','Ko','Mo','Go','To'],
        getSizeLitteral : function (size){
            if(isNaN(size)){
                return size;
            }
            var i = 0;
            while((size >= 1024) && i < this.tabSize.length){
                size = size/1024;
                i++;
            }

            return this.round(size,2) + ' '+this.tabSize[i];
        },
        round : function (number,decimal){
            decimal = Math.pow(10,decimal);
           return  Math.floor(number*decimal)/decimal;
        },
        statusSeedBox : function(status){
            var libelle = ['STOPPED','CHECK_WAIT','CHECK','DOWNLOAD_WAIT','DOWNLOAD','SEED_WAIT','SEED'];
            if(status !== undefined){
                if(status >0 && status <= libelle.length){
                    return libelle[status];  
                }else{
                    return 'UNKWOWN';
                }
            }else{
                return 'PAUSE';
            }
        }
    }
});