<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 14:30
 */

namespace common\products\forms;


use common\products\ebay\EbayGate;
use common\products\ebay\EbayProduct;

class ProductDetailFrom extends \yii\base\Model
{

    public $type;

    public $id;

    public $sku;

    public $quantity = 1;

    public $seller;

    public $weight;

    public $sub_product_url;

    public $with_detail = false;

    /**
     * @param array $data
     * @param string $formName
     * @return bool
     */
    public function load($data, $formName = '')
    {
        return parent::load($data, $formName);
    }

    /**
     * @return array|mixed
     */
    public function getFirstErrors()
    {
        $error = parent::getFirstErrors();
        return reset($error);
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'type', 'id', 'sku', 'quantity', 'seller', 'weight', 'sub_product_url', 'with_detail'
        ];
    }

    public function rules()
    {
        return [
            ['type', 'required'],
            ['id', 'required'],
            ['id', 'string', 'min' => 3, 'max' => 40],
            ['id', 'filter', 'filter' => '\yii\helpers\Html::encode'],
            ['sku', 'string', 'min' => 3, 'max' => 12],
            ['sku', 'filter', 'filter' => '\yii\helpers\Html::encode'],
            ['quantity', 'default', 'value' => 1],
            ['seller', 'string'],
            ['seller', 'filter', 'filter' => '\yii\helpers\Html::encode'],
            ['weight', 'integer'],
            ['sub_product_url', 'string']
        ];
    }

    /**
     * @param bool $renew
     * @return bool|EbayProduct
     * @throws \HttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @throws \yii\web\ServerErrorHttpException
     */
    public function detail($renew = false)
    {

        if (!$this->validate()) {
            return false;
        }
        $type = strtoupper($this->type);
        $gate = new EbayGate();
        /** @var  $product EbayProduct */
        $product = $gate->getProduct($this->id, $renew);
        if (is_array($product) && isset($product['success']) && $product['success'] === false) {
            $this->addError($this->isSku() ? 'sku' : 'id', $product['message']);
            return false;
        }
        if ($this->isSku()) {
            foreach ($product->variation_mapping as $variation) {
                if ($variation->variation_sku === $this->sku) {
                    $product->sell_price = $variation->variation_price;
                    $product->available_quantity = $variation->available_quantity;
                    $product->quantity_sold =  $variation->quantity_sold;
                    $product->item_sku = $this->sku;
                    break;
                }
            }
            $product->current_variation = $product->getSpecific($this->sku);
        }
        if ($this->weight !== null && trim($this->weight) > 0) {
            $product->shipping_weight = $this->weight;
        }
        if ($this->quantity !== null && trim($this->quantity) > 0) {
            $product->quantity = $this->quantity;
        }

        $product->init();
        if ($this->with_detail === false) {
            $product->description = null;
        }
        return $product;
    }

    public function isSku()
    {
        return $this->sku !== null && trim($this->sku) !== '';
    }
}