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

    /**
     * @return $this
     */
    public function addSelectColumn() {
        $this->select([
            'order.id',
            'order.ordercode',
            'order.current_status',
            'order.total_paid_amount_local',
            'order.potential',
            'order.order_boxme',
            'order.shipment_boxme',
            'order.store_id',
            'order.type_order',
            'order.created_at',
            'order.portal',
            'order.version',
            'order.purchase_order_id',
            'order.purchase_transaction_id',
            'order.sale_support_id',
            'order.seller_store',
            'order.seller_name',
            'order.customer_type',
            'order.buyer_name',
            'order.buyer_phone',
            'order.buyer_email',
            'order.buyer_address',
            'order.buyer_district_name',
            'order.buyer_province_name',
            'order.buyer_post_code',
            'order.buyer_country_id',
            'order.buyer_province_id',
            'order.buyer_district_id',
            'order.receiver_country_id',
            'order.receiver_province_id',
            'order.receiver_district_id',
            'order.receiver_name',
            'order.receiver_phone',
            'order.receiver_email',
            'order.receiver_address',
            'order.receiver_district_name',
            'order.receiver_province_name',
            'order.receiver_post_code',
            'order.note_by_customer',
            'order.exchange_rate_fee',
            'order.total_price_amount_origin',
            'order.total_origin_fee_local',
            'order.total_origin_tax_fee_amount',
            'order.note',
            'order.total_origin_tax_fee_local',
            'order.total_origin_shipping_fee_local',
            'order.total_weshop_fee_amount',
            'order.total_weshop_fee_local',
            'order.total_weight_temporary',
            'order.total_intl_shipping_fee_local',
            'order.total_custom_fee_amount',
            'order.total_delivery_fee_local',
            'order.total_packing_fee_local',
            'order.total_inspection_fee_local',
            'order.total_insurance_fee_local',
            'order.additional_service',
            'order.total_fee_amount_local',
            'order.total_amount_local',
            'order.coupon_id',
            'order.promotion_id',
            'order.total_final_amount_local',
            'order.total_paid_amount_local',
            'order.supporting',
            'order.supported',
            'order.ready_purchase',
            'order.purchase_start',
            'order.seller_shipped',
            'order.supported',
            'order.stockin_us',
            'order.stockout_us',
            'order.stockin_local',
            'order.stockout_local',
            'order.at_customer',
            'order.returned',
            'order.cancelled',
            'order.lost',
            'order.check_update_payment',
            'order.mark_supporting',
            'order.check_insurance',
            'order.check_inspection',
            'order.check_packing_wood',
            'order.tracking_codes',
            'order.purchase_note',
            'order.purchase_order_id',
            'order.purchase_transaction_id',
            'order.contacting',
            'order.awaiting_payment',
            'order.awaiting_confirm_purchase',
            'order.delivering',
            'order.delivered',
            'order.created_at',
            'order.seller_id',
            'order.junk',
            'order.purchasing',
            'order.refunded',
            'order.purchased',
        ]);
        return $this;
    }

    public function withFullRelations(){
        $this->with([
            'products.productFees' => function ($q) {
                /** @var ActiveQuery $q */
                $q->select([
                    'name',
                    'target_id',
                    'amount',
                    'local_amount',
                    'discount_amount'
                ]);
            },

            /** TODO 08/07/2019 join tracking_code (draft_extension_tracking_map && package )*/
            /**
            'products.packages' => function ($q) {
                // @var ActiveQuery $q
                $q->select([
                    'tracking_code',
                    'product_id',
                    'order_id',
                    'id',
                ]);
            },
            'products.trackingCodes' => function ($q) { },
            */
            'seller' => function ($q) {
                /** @var ActiveQuery $q */
                $q->select([
                    'seller_link_store',
                    'seller_name',
                    'id',
                ]);
            },
            /** TODO 08/07/2019 nghiệp vụ bỏ  */
            /**
            'purchaseAssignee',
            'purchaseProducts',
            'purchaseProducts.purchaseOrder',
             **/
            'promotion' => function ($q) {
                /** @var ActiveQuery $q */
                $q->select([
                    'code',
                    'discount_amount',
                    'id'
                ]);
            },
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
//            'walletTransactions' => function ($q) {
//            /** @var ActiveQuery $q */
//                $q->select([
//                    'id',
//                    'order_code',
//                    'transaction_code',
//                    'transaction_amount_local',
//                    'transaction_type',
//                    'transaction_status',
//                    'transaction_description',
//                    'note',
//                    'link_image',
//                    'third_party_transaction_link',
//                    'payment_bank_code',
//                    'created_at',
//                    'updated_at',
//                ]);
//            },
            'products' => function ($q) {
                /** @var ActiveQuery $q */
               $q->select([
                   'id',
                   'link_img',
                   'order_id',
                   'link_origin',
                   'product_link',
                   'product_name',
                   'sku',
                   'parent_sku',
                   'variations',
                   'total_weight_temporary',
                   'current_status',
                   'note_boxme',
                   'note_by_customer',
                   'custom_category_id',
                   'confirm_change_price',
                   'purchased',
                   'quantity_customer',
                   'quantity_purchase',
                   'quantity_inspect',
                   'price_amount_origin',
                   'price_amount_local',
                   'total_final_amount_origin',
                   'total_final_amount_local',
                   'check_special',
               ]);
            },
        ]);
        return $this;
    }

    public function countPurchase() {

    }
}
