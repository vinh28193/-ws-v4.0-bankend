<?php

namespace frontend\modules\payment\methods;

class VisaMasterWidget extends MethodWidget
{

    public function init()
    {
    }
    public function run()
    {
        parent::run();
        return $this->render('visa_master');
    }
}