<?php

namespace userbackend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OrderSearch represents the model behind the search form of `common\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'store_id', 'customer_id', 'new', 'purchase_start', 'purchased', 'seller_shipped', 'stockin_us', 'stockout_us', 'stockin_local', 'stockout_local', 'at_customer', 'returned', 'cancelled', 'lost', 'is_quotation', 'quotation_status', 'receiver_country_id', 'receiver_province_id', 'receiver_district_id', 'receiver_address_id', 'seller_id', 'sale_support_id', 'is_email_sent', 'is_sms_sent', 'difference_money', 'coupon_id', 'xu_time', 'promotion_id', 'created_at', 'updated_at', 'purchase_assignee_id', 'total_quantity', 'total_purchase_quantity', 'remove', 'supported', 'ready_purchase', 'supporting', 'check_update_payment'], 'integer'],
            [['ordercode', 'type_order', 'customer_type', 'portal', 'utm_source', 'current_status', 'quotation_note', 'receiver_email', 'receiver_name', 'receiver_phone', 'receiver_address', 'receiver_country_name', 'receiver_province_name', 'receiver_district_name', 'receiver_post_code', 'note_by_customer', 'note', 'seller_name', 'seller_store', 'currency_purchase', 'payment_type', 'support_email', 'xu_log', 'purchase_order_id', 'purchase_transaction_id', 'purchase_account_id', 'purchase_account_email', 'purchase_card', 'purchase_refund_transaction_id', 'version', 'mark_supporting'], 'safe'],
            [['total_final_amount_local', 'total_amount_local', 'total_origin_fee_local', 'total_price_amount_origin', 'total_paid_amount_local', 'total_refund_amount_local', 'total_counpon_amount_local', 'total_promotion_amount_local', 'total_fee_amount_local', 'total_custom_fee_amount_local', 'total_origin_tax_fee_local', 'total_origin_shipping_fee_local', 'total_weshop_fee_local', 'total_intl_shipping_fee_local', 'total_delivery_fee_local', 'total_packing_fee_local', 'total_inspection_fee_local', 'total_insurance_fee_local', 'total_vat_amount_local', 'exchange_rate_fee', 'exchange_rate_purchase', 'revenue_xu', 'xu_count', 'xu_amount', 'total_weight', 'total_weight_temporary', 'purchase_amount', 'purchase_amount_buck', 'purchase_amount_refund'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Order::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'store_id' => $this->store_id,
            'customer_id' => $this->customer_id,
            'new' => $this->new,
            'purchase_start' => $this->purchase_start,
            'purchased' => $this->purchased,
            'seller_shipped' => $this->seller_shipped,
            'stockin_us' => $this->stockin_us,
            'stockout_us' => $this->stockout_us,
            'stockin_local' => $this->stockin_local,
            'stockout_local' => $this->stockout_local,
            'at_customer' => $this->at_customer,
            'returned' => $this->returned,
            'cancelled' => $this->cancelled,
            'lost' => $this->lost,
            'is_quotation' => $this->is_quotation,
            'quotation_status' => $this->quotation_status,
            'receiver_country_id' => $this->receiver_country_id,
            'receiver_province_id' => $this->receiver_province_id,
            'receiver_district_id' => $this->receiver_district_id,
            'receiver_address_id' => $this->receiver_address_id,
            'seller_id' => $this->seller_id,
            'total_final_amount_local' => $this->total_final_amount_local,
            'total_amount_local' => $this->total_amount_local,
            'total_origin_fee_local' => $this->total_origin_fee_local,
            'total_price_amount_origin' => $this->total_price_amount_origin,
            'total_paid_amount_local' => $this->total_paid_amount_local,
            'total_refund_amount_local' => $this->total_refund_amount_local,
            'total_counpon_amount_local' => $this->total_counpon_amount_local,
            'total_promotion_amount_local' => $this->total_promotion_amount_local,
            'total_fee_amount_local' => $this->total_fee_amount_local,
            'total_custom_fee_amount_local' => $this->total_custom_fee_amount_local,
            'total_origin_tax_fee_local' => $this->total_origin_tax_fee_local,
            'total_origin_shipping_fee_local' => $this->total_origin_shipping_fee_local,
            'total_weshop_fee_local' => $this->total_weshop_fee_local,
            'total_intl_shipping_fee_local' => $this->total_intl_shipping_fee_local,
            'total_delivery_fee_local' => $this->total_delivery_fee_local,
            'total_packing_fee_local' => $this->total_packing_fee_local,
            'total_inspection_fee_local' => $this->total_inspection_fee_local,
            'total_insurance_fee_local' => $this->total_insurance_fee_local,
            'total_vat_amount_local' => $this->total_vat_amount_local,
            'exchange_rate_fee' => $this->exchange_rate_fee,
            'exchange_rate_purchase' => $this->exchange_rate_purchase,
            'sale_support_id' => $this->sale_support_id,
            'is_email_sent' => $this->is_email_sent,
            'is_sms_sent' => $this->is_sms_sent,
            'difference_money' => $this->difference_money,
            'coupon_id' => $this->coupon_id,
            'revenue_xu' => $this->revenue_xu,
            'xu_count' => $this->xu_count,
            'xu_amount' => $this->xu_amount,
            'xu_time' => $this->xu_time,
            'promotion_id' => $this->promotion_id,
            'total_weight' => $this->total_weight,
            'total_weight_temporary' => $this->total_weight_temporary,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'purchase_assignee_id' => $this->purchase_assignee_id,
            'purchase_amount' => $this->purchase_amount,
            'purchase_amount_buck' => $this->purchase_amount_buck,
            'purchase_amount_refund' => $this->purchase_amount_refund,
            'total_quantity' => $this->total_quantity,
            'total_purchase_quantity' => $this->total_purchase_quantity,
            'remove' => $this->remove,
            'supported' => $this->supported,
            'ready_purchase' => $this->ready_purchase,
            'supporting' => $this->supporting,
            'check_update_payment' => $this->check_update_payment,
        ]);

        $query->andFilterWhere(['like', 'ordercode', $this->ordercode])
            ->andFilterWhere(['like', 'type_order', $this->type_order])
            ->andFilterWhere(['like', 'customer_type', $this->customer_type])
            ->andFilterWhere(['like', 'portal', $this->portal])
            ->andFilterWhere(['like', 'utm_source', $this->utm_source])
            ->andFilterWhere(['like', 'current_status', $this->current_status])
            ->andFilterWhere(['like', 'quotation_note', $this->quotation_note])
            ->andFilterWhere(['like', 'receiver_email', $this->receiver_email])
            ->andFilterWhere(['like', 'receiver_name', $this->receiver_name])
            ->andFilterWhere(['like', 'receiver_phone', $this->receiver_phone])
            ->andFilterWhere(['like', 'receiver_address', $this->receiver_address])
            ->andFilterWhere(['like', 'receiver_country_name', $this->receiver_country_name])
            ->andFilterWhere(['like', 'receiver_province_name', $this->receiver_province_name])
            ->andFilterWhere(['like', 'receiver_district_name', $this->receiver_district_name])
            ->andFilterWhere(['like', 'receiver_post_code', $this->receiver_post_code])
            ->andFilterWhere(['like', 'note_by_customer', $this->note_by_customer])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'seller_name', $this->seller_name])
            ->andFilterWhere(['like', 'seller_store', $this->seller_store])
            ->andFilterWhere(['like', 'currency_purchase', $this->currency_purchase])
            ->andFilterWhere(['like', 'payment_type', $this->payment_type])
            ->andFilterWhere(['like', 'support_email', $this->support_email])
            ->andFilterWhere(['like', 'xu_log', $this->xu_log])
            ->andFilterWhere(['like', 'purchase_order_id', $this->purchase_order_id])
            ->andFilterWhere(['like', 'purchase_transaction_id', $this->purchase_transaction_id])
            ->andFilterWhere(['like', 'purchase_account_id', $this->purchase_account_id])
            ->andFilterWhere(['like', 'purchase_account_email', $this->purchase_account_email])
            ->andFilterWhere(['like', 'purchase_card', $this->purchase_card])
            ->andFilterWhere(['like', 'purchase_refund_transaction_id', $this->purchase_refund_transaction_id])
            ->andFilterWhere(['like', 'version', $this->version])
            ->andFilterWhere(['like', 'mark_supporting', $this->mark_supporting]);

        return $dataProvider;
    }
}
