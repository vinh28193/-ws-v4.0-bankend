<?php


namespace frontend\modules\payment\methods;


use yii\helpers\Json;

class ATMOnlineWidget extends MethodWidget
{


    public function init()
    {
        parent::init();
        $this->registerClientScript();
    }

    public function run()
    {
        parent::run();
        return $this->render('atm_online');
    }

    protected function registerClientScript(){

    }

}