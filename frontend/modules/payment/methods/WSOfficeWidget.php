<?php


namespace frontend\modules\payment\methods;


class WSOfficeWidget extends MethodWidget
{


    public function run()
    {
        parent::run();
        return $this->render('ws_office');
    }
}