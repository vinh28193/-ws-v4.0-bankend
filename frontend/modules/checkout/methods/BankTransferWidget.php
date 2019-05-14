<?php


namespace frontend\modules\checkout\methods;


class BankTransferWidget extends MethodWidget
{

    public function run()
    {
        parent::run();
        return $this->render('bank_transfer');
    }
}