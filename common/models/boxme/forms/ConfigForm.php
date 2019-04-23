<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 07/03/2019
 * Time: 14:45
 */

namespace common\models\boxme\forms;

use yii\base\Model;

class ConfigForm extends Model
{

    const ORDER_TYPE_CONSOLIDATE = 'consolidate';
    const ORDER_TYPE_FULFILL = 'fulfill';
    const ORDER_TYPE_CROSS_BORDER = 'cross_border';

    const SORT_MODE_PRICE = 'best_price';
    const SORT_MODE_TIME = 'best_time';
    const SORT_MODE_RATING = 'best_rating';

    const ACCEPTED = 'Y';
    const NOT_ACCEPT = 'N';

    /**
     * Shipping service code
     * @var string
     */
    public $delivery_service;

    /**
     * Sort by : best_price (default), best_time, best_rating
     * @var string
     */
    public $sort_mode = self::SORT_MODE_PRICE;

    /**
     * Order type : normal (default), cross_border, consolidate, fulfill
     * @var string
     */
    public $order_type = 'consolidate';

    /**
     * Return mode. 1: to pickup address (default) , 2 : to return address
     * @var integer
     */
    public $return_mode = 2;

    /**
     * @var string
     */
    public $insurance = self::NOT_ACCEPT;

    /**
     * @var string
     */
    public $auto_approve = self::NOT_ACCEPT;

    /**
     * @var string
     */
    public $unit_metric = 'metric';

    /**
     * @var string
     */
    public $document;
    /**
     * @var string
     */
    public $currency = 'VNĐ'; // VNĐ | IDR

    public function attributes()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['currency', 'order_type', 'return_mode'], 'required'],
            [['return_mode'], 'integer'],
            [['delivery_service','sort_mode', 'order_type', 'insurance', 'document', 'currency', 'unit_metric'], 'string'],
        ];
    }
}