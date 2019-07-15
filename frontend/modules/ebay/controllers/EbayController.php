<?php


namespace frontend\modules\ebay\controllers;

use Yii;
use common\products\BaseProduct;
use frontend\controllers\PortalController;

class EbayController extends PortalController
{
    public $portal = BaseProduct::TYPE_EBAY;
    public function init()
    {
        parent::init();
    }
}
