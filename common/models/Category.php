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

/**
 * Class Category
 * @package common\models
 * @property CategoryGroup $categoryGroup
 */
class Category extends DbCategory
{


    const SITE_EBAY = 'ebay';
    const SITE_AMAZON_US = 'amazon';
    const SITE_AMAZON_UK = 'amazon-uk';
    const SITE_AMAZON_JP = 'amazon-jp';


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
     * @param $portal
     * @return int|null
     */
    public static function getSiteIdByPortal($portal)
    {
        return strtolower($portal);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryGroup()
    {
        return $this->hasOne(CategoryGroup::className(), ['id' => 'category_group_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\queries\CategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\CategoryQuery(get_called_class());
    }

    public function safeCreate()
    {
        if ($this->isNewRecord === false) {
            return false;
        }
        if (($categorySafe = self::find()->where(['AND', ['alias' => $this->alias], ['site' => $this->site]])->one()) === null) {
            $this->save(false);
            return $this;
        }
        return $categorySafe;
    }
}
