<?php


namespace common\components;


use Yii;
use yii\helpers\ArrayHelper;

/**
 * Trait PickUpWareHouseTrait
 * @package common\components
 *
 * @property-read array|mixed|null $pickUpWareHouse
 */
trait PickUpWareHouseTrait
{

    /**
     * @return array|mixed|null
     *
     * @Phuchc Thay dia chi Kho cho Từng Khách hàng Vàng + Bạc
     */
    private $_pickUpWareHouse;

    public function getPickUpWareHouse()
    {
        if (!$this->_pickUpWareHouse) {
            $user = method_exists($this, 'getUser') ? call_user_func([$this, 'getUser']) : ((($app = Yii::$app) instanceof \yii\web\Application && ($identity = $app->user->identity) !== null) ? $identity : null);
            if ($user !== null && method_exists($user, 'getPickupWarehouse') && ($wh = call_user_func([$user, 'getPickupWarehouse'])) !== null) {
                $this->_pickUpWareHouse = $wh;
            } elseif (($params = ArrayHelper::getValue(Yii::$app->params, 'pickupUSWHGlobal')) !== null) {
                $current = $params['default'];
                $this->_pickUpWareHouse = ArrayHelper::getValue($params, "warehouses.$current", false);
            }
        }
        return $this->_pickUpWareHouse;

    }
}