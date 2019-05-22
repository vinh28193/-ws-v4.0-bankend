<?php


namespace common\models\cms;


/**
 * Class WsAliasItem
 * @package common\models\cms
 * @property WsCategoryGroup[] $wsCategoryGroups
 */
class WsAliasItem extends \common\models\db_cms\WsAliasItem
{

    const TYPE_LANDING = 'LANDING';
    const TYPE_CATEGORY = "CATEGORY";
    const TYPE_CATEGORY_LANDING = "CATEGORY_LANDING";
    const TYPE_SLIDER = "SLIDER";
    const TYPE_ALIAS = "ALIAS";

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsCategoryGroups()
    {
        return $this->hasMany(WsCategoryGroup::className(), ['ws_alias_item_id' => 'id'])->orderBy('sort ASC');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsImageGroups()
    {
        return $this->hasMany(WsImageGroup::className(), ['ws_alias_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsProductGroups()
    {
        return $this->hasMany(WsProductGroup::className(), ['ws_alias_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsProducts(){
        return $this->hasMany(WsProduct::className(),['ws_product_group_id' => 'id'])->via('wsProductGroups');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsCategories(){
        return $this->hasMany(WsCategory::className(),['ws_category_group_id' => 'id'])->via('wsCategoryGroups');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsImages(){
        return $this->hasMany(WsImage::className(),['ws_category_group_id' => 'id'])->via('wsImageGroups');
    }
}