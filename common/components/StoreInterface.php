<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-25
 * Time: 09:42
 */

namespace common\components;

interface StoreInterface {

    /**
     * @param $condition
     * @return \yii\db\ActiveRecord
     */
    public static function getActiveStore($condition);

    /**
     * @return string
     */
    public static function getStoreReferenceKey();
}