<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Standard\crypt;

use Exception;
/**
 * Description of OpenSSLClass
 *
 * @author xgld8274
 */
class OpenSSLClass {
    //put your code here
    const FILE_ENCRYPTION_BLOCKS = 10000;
    
    public static function generateKeyPrivateFile($filename){
        $encryption_key = base64_encode(bin2hex(openssl_random_pseudo_bytes(128)));
        if(file_exists($filename)){
            unlink($filename);
        }
        $fpOut = fopen($filename, 'w');
        fwrite($fpOut,$encryption_key);
    }
    
    public static function decryptFile($fileread, $keyfile, $fileoutput)
    {
        $key = fread(fopen($keyfile, 'r'), filesize($keyfile));

        $error = false;
        if(!file_exists($fileoutput)){
            if ($fpOut = fopen($fileoutput, 'w')) {
                if ($fpIn = fopen($fileread, 'rb')) {
                    // Get the initialzation vector from the beginning of the file
                    $iv = fread($fpIn, 16);
                    while (!feof($fpIn)) {
                        // we have to read one block more for decrypting than for encrypting
                        $ciphertext = fread($fpIn, 16 * (self::FILE_ENCRYPTION_BLOCKS + 1));
                        $plaintext = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
                        // Use the first 16 bytes of the ciphertext as the next initialization vector
                        $iv = substr($ciphertext, 0, 16);
                        fwrite($fpOut, $plaintext);
                    }
                    fclose($fpIn);
                } else {
                    $error = true;
                }
                fclose($fpOut);
            } else {
                $error = true;
            }

            return $error ? false : $fileoutput;
        }
        throw new Exception('[decryptFile] Dest file exist,delete it :'.$fileoutput);    
    }
    
    public static function cryptFile($fileread,$keyfile ,$fileoutput){
        $key = fread(fopen($keyfile, 'r'),filesize($keyfile));
        $iv =  openssl_random_pseudo_bytes(16);
        $error = false;
        if(!file_exists($fileoutput)){
            if ($fpOut = fopen($fileoutput, 'w')) {
                // Put the initialzation vector to the beginning of the file
                fwrite($fpOut, $iv);
                if ($fpIn = fopen($fileread, 'rb')) {
                    while (!feof($fpIn)) {
                        $plaintext = fread($fpIn, 16 * self::FILE_ENCRYPTION_BLOCKS);
                        $ciphertext = openssl_encrypt($plaintext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
                        // Use the first 16 bytes of the ciphertext as the next initialization vector
                        $iv = substr($ciphertext, 0, 16);
                        fwrite($fpOut, $ciphertext);
                    }
                    fclose($fpIn);
                } else {
                    $error = true;
                }
                fclose($fpOut);
            } else {
                $error = true;
            }

            return $error ? false : $fileoutput;
        }
        throw new Exception('[cryptFile] Dest file exist,delete it :'.$fileoutput);
    }
    
    
    public static function cryptkeyPubFile($fileSrc,$fileDest,$keyPublique){
        $key = fread(fopen($fileSrc, 'r'));
        $out = '';
        openssl_public_encrypt($key,$out,$keyPublique);
        if(!file_exists($fileDest)){
            fwrite(fopen($fileDest, 'w'),$out);
        }else{
            throw new Exception('[cryptkeyPubFile] Dest file exist,delete it :'.$fileDest);
        }
        
    }
    
    public static function decryptkeyPubFile($fileSrc,$fileDest,$keyPublique){
        $key = fread(fopen($fileSrc, 'r'));
        $out = '';
        openssl_public_decrypt($key,$out,$keyPublique);
        if(!file_exists($fileDest)){
            fwrite(fopen($fileDest, 'w'),$out);
        }else{
            throw new Exception('[decryptkeyPubFile] Dest file exist,delete it :'.$fileDest);
        }
        
    }
}
