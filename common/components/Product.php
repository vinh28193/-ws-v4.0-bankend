<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 05/03/2019
 * Time: 09:55
 */

namespace common\components;


use common\models\analytics\ProductAnalytics;
use common\models\weshop\subproduct\CateProduct;
use common\models\weshop\subproduct\Image;
use common\models\weshop\subproduct\Option;
use common\models\weshop\subproduct\Provider;
use common\models\weshop\subproduct\VariationMapping;
use common\models\weshop\subproduct\VariationOption;
use yii\helpers\ArrayHelper;

class Product implements AdditionalFeeInterface
{
    use  StoreAdditionalFeeRegisterTrait;
    use  AdditionalFeeTrait;

    const STORE_US = 'amazon.com';
    const STORE_JP = 'amazon.co.jp';
    const TYPE_EBAY = 'EBAY';
    const TYPE_AMZON_US = 'AMAZON';
    const TYPE_AMZON_JP = 'AMAZON-JP';
    const TYPE_AMZON_UK = 'AMAZON-UK';
    const TYPE_OTHER = 'OTHER';
    const TYPE_NEXTTECH = 'NEXTTECH';

    public $provider;
    public $categories;
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
    public $forWholeSale = false;
    public $rate_count;
    public $bid;
    /** @var StoreManager $store */
    public $store;
    public $parent_item;
    public $usTaxRate;
    public $relate_products;
    public $currentVariation;
    public $type = self::TYPE_EBAY;
    public $product_type = 0; //0:buynow,1:request;2:aution;

    public $currentUrl;
    public $currentImage;
    public $price_api;
    public $currency_api;
    public $ex_rate_api;
    public $suggest_set_session;
    public $suggest_set_purchase;

    public function __construct(StoreManager $storeManager, $wsData)
    {
        $this->store = $storeManager;
        if ($wsData == null) return null;
        $wsData = (array)$wsData;

        $this->provider = $this->type == self::TYPE_EBAY ? $wsData['provider'] : $this->getProviders($wsData['provider']);
        $productAttr = get_object_vars($this);
        foreach ($wsData as $k => $v) {
            foreach ($productAttr as $k1 => $v1) {
                if (!is_array($this->$k1) && !is_object($this->$k1))
                    if ($k == $k1) {
                        $this->$k1 = $v;
                    }
            }
        }
        $this->forWholeSale = $this->getIsForWholeSale();

        $this->generateOriginLink();
        $this->setVariationOptions();
        $this->setVariationMapping();
        $this->setTechnicalSpecific();
        $this->setRelateProduct();
        $this->setImages();
        $this->price_api = isset($wsData['PriceApi']) ? $wsData['PriceApi'] : null ;
        $this->currency_api = isset($wsData['CurrencyApi']) ? $wsData['CurrencyApi'] : null;
        $this->ex_rate_api = isset($wsData['ExRateApi'] ) ? $wsData['ExRateApi'] * $storeManager->getExchangeRate() : $storeManager->getExchangeRate();
        $this->suggest_set_session = isset($wsData['suggest_set_session']) ? $wsData['suggest_set_session'] : null;
        $this->suggest_set_purchase = isset($wsData['suggest_set_purchase']) ? $wsData['suggest_set_purchase'] : null;
        unset($wsData);
    }

    public function getProviders($data)
    {
        $rs = [];
        foreach ($data as $datum) {
            $rs[] = new Provider($datum);
        }
        return $rs;
    }

    /**
     * @return string
     */
    public function generateOriginLink(){
        $source = self::STORE_US;
        if($this->type == self::TYPE_EBAY){
            $this->item_origin_url = "http://rover.ebay.com/rover/1/711-53200-19255-0/1?icep_ff3=2&pub=5575037825&toolid=10001&campid=5337238823&customid=&icep_item=" . $this->item_id . "&ipn=psmain&icep_vectorid=229466&kwid=902099&mtid=824&kw=lg";
        }else{
            if($this->type === self::TYPE_AMZON_JP){
                $source = self::STORE_JP;
            }
            $id = strtoupper($this->item_id);
            $this->item_origin_url = "https://$source/gp/product/$id?ie=UTF8&tag=wp034-20&camp=1789&linkCode=xm2&creativeASIN=$id";
        }
        return $this->item_origin_url;
    }

    /**
     * Google Analytics Enhanced Ecommerce
     * Product data represents individual products that were viewed, added to the shopping cart, etc.
     */
    public function getEnhancedEcommerce()
    {
        return new ProductAnalytics([
            'id' => $this->item_id,
            'name' => $this->item_name,
            'category' => ArrayHelper::getValue(Category::getAlias($this->category_id), $this->store->isVN() ? 'name' : 'originName'),
            'variant' => $this->getSpecific($this->item_sku),
            'price' => $this->getLocalizeTotalPrice(),
            'quantity' => $this->quantity,
            'position' => 0,
            'list' => $this->type,
        ]);
    }

    public function getSpecific($sku)
    {
        if (count($this->variation_mapping) == 0) return "";
        foreach ($this->variation_mapping as $item) {
            if ($item->variation_sku == $sku) {
                $rs = [];
                foreach ($item->options_group as $option) {
                    $data[$option->name] = $option->value;
                    $rs = array_merge($rs, $data);
                }
                return json_encode($rs);
            }
        }
        return null;
    }

    function setVariationOptions()
    {

        $rs = [];
        foreach ($this->variation_options as $datum) {
            $it = new VariationOption();
            $it->images_mapping = $datum['images_mapping'];
            $it->name = preg_replace('/[^A-Za-z0-9\-\ ][\p{Han}\p{Katakana}\p{Hiragana}]/', '', $datum['name']);
            $it->values = preg_replace('/[^A-Za-z0-9\-\. ][\p{Han}\p{Katakana}\p{Hiragana}]/', '', $datum['values']);
            $it->setImagesMapping();
            $rs[] = $it;
        }
        $this->variation_options = $rs;
    }

    function setVariationMapping()
    {
        $rs = [];
        foreach ($this->variation_mapping as $item) {
            $it = new VariationMapping($item);
            $rs[] = $it;
        }
        $this->variation_mapping = $rs;
    }

    function setTechnicalSpecific()
    {
        $rs = [];
        foreach ($this->technical_specific as $item) {
            $it = new Option($item);
            $rs[] = $it;
        }
        $this->technical_specific = $rs;
    }

    function setRelateProduct()
    {
        $rs = [];
        if($this->relate_products){
            foreach ($this->relate_products as $item) {
                $it = new CateProduct($item);
                $rs[] = $it;
            }
            $this->relate_products = $rs;

        }
    }

    public function setImages()
    {
        $rs = [];
        if (count($this->primary_images) > 0)
            foreach ($this->primary_images as $img) {
                $it = new Image($img);
                $rs[] = $it;
            }
        $this->primary_images = $rs;
    }

    public function checkOutOfStock()
    {
        return $this->available_quantity - $this->quantity_sold < 0 ? $this->available_quantity : $this->available_quantity - $this->quantity_sold;
    }

    public function getSalePercent()
    {
        if ($this->start_price > $this->sell_price) return
            round(100 * ($this->start_price - $this->sell_price) / $this->start_price);
        else return 0;
    }

    public function getSpecificJapan($sku)
    {
        if (count($this->variation_mapping) == 0) return "";
        foreach ($this->variation_mapping as $item) {
            if ($item->variation_sku == $sku) {
                $rs = '{';
                foreach ($item->options_group as $option) {
                    $keyName = "Translate_google_japanese_".$option->name;
                    $name = \Yii::$app->cache->get($keyName);
                    if(!$name){
                        $stringSrc = LocaleStringResource::find()->where(['active' => 1, 'ResourceName' => $keyName, 'LanguageId' => 1])->one();
                        $name = $stringSrc->ResourceValue;
                    }
                    if(!$name){
                        $name = GoogleTranslate::translateVN($option->name,'en');
                        $name = RedisLanguage::getLanguageByKey($keyName,$name);
                        \Yii::$app->cache->set($keyName,$name,60*60*24*7);
                    }
                    $keyValue = "Translate_google_japanese_".$option->value;
                    $value = \Yii::$app->cache->get($keyValue);
                    if(!$value ){
                        $stringSrc = LocaleStringResource::find()->where(['active' => 1, 'ResourceName' => $keyValue, 'LanguageId' => 1])->one();
                        $value  = $stringSrc->ResourceValue;
                    }
                    if(!$value){
                        $value = GoogleTranslate::translateVN($option->value,'en');
                        $value = RedisLanguage::getLanguageByKey($keyValue,$value);
                        \Yii::$app->cache->set($keyValue,$value,60*60*24*7);
                    }
                    $rs .= '"'.$option->name.'('.$name.')":"'.$option->value.'('.$value.')",';
                }
                $rs = rtrim($rs,',');
                return $rs.'}';
            }
        }
    }

    public function updateBySku($sku)
    {
        $this->parent_item = $this;
        if ($this->type == self::TYPE_AMZON_US || $this->type == self::TYPE_AMZON_JP) {
            $this->item_origin_url = str_replace($this->item_sku, $sku, $this->item_origin_url);
        }
        $this->item_sku = $sku;
        foreach ($this->variation_mapping as $item) {
            if ($item->variation_sku == $sku) {
                $this->start_price = $item->variation_start_price ? $item->variation_start_price : $this->start_price;
                $this->sell_price = $item->variation_price ? $item->variation_price : $this->sell_price;
                $this->available_quantity = $item->available_quantity;// ? $item->available_quantity : $this->available_quantity;
                $this->quantity_sold = $item->quantity_sold;// ? $item->quantity_sold : $this->quantity_sold;
                $this->currentVariation = $item->options_group ? $item->options_group : $this->currentVariation;
                return true;
            }
        }
        if (count($this->variation_mapping) == 0) {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getSeller()
    {
        $checkAmazon = false;
        $checkAmazonNew = false;
        $seller = '';
        $provider_temp = [];
        foreach ($this->provider as $provider) {
            if ($provider->condition == 'New') {

                if ($provider->name == 'Amazon.com') {
                    $seller = $provider->provId;
                    break;
                } elseif (!$checkAmazon && strpos(' -' . $provider->name, "Amazon")) {
                    $seller = $provider->provId;
                    $checkAmazon = true;
                } else {
                    $provider_temp = $provider;
                    $seller = $provider->provId;
                }
                $checkAmazonNew = true;
            } else {
                if (!$checkAmazonNew) {
                    if ($provider->name == 'Amazon.com') {
                        $seller = $provider->provId;
                        $checkAmazon = true;
                    } elseif (!$checkAmazon && strpos(' -' . $provider->name, "Amazon")) {
                        $seller = $provider->provId;
                        $checkAmazon = true;
                    } else {
                        $provider_temp = $provider;
                        $seller = $provider->provId;
                    }
                }
            }

        }
        if(!strpos(' -' . $provider_temp->name, "Amazon") && $seller == $provider_temp->provId){
            $seller = "";
            if($checkAmazonNew){
                foreach ($this->provider as $provider) {
                    if ($provider->condition == 'New') {
                        $seller = $provider->provId;
                        break;
                    }
                }
            }else{
                $seller = $this->provider[0]->provId ;
            }
        }
        return $seller;
    }

    public function updateBySeller($selerId)
    {
        foreach ($this->provider as $provider) {
            if ($provider->provId == $selerId) {
                $this->sell_price = $provider->price;
                $this->condition = $provider->condition;
                $this->is_prime = $provider->is_prime;
                $this->is_free_ship = $provider->is_free_ship;
                $this->tax_fee = $provider->tax_fee;
                $this->price_api = $provider->priceApi ? $provider->priceApi : $provider->price;
                $this->shipping_fee = $provider->shipping_fee;
                return true;
            }
        }
        return false;
    }

    public function getItemType()
    {
        return $this->type;
    }

    public function getTotalOriginPrice()
    {
        return $this->getTotalAdditionalFees([
            'origin_fee','origin_tax_fee','origin_shipping_fee'
        ])[0];
    }

    public function getCustomCategory()
    {
        $std = new \stdClass();
        $std->interShippingB = 123;
        return $std;
    }

    public function getIsForWholeSale()
    {
        return false;
    }

    public function getShippingWeight()
    {
        return $this->shipping_weight;
    }

    public function getShippingQuantity()
    {
        return 1;
    }

    public function getExchangeRate()
    {
        return $this->store->getExchangeRate();
    }

    /**
     * @return boolean
     */
    public function getIsNew()
    {
        // TODO: Implement getIsNew() method.
    }
}
