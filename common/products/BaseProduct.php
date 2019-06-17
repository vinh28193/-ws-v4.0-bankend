<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 08:48
 */

namespace common\products;

use common\components\GetUserIdentityTrait;
use common\helpers\WeshopHelper;
use common\models\User;
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
class BaseProduct extends BaseObject implements AdditionalFeeInterface
{
    use GetUserIdentityTrait;
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
     * @var Provider
     */
    public $provider;

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
    public $ws_link;
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
    /** @var $variation_options VariationOption[] */
    public $variation_options;
    /** @var $variation_mapping VariationMapping[] */
    public $variation_mapping;
    public $technical_specific;
    public $condition;
    public $rate_star;
    public $for_whole_sale = false;
    public $rate_count;
    public $bid;
    public $us_tax_rate;
    /** @var RelateProduct $relate_products */
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
        $additionalFee->withConditions($this, [
            'product_price' => $this->getSellPrice(),
            'shipping_fee' => !$this->is_free_ship ? ($this->shipping_fee * $this->quantity) : 0,
            'tax_fee' => $this->us_tax_rate
        ], false);
        $additionalFee->withCondition($this,'purchase_fee',null);
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
            $this->generateOriginLink();
            $this->isInitialized = true;
            $this->ws_link = WeshopHelper::generateUrlDetail($this->type, $this->item_name, $this->item_id, null, null);
        }
        $this->createYiiInfoToken();
    }

    public $isInitialized = false;

    /**
     * @return string
     */
    protected function generateOriginLink()
    {
        if ($this->type == 'ebay') {
            $this->item_origin_url = "http://rover.ebay.com/rover/1/711-53200-19255-0/1?icep_ff3=2&pub=5575037825&toolid=10001&campid=5337238823&customid=&icep_item=" . $this->item_id . "&ipn=psmain&icep_vectorid=229466&kwid=902099&mtid=824&kw=lg";
        }
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
    public function getType()
    {
        return strtolower($this->type);
    }

    /**
     * @return integer
     */
    public function getTotalOrigin()
    {
        return $this->getAdditionalFees()->getTotalOrigin();
    }

    private $_customCategory;

    /**
     * @param bool $refresh
     * @return Category
     */
    public function getCategory($refresh = false)
    {
        if ($this->_customCategory === null) {
            if (!$this->isEmpty($this->categories)) {
                $key = implode('_', $this->categories);
                if (!($categories = Yii::$app->cache->get($key)) || $refresh) {
                    $categories = Category::find()->forSite($this->type)->alias($this->categories)->all();
                    Yii::$app->cache->set($key, $categories, 60 * 10);
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
                    if ($this->_customCategory !== null) {
                        break;
                    };
                }
            } elseif ($this->category_id !== null) {
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

    public function getUserLevel()
    {
        if($this->getUser()){
            return $this->getUser()->userLever;
        }
        return User::LEVEL_NORMAL;
    }

    /**
     * @return integer
     */
    public function getShippingQuantity()
    {
        return $this->quantity;
    }

    public function getIsNew()
    {
        $condition = strtoupper($this->condition ? $this->condition : '');
        return ($condition === 'NEW' || strpos($condition, 'NEW') !== false || $condition === strtoupper('Manufacturer refurbished'));
    }

    public function getIsSpecial()
    {
        return false;
    }

    /**
     * @return null|array|mixed
     */
    public function getShippingFrom()
    {
        return null;
    }

    /**
     * @return null|array|mixed
     */
    public function getShippingTo()
    {
        return null;
    }

    /**
     * @return null|array|mixed
     */
    public function getShippingParcel()
    {
        return null;
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
    public function getStoreManager()
    {
        return Yii::$app->storeManager;
    }

    /**
     * @return mixed
     */
    public function getLocalizeTotalPrice()
    {
        return $this->getAdditionalFees()->getTotalAdditionalFees()[1];
    }

    public function getLocalizeTotalStartPrice()
    {
        if ($this->start_price == null || $this->start_price == 0) return null;
        $tempPrice = $this->getSellPrice();
        $this->sell_price = $this->start_price;
        $this->init();
        $temp = $this->getAdditionalFees()->getTotalAdditionalFees();
        if (!empty($this->deal_price) && $this->deal_price > 0.0) {
            $deal = $this->deal_price;
            $this->deal_price = null;
            $this->init();
            $temp = $this->getAdditionalFees()->getTotalAdditionalFees();
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

    public function createYiiInfoToken()
    {
        $fees = [];
        foreach ($this->getAdditionalFees()->keys() as $key) {
            $fees[$key] = array_combine(['amount', 'amount_local'], $this->getAdditionalFees()->getTotalAdditionalFees($key));
        }
        Yii::info([
            'id' => $this->item_id,
            'sku' => $this->item_sku,
            'exRate' => $this->getExchangeRate(),
            'IsNew' => $this->getIsNew(),
            'weight' => $this->getShippingWeight(),
            'quantity' => $this->getShippingQuantity(),
            'totalUsPrice' => $this->getTotalOrigin(),
            'totalLocalPrice' => $this->getLocalizeTotalPrice(),
            'fees' => $fees
        ], __CLASS__);
    }
}