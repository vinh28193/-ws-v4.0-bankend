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
     * @return $this
     */
    public function remove()
    {
        $this->andWhere([$this->getColumnName('remove') => 0]);
        return $this;
    }

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

    /**
     * @param array $columns
     * @return $this
     */
    public function defaultSelect($columns = [])
    {
        $this->select(array_merge([
            $this->getColumnName('id')
        ], $columns));
        return $this;
    }

    /**
     * $this->expression('NOW()');
     * $this->expression(['time' => 'NOW()']);
     * @param $columns
     * @return \yii\db\Expression
     */
    public function expression($columns)
    {
//        if (is_string($columns)) {
            return new \yii\db\Expression($columns);
//        } else if (is_array($columns) && isset($columns[0])) {
//            $results = [];
//            foreach ($columns as $name => $definition) {
//                $results[$name] = $this->expression($definition);
//            }
//            return $results;
//        } else {
//            return [$columns[0] => $this->expression($columns[1])];
//        }
    }
    /**
     * @param string $column
     * @param string $value
     * @return $this
     */
    public function whereMore($column,$value){
        $values = explode(',',$value);
        $this->andWhere([$column=>$values]);
        return $this;
    }
    /**
     * @param string[] $columns
     * @param string $value
     * @return $this
     */
    public function whereMoreMultiColumn($columns,$value){
        $values = explode(',',$value);
        $condition = ['or'];
        foreach ($columns as $column){
            $condition[] = [$column => $values];
        }
        $condition = count($values) > 1 ? $condition : $condition[1];
        $this->andWhere($condition);
        return $this;
    }
    /**
     * @param string $column
     * @param string $value
     * @return $this
     */
    public function whereLikeMore($column,$value){
        $values = explode(',',$value);
        $condition = ['or'];
        foreach ($values as $v){
            $condition[] = ['like',$column,$v];
        }
        $condition = count($values) > 1 ? $condition : $condition[1];
        $this->andWhere($condition);
        return $this;
    }
    /**
     * @param string[] $columns
     * @param string $value
     * @return $this
     */
    public function whereLikeMoreMultiColumn($columns,$value){
        $values = explode(',',$value);
        $condition = ['or'];
        foreach ($columns as $column){
            foreach ($values as $v){
                $condition[] = ['like',$column,$v];
            }
        }
        $condition = count($values) > 1 ? $condition : $condition[1];
        $this->andWhere($condition);
        return $this;
    }
}