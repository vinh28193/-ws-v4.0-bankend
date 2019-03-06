<?php
/**
 * Created by PhpStorm.
 * User: vinhvv@peacesoft.net
 * Date: 2/9/18
 * Time: 10:08 AM
 */

namespace common\models\analytics;

/**
 * Class Promotion
 * Represents information about a promotion that has been viewed. It is referred to a promoFieldObject
 * @see promoFieldObject https://developers.google.com/analytics/devguides/collection/analyticsjs/enhanced-ecommerce#promotion-data
 * @package common\models\analytics
 */
class Promotion extends DataType
{
    /**
     * The promotion ID
     * e.g. PROMO_1234
     * Either this field must be set.
     * @var string
     */
    public $id;
    /**
     * The name of the promotion
     * (e.g. Summer Sale).
     * Either this field must be set.
     * @var string
     */
    public $name;
    /**
     * The creative associated with the promotion
     * (e.g. summer_banner2).
     * @var string
     */
    public $creative;
    /**
     * The position of the creative
     * (e.g. banner_slot_1).
     * @var string
     */
    public $position;
}