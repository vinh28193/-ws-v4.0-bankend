<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 14/05/2018
 * Time: 13:37
 */

namespace wallet\modules\v1\models\form;


class ResponePaymentForm
{
    public $status_code;
    public $wallet_transaction_code='';
    public $payment_transaction='';
    public $total_amount;
    public $request_content;
    public $response_content;

    public function __construct($error_code='001',$wallet_transaction_code='',$payment_transaction='',$total_amount=0,$request_content='',$response_content='')
    {
        $this->status_code = $error_code;
        $this->wallet_transaction_code = $wallet_transaction_code;
        $this->payment_transaction = $payment_transaction;
        $this->total_amount = $total_amount;
        $this->request_content = $request_content;
        $this->response_content = $response_content;
        return $this;
    }

}