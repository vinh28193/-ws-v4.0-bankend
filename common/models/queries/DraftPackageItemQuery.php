<?php

namespace common\models\queries;

use common\models\Package;

/**
 * This is the ActiveQuery class for [[\common\models\db\DraftPackageItem]].
 *
 * @see \common\models\db\Package
 */
class DraftPackageItemQuery extends \common\components\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere(['or',['status' => ''],['status' => null],['status' => 0]]);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @return \common\models\db\Package[]|array
     */
    public function all($db = null)
    {
        $this->active();
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\db\Package|array|null
     */
    public function one($db = null)
    {
        $this->active();
        return parent::one($db);
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
