<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 14:30
 */

namespace common\products\forms;

use common\products\BaseProduct;
use yii\helpers\ArrayHelper;

class ProductDetailFrom extends BaseForm
{

    /**
     * @var string
     */
    public $id;

    public $sku;

    public $quantity = 1;

    public $seller;

    public $weight;

    public $sub_product_url;      //ToDo :  Url product con vì chạy qua 2 api mới lấy đủ 1 thông tin details sản phẩm

    public $with_detail = false;  //ToDo :  Thông tin mô tả quá dài . nếu Tru sẽ cắt đi và ngược lại


    public function init()
    {
        parent::init();
    }


    /**
     * @return array
     */
    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), [
            'id', 'sku', 'quantity', 'seller', 'weight', 'sub_product_url', 'with_detail'
        ]);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['id', 'required'],
            ['id', 'string', 'min' => 3, 'max' => 40],
            ['sku', 'string', 'min' => 3, 'max' => 12],
            ['quantity', 'default', 'value' => 1],
            ['seller', 'string'],
            ['seller', 'filter', 'filter' => '\yii\helpers\Html::encode'],
            ['weight', 'integer'],
            ['sub_product_url', 'string'],
            ['with_detail','safe'],
        ]);
    }

    public function attributeLabels()
    {
        return parent::attributeLabels();
    }

    /**
     * @param bool $renew
     * @return bool|BaseProduct
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
        /** @var $success boolean */
        /** @var $product BaseProduct */
        list($success, $product) = $this->getActiveGate()->lookup($this->getParams(), $renew);
        if (!$success || is_string($product)) {
            $this->addError($this->isSku() ? 'sku' : 'id', $product);
            return false;
        }
        if ($this->isSku()) {
            $product->updateBySku($this->sku);
        }
        if ($this->weight !== null && trim($this->weight) > 0) {
            $product->shipping_weight = $this->weight;
        }
        if ($this->quantity !== null && trim($this->quantity) > 0) {
            $product->quantity = $this->quantity;
        }
        if ($this->seller !== null && trim($this->seller) !== '' && $this->type !== 'ebay') {
            $product->updateBySeller($this->seller);
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

    protected function getParams()
    {
        if ($this->type === 'ebay') {
            return $this->id;
        } else {
            $param = [
                'asin_id' => $this->id
            ];
            if ($this->isSku()) {
                $param['asin_id'] = $this->sku;
                $param['parent_asin_id'] = $this->id;
                $param['load_sub_url'] = null;
            }
            return $param;

        }
    }
}
