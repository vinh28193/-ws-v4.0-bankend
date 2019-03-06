<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 08:48
 */

namespace common\products;


use common\components\AdditionalFeeInterface;
use common\components\AdditionalFeeTrait;
use common\components\StoreAdditionalFeeRegisterTrait;

class BaseProduct extends \yii\base\BaseObject implements AdditionalFeeInterface
{

    use StoreAdditionalFeeRegisterTrait;
    use AdditionalFeeTrait;
    use ProductTrait;

    const TYPE_EBAY = 'EBAY';
    const TYPE_AMAZON_US = 'AMAZON';
    const TYPE_AMAZON_JP = 'AMAZON-JP';
    const TYPE_AMAZON_UK = 'AMAZON-UK';
    const TYPE_AMAZON = 'OTHER';

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
        $this->setAdditionalFees([
            'origin_fee' => $this->sell_price,
            'origin_tax_fee' => $this->us_tax_rate,
            'origin_shipping_fee' => $this->shipping_fee
        ], true, true);

        if ($this->isInitialized === false) {
            $this->setVariationMapping();
            $this->setVariationOptions();
            $this->setRelateProduct();
            $this->setImages();
            $this->setTechnicalSpecific();
            $this->isInitialized = true;
        }
    }

    private $isInitialized = false;

    /**
     * @return string
     */
    public function getItemType()
    {
        return $this->type;
    }

    /**
     * @return integer
     */
    public function getTotalOriginPrice()
    {
        return $this->getTotalAdditionFees([
            'origin_fee', 'origin_tax_fee', 'origin_shipping_fee'
        ])[0];
    }

    /**
     * @return mixed
     */
    public function getCustomCategory()
    {
        $std = new \stdClass();
        $std->interShippingB = 10.5;
        return $std;
    }

    /**
     * @return integer
     */
    public function getShippingWeight()
    {
        return $this->shipping_weight;
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

    /**
     * @return integer
     */
    public function getExchangeRate()
    {
        return $this->getStoreManager()->getExchangeRate();
    }
}