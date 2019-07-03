<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-08-21
 * Time: 09:07
 */

namespace common\models\cms;


use Yii;
use yii\base\Model;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use common\components\StoreManager;

class PageForm extends Model
{
    const PAGE_ITEM_LIMIT = 6;

    public $id;
    public $store = 1;
    public $page = 1;

    public $type;

    /**
     * @var string |StoreManager
     */
    public $storeManager = 'storeManager';

    public function init()
    {
        parent::init();
        $this->storeManager = Instance::ensure($this->storeManager, StoreManager::className());
    }

    public function attributes()
    {
        return ['id', 'store', 'page', 'type'];
    }

    public function rules()
    {
        return [
            ['type', 'required'],
            ['type', 'string'],
            ['type', function ($attribute, $params, $validator) {
                if (!$this->hasErrors() && $value = $this->$attribute) {
                    if (!ArrayHelper::isIn($value, [
                        WsPage::TYPE_HOME,
                        WsPage::TYPE_AMZ,
                        WsPage::TYPE_EBAY,
                        WsPage::TYPE_LANDING,
                        WsPage::TYPE_LANDING_REQUEST
                    ])) {
                        $this->addError($attribute, 'Unknown type "' . $value . '"');
                    }
                }
            }],
            ['id', 'required', 'when' => function ($model) {
                return $model->type === WsPage::TYPE_LANDING;
            }],
            ['id', 'integer'],
            ['store', 'default', 'value' => 1],
            ['page', 'default', 'value' => 1]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store' => 'store',
            'page' => 'Page',
            'type' => 'Type'
        ];
    }

    public function formName()
    {
        return '';
    }

    public function getFirstErrors()
    {
        $error = parent::getFirstErrors();
        return reset($error);
    }

    public function initPage()
    {
        if (!$this->validate()) {
            return false;
        }
        if (($page = PageService::getPage($this->type, $this->store, $this->id)) === null) {
            if ($this->id !== null) {
                $this->addError('id', 'Not found page #' . $this->id . ' type "' . $this->type . '" for store "' . $this->store . '"');
            } else {
                $this->addError('store', 'Not found page type "' . $this->type . '"');
            }

            return false;
        }
        $results = [];
        if ($this->type !== WsPage::TYPE_LANDING && $this->type !== WsPage::TYPE_LANDING_REQUEST) {
            $results['alias'] = PageService::getAlias($this->type, $this->store);
            $results['slide'] = ArrayHelper::toArray(PageService::getSlider($page->id));
        }
        $offset = ($this->page - 1) * self::PAGE_ITEM_LIMIT;
        return ArrayHelper::merge($results, [
            'content' => $this->initBlock($page, self::PAGE_ITEM_LIMIT, $offset)
        ]);

    }

    /**
     * @param $page
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function initBlock($page, $limit = self::PAGE_ITEM_LIMIT, $offset = 1)
    {
        $items = [];
        /** @var $pageItems WsPageItem[] */
        if ((count($pageItems = PageService::getPageitem($page->id, $limit, $offset))) > 0) {
            foreach ($pageItems as $key => $item) {
                /** @var $block WsBlock  */
                if (($block = PageService::getBlockByPageItem($item->id)) === null) {
                    continue;
                }
                $data = [];
                $data['block'] = ArrayHelper::toArray($block);
                if ($groupCategory = PageService::getGroupCategoryByBlockId($block->id)) {
//                $data['groupCategory'] = ArrayHelper::toArray($groupCategory);
                    $data['categories'] = ArrayHelper::toArray(PageService::getCategoryByGroupId($groupCategory->id));
                }

                if ($groupProduct = PageService::getGroupProductByBlockId($block->id)) {
//                $data['groupProduct'] = ArrayHelper::toArray($groupProduct);
                    $data['products'] = ArrayHelper::toArray(PageService::getProductByGroupId($groupProduct->id));
//                    $data['products'] = ArrayHelper::toArray(PageService::getProductByGroupId($groupProduct->id),[
//                        'common\models\model_cms\WsProduct' => [
//                            'id', 'item_id', 'item_url', 'item_sku', 'name', 'image_origin', 'image',
//                            'weight', 'calculated_start_price', 'calculated_sell_price', 'rate_count',
//                            'rate_star', 'category_name', 'start_time', 'end_time', 'provider',
//                            'sort', 'create_time', 'update_time',
//                            'type' => function ($self){
//                                /** @var $self \common\models\model_cms\WsProduct */
//                                return $self->getType();
//                            },
//                            'local_start_price' => function ($self){
//                                /** @var $self \common\models\model_cms\WsProduct */
//                                return $self->getLocalStartPrice();
//                            },
//                            'local_sell_price' => function ($self){
//                                /** @var $self \common\models\model_cms\WsProduct */
//                                return $self->getLocalSellPrice();
//                            },
//                            'sale_percent' => function ($self){
//                                /** @var $self \common\models\model_cms\WsProduct */
//                                return $self->getSalePercent();
//                            },
//                        ]
//                    ]);
                }

                if ($groupImage = PageService::getItemGroupImage($block->id, WsImageGroup::TYPE_BRAND)) {
                    $data['images'] = ArrayHelper::toArray(PageService::getImageByGroupId($groupImage->id));
//                    $data['images'] = ArrayHelper::toArray(PageService::getImageByGroupId($groupImage->id));
                }

                if ($groupGrid = PageService::getItemGroupImage($block->id, WsImageGroup::TYPE_GRID)) {
                    $data['grid'] = ArrayHelper::toArray(PageService::GetImageByGroupId($groupGrid->id));
                }
                $items[] = $data;
            }
        }
        return $items;
    }
}