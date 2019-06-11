<?php


namespace frontend\modules\payment\methods;


class InternetBankingWidget extends MethodWidget
{

    public function run()
    {
        parent::run();
        return $this->render('internet_banking');
    }
}