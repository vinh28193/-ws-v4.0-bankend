<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-25
 * Time: 16:00
 */

namespace common\tests\_support;


class ActiveStore extends \yii\base\BaseObject implements \common\components\StoreInterface
{

    public $id = 123;
    public $url = 'localhost:80';

    public static function getActiveStore($condition)
    {
        return new self($condition);
    }
    public static function getStoreReferenceKey()
    {
        return 'store_id';
    }
}