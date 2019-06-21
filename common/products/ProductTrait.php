<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 14:16
 */

namespace common\products;


trait ProductTrait
{

    function setVariationOptions()
    {

        $rs = [];
        foreach ((array)$this->variation_options as $datum) {
            $it = new VariationOption();
            $it->images_mapping = $datum['images_mapping'];
//            $it->name = preg_replace('/[^A-Za-z0-9\-\ ]/', '', $datum['name']);
//            $it->values = preg_replace('/[^A-Za-z0-9\-\ ]/', '', $datum['values']);
            $it->name = $datum['name'];
            $it->values = isset($datum['values']) ? $datum['values'] : (isset($datum['value']) ? $datum['value'] : []);
            $it->setImagesMapping();
            $it->setId();
            $rs[] = $it;
        }
        $this->variation_options = $rs;
    }

    function setVariationMapping()
    {
        $rs = [];
        foreach ((array)$this->variation_mapping as $item) {
            $it = new VariationMapping($item);
            $rs[] = $it;
        }
        $this->variation_mapping = $rs;
    }

    function setTechnicalSpecific()
    {
        $rs = [];
        foreach ((array)$this->technical_specific as $item) {
            $it = new Option($item);
            $rs[] = $it;
        }
        $this->technical_specific = $rs;
    }

    function setRelateProduct()
    {
        $rs = [];
        foreach ((array)$this->relate_products as $item) {
            $it = new RelateProduct($item);
            $rs[] = $it;
        }
        $this->relate_products = $rs;

    }

    public function setImages()
    {
        $rs = [];
        if (!empty($this->primary_images))
            foreach ((array)$this->primary_images as $img) {
                $it = new Image($img);
                $rs[] = $it;
            }
        $this->primary_images = $rs;
    }

    public function setProviders()
    {
        $providers = [];
        foreach ((array)$this->providers as $provider) {
            $providers[] = !is_object($provider) ? new Provider($provider) : $provider;
        }
        $this->providers = $providers;
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

    public function getSpecific($sku)
    {
        if (empty($this->variation_mapping)) {
            return "";
        }
        foreach ((array)$this->variation_mapping as $item) {
            if ($item->variation_sku == $sku) {
                $rs = [];
                foreach ($item->options_group as $option) {
                    $data[$option->name] = $option->value;
                    $rs = array_merge($rs, $data);
                }
                return json_encode($rs);
            }
        }
    }

    public function updateBySku($sku)
    {
        if (count($this->variation_mapping) == 0) {
            return true;
        }

        if ($this->type === self::TYPE_AMAZON_US) {
            $this->item_origin_url = str_replace($this->item_sku, $sku, $this->item_origin_url);
        }

        $this->item_sku = $sku;

        foreach ((array)$this->variation_mapping as $item) {
            if ($item->variation_sku === $sku) {
                $this->start_price = $item->variation_start_price ? $item->variation_start_price : $this->start_price;
                $this->sell_price = $item->variation_price ? $item->variation_price : $this->sell_price;
                $this->available_quantity = $item->available_quantity;  // ? $item->available_quantity : $this->available_quantity;
                $this->quantity_sold = $item->quantity_sold;            // ? $item->quantity_sold : $this->quantity_sold;
                $specific = [];
                foreach ($item->options_group as $option) {
                    $specific = array_merge($specific, [$option->name => $option->value]);
                }
                $this->current_variation = json_encode($specific);
                return true;
            }
        }
        return false;
    }

    public function getCurrentProvider($seller_id = ''){
        $seller_id = $seller_id ? $seller_id : \Yii::$app->request->get('seller');
        $seller_id = $seller_id ? $seller_id : $this->getSeller();
        foreach ($this->providers as $provider) {
            /** @var $provider Provider */
            if($provider->prov_id == $seller_id){
                return $provider;
            }
        }
    }

    /**
     * @return string
     */
    public function getSeller()
    {
        $checkAmazon = false;
        $checkAmazonNew = false;
        $seller = '';
        foreach ($this->providers as $provider) {
            /** @var $provider Provider */
            if ($provider->condition == 'New') {

                if ($provider->name == 'Amazon.com') {
                    $seller = $provider->prov_id;
                    break;
                } elseif (!$checkAmazon && strpos(' -' . $provider->name, "Amazon")) {
                    $seller = $provider->prov_id;
                    $checkAmazon = true;
                } else {
                    $seller = $provider->prov_id;
                }
                $checkAmazonNew = true;
            } else {
                if (!$checkAmazonNew) {
                    if ($provider->name == 'Amazon.com') {
                        $seller = $provider->prov_id;
                        $checkAmazon = true;
                    } elseif (!$checkAmazon && strpos(' -' . $provider->name, "Amazon")) {
                        $seller = $provider->prov_id;
                        $checkAmazon = true;
                    } else {
                        $seller = $provider->prov_id;
                    }
                }
            }

        }
        return $seller;
    }

    public function updateBySeller($selerId)
    {
        if (empty($this->providers)) {
            return false;
        }
        foreach ($this->providers as $provider) {
            /** @var $provider Provider */
            if ($provider->prov_id == $selerId) {
                $this->sell_price = $provider->price;
                $this->condition = $provider->condition;
                $this->is_prime = $provider->is_prime;
                $this->is_free_ship = $provider->is_free_ship;
                $this->tax_fee = $provider->tax_fee;
                $this->shipping_fee = $provider->shipping_fee;
                $this->provider = $provider;
                $this->init();
                return true;
            }
        }
        return false;
    }
}
