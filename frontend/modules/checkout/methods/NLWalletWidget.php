<?php


namespace frontend\modules\checkout\methods;


class NLWalletWidget extends  MethodWidget
{


    public function run()
    {
        parent::run();
        return $this->render('nl_wallet');
    }
}