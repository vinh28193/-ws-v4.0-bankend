<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-23
 * Time: 11:21
 */

namespace common\models;

use common\components\AdditionalFeeInterface;
use common\models\db\Order as DbOrder;
use common\models\db\Promotion;
use Yii;
use yii\db\BaseActiveRecord;

class Order extends DbOrder  // implements AdditionalFeeInterface
{

    use \common\components\StoreAdditionalFeeRegisterTrait;
    use \common\components\AdditionalFeeTrait;

    public function rules()
    {
        return [
            [['note_by_customer', 'note', 'seller_store', 'purchase_order_id', 'purchase_transaction_id', 'purchase_amount', 'purchase_account_email', 'purchase_card', 'purchase_refund_transaction_id', 'total_weight', 'total_weight_temporary'], 'trim'],
            [['current_status'], 'string', 'max' => 200],
            [['quotation_status'], 'in', 'range' => [null,0, 1, 2]],
            [['difference_money'], 'in', 'range' => [0, 1, 2]],
            [['receiver_email', 'support_email', 'purchase_account_email'], 'email'],
            [['seller_store'], 'url'],
            [['note_by_customer', 'note', 'seller_store', 'purchase_order_id', 'purchase_transaction_id', 'purchase_amount', 'purchase_account_email', 'purchase_card', 'purchase_refund_transaction_id', 'total_weight', 'total_weight_temporary'], 'filter', 'filter' => '\yii\helpers\Html::encode'],
            [['note_by_customer', 'note', 'purchase_order_id', 'purchase_transaction_id', 'purchase_amount', 'purchase_card', 'purchase_account_email', 'purchase_refund_transaction_id', 'total_weight', 'total_weight_temporary'], 'filter', 'filter' => '\yii\helpers\Html::encode',],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['receiver_address_id'], 'exist', 'skipOnError' => true, 'targetClass' => Address::className(), 'targetAttribute' => ['receiver_address_id' => 'id']],
            [['receiver_country_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemCountry::className(), 'targetAttribute' => ['receiver_country_id' => 'id']],
            [['receiver_district_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemDistrict::className(), 'targetAttribute' => ['receiver_district_id' => 'id']],
            [['receiver_province_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemStateProvince::className(), 'targetAttribute' => ['receiver_province_id' => 'id']],
            [['sale_support_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sale_support_id' => 'id']],
            [['seller_id'], 'exist', 'skipOnError' => true, 'targetClass' => Seller::className(), 'targetAttribute' => ['seller_id' => 'id']],
            [['store_id'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['store_id' => 'id']],
        ];
    }


    public function getItemType()
    {
        return $this->portal;
    }

    public function getTotalOriginPrice()
    {
        return $this->getTotalAdditionFees([
            'origin_fee', 'origin_tax_fee', 'origin_shipping_fee'
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
        return $this->total_weight;
    }


    public function getExchangeRate()
    {
        return $this->exchange_rate_fee;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotion()
    {
        return $this->hasOne(Promotion::className(), ['id' => 'promotion_id']);
    }


    /**
     * @inheritdoc
     * @return \common\models\queries\OrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return Yii::createObject(\common\models\queries\OrderQuery::className(), [get_called_class()]);
    }

    public function fields()
    {
        $fields = parent::fields();
        unset($fields['id']);
        return array_merge($fields,[
            'store' => function($model) { return $model->store_id  === 1 ? 'Viet Nam' : 'Indo';}
        ]);
    }


    // Optional sort/filter params: page,limit,order,search[name],search[email],search[id]... etc

    static public function search($params)
    {

        $page = Yii::$app->getRequest()->getQueryParam('page');
        $limit = Yii::$app->getRequest()->getQueryParam('limit');
        $order = Yii::$app->getRequest()->getQueryParam('order');

        $search = Yii::$app->getRequest()->getQueryParam('search');

        if(isset($search)){
            $params=$search;
        }

        $limit = isset($limit) ? $limit : 10;
        $page = isset($page) ? $page : 1;

        $offset = ($page - 1) * $limit;

        $query = Order::find()
            ->with([
                'products',
                'promotion',
                'orderFees',
                'packageItems',
                'walletTransactions',
                'seller',
                'saleSupport' => function ($q) {
                    /** @var ActiveQuery $q */
                    $q->select(['username','email','id','status', 'created_at', 'updated_at']);
                }
            ])
            ->asArray(true)
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

        if(isset($params['type_order'])){
            $query->andFilterWhere(['type_order' => $params['type_order'] ]);
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

        if(isset($order)){
            $query->orderBy($order);
        }


        if(isset($order)){
            $query->orderBy($order);
        }

        $additional_info = [
            'page' => $page,
            'size' => $limit,
            'totalCount' => (int)$query->count()
        ];

        return [
            'data' => $query->all(),
            'info' => $additional_info
        ];
    }

}