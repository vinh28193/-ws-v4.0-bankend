<?php
/**
 * Created by PhpStorm.
 * User: vinhvv@peacesoft.net
 * Date: 2/9/18
 * Time: 10:05 AM
 */

namespace common\models\analytics;

use yii\base\BaseObject;
use yii\base\InvalidConfigException;

/**
 * Class DataType
 * There are multiple types of ecommerce data you can send using analytics.js: impression data, product data, promotion data, and action data.
 * @see https://developers.google.com/analytics/devguides/collection/analyticsjs/enhanced-ecommerce
 * @package common\models\analytics
 */
class DataType extends BaseObject
{
    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
        parent::init();

    }
}