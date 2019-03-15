<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-15
 * Time: 21:06
 */

namespace common\components\db;

/**
 * Class ActiveQuery
 * @package common\components\db
 */

class ActiveQuery extends \yii\db\ActiveQuery
{
    /**
     * @return string
     */
    public function getTableName()
    {
        /** @var  $class \yii\db\ActiveRecord */
        $class = $this->modelClass;
        return $class::tableName();
    }

    /**
     * @param $name
     * @param bool|string $tableName
     * @return string
     */
    public function getColumnName($name, $tableName = true)
    {
        if ($tableName === true) {
            return $this->getTableName() . '.' . $name;
        } elseif (is_string($tableName)) {
            return "$tableName.$name";
        }
        return $name;
    }
}