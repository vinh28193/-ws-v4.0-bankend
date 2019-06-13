<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-07
 * Time: 13:42
 */

namespace common\models;

use common\models\db\TargetAdditionalFee;
use common\models\draft\DraftExtensionTrackingMap;
use Yii;
use yii\helpers\ArrayHelper;
use common\models\db\Product as DbProduct;
use common\models\queries\ProductQuery;
use common\components\AdditionalFeeTrait;

/**
 * Class Product
 * @package common\models
 * @property Order $order
 * @property Category $category
 * @property TargetAdditionalFee[] $productFees
 *
 *
 */
class Product extends DbProduct
{

    const STATUS_NEW = 'NEW'; // Lv 1; đơn mới được tạo, next status SUPPORTING
    const STATUS_JUNK = 'JUNK'; // end of status
    const STATUS_SUPPORTING = 'SUPPORTING'; // Lv 2; đơn đang được chăm sóc, next status SUPPORTED
    const STATUS_SUPPORTED = 'SUPPORTED'; // Lv 3; đơn đã được chăm sóc, next status READY_PURCHASE
    const STATUS_READY2PURCHASE = 'READY2PURCHASE'; // Lv 4; đơn đã sẵn sàng mua hàng, next status PURCHASING, PURCHASE_PART or REFUNDING
    const STATUS_PURCHASING = 'PURCHASING'; // Lv 5; đơn đang trong quá trình mua hàng, next status PURCHASED
    const STATUS_PURCHASE_PART = 'PURCHASE_PART'; // Lv 6; đơn đang trong quá trình mua hàng nhưng mới mua được 1 phần, next status PURCHASED
    const STATUS_PURCHASED = 'PURCHASED'; // Lv7; đơn đã mua, next status REFUNDING
    const STATUS_REFUNDING = 'REFUNDING'; //Lv8; đơn đang chuyển hoàn, next status REFUNDED
    const STATUS_REFUNDED = 'REFUNDED'; //Lv8; đơn đã chuyển hoàn, end of status
    const STATUS_CANCEL = 'CANCELLED';
    const STATUS_SELLER_SHIPPED = 'SELLER_SHIPPED';
    const STATUS_STOCK_IN_US = 'STOCK_IN_US';
    const STATUS_STOCK_OUT_US = 'STOCK_OUT_US';
    const STATUS_STOCK_IN_LOCAL = 'STOCK_IN_LOCAL';
    const STATUS_STOCK_OUT_LOCAL = 'STOCK_OUT_LOCAL';
    const STATUS_AT_CUSTOMER = 'AT_CUSTOMER';
    const STATUS_RETURNED = 'RETURNED';

    const STATUS_NEED_CONFIRM_CHANGE_PRICE = 1;
    const STATUS_CONFIRMED_CHANGE_PRICE = 0;

    /**
     * @inheritdoc
     * @return ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return Yii::createObject(ProductQuery::className(), [get_called_class()]);
    }


    // Optional sort/filter params: page,limit,Product,search[name],search[email],search[id]... etc

    static public function search($params)
    {

        $page = Yii::$app->getRequest()->getQueryParam('page');
        $limit = Yii::$app->getRequest()->getQueryParam('limit');
        $Product = Yii::$app->getRequest()->getQueryParam('Product');

        $search = Yii::$app->getRequest()->getQueryParam('search');

        if (isset($search)) {
            $params = $search;
        }

        $limit = isset($limit) ? $limit : 10;
        $page = isset($page) ? $page : 1;

        $offset = ($page - 1) * $limit;

        $query = Product::find()
            //->withFullRelations()
            ->filter($params)
            ->limit($limit)
            ->offset($offset);

        if (isset($params['id'])) {
            $query->andFilterWhere(['id' => $params['id']]);
        }

        if (isset($params['created_at'])) {
            $query->andFilterWhere(['created_at' => $params['created_at']]);
        }
        if (isset($params['updated_at'])) {
            $query->andFilterWhere(['updated_at' => $params['updated_at']]);
        }
        if (isset($params['receiver_email'])) {
            $query->andFilterWhere(['like', 'receiver_email', $params['receiver_email']]);
        }

        /*

        if(isset($params['typeSearch']) and isset($params['keyword']) ){
            $query->andFilterWhere(['like',$params['typeSearch'],$params['keyword']]);
        }else{
            $query->andWhere(['or',
                ['like', 'id', $params['keyword']],
                ['like', 'seller_name', $params['keyword']],
                ['like', 'seller_store', $params['keyword']],
                ['like', 'portal', $params['keyword']],
            ]);
        }
        */

        if (isset($params['type_Product'])) {
            $query->andFilterWhere(['type_Product' => $params['type_Product']]);
        }
        if (isset($params['current_status'])) {
            $query->andFilterWhere(['current_status' => $params['current_status']]);
        }
        if (isset($params['time_start']) and isset($params['time_end'])) {
            $query->andFilterWhere(['or',
                ['>=', 'created_at', $params['time_start']],
                ['<=', 'updated_at', $params['time_end']]
            ]);
        }

        if (isset($Product)) {
            $query->ProductBy($Product);
        }


        if (isset($Product)) {
            $query->ProductBy($Product);
        }

        $additional_info = [
            'page' => $page,
            'size' => $limit,
            'totalCount' => (int)$query->count()
        ];

        $data = (array)$query->all();
        return array_merge($data, $additional_info);

    }

    public function getProductFees()
    {
        return $this->hasMany(TargetAdditionalFee::className(), ['target_id' => 'id'])->andWhere(['target' => 'product']);
    }

    public function getUsShippingFee()
    {
        return self::getProductFees()->andWhere(['name' => 'origin_shipping_fee']);
    }

    public function getUnitPrice()
    {
        return self::getProductFees()->andWhere(['name' => 'product_price_origin']);
    }

    public function getUsTax()
    {
        return self::getProductFees()->andWhere(['type' => 'tax_fee_origin']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackages()
    {
        return $this->hasMany(Package::className(), ['order_id' => 'id']);
    }

    public function updateSellerShipped($time = null, $updateNew = false)
    {
        $this->current_status = $this->seller_shipped ? $this->current_status : self::STATUS_SELLER_SHIPPED;
        if ($updateNew || !$this->seller_shipped) {
            $this->seller_shipped = $time ? $time : time();
        }
    }

    public function updateStockinUs($time = null, $updateNew = false)
    {
        $this->current_status = $this->stockin_us ? $this->current_status : self::STATUS_STOCK_IN_US;
        if ($updateNew || !$this->stockin_us) {
            $this->stockin_us = $time ? $time : time();
        }
    }

    public function updateStockoutUs($time = null, $updateNew = false)
    {
        $this->current_status = $this->stockout_us ? $this->current_status : self::STATUS_STOCK_OUT_US;
        if ($updateNew || !$this->stockout_us) {
            $this->stockout_us = $time ? $time : time();
        }
    }

    public function updateStockinLocal($time = null, $updateNew = false)
    {
        $this->current_status = $this->stockin_local ? $this->current_status : self::STATUS_STOCK_IN_LOCAL;
        if ($updateNew || !$this->stockin_local) {
            $this->stockin_local = $time ? $time : time();
        }
    }

    public function updateStockoutLocal($time = null, $updateNew = false)
    {
        $this->current_status = $this->stockout_local ? $this->current_status : self::STATUS_STOCK_OUT_LOCAL;
        if ($updateNew || !$this->stockout_local) {
            $this->stockout_local = $time ? $time : time();
        }
    }

    public function getTrackingCodes()
    {
        return $this->hasMany(DraftExtensionTrackingMap::className(), ['product_id' => 'id']);
    }
}
