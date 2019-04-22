<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-04-22
 * Time: 17:07
 */

namespace common\models\cms;

use Yii;
use yii\helpers\ArrayHelper;
use common\components\StoreManager;

class PageService
{

    public static function getPage($type, $store = 1, $id = null)
    {
        $key = 'PAGE_CACHE_KEY_PREFIX';
        if ($id !== null) {
            $key .= '_ID' . $id;
        }
        $key .= '_STORE' . $store . '_TYPE' . $type;
        if (!($page = Yii::$app->cache->get($key))) {
            $query = WsPage::find();
            $where = ['AND'];
            $where[] = ['type' => $type];
            $where[] = ['store_id' => $store];
            $where[] = ['status' => 1];
            if ($id !== null) {
                $where[] = ['id' => $id];
            }
            $query->where($where);
            $page = $query->one();
            Yii::$app->cache->set($key, $page, 60 * 60);
        }
        return $page;
    }

    public static function getPageItem($pageId, $limit = 6, $offset = 0)
    {
        $key = 'LIST_PAGE_ITEM_BY_PAGE_' . $pageId;
        if ($limit < 0 && $offset < 0) {
            $key .= '_COUNT';
        } else {
            $key .= '_LIMIT' . $limit . '_OFFSET' . $offset;
        }
        if (!($items = Yii::$app->cache->get($key))) {
            $query = WsPageItem::find()->where(['ws_page_id' => $pageId])
                ->andWhere(['status' => 1])
                //->orderBy('sort asc')
                ->orderBy([
                    'sort' => SORT_ASC,
                    'id' => SORT_ASC,
                ])->limit($limit)->offset($offset);
            if ($limit < 0 && $offset < 0) {
                $items = $query->count();
            } else {
                $items = $query->all();
            }

            Yii::$app->cache->set($key, $items, 60 * 60);
        }
        return $items;
    }

    public static function getAlias($type, $store = 1)
    {
        if ($type === WsPage::TYPE_AMZ) {
            $type = 'amazon';
        } elseif ($type === WsPage::TYPE_EBAY) {
            $type = 'ebay';
        } elseif ($type === WsPage::TYPE_HOME) {
            $type = 'open';
        }
        $key = 'ALIAS_' . $store . $type;
        $alias = Yii::$app->request->post('noCache', false) == 1 ? null : Yii::$app->cache->get($key);
        if (!($alias)) {
            if ($type !== null && ($alias = WsAlias::findOne(['store_id' => $store, 'type' => $type])) !== null) {
                $alias = [
                    'alias' => ArrayHelper::toArray($alias),
                    'landing' => $alias->getLandingProduct(),
                    'categories' => $alias->getCategoryList(),
                    'images' => $alias->getImageGrid(),
                ];
                Yii::$app->cache->set($key, $alias, 60 * 60);
            }
        }
        return !$alias ? [] : (array)$alias;
    }

    public static function getSlider($pageId)
    {
        $key = 'SLIDE_' . $pageId;
        if (!($slides = Yii::$app->cache->get($key))) {
            if (
                ($pageItem = WsPageItem::find()->where(['type' => WsPageItem::TYPE_SLIDER, 'ws_page_id' => $pageId, 'status' => 1])->one()) !== null &&
                ($imageGroup = WsImageGroup::find()->where(['ws_page_item_id' => $pageItem->id])->one()) !== null
            ) {
                $slides = WsImage::find()->where(['ws_image_group_id' => $imageGroup->id, 'status' => 1])->orderBy('sort ASC')->all();
            }
            Yii::$app->cache->set($key, $slides, 60 * 60);
        }
        return $slides;
    }

    public static function getBlockByPageItem($pageItemId)
    {
        $key = 'ITEM_BLOCK_BY_PAGE_ITEM_ID_' . $pageItemId;
        if (!($blocks = Yii::$app->cache->get($key))) {
            $blocks = WsBlock::find()
                ->where([
                    'AND',
                    ['ws_page_item_id' => $pageItemId],
                    ['IS NOT', 'type', new \yii\db\Expression('NULL')]
                ])
                ->one();
            Yii::$app->cache->set($key, $blocks, 60 * 60);
        }
        return $blocks;
    }

    public static function getGroupCategoryByBlockId($blockId)
    {
        $key = 'GROUP_CATEGORY_BY_BLOCK_ID' . $blockId;
        if (!($groupCategory = Yii::$app->cache->get($key))) {
            $groupCategory = WsCategoryGroup::find()->where(['ws_block_id' => $blockId])->one();
            Yii::$app->cache->set($key, $groupCategory, 60 * 60);
        }
        return $groupCategory;

    }

    public static function getCategoryByGroupId($groupId)
    {
        $key = 'LIST_CATE_BY_GROUP_' . $groupId;
        if (!($categories = Yii::$app->cache->get($key))) {
            $categories = WsCategory::find()
                ->where(['ws_category_group_id' => $groupId])
                ->orderBy('sort DESC')
                ->all();
            Yii::$app->cache->set($key, $categories, 60 * 60);
        }
        return $categories;

    }

    public static function getGroupProductByBlockId($blockId)
    {

        $key = 'ITEM_GROUP_BY_BLOCK_ID_' . $blockId;
        if (!($groupProduct = Yii::$app->cache->get($key))) {
            $groupProduct = WsProductGroup::find()->where(['ws_block_id' => $blockId])->one();
            Yii::$app->cache->set($key, $groupProduct, 60 * 60);
        }
        return $groupProduct;
    }

    public static function getProductByGroupId($groupId)
    {

        $key = 'LIST_PRODUCT_BY_GROUP_TOP_' . $groupId;
        $products = Yii::$app->request->post('noCache', false) == 1 ? null : Yii::$app->cache->get($key);
        if (!$products) {
            $products = WsProduct::find()
                ->select('*,(ws_product.calculated_sell_price * ' . self::getStoreManager()->getExchangeRate() . ') as Local_calculated_sell_price')
//                ->select([
//                    'item_id', 'item_url', 'item_sku', 'name', 'image_origin', 'image', 'start_price', 'sell_price', 'weight', 'category_id',
//                    'calculated_start_price', 'calculated_sell_price', 'rate_count', 'rate_star', 'category_name', 'start_time', 'end_time', 'provider',
//                    new \yii\db\Expression('CASE WHEN item_url like "%ebay%" THEN "ebay" WHEN item_url like "%amazon%" THEN "amazon" ELSE "other" END as type')
//                ])
                ->where(['ws_product_group_id' => $groupId, 'status' => 1])
                ->orderBy(['sort' => SORT_ASC])
                ->asArray()->all();
            Yii::$app->cache->set($key, $products, 60 * 60);
        }
        return $products;
    }

    public static function getItemGroupImage($blockId, $type = WsImageGroup::TYPE_BRAND)
    {
        $key = 'ITEM_GROUP_IMAGE_BY_BLOCK_ID_' . $blockId . $type;
        if (!($groupImage = Yii::$app->cache->get($key))) {
            $groupImage = WsImageGroup::find()->where(['ws_block_id' => $blockId, 'type' => $type])->one();
            Yii::$app->cache->set($key, $groupImage, 60 * 60);
        }
        return $groupImage;
    }

    public static function getImageByGroupId($ImageGroupId)
    {
        $key = 'LIST_IMAGE_BRAN_BY_BLOCK_' . $ImageGroupId;
        if (!($images = Yii::$app->cache->get($key))) {
            $images = WsImage::find()
                ->where(['ws_image_group_id' => $ImageGroupId])
                ->orderBy(['sort' => SORT_ASC])
                ->all();
            Yii::$app->cache->set($key, $images, 60 * 60);
        }
        return $images;
    }

    /**
     * @return StoreManager
     */
    public static function getStoreManager()
    {
        return Yii::$app->storeManager;
    }
}