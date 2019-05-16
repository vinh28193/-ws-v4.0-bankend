<?php


namespace frontend\modules\checkout\methods;


class AlepayWidget extends MethodWidget
{

    public function run()
    {
        parent::run();
        echo $this->render('alepay');
    }
}