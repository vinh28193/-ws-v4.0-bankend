<?php


namespace frontend\modules\payment\methods;


class AlepayWidget extends MethodWidget
{

    public function run()
    {
        parent::run();
        echo $this->render('alepay');
    }
}