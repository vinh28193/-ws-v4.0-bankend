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


    private static $cart;

    /**
     * @return CartManager
     */
    public static function getCartManager()
    {
        if (!is_object(self::$cart)) {
            self::$cart = Yii::$app->cart;
        }
        return self::$cart;
    }

    public static function getAllTypes()
    {
        return [self::TYPE_SHOPPING, self::TYPE_BUY_NOW, self::TYPE_INSTALLMENT];
    }

    public static function getSession()
    {
        return Yii::$app->session;
    }


    public static function addSelectedItem($type, $param)
    {

        if (($items = self::getSelectedItems($type)) === null) {
            self::setSelectedItems($type, $param);
            return true;
        } elseif (!self::isExist($type, $param)) {
            $items = array_merge($items, !is_array($param) ? [$param] : $param);
//            if (!isset($param['id'])) {
//                if (($item = self::getCartManager()->getItem($type, $param['key'])) === null || $item === false) {
//                    return false;
//                }
//                $param = CartHelper::mapCartKeys([$item]);
//                $items = array_merge($items, $param);
//            } else {
//                $child = ArrayHelper::getValue($items, $param['key'], []);
//                $newChild = ['id' => $param['id']];
//                if (isset($param['sku'])) {
//                    $newChild['sku'] = $param['sku'];
//                }
//                $child[] = $newChild;
//                $items = array_merge($items, [
//                    $param['key'] => $child
//                ]);
//            }

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
        Yii::info($items, $type);
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


    /**
     * @param $type
     * @param $param
     * @return bool
     */
    public static function isExist($type, $param)
    {
        if (($items = self::getSelectedItems($type)) === null) {
            return false;
        }
//        $isChild = isset($param['id']);
//        if (!isset($items[$param['key']])) {
//            return false;
//        } elseif (!$isChild) {
//            return true;
//        } elseif (($childs = $items[$param['key']]) !== null && !empty($childs)) {
//            foreach ($childs as $child) {
//                if (self::getCartManager()->isDetectedProduct($child, $param)) {
//                    return true;
//                }
//            }
//        }
//        return false;
        return ArrayHelper::isIn($param, $items);

    }

    public static function removeSelectedItem($type, $param)
    {
        $items = [];
//        $removedAll = !isset($param['id']) || (isset($param['id']) && $param['id'] === null);
        $removed = false;
        foreach (self::getSelectedItems($type) as $key => $childs) {
//            if ($key === $param['key']) {
//                if ($removedAll) {
//                    $removed = true;
//                    continue;
//                } else {
//                    $newChild = [];
//                    foreach ($childs as $child) {
//                        if (self::getCartManager()->isDetectedProduct($child, $param)) {
//                            $removed = true;
//                            continue;
//                        }
//                        $newChild[] = $child;
//                    }
//                    $childs = $newChild;
//                    if (empty($childs) && $removed) {
//                        continue;
//                    }
//                }
//            }
//            $items[$key] = $childs;

            if ($param === $childs) {
                $removed = true;
                continue;
            }
            $items[] = $childs;
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

    public static function isValidType($type)
    {
        return ArrayHelper::isIn($type, [
            self::TYPE_BUY_NOW,
            self::TYPE_SHOPPING,
            self::TYPE_INSTALLMENT
        ]);
    }
}
