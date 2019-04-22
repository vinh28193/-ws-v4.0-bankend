<?php

namespace common\models\cms;

use Yii;

class WsAlias extends \common\models\db_cms\WsAlias
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWsAliasItems()
    {
        return $this->hasMany(WsAliasItem::className(), ['ws_alias_id' => 'id'])->where(['status' => 1])->orderBy('sort ASC');
    }

    public function getData(){

    }
    public function getLandingProduct()
    {
        $key = 'ITEM_PRODUCT_AMAZONE_BY_' . '_ALIAS_' . $this->id . '-' . WsAliasItem::TYPE_LANDING;
        $landingProduct = Yii::$app->request->post('noCache',false) == 1 ? null : Yii::$app->cache->get($key);
        if (!$landingProduct) {
            $landingProduct = $this->getWsAliasItems()->where(['type' => WsAliasItem::TYPE_LANDING, 'status' => 1, 'is_head' => 0])
                ->with([
                    'wsProductGroups.wsProducts' => function (\yii\db\ActiveQuery $query) {
                        $query->select('ws_product.*,(ws_product.calculated_sell_price * ' . Yii::$app->storeManager->getExchangeRate() . ') as Local_calculated_sell_price');
                        $query->asArray();
                    }
                ])->asArray()->all();

            Yii::$app->cache->set($key, $landingProduct, 60 * 60 * 24);
        }
        return $landingProduct;
    }

    public function getCategoryList()
    {
        $key = 'ITEM_PRODUCT_AMAZONE_BY_' . '_ALIAS_' . $this->id . '-' . WsAliasItem::TYPE_CATEGORY;
        $categoryList = Yii::$app->request->post('noCache',false) == 1 ? null : Yii::$app->cache->get($key);
        if (!($categoryList)) {
            $categoryList = $this->getWsAliasItems()->where(['type' => WsAliasItem::TYPE_CATEGORY, 'status' => 1, 'is_head' => 0])->with('wsCategoryGroups.wsParentCategories.wsCategories')->asArray()->all();
            Yii::$app->cache->set($key, $categoryList, 60 * 60 * 24);
        }
        return $categoryList;
    }

    public function getImageGrid()
    {
        $key = 'ITEM_PRODUCT_AMAZONE_BY_ALIAS_' . $this->id . '-' . WsAliasItem::TYPE_SLIDER;
        if (!($imageGrid = Yii::$app->cache->get($key))) {
            $imageGrid = $this->getWsAliasItems()->where(['type' => WsAliasItem::TYPE_SLIDER, 'status' => 1, 'is_head' => 0])->with('wsImageGroups.wsImages')->asArray()->all();

            Yii::$app->cache->set($key, $imageGrid, 60 * 60 * 24);
        }
        return $imageGrid;
    }

    public function getSubHead()
    {
        $key = 'ITEM_PRODUCT_AMAZONE_BY_ALIAS_' . $this->id . '-SUB_HEAD';
        if (!($subHead = Yii::$app->cache->get($key))) {
            $subHead = $this->getWsAliasItems()->where(['status' => 1, 'is_head' => 1])
                ->with('wsCategoryGroups.wsCategories.wsCategories', 'wsProductGroups.wsProducts', 'wsImageGroups.wsImages')
                ->all();
            Yii::$app->cache->set($key, $subHead, 60 * 60 * 24);
        }
        return $subHead;
    }
}