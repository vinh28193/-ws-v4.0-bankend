<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 08:48
 */

namespace common\products;

use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use common\models\Category;
use common\components\AdditionalFeeInterface;
use common\components\AdditionalFeeTrait;
use common\components\StoreAdditionalFeeRegisterTrait;

/**
 * Class BaseProduct
 * @package common\products
 * Product EBAY / AMAZON API trả về + tính toán phí để hiên thị lên detail + search + card + checkout  cho khách hàng
 */
class BaseProduct extends  BaseObject implements AdditionalFeeInterface
{

    use AdditionalFeeTrait;
    use ProductTrait;

    const TYPE_EBAY = 'EBAY';
    const TYPE_AMAZON_US = 'AMAZON';
    const TYPE_AMAZON_JP = 'AMAZON-JP';
    const TYPE_AMAZON_UK = 'AMAZON-UK';
    const TYPE_OTHER = 'OTHER';

    const PRODUCT_TYPE_BUY_NOW = 0;
    const PRODUCT_TYPE_REQUEST = 1;
    const PRODUCT_TYPE_AUCTION = 1;


    /**
     * @var Provider[]
     */
    public $providers;
    /**
     * @var array
     */
    public $categories;
    /**
     * @var integer
     */
    public $category_id;
    public $category_name;
    public $description;
    public $primary_images;
    public $parent_category_id;
    public $parent_category_name;
    public $item_id;
    public $item_sku;
    public $item_name;
    public $item_origin_url;
    public $parent_item_id;
    public $retail_price;
    public $sell_price;
    public $start_price;
    public $deal_price;
    public $deal_time;
    public $start_time;
    public $end_time;
    public $quantity = 1;
    public $available_quantity;
    public $quantity_sold;
    public $shipping_fee;
    public $shipping_weight = 1;
    public $is_prime = false;
    public $tax_fee;
    public $variation_options;
    public $variation_mapping;
    public $technical_specific;
    public $condition;
    public $rate_star;
    public $for_whole_sale = false;
    public $rate_count;
    public $bid;
    public $parent_item;
    public $us_tax_rate;
    public $relate_products;
    public $current_variation;
    public $type = self::TYPE_EBAY;
    public $product_type = self::PRODUCT_TYPE_BUY_NOW;

    public $is_free_ship = false;

    public $current_url;
    public $current_image;
    public $price_api;
    public $currency_api;
    public $ex_rate_api;
    public $suggest_set_session;
    public $suggest_set_purchase;

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
        parent::init();
        $additionalFee = $this->getAdditionalFees();
        $additionalFee->removeAll(); // đảm bảo dữ liệu không bị đúp lên nhiều lần
        $additionalFee->withConditions($this,[
            'product_price_origin' => $this->getSellPrice(),
            'tax_fee_origin' => $this->us_tax_rate,
            'origin_shipping_fee' => $this->shipping_fee
        ], true);
        /**
         * Todo function initDefaultProperty
         * - vì mấy hàm này chỉ có tác dụng sử dụng 1 lần khi create object nên chỉ cần viết 1 hàm
         * - để là protected để ghi đè
         * - isInitialized được chuyển xuống
         * - remove trait ProductTrait (chuyển tất cả về base product)
         */
        if ($this->isInitialized === false) {
            $this->setVariationMapping();
            $this->setVariationOptions();
            $this->setRelateProduct();
            $this->setImages();
            $this->setTechnicalSpecific();
            $this->setProviders();
            $this->isInitialized = true;
        }
    }

    public $isInitialized = false;

    /**
     * @return string
     */
    protected function generateOriginLink(){
        return $this->item_origin_url;
    }

    /**
     * @return float|int
     */
    public function getSellPrice()
    {
        if (!empty($this->deal_price) && $this->deal_price > 0.0) {
            return $this->deal_price * $this->quantity;
        } else {
            return $this->sell_price * $this->quantity;
        }
    }

    /**
     * @return float|int
     */
    public function getCategoryName()
    {
        if (!empty($this->category_name)) {
            return $this->category_name;
        }
    }

    /**
     * @return string
     */
    public function getItemType()
    {
        return strtolower($this->type);
    }

    /**
     * @return integer
     */
    public function getTotalOriginPrice()
    {
        return $this->getAdditionalFees()->getTotalAdditionFees([
            'product_price_origin', 'tax_fee_origin', 'origin_shipping_fee'
        ])[0];
    }

    private $_customCategory;

    /**
     * @param bool $refresh
     * @return Category
     */
    public function getCustomCategory($refresh = false)
    {
        if ($this->_customCategory === null) {
            if (!$this->isEmpty($this->categories)) {
                $key = implode('_', $this->categories);
                if (!($categories = Yii::$app->cache->get($key)) || $refresh) {
                    $categories = Category::find()->forSite($this->getSiteMapping())->alias($this->categories)->all();
                    Yii::$app->cache->set($key,$categories,60 * 10);
                }
                $this->_customCategory = null;
                for ($i = count($this->categories) - 1; $i >= 0; $i--) {
                    foreach ($categories as $category) {
                        /** @var $category Category */
                        if ($category->alias == $this->categories[$i]) {
                            $this->_customCategory = $category;
                            break;
                        }
                    }
                    if ($this->_customCategory !== null){
                        break;
                    };
                }
            }elseif ($this->category_id !== null){
                if (!($category = Yii::$app->cache->get($this->category_id))) {
                    $category = Category::find()->where(['alias' => $this->category_id])->one();
                    Yii::$app->cache->set($this->category_id, $category, 60 * 10);
                }
                $this->_customCategory = $category;
            }
        }
        return $this->_customCategory;
    }

    /**
     * @return integer|null
     */
    public function getSiteMapping()
    {
        return ArrayHelper::getValue([
            self::TYPE_EBAY => Category::SITE_EBAY,
            self::TYPE_AMAZON_US => Category::SITE_AMAZON_US,
            self::TYPE_AMAZON_UK => Category::SITE_AMAZON_UK,
            self::TYPE_AMAZON_JP => Category::SITE_AMAZON_JP
        ], $this->type, null);
    }

    /**
     * @return integer
     */
    public function getShippingWeight()
    {
        if ($this->shipping_weight == 0 || $this->shipping_weight == null) {
            return $this->roundShippingWeight($this->shipping_weight) * $this->quantity;
        }
        return $this->roundShippingWeight($this->shipping_weight * $this->quantity);
    }

    public function roundShippingWeight($inputWeight = 0, $minWeight = 0.5)
    {
        return floatval($inputWeight) < $minWeight ? $minWeight : round($inputWeight, 2);
    }

    /**
     * @return integer
     */
    public function getShippingQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return boolean
     */
    public function getIsForWholeSale()
    {
        return $this->for_whole_sale;
    }

    public function getIsNew()
    {
        $condition = strtoupper($this->condition ? $this->condition : '');
        return ($condition === 'NEW' || strpos($condition, 'NEW') !== false || $condition === strtoupper('Manufacturer refurbished'));
    }

    /**
     * @return integer
     */
    public function getExchangeRate()
    {
        return $this->getStoreManager()->getExchangeRate();
    }

    /**
     * @return \common\components\StoreManager
     */
    public function getStoreManager(){
        return Yii::$app->storeManager;
    }

    /**
     * @return mixed
     */
    public function getLocalizeTotalPrice()
    {
        return $this->getAdditionalFees()->getTotalAdditionFees()[1];
    }

    public function getLocalizeTotalStartPrice()
    {
        if ($this->start_price == null || $this->start_price == 0) return null;
        $tempPrice = $this->getSellPrice();
        $this->sell_price = $this->start_price;
        $this->init();
        $temp = $this->getAdditionalFees()->getTotalAdditionFees();
        if (!empty($this->deal_price) && $this->deal_price > 0.0) {
            $deal = $this->deal_price;
            $this->deal_price = null;
            $this->init();
            $temp = $this->getAdditionalFees()->getTotalAdditionFees();
            $this->deal_price = $deal;
        }
        //restore the sell_price to be $tempPrice before
        $this->sell_price = $tempPrice;
        $this->init();
        return $temp[1];
    }

    /**
     * @param $value
     * @return bool
     */
    public function isEmpty($value)
    {
        return \common\helpers\WeshopHelper::isEmpty($value);
    }
}