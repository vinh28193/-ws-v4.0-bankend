<?php


namespace frontend\modules\amazonJp\controllers;


use common\products\BaseProduct;
use frontend\controllers\PortalController;

class AmazonJpController extends PortalController
{

    public $portal = BaseProduct::TYPE_AMAZON_JP;
}