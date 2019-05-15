<?php
/**
 * Created by PhpStorm.
 * User: Panda
 * Date: 12/28/2017
 * Time: 4:17 PM
 */

namespace common\models\payment\philippines;


use common\models\model\Order;
use common\models\model\PaymentGatewayRequests;
use common\models\payment\interfaces\Merchant;
use common\models\payment\Payment;
use common\models\payment\PaymentResponse;
use common\components\UrlComponent;

class Paynamics implements Merchant
{

    public $_noturl;
    public $_mid;
    public $cert;
    public $payment;
    public $check_out_url;
    public $_ipaddress;
    public $_request_id;
    public $_amount;
    public $_resurl;
    public $_cancelurl;
    public $_fname; // kindly set this to first name of the cutomer
    public $_mname = ""; // kindly set this to middle name of the cutomer
    public $_lname = ""; // kindly set this to last name of the cutomer
    public $_addr1; // kindly set this to address1 of the cutomer
    public $_addr2 = ""; // kindly set this to address2 of the cutomer
    public $_city; // kindly set this to city of the cutomer
    public $_state; // kindly set this to state of the cutomer
    public $_country; // kindly set this to country of the cutomer
    public $_zip = ""; // kindly set this to zip/postal of the cutomer
    public $_sec3d = "try3d"; //
    public $_email; // kindly set this to email of the cutomer
    public $_phone; // kindly set this to phone number of the cutomer
    public $_mobile = ""; // kindly set this to mobile number of the cutomer
    public $_clientip;
    public $_currency = "PHP"; //PHP or USD

    public function __construct(Payment $payment = null)
    {
        if ($payment != null) {
            $this->payment = $payment;
            $this->_noturl = $payment->website->getUrl().'/payment/paynamics/return.html'; // url where response is posted
            $testMail = ["thai.mepro@gmail.com", "weshop123@gmai.com", "weshopdev@gmail.com", 'buiquangquyet@gmail.com', 'weshoptest@gmail.com', 'dev.weshopasia@gmail.com', 'testweshop@gmail.com'];
            if (YII_DEBUG || in_array($payment->customer_email, $testMail)) {
                $payment->returnUrl = $payment->website->getUrl() . "/callback/paynamics/result.html";
                $this->_mid = '000000080617C28AEFD1'; //<-- your merchant id
                $this->cert = '72A59988FB4EB09DB5D1AC7212E8E522'; //<-- your merchant key
                $this->check_out_url = "https://testpti.payserv.net/webpaymentv2/default.aspx";
                $this->_ipaddress = "128.199.200.56";
            } else {
                $payment->returnUrl = "https://weshop.ph/callback/paynamics/result.html";
                $this->_mid = '00000020061739CBF9B4'; //<-- your merchant id
                $this->cert = '0477575812EC72F26C055E5A7AC6B877'; //<-- your merchant key
                $this->check_out_url = "https://ptiapps.paynamics.net/webpayment_v2/default.aspx";
                $this->_ipaddress = "128.199.200.56";
            }
            $this->_resurl = $this->payment->returnUrl; //url of merchant landing page
            $this->_cancelurl = $this->payment->cancelUrl; //url of merchant landing page
            $this->_request_id = $this->payment->order_bin;

            if ($request->page == Payment::PAGE_ADDFEE) {
                $this->_cancelurl = $this->payment->website->getUrl() . UrlComponent::step3_addfee($request->addfee_bin);
                $this->_request_id = $this->payment->addfee_bin;
            } else {
                $this->_cancelurl = $request->website->getUrl() . UrlComponent::step3_bill($request->order_bin);
                $this->_request_id = $request->order_bin;
            }

            $this->_fname = $this->payment->customer_name; // kindly set this to first name of the cutomer
            $this->_mname = ""; // kindly set this to middle name of the cutomer
            $this->_lname = ""; // kindly set this to last name of the cutomer
            $this->_addr1 = $this->payment->customer_address; // kindly set this to address1 of the cutomer
            $this->_addr2 = ""; // kindly set this to address2 of the cutomer
            $this->_city = $this->payment->customer_city; // kindly set this to city of the cutomer
            $this->_state = $this->payment->customer_district; // kindly set this to state of the cutomer
            $this->_country = $this->payment->customer_country; // kindly set this to country of the cutomer
            $this->_zip = ""; // kindly set this to zip/postal of the cutomer
            $this->_sec3d = "try3d"; //
            $this->_email = $this->payment->customer_email; // kindly set this to email of the cutomer
            $this->_phone = $this->payment->customer_phone; // kindly set this to phone number of the cutomer
            $this->_mobile = ""; // kindly set this to mobile number of the cutomer
            $this->_clientip = $_SERVER['REMOTE_ADDR'];
            $this->_currency = "PHP"; //PHP or USD
        } else {
            if (YII_DEBUG) {
                $this->_mid = '000000080617C28AEFD1'; //<-- your merchant id
                $this->cert = '72A59988FB4EB09DB5D1AC7212E8E522'; //<-- your merchant key
                $this->check_out_url = "https://testpti.payserv.net/webpaymentv2/default.aspx";
                $this->_ipaddress = "128.199.200.56";
            } else {
                $this->_mid = '00000020061739CBF9B4'; //<-- your merchant id
                $this->cert = '0477575812EC72F26C055E5A7AC6B877'; //<-- your merchant key
                $this->check_out_url = "https://ptiapps.paynamics.net/webpayment_v2/default.aspx";
                $this->_ipaddress = "128.199.200.56";
            }
        }

    }

    public function createPayment()
    {
        $b64string = base64_encode($this->buildXMLRequest());
        $data = '<form action="' . $this->check_out_url . '" method="post">' .
            '<input type="hidden" name="paymentrequest" value="' . $b64string . '">' .
            '</form>';

        return new PaymentResponse(true, $this->_request_id, $data, "Create data success", "POST", $this->payment);
    }

    public static function checkPayment($request = null)
    {
        $body = $request['paymentresponse'];
        $body = str_replace(" ", "+", $body);
        $decodeBody = base64_decode($body);
        $xml = simplexml_load_string($decodeBody, 'SimpleXMLElement', LIBXML_NOCDATA);
        $data = json_decode(json_encode((array)$xml), true);
        $cert="0477575812EC72F26C055E5A7AC6B877";
//        $log = PaymentGatewayRequests::getRequest($data['application']['request_id']);
        $forSign = $data['application']['merchantid'] . $data['application']['request_id'] . $data['application']['response_id'] . $data['responseStatus']['response_code'] . $data['responseStatus']['response_message'] . $data['responseStatus']['response_advise'] . $data['application']['timestamp'] . $data['application']['rebill_id'] . $cert;
        $_sign = hash("sha512", $forSign);
//        print_r($_sign);
//        print_r("\n");
//        print_r($data['application']['signature'] );die;
//        //CHECK SIGNATURE
        if ($data['application']['signature'] != $_sign) return "Error";

        switch ($data['responseStatus']['response_code']) {
            case "GR001":
                Order::updatePaymentSuccess($data['application']['request_id']);
                return "Success";
        }
        return "Nothing update";
        // TODO: Implement checkPayment() method.
    }

    public function getErrorCode($code)
    {
        // TODO: Implement getErrorCode() method.
    }

    public function getStatusCode($code)
    {
        // TODO: Implement getStatusCode() method.
    }

    public function buildXMLRequest()
    {
        $discount_each_item = 0;
        $totalItemAmount = 0;
        foreach ($this->payment->items as $item) {
            $totalItemAmount += $item->TotalAmountInLocalCurrencyDisplay;
        }
        if ($totalItemAmount > $this->payment->total_amount) {
            $discount_each_item = ($totalItemAmount - $this->payment->total_amount) / ($this->payment->items ? count($this->payment->items) : 1);
        }

        $strxml = "";
        $strxml = $strxml . "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";
        $strxml = $strxml . "<Request>";
        $strxml = $strxml . "<orders>";
        $strxml = $strxml . "<items>";
        $this->_amount = $this->payment->total_amount;
        $check_price = 0;
        foreach ($this->payment->items as $item) {
            $item_price = number_format(($item->TotalAmountInLocalCurrencyDisplay - $discount_each_item) / $item->quantity, 2, '.', '');
            $check_price += $item_price * $item->quantity;
            $strxml = $strxml . "<Items>";
            $strxml = $strxml . "<itemname>" . $item->Name . "(OrderId: " . $item->orderId . " - Qty: " . $item->quantity . ")</itemname><quantity>" . $item->quantity . "</quantity><amount>" . $item_price . "</amount>"; // pls change this value to the preferred item to be seen by customer. (eg. Room Detail (itemname - Beach Villa, 1 Room, 2 Adults       quantity - 0       amount - 10)) NOTE : total amount of item/s should be equal to the amount passed in amount xml node below.
            $strxml = $strxml . "</Items>";
        }
        $this->_amount = number_format($this->_amount != $check_price ? $check_price : $this->_amount, 2, '.', '');
        $strxml = $strxml . "</items>";
        $strxml = $strxml . "</orders>";
        $strxml = $strxml . "<mid>" . $this->_mid . "</mid>";
        $strxml = $strxml . "<request_id>" . $this->_request_id . "</request_id>";
        $strxml = $strxml . "<ip_address>" . $this->_ipaddress . "</ip_address>";
        $strxml = $strxml . "<notification_url>" . $this->_noturl . "</notification_url>";
        $strxml = $strxml . "<response_url>" . $this->_resurl . "</response_url>";
        $strxml = $strxml . "<cancel_url>" . $this->_cancelurl . "</cancel_url>";
        $strxml = $strxml . "<mtac_url></mtac_url>"; // pls set this to the url where your terms and conditions are hosted
        $strxml = $strxml . "<descriptor_note>''</descriptor_note>"; // pls set this to the descriptor of the merchant ""
        $strxml = $strxml . "<fname>" . $this->_fname . "</fname>";
        $strxml = $strxml . "<lname>" . $this->_lname . "</lname>";
        $strxml = $strxml . "<mname>" . $this->_mname . "</mname>";
        $strxml = $strxml . "<address1>" . $this->_addr1 . "</address1>";
        $strxml = $strxml . "<address2>" . $this->_addr2 . "</address2>";
        $strxml = $strxml . "<city>" . $this->_city . "</city>";
        $strxml = $strxml . "<state>" . $this->_state . "</state>";
        $strxml = $strxml . "<country>" . $this->_country . "</country>";
        $strxml = $strxml . "<zip>" . "" . "</zip>";
        $strxml = $strxml . "<secure3d>" . $this->_sec3d . "</secure3d>";
        $strxml = $strxml . "<trxtype>sale</trxtype>";
        $strxml = $strxml . "<email>" . $this->_email . "</email>";
        $strxml = $strxml . "<phone>" . $this->_phone . "</phone>";
        $strxml = $strxml . "<mobile>" . $this->_mobile . "</mobile>";
        $strxml = $strxml . "<client_ip>" . $this->_clientip . "</client_ip>";
        $strxml = $strxml . "<amount>" . $this->_amount . "</amount>";
        $strxml = $strxml . "<currency>" . $this->_currency . "</currency>";
        $strxml = $strxml . "<mlogo_url></mlogo_url>"; // pls set this to the url where your logo is hosted
        $strxml = $strxml . "<pmethod></pmethod>";
        $strxml = $strxml . "<signature>" . $this->getSign() . "</signature>";
        $strxml = $strxml . "</Request>";
        return $strxml;
    }

    public function getSign()
    {
        $forSign = $this->_mid . $this->_request_id . $this->_ipaddress . $this->_noturl . $this->_resurl . $this->_fname . $this->_lname . $this->_mname . $this->_addr1 . $this->_addr2 . $this->_city . $this->_state . $this->_country . $this->_zip . $this->_email . $this->_phone . $this->_clientip . $this->_amount . $this->_currency . $this->_sec3d;
        return hash("sha512", $forSign . $this->cert);
    }
}