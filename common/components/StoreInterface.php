<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-16
 * Time: 08:45
 */

namespace common\components;


interface StoreInterface
{

    public static function getStoreReferenceKey();
    public static function getActiveStore($condition);
}