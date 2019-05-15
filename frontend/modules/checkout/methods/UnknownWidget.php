<?php


namespace frontend\modules\checkout\methods;


class UnknownWidget extends MethodWidget
{

    public function run()
    {
        parent::run();
        return $this->render('unknown');
    }
}