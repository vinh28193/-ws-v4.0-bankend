<?php


namespace frontend\modules\checkout\controllers;


use frontend\controllers\FrontendController;
use frontend\modules\checkout\Module;
use yii\helpers\ArrayHelper;

class CheckoutController extends FrontendController
{

    /**
     * @var string
     */
    public $layout = '@frontend/views/layouts/checkout';

    /**
     * @var Module
     */
    public $module;

    /**
     * @var string
     */
    public $breadcrumbParam = [];

    public function defaultLayoutParams()
    {
        return ArrayHelper::merge(parent::defaultLayoutParams(), [
            'breadcrumbParam' => $this->breadcrumbParam
        ]);
    }

    /**
     * @return array
     */
    public function ogMetaTag()
    {
        return parent::ogMetaTag();
    }


}