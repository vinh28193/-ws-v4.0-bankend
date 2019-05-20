<?php


namespace frontend\modules\payment\methods;


class NLWalletWidget extends  MethodWidget
{


    public function run()
    {
        parent::run();
        return $this->render('nl_wallet');
    }
}