<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-04
 * Time: 17:26
 */

namespace common\models\queries;


use yii\db\ActiveQuery;

class OrderQuery extends \common\components\db\ActiveQuery
{

    /**
     * @param $params
     * @return $this
     */
    public function filter($params){

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function defaultSelect($columns = [])
    {
        $columns = array_merge([
            $this->getColumnName('id'),
            $this->getColumnName('store_id'),
            $this->getColumnName('type_order'),
            $this->getColumnName('customer_id'),
            $this->getColumnName('portal'),
            $this->getColumnName('quotation_note'),
            $this->getColumnName('receiver_email'),
            $this->getColumnName('receiver_name'),
            $this->getColumnName('receiver_phone'),
            $this->getColumnName('receiver_address'),
            $this->getColumnName('receiver_country_name'),
            $this->getColumnName('receiver_province_name'),
            $this->getColumnName('receiver_district_name'),
            $this->getColumnName('receiver_post_code'),
            $this->getColumnName('note_by_customer'),
            $this->getColumnName('seller_name'),
            $this->getColumnName('currency_purchase'),
            $this->getColumnName('support_email'),
            $this->getColumnName('purchase_order_id'),
            $this->getColumnName('purchase_transaction_id'),
            $this->getColumnName('purchase_amount'),
            $this->getColumnName('purchase_account_email'),
            $this->getColumnName('purchase_card'),
        ],$columns);
        return parent::defaultSelect($columns);
    }

    public function withFullRelations(){
        $this->with([
            'products.productFees',
            'products.packages',
            'products.trackingCodes',
            'seller',
            'purchaseAssignee',
            'purchaseProducts',
            'purchaseProducts.purchaseOrder',
            'promotion',
            'package',
            'saleSupport' => function ($q) {
                /** @var ActiveQuery $q */
                $q->select(['username','email','id','status', 'created_at', 'updated_at']);
            }
        ]);
//        $this->innerJoinWith([
//            'products',
//        ]);
        $this->joinWith([
            'walletTransactions',
            'products',
            'user',
        ]);
        return $this;
    }

    public function countPurchase() {

    }
}
