<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 28/03/2019
 * Time: 11:41 SA
 */

namespace common\models;

use Yii;
use common\components\AdditionalFeeInterface;
use common\models\db\Category as DbCategory;
use common\models\queries\CategoryQuery;
/**
 * Class Category
 * @package common\models
 * @property CategoryGroup $categoryGroup
 */
class Category extends DbCategory
{

    const SITE_EBAY = 2;
    const SITE_AMAZON_US = 26;
    const SITE_AMAZON_UK = 27;
    const SITE_AMAZON_JP = 1014;

    public function getCustomFee(AdditionalFeeInterface $additionalFee)
    {

        if (
            $this->categoryGroup !== null &&
            ($group = $this->categoryGroup) instanceof CategoryGroup &&
            ($customFee = $group->customFeeCalculator($additionalFee)) > 0
        ) {
            return $customFee;
        } else if ($this->custom_fee !== null && $this->custom_fee > 0) {
            Yii::info("Custom fee on Database of ID{$this->id} FEE {$this->custom_fee}", 'CUSTOM FEE INFORMATION');
            return $this->custom_fee * $additionalFee->getShippingQuantity();
        } else {
            return 0;
        }

    }

    /**
     * {@inheritdoc}
     * @return CategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryGroup()
    {
        return $this->hasOne(CategoryGroup::className(), ['id' => 'category_group_id'])->where(['active' => 1]);
    }
}
