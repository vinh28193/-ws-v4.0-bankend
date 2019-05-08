<?php

namespace frontend\modules\amazon\controllers;


use common\products\BaseProduct;
use frontend\controllers\PortalController;

class AmazonController extends PortalController
{

    public $portal = BaseProduct::TYPE_AMAZON_US;
}