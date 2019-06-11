<?php


namespace frontend\modules\payment\methods;


class BankTransferVN4 extends MethodWidget
{

    public function run()
    {
        parent::run();
        return $this->render('bank_transfer_vn_ver4');
    }
}