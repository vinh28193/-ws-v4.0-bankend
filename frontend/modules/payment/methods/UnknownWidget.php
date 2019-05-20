<?php


namespace frontend\modules\payment\methods;


class UnknownWidget extends MethodWidget
{

    public function run()
    {
        parent::run();
        return $this->render('unknown');
    }
}