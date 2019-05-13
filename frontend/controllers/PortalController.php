<?php


namespace frontend\controllers;


use common\products\BaseProduct;
use yii\helpers\ArrayHelper;

class PortalController extends FrontendController
{
    public $layout = '@frontend/views/layouts/portal';

    public $portal = BaseProduct::TYPE_EBAY;

    public function defaultLayoutParams()
    {
        return ArrayHelper::merge(parent::defaultLayoutParams(),[
            'portal' => $this->portal,
        ]);
    }
}