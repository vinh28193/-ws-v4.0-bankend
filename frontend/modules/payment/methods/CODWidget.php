<?php


namespace frontend\modules\payment\methods;


class CODWidget extends MethodWidget
{


    public function run()
    {
        parent::run();
        return $this->render('cod');
    }
}