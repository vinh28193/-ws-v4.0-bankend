<?php
/**
 * Created by PhpStorm.
 * User: Panda
 * Date: 1/11/2018
 * Time: 9:15 AM
 */

namespace common\models\payment\thailand;


use common\lib\Log4P;
use common\models\model\Order;
use common\models\model\PaymentGatewayRequests;
use common\models\payment\interfaces\Merchant;
use common\models\payment\Payment;
use common\models\payment\PaymentResponse;

class C2P2 implements Merchant
{

    public $payment;
    public $payment_url;
    public $version = "7.2";
    public $merchant_id;
    public $secret_key;
    public $currency = 764;
    public $result_url;
    public $payment_description;
    public $order_id;
    public $amount;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;

    }

    public function createPayment()
    {

        if (\Yii::$app->params['environments_product_c2p2'] == false) {
            $this->payment_url = "https://demo2.2c2p.com/2C2PFrontEnd/RedirectV3/payment";
            $this->merchant_id = "JT04";
            $this->secret_key = "QnmrnH6QE23N";
        }else{
            $this->payment_url = $this->payment->config->submitUrl;
            $this->merchant_id = $this->payment->config->merchainId;
            $this->secret_key = $this->payment->config->merchainPassword;
        }
        $this->result_url = $this->payment->website->getUrl() . '/payment/c2p2/return.html';
        $this->amount = $this->formatPrice($this->payment->total_amount);
        if ($this->payment->page == Payment::PAGE_ADDFEE) {
            $this->order_id = $this->payment->addfee_bin;
        } else {
            $this->order_id = $this->payment->order_bin;
        }
        $this->payment_description = trim($this->payment->order_note ? $this->payment->order_note : "Payment bin" . $this->order_id);
        return new PaymentResponse(true, $this->order_id, $this->buildRequest(), "Success", "POST", $this->payment);
    }

    public static function checkPayment($request = null)
    {
        $version = $_REQUEST["version"];
        $request_timestamp = $_REQUEST["request_timestamp"];
        $merchant_id = $_REQUEST["merchant_id"];
        $currency = $_REQUEST["currency"];
        $order_id = $_REQUEST["order_id"];
        $amount = $_REQUEST["amount"];
        $invoice_no = $_REQUEST["invoice_no"];
        $transaction_ref = $_REQUEST["transaction_ref"];
        $approval_code = $_REQUEST["approval_code"];
        $eci = $_REQUEST["eci"];
        $transaction_datetime = $_REQUEST["transaction_datetime"];
        $payment_channel = $_REQUEST["payment_channel"];
        $payment_status = $_REQUEST["payment_status"];
        $channel_response_code = $_REQUEST["channel_response_code"];
        $channel_response_desc = $_REQUEST["channel_response_desc"];
        $masked_pan = $_REQUEST["masked_pan"];
        $stored_card_unique_id = $_REQUEST["stored_card_unique_id"];
        $backend_invoice = $_REQUEST["backend_invoice"];
        $paid_channel = $_REQUEST["paid_channel"];
        $paid_agent = $_REQUEST["paid_agent"];
        $payment_scheme = $_REQUEST["payment_scheme"];
        $user_defined_1 = $_REQUEST["user_defined_1"];
        $user_defined_2 = $_REQUEST["user_defined_2"];
        $user_defined_3 = $_REQUEST["user_defined_3"];
        $user_defined_4 = $_REQUEST["user_defined_4"];
        $user_defined_5 = $_REQUEST["user_defined_5"];
        $browser_info = $_REQUEST["browser_info"];
        $hash_value = $_REQUEST["hash_value"];
        $checkHashStr = $version . $request_timestamp . $merchant_id . $order_id . $invoice_no . $currency . $amount . $transaction_ref . $approval_code . $eci . $transaction_datetime . $payment_channel . $payment_status . $channel_response_code . $channel_response_desc . $masked_pan . $stored_card_unique_id . $backend_invoice . $paid_channel . $paid_agent . $user_defined_1 . $user_defined_2 . $user_defined_3 . $user_defined_4 . $user_defined_5 . $browser_info . $payment_scheme;
        if (\Yii::$app->params['environments_product_c2p2']==true) {
            $SECRETKEY = "7ZDkgrnBHNWb";
        }else{
            $SECRETKEY = "QnmrnH6QE23N";
        }

        $checkHash = hash_hmac('sha1', $checkHashStr, $SECRETKEY, false);    //Compute hash value
        //Validate response hash_value
        if (strcmp(strtolower($hash_value), strtolower($checkHash)) == 0) {
            if ($payment_status == "000") {
                Order::updatePaymentSuccess($order_id);

            }
        }
        $log = new PaymentGatewayRequests();
        $log->binCode = $order_id;
        $log->requestId = $invoice_no;
        $log->requestUrl = "payment/c2p2/return.html";
        $log->requestType = "CALBACK";
        $log->storeId = 10;
        $log->responseContent = json_encode($_REQUEST);
        $log->createTime = date('Y-m-d H:i:s');
        $log->paymentGateway = "C2P2";
        $log->saveRequest();
        return $order_id;

    }

    public function getErrorCode($code)
    {
        // TODO: Implement getErrorCode() method.
    }

    public function getStatusCode($code)
    {
        // TODO: Implement getStatusCode() method.
    }

    public function getSign()
    {
        $params = $this->version . $this->merchant_id . $this->payment_description . $this->order_id . $this->currency . $this->amount . $this->result_url . $this->result_url;
        return hash_hmac('sha1', $params, $this->secret_key, false);    //Compute hash value
    }


    public function buildRequest()
    {
        return '<form method="post" action="' . $this->payment_url . '">
		<input type="hidden" name="version" value="' . $this->version . '"/>
		<input type="hidden" name="merchant_id" value="' . $this->merchant_id . '"/>
		<input type="hidden" name="currency" value="' . $this->currency . '"/>
		<input type="hidden" name="result_url_1" value="' . $this->result_url . '"/>
		<input type="hidden" name="result_url_2" value="' . $this->result_url . '"/>
		<input type="hidden" name="hash_value" value="' . $this->getSign() . '"/>
		PRODUCT INFO : <input type="text" name="payment_description" value="' . $this->payment_description . '"  readonly/><br/>
		ORDER NO : <input type="text" name="order_id" value="' . $this->order_id . '"  readonly/><br/>
		AMOUNT: <input type="text" name="amount" value="' . $this->amount . '" readonly/><br/>
	</form>';
    }

    /**
     * Amount formatted into 12 digit format with
     * leading zero. Minor unit appended to the last
     * digit depending on number of Minor unit specified
     * in ISO 4217.
     * @param $price
     * @return int|string
     */
    private function formatPrice($price)
    {
        $price = (int)($price * 100);
        $price = str_pad((string)$price, 12, '0', STR_PAD_LEFT);
        return $price;
    }
}