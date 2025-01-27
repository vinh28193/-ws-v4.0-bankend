<?php

/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 10/05/2018
 * Time: 10:00
 */

namespace frontend\modules\payment\providers\wallet;

use common\models\Customer;
use Yii;
use Exception;
use yii\base\BaseObject;
use yii\httpclient\Client;


class WalletService extends BaseObject
{
    const MERCHANT_IP_PRO = 1;
    const MERCHANT_IP_DEV = 4;
    const WITHDRAW_MIN_AMOUNT = 10000;
    const TYPE_TOP_UP = 'TOP_UP';
    const TYPE_FREEZE = 'FREEZE';
    const TYPE_UN_FREEZE = 'UN_FREEZE';
    const TYPE_PAY_ORDER = 'PAY_ORDER';
    const TYPE_PAY_ADDFEE = 'PAY_ADDFEE';
    const TYPE_REFUND = 'REFUND';
    const TYPE_WITH_DRAW = 'WITH_DRAW';

    const STATUS_QUEUE = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_COMPLETE = 2;
    const STATUS_CANCEL = 3;
    const STATUS_FAIL = 4;

    public $merchant_id = self::MERCHANT_IP_PRO;
    public $type;
    public $transaction_code;
    public $total_amount;
    public $payment_method;
    public $payment_provider;
    public $bank_code;
    public $payment_transaction;
    public $cardholderName;
    public $cardnumber;
    public $fee;
    public $amount;


    public $otp_type;
    public $otp_code;
    /**
     * @var WalletClient
     */
    private $_walletClient;

    /**
     * @return WalletClient
     */
    public function getWalletClient()
    {
//        if (!is_object($this->_walletClient)) {
//            $this->_walletClient = Yii::$app->authClientCollection->getClient('wallet');
//        }
//        return $this->_walletClient;
        return null;
    }

    public static function isGuest()
    {
        try {
            return !(new static())->testConnection()['success'];
        } catch (\Exception $exception) {
            return true;
        }
    }

    public function response($success, $message, $data = null)
    {
        return ['success' => $success, 'message' => $message, 'data' => $data];
    }

    public function callApiRequest($url, $params, $method = "POST")
    {
//        try {
//            $client = $this->getWalletClient();
//            return $client->createApiRequest()
//                ->setMethod($method)
//                ->setFormat('json')
//                ->setUrl($url)
//                ->setData($params)->send()->getData();
//        } catch (Exception $exception) {
//            Yii::error($exception);
//            return null;
//        }
        return null;
    }

    public function testConnection()
    {
        return $this->callApiRequest('wallet/test-connection', []);

    }

    public function login($password)
    {
//        /** @var  $user Customer */
//        $user = Yii::$app->user->identity;
//
//        $walletClient = $this->getWalletClient();
//        try {
//            $walletClient->authenticateUser($user->username, $password);
//            return $this->response(true, 'Login success');
//        } catch (Exception $exception) {
//            $client = new Client([
//                'baseUrl' => $walletClient->apiBaseUrl
//            ]);
//            $request = $client->createRequest();
//            $request->setUrl('wallet-no-auth/create-wallet');
//            $request->setMethod('POST');
//            $request->setData(['customer' => $user->getAttributes()]);
//            $response = $client->send($request);
//            if ($response->isOk) {
//                $response = $response->getData();
//                if ($response['success']) {
//                    try {
//                        $walletClient->authenticateUser($user->username, Yii::$app->request->post('password'));
//                        return $this->response(true, 'Login success');
//                    } catch (Exception $e) {
//                        return $this->response(false, $e->getMessage());
//                    }
//
//                }
//            }
//            return $this->response(false, 'can not login, cause unknown error');
//        }
        return $this->response(false, 'Error');
    }

    public function topUpTransaction()
    {
        $data = [];
        if ($this->transaction_code !== null) {
            $data['transaction_code'] = $this->transaction_code;
        }
        $data['amount_total'] = $this->total_amount;
        $data['method'] = $this->payment_method;
        $data['payment_provider'] = $this->payment_provider;
        $data['bank_code'] = $this->bank_code;
        $data['merchant_id'] = @self::MERCHANT_IP_PRO;

        return $this->callApiRequest('topup/create', $data);
    }

    public function walletInformation()
    {
        return $this->callApiRequest('wallet/information', []);
    }

    public function pushToTopUp()
    {
        $data['wallet_transaction_code'] = $this->transaction_code;
        $data['payment_transaction'] = $this->payment_transaction;
        $data['time'] = date('Y-m-d H:i:s');
        return $this->callApiRequest('topup/pushtotopup', $data);
    }

    public function pushToTopUpNoAuth()
    {
//        $data['wallet_transaction_code'] = $this->transaction_code;
//        $data['payment_transaction'] = $this->payment_transaction;
//        $data['time'] = date('Y-m-d H:i:s');
//        $wlClient = $this->getWalletClient();
//        $client = new Client([
//            'baseUrl' => $wlClient->apiBaseUrl
//        ]);
//        $request = $client->createRequest()
//            ->setMethod('POST')
//            ->setUrl('wallet-no-auth/push-to-top-up')
//            ->setData($data);
//        $response = $client->send($request);
//        if (!$response->isOk) {
//            return $this->response(false, "can not connect to server");
//        }
//        $response = $response->getData();
//        return $this->response($response['success'], $response['message'], $response['data']);
        return $this->response(false, "error v4.1 ");
    }

    public function getTopUpTransaction()
    {
        return $this->callApiRequest('topup/gettransaction', ['wallet_transaction_code' => $this->transaction_code]);
    }


    public function listTransaction($filter = null, $limit = 20, $offset = 0)
    {
        $data['limit'] = $limit;
        $data['offset'] = $offset;

        if ($filter) {
            $data['filter'] = json_encode($filter);
        }
        return $this->callApiRequest('transaction/index', $data);
    }

    public function detailWalletClient()
    {
        return $this->callApiRequest('clients/detail', []);
    }


    public function cancelTopUpTransaction()
    {
        return $this->callApiRequest('clients/detail', ['wallet_transaction_code' => $this->transaction_code]);
    }

    public function createPaymentTransaction()
    {
        $data['merchant_id'] = $this->merchant_id;
        $data['transaction_code'] = $this->payment_transaction;
        $data['total_amount'] = $this->total_amount;
        $data['payment_method'] = $this->payment_provider;
        $data['payment_provider'] = $this->payment_provider;
        $data['bank_code'] = $this->bank_code;
        $data['otp_receive_type'] = $this->otp_type;
        return $this->callApiRequest('transaction/create', $data);
    }

    public function createSafePaymentTransaction()
    {
        $data['merchant_id'] = $this->merchant_id;
        $data['transaction_code'] = $this->payment_transaction;
        $data['total_amount'] = $this->total_amount;
        $data['payment_method'] = $this->payment_provider;
        $data['payment_provider'] = $this->payment_provider;
        $data['bank_code'] = $this->bank_code;
        $data['otp_receive_type'] = $this->otp_type;
        return $this->callApiRequest('transaction/create-safe', $data);
    }

    public function transactionDetail()
    {
        return $this->callApiRequest('transaction/detail', ['transaction_code' => $this->transaction_code]);
    }

    public function transactionSuccess()
    {
        return $this->callApiRequest('transaction/success', ['transaction_code' => $this->transaction_code]);

    }

    public function validateOtp()
    {
        return $this->callApiRequest('transaction/verify-opt', [
            'transaction_code' => $this->transaction_code,
            'otp_code' => $this->otp_code
        ]);
    }

    public function refreshOtp()
    {
        return $this->callApiRequest('transaction/refresh-otp', [
            'transaction_code' => $this->transaction_code,
            'otp_receive_type' => $this->otp_type,
        ]);
    }

    public function createWithdraw()
    {
        return $this->callApiRequest('withdraw/create', [
            'amount_total' => $this->total_amount,
            'method' => $this->payment_method,
            'note' => '',
            'merchant_id' => 1,
            'transaction_code' => '',
            'payment_provider' => $this->payment_method == 'NL' ? 'Rút tiền về ví Ngân Lượng' : 'Rút tiền về tài khoản ngân hàng',
            'bank_code' => $this->bank_code,
            'typeSendOtp' => 3,
            'requestContent' => json_encode([
                'numberCard' => $this->cardnumber,
                'cardholderName' => $this->cardholderName,
                'amount' => $this->amount,
                'fee' => $this->fee,
                'total_amount' => $this->total_amount,
            ]),
        ]);
    }

    public function cancelWithdraw()
    {
        return $this->callApiRequest('transaction/cancel-withdraw', [
            'transaction_code' => $this->transaction_code,
        ]);
    }

}