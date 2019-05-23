<?php


namespace common\models\cms;
/**
 * Class WsCategoryGroup
 * @package common\models\cms
 * @property WsCategory[] $wsParentCategories
 */

class WsCategoryGroup extends \common\models\db_cms\WsCategoryGroup
{

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsParentCategories()
    {
        return $this->hasMany(WsCategory::className(), ['ws_category_group_id' => 'id'])->where(['IS', 'parent_id', new \yii\db\Expression('NULL')]);
    }
}