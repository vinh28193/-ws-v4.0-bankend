<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $id ID
 * @property int $store_id hàng của nước nào Weshop Indo hay Weshop VIET NAM
 * @property string $type_order Hình thức mua hàng: SHOP | REQUEST | POS | SHIP
 * @property int $customer_id  Mã id của customer : có thể là khách buôn hoặc khách lẻ 
 * @property string $customer_type  Mã id của customer : Retail Customer : Khách lẻ . Wholesale customers 
 * @property string $portal portal ebay, amazon us, amazon jp ...: EBAY/ AMAZON_US / AMAZON_JAPAN / OTHER / WEBSITE NGOÀI 
 * @property string $utm_source Đơn theo viết được tạo ra bới chiến dịch nào : Facebook ads, Google ads , eomobi , etc ,,,, 
 * @property string $new time NEW
 * @property string $purchased time PURCHASED
 * @property string $seller_shipped time SELLER_SHIPPED
 * @property string $stockin_us time STOCKIN_US
 * @property string $stockout_us time STOCKOUT_US
 * @property string $stockin_local time STOCKIN_LOCAL
 * @property string $stockout_local time STOCKOUT_LOCAL
 * @property string $at_customer time AT_CUSTOMER
 * @property string $returned time RETURNED : null
 * @property string $cancelled  time CANCELLED : null :  Đơn hàng đã  thanh toán --> thì hoàn  tiền ; Đơn hàng chưa thanh toán --> thì Hủy
 * @property string $lost  time LOST : null : Hàng mất ở kho Mỹ hoặc hải quan hoặc kho VN hoặc trên đường giao cho KH 
 * @property string $current_status Trạng thái hiện tại của order : update theo trạng thái của sản phẩm cuối 
 * @property int $is_quotation Đánh dấu đơn báo giá
 * @property int $quotation_status Duyệt đơn báo giá nên đơn có Trạng thái báo giá. null : là hàng SHOP ,  0 - pending, 1- approve, 2- deny
 * @property string $quotation_note note đơn request
 * @property string $receiver_email Email người nhận
 * @property string $receiver_name Họ tên người nhận
 * @property string $receiver_phone Số điện thoại người nhận
 * @property string $receiver_address Địa chỉ người nhận
 * @property int $receiver_country_id Mã Country người nhận
 * @property string $receiver_country_name Country người nhận
 * @property int $receiver_province_id  mã Tỉnh thành người nhận
 * @property string $receiver_province_name Tên Tỉnh thành người nhận
 * @property int $receiver_district_id Mã Quận huyện người nhận
 * @property string $receiver_district_name  Tên Quận huyện người nhận
 * @property string $receiver_post_code  Mã bưu điện người nhận
 * @property int $receiver_address_id id address của người nhận trong bảng address
 * @property string $note_by_customer Ghi chú của customer hoặc ghi chú cho người nhận 
 * @property string $note Ghi chú cho đơn hàng
 * @property int $seller_id Mã người bán 
 * @property string $seller_name Tên người bán
 * @property string $seller_store Link shop của người bán
 * @property string $total_final_amount_local  Tổng giá trị đơn hàng ( Số tiền đã trừ đi giảm giá ) : số tiền cuối cùng khách hàng phải thanh toán và tính theo tiền local
 * @property string $total_amount_local  Tổng giá trị đơn hàng : Số tiền chưa tính giảm giá 
 * @property string $total_origin_fee_local Tổng phí gốc tại xuất xứ (Tiền Local)
 * @property string $total_price_amount_origin  Tổng Tiền Hàng ( Theo tiền ngoại tê của EBAY / AMAZON  / WEBSITE NGOÀI) : Tổng giá tiền gốc các item theo ngoại tệ 
 * @property string $total_paid_amount_local Tổng số tiền khách hàng đã thanh toán : Theo tiền local 
 * @property string $total_refund_amount_local số tiền đã hoàn trả cho khách hàng : Theo tiền local
 * @property string $total_counpon_amount_local Tổng số tiền giảm giá bằng mã counpon . Ví dụ MÃ VALENTIN200 áp dụng cho khách hàng mới 
 * @property string $total_promotion_amount_local Tổng số tiền giảm giá do promotion . Vi Dụ : Chương trình giảm giá trừ 200.000 VNĐ cho cả đơn 
 * @property string $total_fee_amount_local tổng phí đơn hàng
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
 * @property string $exchange_rate_fee  Tỉ Giá Tính Phí Local : áp dung theo tỉ giá của VietCombank Crowler upate từ 1 bảng systeam_curentcy : Tỷ giá từ USD => tiền local
 * @property string $exchange_rate_purchase Tỉ Giá mua hàng : áp dung theo tỉ giá của VietCombank , Ẩn với Khách. Tỉ giá USD / Tỉ giá Yên / Tỉ giá UK .Tỷ giá từ tiền website gốc => tiền local. VD: yên => vnd
 * @property string $currency_purchase  Loại tiền mua hàng là : USD,JPY,AUD .....
 * @property string $payment_type hinh thuc thanh toan. -online_payment, 'VT'...
 * @property int $sale_support_id Người support đơn hàng
 * @property string $support_email email người support
 * @property int $is_email_sent  đánh đâu đơn này đã được gửi email tạo thành công đơn hàng
 * @property int $is_sms_sent đánh đâu đơn này đã được gửi SMS tạo thành công đơn hàng
 * @property int $difference_money 0: mac dinh, 1: lech, 2:ẩn thông báo bằng quyền của Admin
 * @property string $coupon_id  id mã giảm giá
 * @property string $revenue_xu số xu được nhận
 * @property string $xu_count số xu sử dụng
 * @property string $xu_amount giá trị quy đổi ra tiền
 * @property string $xu_time thời gian mốc sử dụng mã xu  
 * @property string $xu_log trừ từ xu đang có vào đơn , Quy chế sinh ra xu là khách hàng nhận được hàng thành công mới tự động sinh ra xu 
 * @property string $promotion_id id của promotion : Id Chạy chương trình promotion
 * @property string $total_weight
 * @property string $total_weight_temporary
 * @property string $created_at Update qua behaviors tự động  
 * @property string $updated_at Update qua behaviors tự động
 * @property string $purchase_order_id Mã order đặt mua với NB là EBAY / AMAZON / hoặc Website ngoài : mã order purchase ( dạng list, cách nhau = dấu phẩy)
 * @property string $purchase_transaction_id Mã thanh toán Paypal với eBay, amazon thanh toán bằng thẻ, k lấy được mã giao dịch ( dạng list, cách nhau = dấu phẩy)
 * @property string $purchase_amount số tiền thanh toán thực tế với người bán EBAY/AMAZON, lưu ý : Số đã trừ Buck/Point ( và là dạng list, cách nhau = dấu phẩy)
 * @property string $purchase_account_id id tài khoản mua hàng
 * @property string $purchase_account_email email tài khoản mua hàng
 * @property string $purchase_card thẻ thanh toán
 * @property string $purchase_amount_buck số tiền buck thanh toán
 * @property string $purchase_amount_refund số tiền người bán hoàn
 * @property string $purchase_refund_transaction_id mã giao dịch hoàn
 * @property int $total_quantity  Tổng số lượng khách hàng đặt = tổng các số lượng trên bảng product
 * @property int $total_purchase_quantity  Tổng số lượng nhân viên đi mua hàng thực tế của cả đơn = tổng các số lượng mua thực tế trên bảng product
 * @property int $remove đơn đánh đấu 1 là đã xóa , mặc định 0 : chưa xóa
 * @property string $ordercode
 * @property string $version version 4.0
 */
class Order extends \common\components\db\ActiveRecord
{
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
            [['store_id', 'type_order', 'customer_id', 'customer_type', 'portal', 'receiver_email', 'receiver_name', 'receiver_phone', 'receiver_address', 'receiver_country_id', 'receiver_country_name', 'receiver_province_id', 'receiver_province_name', 'receiver_district_id', 'receiver_district_name', 'receiver_post_code', 'receiver_address_id', 'payment_type'], 'required'],
            [['store_id', 'customer_id', 'new', 'purchased', 'seller_shipped', 'stockin_us', 'stockout_us', 'stockin_local', 'stockout_local', 'at_customer', 'returned', 'cancelled', 'lost', 'is_quotation', 'quotation_status', 'receiver_country_id', 'receiver_province_id', 'receiver_district_id', 'receiver_address_id', 'seller_id', 'sale_support_id', 'is_email_sent', 'is_sms_sent', 'difference_money', 'coupon_id', 'xu_time', 'promotion_id', 'created_at', 'updated_at', 'total_quantity', 'total_purchase_quantity', 'remove'], 'integer'],
            [['note_by_customer', 'note', 'seller_store', 'purchase_order_id', 'purchase_transaction_id', 'purchase_account_id', 'purchase_account_email', 'purchase_card', 'purchase_refund_transaction_id'], 'string'],
            [['total_final_amount_local', 'total_amount_local', 'total_origin_fee_local', 'total_price_amount_origin', 'total_paid_amount_local', 'total_refund_amount_local', 'total_counpon_amount_local', 'total_promotion_amount_local', 'total_fee_amount_local', 'total_origin_tax_fee_local', 'total_origin_shipping_fee_local', 'total_weshop_fee_local', 'total_intl_shipping_fee_local', 'total_custom_fee_amount_local', 'total_delivery_fee_local', 'total_packing_fee_local', 'total_inspection_fee_local', 'total_insurance_fee_local', 'total_vat_amount_local', 'exchange_rate_fee', 'exchange_rate_purchase', 'revenue_xu', 'xu_count', 'xu_amount', 'total_weight', 'total_weight_temporary', 'purchase_amount', 'purchase_amount_buck', 'purchase_amount_refund'], 'number'],
            [['type_order', 'portal', 'utm_source', 'quotation_note', 'receiver_email', 'receiver_name', 'receiver_phone', 'receiver_address', 'receiver_country_name', 'receiver_province_name', 'receiver_district_name', 'receiver_post_code', 'seller_name', 'currency_purchase', 'payment_type', 'support_email', 'xu_log', 'ordercode', 'version'], 'string', 'max' => 255],
            [['customer_type'], 'string', 'max' => 11],
            [['current_status'], 'string', 'max' => 200],
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
            'customer_id' => 'Customer ID',
            'customer_type' => 'Customer Type',
            'portal' => 'Portal',
            'utm_source' => 'Utm Source',
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
            'is_quotation' => 'Is Quotation',
            'quotation_status' => 'Quotation Status',
            'quotation_note' => 'Quotation Note',
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
            'seller_id' => 'Seller ID',
            'seller_name' => 'Seller Name',
            'seller_store' => 'Seller Store',
            'total_final_amount_local' => 'Total Final Amount Local',
            'total_amount_local' => 'Total Amount Local',
            'total_origin_fee_local' => 'Total Origin Fee Local',
            'total_price_amount_origin' => 'Total Price Amount Origin',
            'total_paid_amount_local' => 'Total Paid Amount Local',
            'total_refund_amount_local' => 'Total Refund Amount Local',
            'total_counpon_amount_local' => 'Total Counpon Amount Local',
            'total_promotion_amount_local' => 'Total Promotion Amount Local',
            'total_fee_amount_local' => 'Total Fee Amount Local',
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
            'payment_type' => 'Payment Type',
            'sale_support_id' => 'Sale Support ID',
            'support_email' => 'Support Email',
            'is_email_sent' => 'Is Email Sent',
            'is_sms_sent' => 'Is Sms Sent',
            'difference_money' => 'Difference Money',
            'coupon_id' => 'Coupon ID',
            'revenue_xu' => 'Revenue Xu',
            'xu_count' => 'Xu Count',
            'xu_amount' => 'Xu Amount',
            'xu_time' => 'Xu Time',
            'xu_log' => 'Xu Log',
            'promotion_id' => 'Promotion ID',
            'total_weight' => 'Total Weight',
            'total_weight_temporary' => 'Total Weight Temporary',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'purchase_order_id' => 'Purchase Order ID',
            'purchase_transaction_id' => 'Purchase Transaction ID',
            'purchase_amount' => 'Purchase Amount',
            'purchase_account_id' => 'Purchase Account ID',
            'purchase_account_email' => 'Purchase Account Email',
            'purchase_card' => 'Purchase Card',
            'purchase_amount_buck' => 'Purchase Amount Buck',
            'purchase_amount_refund' => 'Purchase Amount Refund',
            'purchase_refund_transaction_id' => 'Purchase Refund Transaction ID',
            'total_quantity' => 'Total Quantity',
            'total_purchase_quantity' => 'Total Purchase Quantity',
            'remove' => 'Remove',
            'ordercode' => 'Ordercode',
            'version' => 'Version',
        ];
    }
}
