<?php


namespace common\components\cart;

use Yii;
use yii\helpers\ArrayHelper;

class CartSelection
{
    const SESSION_NAME = 'CartSelection';

    const TYPE_INSTALLMENT = 'installment';
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


    public static function addSelectedItem($type, $item)
    {
        if (($items = self::getSelectedItems($type)) === null) {
            self::setSelectedItems($type, $item);
            return true;
        } elseif (!self::isExist($type, $item)) {
            $items = array_merge($items, !is_array($item) ? [$item] : $item);
            self::setSelectedItems($type, $items);
            return true;
        }
        return false;

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
        $selected = !is_array($items) ? [$items] : $items;
        self::getSession()->set(self::SESSION_NAME, [$type => $selected]);
    }

    public static function countSelectedItems($type)
    {
        if (($selected = self::getSelectedItems($type)) === null) {
            return 0;
        }
        return count($selected);
    }

    public static function isExist($type, $item)
    {
        if (($items = self::getSelectedItems($type)) === null) {

            return false;
        }
        return ArrayHelper::isIn($item, $items);
    }

    public static function removeSelectedItem($type, $item)
    {
        $items = [];
        $removed = false;
        foreach (self::getSelectedItems($type) as $i) {
            if ($item === $i) {
                $removed = true;
                continue;
            }
            $items[] = $i;
        }
        self::setSelectedItems($type, $items);
        return $removed;
    }

    public static function watchItem($type, $item)
    {
        if (self::isExist($type, $item)) {
            return self::removeSelectedItem($type, $item);
        } else {
            return self::addSelectedItem($type, $item);
        }
    }
}