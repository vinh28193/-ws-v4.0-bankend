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
use common\models\db_cms\Category as DbCmsCategory;

/**
 * Class Category
 * @package common\models
 * @property CategoryGroup $categoryGroup
 */
class Category extends DbCmsCategory
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
        } else if ($this->customFee !== null && $this->customFee > 0) {
            Yii::info("Custom fee on Database of ID{$this->id} FEE {$this->customFee}", 'CUSTOM FEE INFORMATION');
            return $this->customFee * $additionalFee->getShippingQuantity();
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
        $portal = strtolower($portal);
        if ($portal === 'ebay') {
            return self::SITE_EBAY;
        } elseif ($portal === 'amazon') {
            return self::SITE_AMAZON_US;
        } elseif ($portal === 'amazon-jp') {
            return self::SITE_AMAZON_JP;
        } elseif ($portal === 'amazon-uk') {
            return self::SITE_AMAZON_UK;
        } else {
            return null;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryGroup()
    {
        return $this->hasOne(CategoryGroup::className(), ['id' => 'category_group_id']);
    }

    public function safeCreate()
    {
        if ($this->isNewRecord === false) {
            return false;
        }
        if (($categorySafe = self::find()->where(['AND', ['alias' => $this->alias], ['siteId' => $this->siteId]])->one()) === null) {
            $this->save(false);
            return $this;
        }
        return $categorySafe;
    }
}
