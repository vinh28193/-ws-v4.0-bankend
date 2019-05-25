<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-07
 * Time: 13:42
 */

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\db\Product as DbProduct;
use common\models\queries\ProductQuery;
use common\components\AdditionalFeeTrait;

/**
 * Class Product
 * @package common\models
 * @property ProductFee[] $productFee
 * @property ProductFee $usShippingFee
 * @property ProductFee $unitPrice
 * @property ProductFee $usTax
 * @property Order $order
 * @property  Package[] $packages
 *
 *
 */
class Product extends DbProduct
{
    use AdditionalFeeTrait;

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

        if(isset($search)){
            $params=$search;
        }

        $limit = isset($limit) ? $limit : 10;
        $page = isset($page) ? $page : 1;

        $offset = ($page - 1) * $limit;

        $query = Product::find()
            //->withFullRelations()
            ->filter($params)
            ->limit($limit)
            ->offset($offset);

        if(isset($params['id'])) {
            $query->andFilterWhere(['id' => $params['id']]);
        }

        if(isset($params['created_at'])) {
            $query->andFilterWhere(['created_at' => $params['created_at']]);
        }
        if(isset($params['updated_at'])) {
            $query->andFilterWhere(['updated_at' => $params['updated_at']]);
        }
        if(isset($params['receiver_email'])){
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

        if(isset($params['type_Product'])){
            $query->andFilterWhere(['type_Product' => $params['type_Product'] ]);
        }
        if(isset($params['current_status'])){
            $query->andFilterWhere(['current_status' => $params['current_status']]);
        }
        if (isset($params['time_start']) and isset($params['time_end']) ){
            $query->andFilterWhere(['or',
                ['>=', 'created_at', $params['time_start']],
                ['<=', 'updated_at', $params['time_end']]
            ]);
        }

        if(isset($Product)){
            $query->ProductBy($Product);
        }


        if(isset($Product)){
            $query->ProductBy($Product);
        }

        $additional_info = [
            'page' => $page,
            'size' => $limit,
            'totalCount' => (int)$query->count()
        ];

        $data = (array)$query->all();
        return array_merge($data , $additional_info);

    }

    public function getProductFees()
    {
        return $this->hasMany(ProductFee::className(), ['product_id' => 'id']);
//        return parent::getProductFee(); // TODO: Change the autogenerated stub
    }

    public function getUsShippingFee()
    {
        return $this->hasOne(ProductFee::className(), ['product_id' => 'id'])->where(['type' => 'origin_shipping_fee']);
//        return parent::getProductFee(); // TODO: Change the autogenerated stub
    }

    public function getUnitPrice()
    {
        return $this->hasOne(ProductFee::className(), ['product_id' => 'id'])->where(['type' => 'product_price_origin']);
//        return parent::getProductFee(); // TODO: Change the autogenerated stub
    }

    public function getUsTax()
    {
        return $this->hasOne(ProductFee::className(), ['product_id' => 'id'])->where(['type' => 'tax_fee_origin']);
//        return parent::getProductFee(); // TODO: Change the autogenerated stub
    }

    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackages()
    {
        return $this->hasMany(Package::className(), ['order_id' => 'id']);
    }
}
