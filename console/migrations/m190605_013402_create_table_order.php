<?php

use yii\db\Migration;

class m190605_013402_create_table_order extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%order}}', [
            'id' => $this->integer(11)->notNull()->append('AUTO_INCREMENT PRIMARY KEY')->comment('ID'),
            'ordercode' => $this->string(255)->comment('ordercode : BIN Code Weshop : WSVN , WSINDO'),
            'store_id' => $this->integer(11)->notNull()->comment('hàng của nước nào Weshop Indo hay Weshop VIET NAM'),
            'type_order' => $this->string(255)->notNull()->comment('Hình thức mua hàng: SHOP | REQUEST | POS | SHIP'),
            'customer_id' => $this->integer(11)->notNull()->comment(' Mã id của customer : có thể là khách buôn hoặc khách lẻ '),
            'customer_type' => $this->string(11)->notNull()->comment(' Mã id của customer : Retail Customer : Khách lẻ . Wholesale customers '),
            'portal' => $this->string(255)->notNull()->comment('portal ebay, amazon us, amazon jp ...: EBAY/ AMAZON_US / AMAZON_JAPAN / OTHER / WEBSITE NGOÀI '),
            'utm_source' => $this->string(255)->comment('Đơn theo viết được tạo ra bới chiến dịch nào : Facebook ads, Google ads , eomobi , etc ,,,, '),
            'new' => $this->bigInteger(20)->comment('time NEW'),
            'purchase_start' => $this->bigInteger(20),
            'purchased' => $this->bigInteger(20)->comment('time PURCHASED'),
            'seller_shipped' => $this->bigInteger(20)->comment('time SELLER_SHIPPED'),
            'stockin_us' => $this->bigInteger(20)->comment('time STOCKIN_US'),
            'stockout_us' => $this->bigInteger(20)->comment('time STOCKOUT_US'),
            'stockin_local' => $this->bigInteger(20)->comment('time STOCKIN_LOCAL'),
            'stockout_local' => $this->bigInteger(20)->comment('time STOCKOUT_LOCAL'),
            'at_customer' => $this->bigInteger(20)->comment('time AT_CUSTOMER'),
            'returned' => $this->bigInteger(20)->comment('time RETURNED : null'),
            'cancelled' => $this->bigInteger(20)->comment(' time CANCELLED : null :  Đơn hàng đã  thanh toán --> thì hoàn  tiền ; Đơn hàng chưa thanh toán --> thì Hủy'),
            'lost' => $this->bigInteger(20)->comment(' time LOST : null : Hàng mất ở kho Mỹ hoặc hải quan hoặc kho VN hoặc trên đường giao cho KH '),
            'current_status' => $this->string(200)->comment('Trạng thái hiện tại của order : update theo trạng thái của sản phẩm cuối '),
            'is_quotation' => $this->tinyInteger(4)->comment('Đánh dấu đơn báo giá'),
            'quotation_status' => $this->tinyInteger(4)->comment('Duyệt đơn báo giá nên đơn có Trạng thái báo giá. null : là hàng SHOP ,  0 - pending, 1- approve, 2- deny'),
            'quotation_note' => $this->string(255)->comment('note đơn request'),
            'receiver_email' => $this->string(255)->notNull()->comment('Email người nhận'),
            'receiver_name' => $this->string(255)->notNull()->comment('Họ tên người nhận'),
            'receiver_phone' => $this->string(255)->notNull()->comment('Số điện thoại người nhận'),
            'receiver_address' => $this->string(255)->notNull()->comment('Địa chỉ người nhận'),
            'receiver_country_id' => $this->integer(11)->notNull()->comment('Mã Country người nhận'),
            'receiver_country_name' => $this->string(255)->notNull()->comment('Country người nhận'),
            'receiver_province_id' => $this->integer(11)->notNull()->comment(' mã Tỉnh thành người nhận'),
            'receiver_province_name' => $this->string(255)->notNull()->comment('Tên Tỉnh thành người nhận'),
            'receiver_district_id' => $this->integer(11)->notNull()->comment('Mã Quận huyện người nhận'),
            'receiver_district_name' => $this->string(255)->notNull()->comment(' Tên Quận huyện người nhận'),
            'receiver_post_code' => $this->string(255)->comment(' Mã bưu điện người nhận'),
            'receiver_address_id' => $this->integer(11)->notNull()->comment('id address của người nhận trong bảng address'),
            'note_by_customer' => $this->text()->comment('Ghi chú của customer hoặc ghi chú cho người nhận '),
            'note' => $this->text()->comment('Ghi chú cho đơn hàng'),
            'seller_id' => $this->integer(11)->comment('Mã người bán '),
            'seller_name' => $this->string(255)->comment('Tên người bán'),
            'seller_store' => $this->text()->comment('Link shop của người bán'),
            'total_final_amount_local' => $this->decimal(18, 2)->comment(' Tổng giá trị đơn hàng ( Số tiền đã trừ đi giảm giá ) : số tiền cuối cùng khách hàng phải thanh toán và tính theo tiền local'),
            'total_amount_local' => $this->decimal(18, 2)->comment(' Tổng giá trị đơn hàng : Số tiền chưa tính giảm giá '),
            'total_origin_fee_local' => $this->decimal(18, 2)->comment('Tổng phí gốc tại xuất xứ (Tiền Local)'),
            'total_price_amount_origin' => $this->decimal(18, 2)->comment(' Tổng Tiền Hàng ( Theo tiền ngoại tê của EBAY / AMAZON  / WEBSITE NGOÀI) : Tổng giá tiền gốc các item theo ngoại tệ '),
            'total_paid_amount_local' => $this->decimal(18, 2)->comment('Tổng số tiền khách hàng đã thanh toán : Theo tiền local '),
            'total_refund_amount_local' => $this->decimal(18, 2)->comment('số tiền đã hoàn trả cho khách hàng : Theo tiền local'),
            'total_counpon_amount_local' => $this->decimal(18, 2)->comment('Tổng số tiền giảm giá bằng mã counpon . Ví dụ MÃ VALENTIN200 áp dụng cho khách hàng mới '),
            'total_promotion_amount_local' => $this->decimal(18, 2)->comment('Tổng số tiền giảm giá do promotion . Vi Dụ : Chương trình giảm giá trừ 200.000 VNĐ cho cả đơn '),
            'total_fee_amount_local' => $this->decimal(18, 2)->comment('tổng phí đơn hàng'),
            'total_origin_tax_fee_local' => $this->decimal(18, 2)->comment('Tổng phí tax tại xuất xứ'),
            'total_origin_shipping_fee_local' => $this->decimal(18, 2)->comment('Tổng phí vận chuyển tại xuất xứ'),
            'total_weshop_fee_local' => $this->decimal(18, 2)->comment('Tổng phí Weshop'),
            'total_intl_shipping_fee_local' => $this->decimal(18, 2)->comment('Tổng phí vận chuyển quốc tế'),
            'total_custom_fee_amount_local' => $this->decimal(18, 2)->comment('Tổng phí phụ thu'),
            'total_delivery_fee_local' => $this->decimal(18, 2)->comment('Tổng phí vận chuyển nội địa'),
            'total_packing_fee_local' => $this->decimal(18, 2)->comment('Tống phí hàng'),
            'total_inspection_fee_local' => $this->decimal(18, 2)->comment('Tổng phí kiểm hàng'),
            'total_insurance_fee_local' => $this->decimal(18, 2)->comment('Tổng phí bảo hiểm'),
            'total_vat_amount_local' => $this->decimal(18, 2)->comment('Tổng phí VAT'),
            'exchange_rate_fee' => $this->decimal(18, 2)->comment(' Tỉ Giá Tính Phí Local : áp dung theo tỉ giá của VietCombank Crowler upate từ 1 bảng systeam_curentcy : Tỷ giá từ USD => tiền local'),
            'exchange_rate_purchase' => $this->decimal(18, 2)->comment('Tỉ Giá mua hàng : áp dung theo tỉ giá của VietCombank , Ẩn với Khách. Tỉ giá USD / Tỉ giá Yên / Tỉ giá UK .Tỷ giá từ tiền website gốc => tiền local. VD: yên => vnd'),
            'currency_purchase' => $this->string(255)->comment(' Loại tiền mua hàng là : USD,JPY,AUD .....'),
            'payment_type' => $this->string(255)->notNull()->comment('hinh thuc thanh toan. -online_payment, \'VT\'...'),
            'transaction_code' => $this->string(32),
            'sale_support_id' => $this->integer(11)->comment('Người support đơn hàng'),
            'support_email' => $this->string(255)->comment('email người support'),
            'is_email_sent' => $this->tinyInteger(1)->comment(' đánh đâu đơn này đã được gửi email tạo thành công đơn hàng'),
            'is_sms_sent' => $this->tinyInteger(1)->comment('đánh đâu đơn này đã được gửi SMS tạo thành công đơn hàng'),
            'difference_money' => $this->tinyInteger(1)->comment('0: mac dinh, 1: lech, 2:ẩn thông báo bằng quyền của Admin'),
            'coupon_id' => $this->bigInteger(20)->comment(' id mã giảm giá'),
            'revenue_xu' => $this->decimal(18, 2)->comment('số xu được nhận'),
            'xu_count' => $this->decimal(18, 2)->comment('số xu sử dụng'),
            'xu_amount' => $this->decimal(18, 2)->comment('giá trị quy đổi ra tiền'),
            'xu_time' => $this->bigInteger(20)->comment('thời gian mốc sử dụng mã xu  '),
            'xu_log' => $this->string(255)->comment('trừ từ xu đang có vào đơn , Quy chế sinh ra xu là khách hàng nhận được hàng thành công mới tự động sinh ra xu '),
            'promotion_id' => $this->bigInteger(20)->comment('id của promotion : Id Chạy chương trình promotion'),
            'total_weight' => $this->decimal(18, 2),
            'total_weight_temporary' => $this->decimal(18, 2),
            'created_at' => $this->bigInteger(20)->comment('Update qua behaviors tự động  '),
            'updated_at' => $this->bigInteger(20)->comment('Update qua behaviors tự động'),
            'purchase_assignee_id' => $this->integer(11)->comment('Id nhân viên mua hàng'),
            'purchase_order_id' => $this->text()->comment('Mã order đặt mua với NB là EBAY / AMAZON / hoặc Website ngoài : mã order purchase ( dạng list, cách nhau = dấu phẩy)'),
            'purchase_transaction_id' => $this->text()->comment('Mã thanh toán Paypal với eBay, amazon thanh toán bằng thẻ, k lấy được mã giao dịch ( dạng list, cách nhau = dấu phẩy)'),
            'purchase_amount' => $this->decimal(18, 2)->comment('số tiền thanh toán thực tế với người bán EBAY/AMAZON, lưu ý : Số đã trừ Buck/Point ( và là dạng list, cách nhau = dấu phẩy)'),
            'purchase_account_id' => $this->text()->comment('id tài khoản mua hàng'),
            'purchase_account_email' => $this->text()->comment('email tài khoản mua hàng'),
            'purchase_card' => $this->text()->comment('thẻ thanh toán'),
            'purchase_amount_buck' => $this->decimal(18, 2)->comment('số tiền buck thanh toán'),
            'purchase_amount_refund' => $this->decimal(18, 2)->comment('số tiền người bán hoàn'),
            'purchase_refund_transaction_id' => $this->text()->comment('mã giao dịch hoàn'),
            'total_quantity' => $this->integer(11)->comment(' Tổng số lượng khách hàng đặt = tổng các số lượng trên bảng product'),
            'total_purchase_quantity' => $this->integer(11)->comment(' Tổng số lượng nhân viên đi mua hàng thực tế của cả đơn = tổng các số lượng mua thực tế trên bảng product'),
            'remove' => $this->tinyInteger(4)->comment('đơn đánh đấu 1 là đã xóa , mặc định 0 : chưa xóa'),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
            'mark_supporting' => $this->bigInteger(20),
            'supported' => $this->bigInteger(20),
            'ready_purchase' => $this->bigInteger(20),
            'supporting' => $this->bigInteger(20),
            'check_update_payment' => $this->integer(11),
            'confirm_change_price' => $this->integer(11)->comment('0: là không có thay đổi giá hoặc có thay đổi nhưng đã confirm. 1: là có thay đổi cần xác nhận'),
        ], $tableOptions);

        $this->createIndex('idx-order-coupon_id', '{{%order}}', 'coupon_id');
        $this->createIndex('idx-order-sale_support_id', '{{%order}}', 'sale_support_id');
        $this->createIndex('idx-order-receiver_district_id', '{{%order}}', 'receiver_district_id');
        $this->createIndex('idx-order-receiver_country_id', '{{%order}}', 'receiver_country_id');
        $this->createIndex('idx-order-store_id', '{{%order}}', 'store_id');
        $this->createIndex('idx-order-promotion_id', '{{%order}}', 'promotion_id');
        $this->createIndex('idx-order-seller_id', '{{%order}}', 'seller_id');
        $this->createIndex('idx-order-receiver_address_id', '{{%order}}', 'receiver_address_id');
        $this->createIndex('idx-order-receiver_province_id', '{{%order}}', 'receiver_province_id');
        $this->createIndex('idx-order-customer_id', '{{%order}}', 'customer_id');
        $this->addForeignKey('fk-order-seller_id', '{{%order}}', 'seller_id', '{{%seller}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-order-store_id', '{{%order}}', 'store_id', '{{%store}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-order-user', '{{%order}}', 'purchase_assignee_id', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('fk-order-customer_id', '{{%order}}', 'customer_id', '{{%customer}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-order-sale_support_id', '{{%order}}', 'sale_support_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%order}}');
    }
}
