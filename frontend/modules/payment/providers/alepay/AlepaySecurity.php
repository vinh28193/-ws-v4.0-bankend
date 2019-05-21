<?php


namespace frontend\modules\payment\providers\alepay;

use phpseclib\Crypt\RSA;
use yii\base\BaseObject;
use yii\helpers\Json;
use yii\helpers\StringHelper;

class AlepaySecurity extends BaseObject
{

    /**
     * @var string
     */
    public $publicKey;

    /**
     * @var object
     */
    private $_provider;

    public function setProvider($provider)
    {
        $this->_provider = $provider;
    }

    public function getProvider()
    {
        if (!is_object($this->_provider)) {
            $this->_provider = new RSA();
            $this->_provider->loadKey($this->publicKey, RSA::PRIVATE_FORMAT_PKCS1);
            $this->_provider->setEncryptionMode(RSA::ENCRYPTION_PKCS1);
        }
        return $this->_provider;
    }

    /**
     * @param $plaintext
     * @return string
     */
    public function encrypt($plaintext)
    {
        return $this->getProvider()->encrypt($plaintext);
    }

    /**
     * @param $ciphertext
     * @return string
     */
    public function decrypt($ciphertext)
    {
        return $this->getProvider()->decrypt($ciphertext);
    }

    public function md5Data($data)
    {
        return call_user_func('md5', $data);
    }

    /**
     * @param $data
     * @return string
     */
    public function base64UrlEncode($data)
    {
        return StringHelper::base64UrlEncode($data);
    }

    /**
     * @param $data
     * @return string
     */
    public function base64UrlDecode($data)
    {
        return StringHelper::base64UrlDecode($data);
    }

    /**
     * @param $string
     * @param int $options
     * @return string
     */
    public function jsonEncode($string, $options = 320)
    {
        return Json::encode($string, $options);
    }

    /**
     * @param $string
     * @param bool $asArray
     * @return mixed
     */
    public function jsonDecode($string, $asArray = true)
    {
        return Json::decode($string, $asArray);
    }
}