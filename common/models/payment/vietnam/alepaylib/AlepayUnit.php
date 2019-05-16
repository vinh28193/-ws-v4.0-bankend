<?php
namespace common\models\payment\vietnam\alepaylib;
use common\models\payment\vietnam\alepaylib\Crypt\Crypt_RSA;

/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 20/08/2018
 * Time: 11:04
 */
class AlepayUnit
{
    public function encryptData($data, $publicKey)
    {
        $rsa = new Crypt_RSA();
        $rsa->loadKey($publicKey); // public key
        $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
        $output = $rsa->encrypt($data);
        return base64_encode($output);
    }


    public function decryptData($data, $publicKey)
    {
        $rsa = new Crypt_RSA();
        $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
        $ciphertext = base64_decode($data);
        $rsa->loadKey($publicKey); // public key
        $output = $rsa->decrypt($ciphertext);
        // $output = $rsa->decrypt($data);
        return $output;
    }

    public function decryptCallbackData($data, $publicKey)
    {
        $decoded = base64_decode($data);
        return $this->decryptData($decoded, $publicKey);
    }
}