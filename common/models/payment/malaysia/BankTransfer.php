<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-06-29
 * Time: 17:28
 */

namespace common\models\payment\malaysia;


use common\models\payment\Payment;
use common\models\payment\PaymentResponse;

class BankTransfer
{
    /**
     * @var Payment
     */
    public $payment;
    public $binCode;
    public function createPayment(){
        $this->binCode = $this->payment->order_bin;
        if($this->payment->page == Payment::PAGE_ADDFEE){
            $this->binCode = $this->payment->addfee_bin;
        }
        $url = "{$this->payment->website->getUrl()}/bank-transfer/{$this->binCode}/success.html";
        return new PaymentResponse(true,null,$url,'Success','GET',$this->payment);
    }
}