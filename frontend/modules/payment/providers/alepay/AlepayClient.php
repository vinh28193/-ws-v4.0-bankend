<?php


namespace frontend\modules\payment\providers\alepay;

use Yii;
use yii\base\Component;
use yii\httpclient\Client;

/**
 * Class AlepayClient
 * @package frontend\modules\payment\providers\alepay
 * @property-read AlepaySecurity $security
 * @property-read Client $httpClient
 */
class AlepayClient extends Component
{

    const ENV_PROD = 'PROD';
    const ENV_SANDBOX = 'SANDBOX';
    public $env = self::ENV_SANDBOX;
    public $baseUrl = 'https://alepay.vn/checkout/v1';
    public $apiKey = 'g84sF7yJ2cOrpQ88VbdZoZfiqX4Upx';
    public $checksumKey = 'lXntf6CIZbSgzMqTz1nQ11jPKhGfsF';
    public $encryptKey = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCKWYg7jKrTqs83iIvYxlLgMqIy4MErNsoBKU2MHaG5ccntzGeNcDba436ds';
    public $callbackUrl = '';


    /**
     * @var AlepaySecurity
     */
    private $_security;

    /**
     * @return AlepaySecurity
     * @throws \yii\base\InvalidConfigException
     */
    public function getSecurity()
    {
        if ($this->_security === null) {
            $this->_security = Yii::createObject([
                'class' => AlepaySecurity::className(),
                'publicKey' => $this->encryptKey,
            ]);
        }
        return $this->_security;
    }

    private $_httpClient;

    public function getHttpClient()
    {
        if ($this->_httpClient === null) {
            $this->_httpClient = Yii::createObject([
                'class' => Client::className(),
                'baseUrl' => $this->baseUrl,
            ]);
        }
        return $this->_httpClient;
    }

    public function createHttpRequest($url, $data)
    {
        $dataJson = $this->security->jsonEncode($data);

        $dataEncrypt = $this->security->encrypt($dataJson);
        var_dump($dataEncrypt);die;
        $checksum = $this->security->md5Data($dataEncrypt . $this->checksumKey);
        $request = $this->httpClient->createRequest();
        $request->setData([
            'token' => $this->apiKey,
            'data' => $dataEncrypt,
            'checksum' => $checksum
        ]);
        $request->setUrl($url);
        $request->setMethod('POST');
        $request->setFormat(Client::FORMAT_JSON);
        $response = $this->httpClient->send($request);
        if (!$response->isOk) {
            return false;
        }
        $response = $response->getData();
        return $this->security->decrypt($response);
    }

    protected function getBaseUrl()
    {
        $baseUrl = $this->baseUrl;
        if ($this->env === self::ENV_SANDBOX) {
            $baseUrl = str_replace('https://alepay.vn', 'https://alepay-sandbox.nganluong.vn', $baseUrl);
        }
        return $baseUrl;
    }

    public function getInstallmentInfo($amount, $currencyCode)
    {
        return $this->createHttpRequest('get-installment-info', [
            'amount' => $amount,
            'currencyCode' => $currencyCode
        ]);
    }
}