<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 09:57
 */

namespace common\products\ebay;

use Yii;
use yii\helpers\ArrayHelper;

class EbaySearchRequest extends \common\products\BaseRequest
{

    public $limit;
    public $sellers;
    public $type;
    public $usTax;
    public $usShippingFee;
    public $itemsPerPage;

    public function rules()
    {
        $rules = ArrayHelper::merge(parent::rules(), [
            [['sellers'], 'string'],
            [['sellers'], 'filter', 'filter' => 'trim'],
            [['sellers'], 'filter', 'filter' => '\yii\helpers\Html::encode'],
            //[['filter'],'match',['pattern' => '']],         \\ Todo make RegularExpression match string "Condition:1000,1500;Color:Blue,Greed,Red;Display:7%20Inch"
            [['limit', 'type', 'usTax', 'usShippingFee', 'itemsPerPage'], 'integer'],
            [['min_price', 'max_price'], 'filter', 'skipOnEmpty' => true,'filter' => function ($value) {
                $ex = $this->getStoreManager()->getExchangeRate();
                return round($value/$ex ,2);
            }],
            [['min_price', 'max_price', 'sellers'], 'filter', 'skipOnEmpty' => true, 'filter' => function ($value) {
                return (string)$value;
            }],
            [['min_price', 'max_price', 'sellers'], 'filter', 'skipOnEmpty' => true, 'filter' => function ($value) {
                return $value ? (!is_array($value) ? [$value] : $value) : null;
            }],
            [['category'], 'filter', 'skipOnEmpty' => true, 'filter' => function ($value) {
                if ($value != null && !is_array($value)) {
                    $results[] = $value;
                } else {
                    $results = $value ? $value : [];
                }
                return $results;
            }],
            ['type','default','value' => 0],
            ['type', function ($attribute, $params, $validator) {
                if (!$this->hasErrors()) {
                    $value = (int)$this->$attribute;
                    if (!($value === 1 || $value === 0 || $value === 2)) {
                        $error = Yii::t('frontend','{attribute} must be either "0", "1" or "2".');
                        $this->addError($attribute, $error);
                    }
                }
            }],
        ]);
        return $rules;
    }

    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), [
            'limit',
            'sellers',
            'type',
            'usTax',
            'usShippingFee',
            'itemsPerPage'
        ]);
    }

    public function attributeLabels()
    {
        // Todo Yii::t
        return ArrayHelper::merge(parent::attributeLabels(), [
            'limit' => 'Limit',
            'sellers' => 'Sellers',
            'type' => 'Type',
            'usTax' => 'US Tax',
            'usShippingFee' => 'US Shipping Fee',
            'itemsPerPage' => 'Items Per Page'
        ]);
    }

    /**
     * @param $filter
     * @return array
     */
    private function extractFilter($filter)
    {
        $itemFilterParam = ['Condition', 'MinPrice', 'MaxPrice'];
        $itemFilters = [];
        $aspectFilters = [];
        if(!$this->isEmpty($filter)){
            foreach (explode(';', $filter) as $element) {
                if($element){
                    list($name, $value) = explode(':', $element);
                    if (ArrayHelper::isIn($name, $itemFilterParam)) {
                        $item = [];
                        $item['name'] = $name;
                        $item['value'] = explode(',', $value);
                        $itemFilters[] = $item;

                    } else {
                        $aspect = [];
                        $aspect['name'] = $name;
                        $aspect['value'] = explode(',', $value);
                        $aspectFilters[] = $aspect;
                    }
                }
            }
        }

        return [$itemFilters, $aspectFilters];
    }

    private function makeParam($name, $value)
    {
        return [
            'name' => $name,
            'value' => $value
        ];
    }


    public function params(){
        $post = [];
        $post['keywords'] = $this->keyword ? $this->keyword : '';
        $post['page'] = $this->page ? $this->page : 1;
        $post['itemsPerPage'] = $this->itemsPerPage ? $this->itemsPerPage : 36;
        $post['categoryId'] = $this->category;
        $post['sortOrder'] = $this->sort;
        list($itemFilters, $aspectFilter) = $this->extractFilter($this->filter);
        $post['aspectFilter'] = $aspectFilter;
        $post['itemFilter'] = $itemFilters;
        $itemFilters = [];
        if (!$this->isEmpty($this->type)) {
            $itemFilters[] = $this->makeParam('ListingType', $this->type == 1 ? ['Auction', 'AuctionWithBIN'] : ['FixedPrice', 'StoreInventory']);
//            $itemFilters[] = $this->makeParam('ListingType', []);
        }
        if (!$this->isEmpty($this->max_price)) {
            $itemFilters[] = $this->makeParam('MaxPrice',$this->max_price);
        }
        if (!$this->isEmpty($this->min_price)) {
            $itemFilters[] = $this->makeParam('MinPrice',$this->min_price);
        }
        if (!$this->isEmpty($this->sellers)) {
            $itemFilters[] = $this->makeParam('Seller', $this->sellers);
        }
        if (!$this->isEmpty($itemFilters)) {
            $post['itemFilter'] = array_merge($post['itemFilter'], $itemFilters);
        }

        if (!$this->isEmpty($this->itemsPerPage)) {
            $post['itemsPerPage'] = $this->itemsPerPage;
        }
        return $post;
    }
}
