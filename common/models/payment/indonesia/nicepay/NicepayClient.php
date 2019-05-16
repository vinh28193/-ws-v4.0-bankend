<?php
/**
 * Created by PhpStorm.
 * User: tunglt
 * Date: 19/12/2017
 * Time: 4:54 PM
 */

namespace common\models\payment\indonesia\nicepay;


class NicepayClient
{
    const BANK_INSTALMENT = 'BMRI';
    const INSTALMENT_TYPE = 2;

    public $instalment_bank;
    public $instalment_method;
    public $instalment_month = 3;
    public $instalment_type = 2;
    public $total_amount;
    public $fee = 0;

    public function __construct($data)
    {
        $this->instalment_bank = $data['instalment_bank'];
        $this->instalment_method = $data['instalment_method'];
        $this->instalment_month = $data['instalment_month'];
        $this->instalment_type = $data['instalment_type'];
        $this->total_amount = $data['total_amount'];
    }

    public function calculateInstallmentFee()
    {
        $percent = 0;
        if($this->instalment_month == 6){
            $percent = 5;
        }elseif($this->instalment_month == 12){
            $percent = 8;
        }
        if($this->instalment_bank == @self::BANK_INSTALMENT && $this->instalment_type == @self::INSTALMENT_TYPE){
            $this->fee = (($percent/100)*$this->total_amount);
        }
        return $this->fee;
    }

    public function calculateInstallmentFeePerMonth(){
        return ($this->total_amount + $this->calculateInstallmentFee())/$this->instalment_month;
    }
}