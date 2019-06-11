<?php


namespace frontend\modules\payment\methods;


class QRCodeWidget extends MethodWidget
{


    public function run()
    {
        parent::run();
        return $this->render('qr_code');
    }
}