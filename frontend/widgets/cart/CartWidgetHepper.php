<?php


namespace frontend\widgets\cart;


use common\components\cart\CartSelection;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class CartWidgetHepper
{



    public static function getCartProductId($key,$productId, $productSku = null)
    {
        $key .= $productId;
        if ($productSku !== null) {
            $key .= Inflector::id2camel($productSku);
        }
//        $key = Inflector::camelize($key);
        return "cartProduct{$key}";
    }

    public static function getIsChecked($id, $productId, $productSku = null)
    {
        $params = ['key' => $id, 'id' => $productId];
        if ($productSku !== null) {
            $params = ArrayHelper::merge($params, ['sku' => $productSku]);
        }
        return CartSelection::isExist(CartSelection::TYPE_SHOPPING, $params);
    }
}