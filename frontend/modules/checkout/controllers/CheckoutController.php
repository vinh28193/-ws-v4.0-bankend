<?php


namespace frontend\modules\checkout\controllers;


use frontend\controllers\FrontendController;
use yii\helpers\ArrayHelper;

class CheckoutController extends FrontendController
{

    public $layout = '@frontend/views/layouts/checkout';

    public $step = 1;

    public function defaultLayoutParams()
    {
        return ArrayHelper::merge(parent::defaultLayoutParams(),[
            'step' => $this->step,
        ]);
    }

}