<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-05-11
 * Time: 09:15
 */

namespace common\models\payment\wallet;

use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use common\components\RedisLanguage;
use common\components\UrlComponent;
use common\models\payment\Payment;
use common\models\payment\PaymentResponse;


/**
 * Class Wallet
 * @package common\models\payment\wallet
 *
 * @property Payment $payment
 */
class Wallet extends BaseObject
{

    const WALLET_CHECKOUT_OTP = '/account/wallet/otp-verify';

    public $merchantId;
    public $binCode;
    public $totalAmount;
    public $otpReceiveType;

    /**
     * @var Payment
     */
    private $_payment;
    public function setPayment($payment){
        $this->_payment = $payment;
    }
    public function getPayment(){
        return $this->_payment;
    }

    private $_website;
    public function getWebsite(){
        return $this->_website;
    }
    public function setWebsite($ws){
        $this->_website = $ws;
    }

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $payment = $this->getPayment();
        if(!$payment && !$payment instanceof Payment){
            throw new InvalidConfigException(__CLASS__.'::$payment must be requited and instance of \common\models\payment\Payment');
        }
        $website = $this->getWebsite();
        if(!$website && !$website instanceof \common\models\weshop\Website){
            throw new InvalidConfigException(__CLASS__.'::$payment must be requited and instance of \common\models\weshop\Website');
        }
    }

    /**
     * @var \common\models\db\WalletClient
     */
    private $_walletClient;

    public function getWalletClient(){
        if(!$this->_walletClient){
            // Todo get component via Yii::$app
            $this->_walletClient = \Yii::$app->authClientCollection->getClient('wallet');
        }
        return $this->_walletClient;
    }

    public function setWalletClient($walletClient){
        $this->_walletClient = $walletClient;
    }
    /**
     * Tạo request thành công rồi chuyển tới trang xác nhận otp
     * @return PaymentResponse
     */
    public function create(){

        $this->merchantId = 1;
        $this->binCode = $this->payment->order_bin;
        $this->otpReceiveType = $this->payment->otp_verify_method;
        $this->totalAmount = $this->payment->total_amount;
        if($this->payment->page == Payment::PAGE_ADDFEE){
            $this->binCode = $this->payment->addfee_bin;
            $this->totalAmount = $this->payment->website->roundMoneyAmount($this->payment->total_amount);
        }

        $posts = $this->createPostData();

        $request = $this->getWalletClient()->createApiRequest()
            ->setMethod('post')
            ->setFormat('json')
            ->setUrl('transaction/create')
            ->setData($posts);
        $response = $request->send();

        if(!$response->isOk){
            return new PaymentResponse(false, null, null, "Can't not connent to server", "GET", $this->getPayment());
        }
        $response = $response->getData();
        if ($response['success'] === false){
            return new PaymentResponse(false, null, null, 'Not Found', "GET",  $this->getPayment());
        }
        if ($response['success'] === true && $response['code'] !== '0000') {
            return new PaymentResponse(false, null, null, $response['message'], "GET",  $this->getPayment());
        }
        list($queue,$code) = $response['data'];
        return new PaymentResponse(true, $response['data'], $this->getCheckoutOtpUrl($response['data']), "OK", "GET", $this->getPayment());

    }

    /**
    /**
     * @return array
     */
    public function createPostData(){
        return [
            'merchant_id' =>$this->merchantId,
            'transaction_code' => $this->binCode,
            'total_amount' => $this->totalAmount,
            'payment_method' => 'WALLET_WESHOP',
            'payment_provider' => 'Wallet weshop',
            'bank_code' => 'Wallet',
            'otp_receive_type' => $this->otpReceiveType,
        ];
    }
    public function getCheckoutOtpUrl($code){
        return $this->getWebsite()->getUrl().self::WALLET_CHECKOUT_OTP.'/'.$code;
    }
}