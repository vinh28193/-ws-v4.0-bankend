<?php


namespace common\components\cart;

use Yii;

class CartSelection
{
    const SESSION_NAME = 'CartSelection';

    const TYPE_BUY_NOW = 'buynow';
    const TYPE_SHOPPING = 'shopping';


    public static function getCartManager()
    {
        return Yii::$app->cart;
    }

    public static function getSession()
    {
        return Yii::$app->session;
    }

    public static function getSelectedItems($type = null)
    {

        if (($cart = self::getSession()->get(self::SESSION_NAME)) === null) {
            return null;
        }
        if ($type !== null) {
            return isset($cart[$type]) ? $cart[$type] : null;
        }
        return $cart;
    }

    public static function setSelectedItems($type, $items)
    {
        if ($type === self::TYPE_BUY_NOW) {
            $selected = !is_array($items) ? [$items] : $items;
        } else {
            if (($selected = self::getSelectedItems($type)) === null) {
                $selected = [];
            }
            $selected[] = $items;
            $selected = array_unique($selected);
        }
        self::getSession()->set(self::SESSION_NAME, [$type => $selected]);
    }

    public static function countSelectedItems($type)
    {
        if (($selected = self::getSelectedItems($type)) === null) {
            return 0;
        }
        return count($selected);
    }
}