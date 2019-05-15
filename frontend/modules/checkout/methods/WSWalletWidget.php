<?php


namespace frontend\modules\checkout\methods;


class WSWalletWidget extends MethodWidget
{

    public function init()
    {

    }

    public function run()
    {
        parent::run();
        return $this->render('ws_wallet');
    }
}