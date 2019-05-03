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

    /**
     * @return \yii\caching\CacheInterface
     */
    public static function getCache()
    {
        return Yii::$app->cache;
    }

    /**
     * @param $type
     * @param int $store
     * @param null $id
     * @param bool $flushCache
     * @return array|mixed
     */
    public static function getPage($type, $store = 1, $id = null, $flushCache = false)
    {
        $key = 'PAGE_CACHE_KEY_PREFIX';
        if ($id !== null) {
            $key .= '_ID' . $id;
        }
        $key .= '_STORE' . $store . '_TYPE' . $type;
        if (!($page = self::getCache()->get($key)) || $flushCache) {
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
            self::getCache()->set($key, $page, 60 * 60);
        }
        return $page;
    }

    public static function getPageItem($pageId, $limit = 6, $offset = 0, $flushCache = false)
    {
        $key = 'LIST_PAGE_ITEM_BY_PAGE_' . $pageId;
        if ($limit < 0 && $offset < 0) {
            $key .= '_COUNT';
        } else {
            $key .= '_LIMIT' . $limit . '_OFFSET' . $offset;
        }
        if (!($items = self::getCache()->get($key)) || $flushCache) {
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

            self::getCache()->set($key, $items, 60 * 60);
        }
        return $items;
    }

    public static function getAlias($type, $store = 1, $flushCache = false)
    {
        if ($type === WsPage::TYPE_AMZ) {
            $type = 'amazon';
        } elseif ($type === WsPage::TYPE_EBAY) {
            $type = 'ebay';
        } elseif ($type === WsPage::TYPE_HOME) {
            $type = 'open';
        }
        $key = 'ALIAS_' . $store . $type;
        if (!($alias = self::getCache()->get($key)) || $flushCache) {
            if ($type !== null && ($alias = WsAlias::findOne(['store_id' => $store, 'type' => $type])) !== null) {
                $alias = [
                    'alias' => $alias->getAttributes(),
                    'landing' => $alias->getLandingProduct(),
                    'categories' => $alias->getCategoryList(),
                    'images' => $alias->getImageGrid(),
                ];
                self::getCache()->set($key, $alias, 60 * 60);
            }
        }
        return !$alias ? [] : (array)$alias;
    }

    /**
     * @param $pageId
     * @param bool $flushCache
     * @return array|mixed|\yii\db\ActiveRecord[]
     */
    public static function getSlider($pageId, $flushCache = false)
    {
        $key = 'SLIDE_' . $pageId;
        if (!($slides = self::getCache()->get($key)) || $flushCache) {
            if (
                ($pageItem = WsPageItem::find()->where(['type' => WsPageItem::TYPE_SLIDER, 'ws_page_id' => $pageId, 'status' => 1])->one()) !== null &&
                ($imageGroup = WsImageGroup::find()->where(['ws_page_item_id' => $pageItem->id])->one()) !== null
            ) {
                $slides = WsImage::find()->where(['ws_image_group_id' => $imageGroup->id, 'status' => 1])->orderBy('sort ASC')->all();
            }
            self::getCache()->set($key, $slides, 60 * 60);
        }
        return $slides;
    }

    public static function getBlockByPageItem($pageItemId, $flushCache = false)
    {
        $key = "ITEM_BLOCK_BY_PAGE_ITEM_{$pageItemId}_ID";
        if (!($blocks = self::getCache()->get($key)) || $flushCache) {
            $blocks = WsBlock::find()
                ->where([
                    'AND',
                    ['ws_page_item_id' => $pageItemId],
                    ['IS NOT', 'type', new \yii\db\Expression('NULL')]
                ])
                ->one();
            self::getCache()->set($key, $blocks, 60 * 60);
        }
        return $blocks;
    }

    public static function getGroupCategoryByBlockId($blockId, $flushCache = false)
    {
        $key = 'GROUP_CATEGORY_BY_BLOCK_ID' . $blockId;
        if (!($groupCategory = self::getCache()->get($key)) || $flushCache) {
            $groupCategory = WsCategoryGroup::find()->where(['ws_block_id' => $blockId])->one();
            self::getCache()->set($key, $groupCategory, 60 * 60);
        }
        return $groupCategory;

    }

    public static function getCategoryByGroupId($groupId, $flushCache = false)
    {
        $key = 'LIST_CATE_BY_GROUP_' . $groupId;
        if (!($categories = self::getCache()->get($key)) || $flushCache) {
            $categories = WsCategory::find()
                ->where(['ws_category_group_id' => $groupId])
                ->orderBy('sort DESC')
                ->all();
            self::getCache()->set($key, $categories, 60 * 60);
        }
        return $categories;

    }

    public static function getGroupProductByBlockId($blockId, $flushCache = false)
    {

        $key = 'ITEM_GROUP_BY_BLOCK_ID_' . $blockId;
        if (!($groupProduct = self::getCache()->get($key)) || $flushCache) {
            $groupProduct = WsProductGroup::find()->where(['ws_block_id' => $blockId])->one();
            self::getCache()->set($key, $groupProduct, 60 * 60);
        }
        return $groupProduct;
    }

    public static function getProductByGroupId($groupId, $flushCache = false)
    {

        $key = 'LIST_PRODUCT_BY_GROUP_TOP_' . $groupId;
        if (!($products = self::getCache()->get($key)) || $flushCache) {
            $products = WsProduct::find()
//                ->select('*,(ws_product.calculated_sell_price * ' . self::getStoreManager()->getExchangeRate() . ') as local_calculated_sell_price')
//                ->select([
//                    'item_id', 'item_url', 'item_sku', 'name', 'image_origin', 'image', 'start_price', 'sell_price', 'weight', 'category_id',
//                    'calculated_start_price', 'calculated_sell_price', 'rate_count', 'rate_star', 'category_name', 'start_time', 'end_time', 'provider',
//                    new \yii\db\Expression('CASE WHEN item_url like "%ebay%" THEN "ebay" WHEN item_url like "%amazon%" THEN "amazon" ELSE "other" END as type')
//                ])
                ->where(['ws_product_group_id' => $groupId, 'status' => 1])
                ->orderBy(['sort' => SORT_ASC])
                ->asArray()->all();
            self::getCache()->set($key, $products, 60 * 60);
        }
        return $products;
    }

    public static function getItemGroupImage($blockId, $type = WsImageGroup::TYPE_BRAND, $flushCache = false)
    {
        $key = 'ITEM_GROUP_IMAGE_BY_BLOCK_ID_' . $blockId . $type;
        if (!($groupImage = self::getCache()->get($key)) || $flushCache) {
            $groupImage = WsImageGroup::find()->where(['ws_block_id' => $blockId, 'type' => $type])->one();
            self::getCache()->set($key, $groupImage, 60 * 60);
        }
        return $groupImage;
    }

    public static function getImageByGroupId($ImageGroupId, $flushCache = false)
    {
        $key = 'LIST_IMAGE_BRAN_BY_BLOCK_' . $ImageGroupId;
        if (!($images = self::getCache()->get($key)) || $flushCache) {
            $images = WsImage::find()
                ->where(['ws_image_group_id' => $ImageGroupId])
                ->orderBy(['sort' => SORT_ASC])
                ->all();
            self::getCache()->set($key, $images, 60 * 60);
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