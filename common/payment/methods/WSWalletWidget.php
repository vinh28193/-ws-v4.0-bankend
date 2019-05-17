<?php


namespace common\payment\methods;


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

    public function registerClientScripts()
    {

    }
}