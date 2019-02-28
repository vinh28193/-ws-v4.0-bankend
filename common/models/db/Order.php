<?php

namespace common\models\db;

use Yii;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "order".
 *
 * @property int $id ID
 * @property int $store_id hàng của nước nào
 * @property string $type_order Hình thức mua hàng: SHOP | REQUEST | POS | SHIP
 * @property string $portal portal ebay, amazon us, amazon jp ...
 * @property int $is_quotation Đánh dấu đơn báo giá
 * @property int $quotation_status Trạng thái báo giá. 0 - pending, 1- approve, 2- deny
 * @property string $quotation_note note đơn request
 * @property int $customer_id id của customer
 * @property string $receiver_email
 * @property string $receiver_name
 * @property string $receiver_phone
 * @property string $receiver_address
 * @property int $receiver_country_id
 * @property string $receiver_country_name
 * @property int $receiver_province_id
 * @property string $receiver_province_name
 * @property int $receiver_district_id
 * @property string $receiver_district_name
 * @property string $receiver_post_code
 * @property int $receiver_address_id id address của người nhận trong bảng address
 * @property string $note_by_customer Ghi chú của customer
 * @property string $note Ghi chú cho đơn hàng
 * @property string $payment_type hinh thuc thanh toan. -online_payment, 'VT'...
 * @property int $sale_support_id Người support đơn hàng
 * @property string $support_email email người support
 * @property string $coupon_id mã giảm giá
 * @property string $coupon_code mã giảm giá
 * @property string $coupon_time thời gian sử dụng
 * @property string $revenue_xu số xu được nhận
 * @property string $xu_count số xu sử dụng
 * @property string $xu_amount giá trị quy đổi ra tiền
 * @property int $is_email_sent
 * @property int $is_sms_sent
 * @property int $total_quantity
 * @property int $promotion_id id của promotion
 * @property int $difference_money 0: mac dinh, 1: lech, 2:ẩn thông báo bằng quyền của Admin
 * @property string $utm_source
 * @property int $seller_id
 * @property string $seller_name
 * @property string $seller_store
 * @property string $total_final_amount_local số tiền cuối cùng khách hàng phải thanh toán
 * @property string $total_paid_amount_local số tiền khách hàng đã thanh toán
 * @property string $total_refund_amount_local số tiền đã hoàn trả cho khách hàng
 * @property string $total_amount_local tổng giá đơn hàng
 * @property string $total_fee_amount_local tổng phí đơn hàng
 * @property string $total_counpon_amount_local Tổng số tiền giảm giá bằng mã counpon
 * @property string $total_promotion_amount_local Tổng số tiền giảm giá do promotion
 * @property string $total_origin_fee_local Tổng phí gốc tại xuất xứ (Tiền Local)
 * @property string $total_origin_tax_fee_local Tổng phí tax tại xuất xứ
 * @property string $total_origin_shipping_fee_local Tổng phí vận chuyển tại xuất xứ
 * @property string $total_weshop_fee_local Tổng phí Weshop
 * @property string $total_intl_shipping_fee_local Tổng phí vận chuyển quốc tế
 * @property string $total_custom_fee_amount_local Tổng phí phụ thu
 * @property string $total_delivery_fee_local Tổng phí vận chuyển nội địa
 * @property string $total_packing_fee_local Tống phí hàng
 * @property string $total_inspection_fee_local Tổng phí kiểm hàng
 * @property string $total_insurance_fee_local Tổng phí bảo hiểm
 * @property string $total_vat_amount_local Tổng phí VAT
 * @property string $exchange_rate_fee Tỷ giá từ USD => tiền local
 * @property string $exchange_rate_purchase Tỷ giá từ tiền website gốc => tiền local. VD: yên => vnd
 * @property string $currency_purchase USD,JPY,AUD .....
 * @property string $purchase_order_id mã order purchase ( dạng list, cách nhau = dấu phẩy)
 * @property string $purchase_transaction_id Mã thanh toán Paypal với eBay, amazon thanh toán bằng thẻ, k lấy được mã giao dịch ( dạng list, cách nhau = dấu phẩy)
 * @property string $purchase_amount số tiền đã thanh toán với người bán, Số đã trừ Buck/Point ( dạng list, cách nhau = dấu phẩy)
 * @property int $purchase_account_id id tài khoản mua hàng
 * @property string $purchase_account_email email tài khoản mua hàng
 * @property string $purchase_card thẻ thanh toán
 * @property string $purchase_amount_buck số tiền buck thanh toán
 * @property string $purchase_amount_refund số tiền người bán hoàn
 * @property string $purchase_refund_transaction_id mã giao dịch hoàn
 * @property string $total_weight cân nặng tính phí
 * @property string $total_weight_temporary cân nặng tạm tính
 * @property string $new time NEW
 * @property string $purchased time PURCHASED
 * @property string $seller_shipped time SELLER_SHIPPED
 * @property string $stockin_us time STOCKIN_US
 * @property string $stockout_us time STOCKOUT_US
 * @property string $stockin_local time STOCKIN_LOCAL
 * @property string $stockout_local time STOCKOUT_LOCAL
 * @property string $at_customer time AT_CUSTOMER
 * @property string $returned time RETURNED
 * @property string $cancelled  time CANCELLED :  Đơn hàng đã  hoặc chưa thanh toán --> nhưng bị hủy và hoàn tiền
 * @property string $lost  time LOST : Hàng mất ở kho Mỹ hoặc hải quan hoặc kho VN hoặc trên đường giao cho KH 
 * @property string $current_status Trạng thái hiện tại của order
 * @property string $created_at Update qua behaviors tự động  
 * @property string $updated_at Update qua behaviors tự động
 * @property int $remove
 *
 * @property Customer $customer
 * @property Address $receiverAddress
 * @property SystemCountry $receiverCountry
 * @property SystemDistrict $receiverDistrict
 * @property SystemStateProvince $receiverProvince
 * @property User $saleSupport
 * @property Seller $seller
 * @property Store $store
 * @property OrderFee[] $orderFees
 * @property PackageItem[] $packageItems
 * @property Product[] $products
 * @property WalletTransaction[] $walletTransactions
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @return array
     */

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ]
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id', 'type_order', 'portal', 'quotation_status', 'is_quotation', 'customer_id', 'receiver_email', 'receiver_name', 'receiver_phone', 'receiver_address', 'receiver_country_id', 'receiver_country_name', 'receiver_province_id', 'receiver_province_name', 'receiver_district_id', 'receiver_district_name', 'receiver_post_code', 'receiver_address_id', 'payment_type', 'sale_support_id', 'support_email', 'total_quantity', 'seller_id', 'seller_name', 'seller_store', 'total_final_amount_local', 'total_promotion_amount_local', 'current_status'], 'required'],
            [['store_id', 'is_quotation', 'quotation_status', 'customer_id', 'receiver_country_id', 'receiver_province_id', 'receiver_district_id', 'receiver_address_id', 'sale_support_id', 'coupon_time', 'is_email_sent', 'is_sms_sent', 'total_quantity', 'promotion_id', 'difference_money', 'seller_id', 'purchase_account_id', 'new', 'purchased', 'seller_shipped', 'stockin_us', 'stockout_us', 'stockin_local', 'stockout_local', 'at_customer', 'returned', 'cancelled', 'lost', 'created_at', 'updated_at', 'remove'], 'integer'],
            [['note_by_customer', 'note', 'seller_store', 'purchase_order_id', 'purchase_transaction_id', 'purchase_amount', 'purchase_account_email', 'purchase_card', 'purchase_refund_transaction_id', 'total_weight', 'total_weight_temporary'], 'string'],
            [['note_by_customer', 'note', 'seller_store', 'purchase_order_id', 'purchase_transaction_id', 'purchase_amount', 'purchase_account_email', 'purchase_card', 'purchase_refund_transaction_id', 'total_weight', 'total_weight_temporary'], 'trim'],
            [['revenue_xu', 'xu_count', 'xu_amount', 'total_final_amount_local', 'total_paid_amount_local', 'total_refund_amount_local', 'total_amount_local', 'total_fee_amount_local', 'total_counpon_amount_local', 'total_promotion_amount_local', 'total_origin_fee_local', 'total_origin_tax_fee_local', 'total_origin_shipping_fee_local', 'total_weshop_fee_local', 'total_intl_shipping_fee_local', 'total_custom_fee_amount_local', 'total_delivery_fee_local', 'total_packing_fee_local', 'total_inspection_fee_local', 'total_insurance_fee_local', 'total_vat_amount_local', 'exchange_rate_fee', 'exchange_rate_purchase', 'purchase_amount_buck', 'purchase_amount_refund'], 'number'],
            [['revenue_xu', 'xu_count', 'xu_amount', 'total_final_amount_local', 'total_paid_amount_local', 'total_refund_amount_local', 'total_amount_local', 'total_fee_amount_local', 'total_counpon_amount_local', 'total_promotion_amount_local', 'total_origin_fee_local', 'total_origin_tax_fee_local', 'total_origin_shipping_fee_local', 'total_weshop_fee_local', 'total_intl_shipping_fee_local', 'total_custom_fee_amount_local', 'total_delivery_fee_local', 'total_packing_fee_local', 'total_inspection_fee_local', 'total_insurance_fee_local', 'total_vat_amount_local', 'exchange_rate_fee', 'exchange_rate_purchase', 'purchase_amount_buck', 'purchase_amount_refund'], 'match', 'pattern' => '/^[0-9]*$/'],
            [['type_order', 'portal', 'quotation_note', 'receiver_email', 'receiver_name', 'receiver_phone', 'receiver_address', 'receiver_country_name', 'receiver_province_name', 'receiver_district_name', 'receiver_post_code', 'payment_type', 'support_email', 'coupon_id', 'coupon_code', 'utm_source', 'seller_name', 'currency_purchase'], 'string', 'max' => 255],
            [['current_status'], 'string', 'max' => 200],
            [['quotation_status', 'difference_money'], 'in', 'range' => [0, 1, 2]],
            [['receiver_email', 'support_email', 'purchase_account_email'], 'email'],
            [['seller_store'], 'url'],
            [['note_by_customer', 'note', 'purchase_order_id', 'purchase_transaction_id', 'purchase_amount', 'purchase_card', 'purchase_account_email', 'purchase_refund_transaction_id', 'total_weight', 'total_weight_temporary'], 'filter','filter' => '\yii\helpers\Html::encode',],
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

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'type_order' => 'Type Order',
            'portal' => 'Portal',
            'is_quotation' => 'Is Quotation',
            'quotation_status' => 'Quotation Status',
            'quotation_note' => 'Quotation Note',
            'customer_id' => 'Customer ID',
            'receiver_email' => 'Receiver Email',
            'receiver_name' => 'Receiver Name',
            'receiver_phone' => 'Receiver Phone',
            'receiver_address' => 'Receiver Address',
            'receiver_country_id' => 'Receiver Country ID',
            'receiver_country_name' => 'Receiver Country Name',
            'receiver_province_id' => 'Receiver Province ID',
            'receiver_province_name' => 'Receiver Province Name',
            'receiver_district_id' => 'Receiver District ID',
            'receiver_district_name' => 'Receiver District Name',
            'receiver_post_code' => 'Receiver Post Code',
            'receiver_address_id' => 'Receiver Address ID',
            'note_by_customer' => 'Note By Customer',
            'note' => 'Note',
            'payment_type' => 'Payment Type',
            'sale_support_id' => 'Sale Support ID',
            'support_email' => 'Support Email',
            'coupon_id' => 'Coupon ID',
            'coupon_code' => 'Coupon Code',
            'coupon_time' => 'Coupon Time',
            'revenue_xu' => 'Revenue Xu',
            'xu_count' => 'Xu Count',
            'xu_amount' => 'Xu Amount',
            'is_email_sent' => 'Is Email Sent',
            'is_sms_sent' => 'Is Sms Sent',
            'total_quantity' => 'Total Quantity',
            'promotion_id' => 'Promotion ID',
            'difference_money' => 'Difference Money',
            'utm_source' => 'Utm Source',
            'seller_id' => 'Seller ID',
            'seller_name' => 'Seller Name',
            'seller_store' => 'Seller Store',
            'total_final_amount_local' => 'Total Final Amount Local',
            'total_paid_amount_local' => 'Total Paid Amount Local',
            'total_refund_amount_local' => 'Total Refund Amount Local',
            'total_amount_local' => 'Total Amount Local',
            'total_fee_amount_local' => 'Total Fee Amount Local',
            'total_counpon_amount_local' => 'Total Counpon Amount Local',
            'total_promotion_amount_local' => 'Total Promotion Amount Local',
            'total_origin_fee_local' => 'Total Origin Fee Local',
            'total_origin_tax_fee_local' => 'Total Origin Tax Fee Local',
            'total_origin_shipping_fee_local' => 'Total Origin Shipping Fee Local',
            'total_weshop_fee_local' => 'Total Weshop Fee Local',
            'total_intl_shipping_fee_local' => 'Total Intl Shipping Fee Local',
            'total_custom_fee_amount_local' => 'Total Custom Fee Amount Local',
            'total_delivery_fee_local' => 'Total Delivery Fee Local',
            'total_packing_fee_local' => 'Total Packing Fee Local',
            'total_inspection_fee_local' => 'Total Inspection Fee Local',
            'total_insurance_fee_local' => 'Total Insurance Fee Local',
            'total_vat_amount_local' => 'Total Vat Amount Local',
            'exchange_rate_fee' => 'Exchange Rate Fee',
            'exchange_rate_purchase' => 'Exchange Rate Purchase',
            'currency_purchase' => 'Currency Purchase',
            'purchase_order_id' => 'Purchase Order ID',
            'purchase_transaction_id' => 'Purchase Transaction ID',
            'purchase_amount' => 'Purchase Amount',
            'purchase_account_id' => 'Purchase Account ID',
            'purchase_account_email' => 'Purchase Account Email',
            'purchase_card' => 'Purchase Card',
            'purchase_amount_buck' => 'Purchase Amount Buck',
            'purchase_amount_refund' => 'Purchase Amount Refund',
            'purchase_refund_transaction_id' => 'Purchase Refund Transaction ID',
            'total_weight' => 'Total Weight',
            'total_weight_temporary' => 'Total Weight Temporary',
            'new' => 'New',
            'purchased' => 'Purchased',
            'seller_shipped' => 'Seller Shipped',
            'stockin_us' => 'Stockin Us',
            'stockout_us' => 'Stockout Us',
            'stockin_local' => 'Stockin Local',
            'stockout_local' => 'Stockout Local',
            'at_customer' => 'At Customer',
            'returned' => 'Returned',
            'cancelled' => 'Cancelled',
            'lost' => 'Lost',
            'current_status' => 'Current Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'remove' => 'Remove',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiverAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'receiver_address_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiverCountry()
    {
        return $this->hasOne(SystemCountry::className(), ['id' => 'receiver_country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiverDistrict()
    {
        return $this->hasOne(SystemDistrict::className(), ['id' => 'receiver_district_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiverProvince()
    {
        return $this->hasOne(SystemStateProvince::className(), ['id' => 'receiver_province_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleSupport()
    {
        return $this->hasOne(User::className(), ['id' => 'sale_support_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeller()
    {
        return $this->hasOne(Seller::className(), ['id' => 'seller_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderFees()
    {
        return $this->hasMany(OrderFee::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackageItems()
    {
        return $this->hasMany(PackageItem::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWalletTransactions()
    {
        return $this->hasMany(WalletTransaction::className(), ['order_id' => 'id']);
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
