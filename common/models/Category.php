<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 28/03/2019
 * Time: 11:41 SA
 */

namespace common\models;


use common\models\weshop\ConditionCustomFee;
use common\models\weshop\TargetCustomFee;

/**
 * Class Category
 * @package common\models
 * @property CategoryGroup $categoryGroup
 */

class Category extends \common\models\db\Category
{
    public function getCustomFee($price, $quantity, $weight, $isNew = false){
        $target = new TargetCustomFee();
        $target->price = $price;
        $target->quantity = $quantity;
        $target->weight = $weight;
        $target->condition = $isNew ? ConditionCustomFee::condition_NEW : ConditionCustomFee::condition_used;
        if($this->categoryGroup){
            return $this->categoryGroup->customFeeCalculator($target);
        }elseif($price > 500){
            if ($isNew) {
                return $quantity * $price * 0.07; // 7% - Trên 500$, mới
            } else {
                return $quantity * $price * 0.05; // 5% - Trên 500$, cũ, chưa tính bảo hiểm
            }
        }else{
            if ($this->custom_fee !== null && $this->custom_fee > 0) {
//                \Yii::info("Custom fee on Database of ID{$this->id} FEE {$this->custom_fee}",'CUSTOM FEE INFORMATION');
                return $this->custom_fee * $quantity;
            } else {
                return 0;
            }
        }
    }

    public function getCategoryGroup(){
        return $this->hasOne(CategoryGroup::className(), ['id' => 'category_group_id'])->where(['active' => 1]);
    }
}
