<?php

use yii\db\Migration;

class m190606_042043_create_table_WS_ORDER extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%ORDER}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'ordercode' => $this->string(255)->comment('ordercode : BIN Code Weshop : WSVN , WSINDO'),
            'store_id' => $this->integer()->notNull()->comment('hang c?a n??c nao Weshop Indo hay Weshop VIET NAM'),
            'type_order' => $this->string(255)->notNull()->comment('Hinh th?c mua hang: SHOP | REQUEST | POS | SHIP'),
            'customer_id' => $this->integer()->notNull()->comment(' M? id c?a customer : co th? la khach buon ho?c khach l? '),
            'customer_type' => $this->string(11)->notNull()->comment(' M? id c?a customer : Retail Customer : Khach l? . Wholesale customers '),
            'portal' => $this->string(255)->notNull()->comment('portal ebay, amazon us, amazon jp ...: EBAY/ AMAZON_US / AMAZON_JAPAN / OTHER / WEBSITE NGOAI '),
            'utm_source' => $this->string(255)->comment('D?n theo vi?t d??c t?o ra b?i chi?n d?ch nao : Facebook ads, Google ads , eomobi , etc ,,,, '),
            'new' => $this->integer()->comment('time NEW'),
            'purchase_start' => $this->integer(),
            'purchased' => $this->integer()->comment('time PURCHASED'),
            'seller_shipped' => $this->integer()->comment('time SELLER_SHIPPED'),
            'stockin_us' => $this->integer()->comment('time STOCKIN_US'),
            'stockout_us' => $this->integer()->comment('time STOCKOUT_US'),
            'stockin_local' => $this->integer()->comment('time STOCKIN_LOCAL'),
            'stockout_local' => $this->integer()->comment('time STOCKOUT_LOCAL'),
            'at_customer' => $this->integer()->comment('time AT_CUSTOMER'),
            'returned' => $this->integer()->comment('time RETURNED : null'),
            'cancelled' => $this->integer()->comment(' time CANCELLED : null :  D?n hang d?  thanh toan --> thi hoan  ti?n ; D?n hang ch?a thanh toan --> thi H?y'),
            'lost' => $this->integer()->comment(' time LOST : null : Hang m?t ? kho M? ho?c h?i quan ho?c kho VN ho?c tren d??ng giao cho KH '),
            'current_status' => $this->string(200)->comment('Tr?ng thai hi?n t?i c?a order : update theo tr?ng thai c?a s?n ph?m cu?i '),
            'is_quotation' => $this->integer()->comment('Danh d?u d?n bao gia'),
            'quotation_status' => $this->integer()->comment('Duy?t d?n bao gia nen d?n co Tr?ng thai bao gia. null : la hang SHOP ,  0 - pending, 1- approve, 2- deny'),
            'quotation_note' => $this->string(255)->comment('note d?n request'),
            'receiver_email' => $this->string(255)->notNull()->comment('Email ng??i nh?n'),
            'receiver_name' => $this->string(255)->notNull()->comment('H? ten ng??i nh?n'),
            'receiver_phone' => $this->string(255)->notNull()->comment('S? di?n tho?i ng??i nh?n'),
            'receiver_address' => $this->string(255)->notNull()->comment('D?a ch? ng??i nh?n'),
            'receiver_country_id' => $this->integer()->notNull()->comment('M? Country ng??i nh?n'),
            'receiver_country_name' => $this->string(255)->notNull()->comment('Country ng??i nh?n'),
            'receiver_province_id' => $this->integer()->notNull()->comment(' m? T?nh thanh ng??i nh?n'),
            'receiver_province_name' => $this->string(255)->notNull()->comment('Ten T?nh thanh ng??i nh?n'),
            'receiver_district_id' => $this->integer()->notNull()->comment('M? Qu?n huy?n ng??i nh?n'),
            'receiver_district_name' => $this->string(255)->notNull()->comment(' Ten Qu?n huy?n ng??i nh?n'),
            'receiver_post_code' => $this->string(255)->comment(' M? b?u di?n ng??i nh?n'),
            'receiver_address_id' => $this->integer()->notNull()->comment('id address c?a ng??i nh?n trong b?ng address'),
            'note_by_customer' => $this->text()->comment('Ghi chu c?a customer ho?c ghi chu cho ng??i nh?n '),
            'note' => $this->text()->comment('Ghi chu cho d?n hang'),
            'seller_id' => $this->integer()->comment('M? ng??i ban '),
            'seller_name' => $this->string(255)->comment('Ten ng??i ban'),
            'seller_store' => $this->text()->comment('Link shop c?a ng??i ban'),
            'total_final_amount_local' => $this->decimal()->comment(' T?ng gia tr? d?n hang ( S? ti?n d? tr? di gi?m gia ) : s? ti?n cu?i cung khach hang ph?i thanh toan va tinh theo ti?n local'),
            'total_amount_local' => $this->decimal()->comment(' T?ng gia tr? d?n hang : S? ti?n ch?a tinh gi?m gia '),
            'total_origin_fee_local' => $this->decimal()->comment('T?ng phi g?c t?i xu?t x? (Ti?n Local)'),
            'total_price_amount_origin' => $this->decimal()->comment(' T?ng Ti?n Hang ( Theo ti?n ngo?i te c?a EBAY / AMAZON  / WEBSITE NGOAI) : T?ng gia ti?n g?c cac item theo ngo?i t? '),
            'total_paid_amount_local' => $this->decimal()->comment('T?ng s? ti?n khach hang d? thanh toan : Theo ti?n local '),
            'total_refund_amount_local' => $this->decimal()->comment('s? ti?n d? hoan tr? cho khach hang : Theo ti?n local'),
            'total_counpon_amount_local' => $this->decimal()->comment('T?ng s? ti?n gi?m gia b?ng m? counpon . Vi d? M? VALENTIN200 ap d?ng cho khach hang m?i '),
            'total_promotion_amount_local' => $this->decimal()->comment('T?ng s? ti?n gi?m gia do promotion . Vi D? : Ch??ng trinh gi?m gia tr? 200.000 VND cho c? d?n '),
            'total_fee_amount_local' => $this->decimal()->comment('t?ng phi d?n hang'),
            'total_origin_tax_fee_local' => $this->decimal()->comment('T?ng phi tax t?i xu?t x?'),
            'total_origin_shipping_fee_local' => $this->decimal()->comment('T?ng phi v?n chuy?n t?i xu?t x?'),
            'total_weshop_fee_local' => $this->decimal()->comment('T?ng phi Weshop'),
            'total_intl_shipping_fee_local' => $this->decimal()->comment('T?ng phi v?n chuy?n qu?c t?'),
            'total_custom_fee_amount_local' => $this->decimal()->comment('T?ng phi ph? thu'),
            'total_delivery_fee_local' => $this->decimal()->comment('T?ng phi v?n chuy?n n?i d?a'),
            'total_packing_fee_local' => $this->decimal()->comment('T?ng phi hang'),
            'total_inspection_fee_local' => $this->decimal()->comment('T?ng phi ki?m hang'),
            'total_insurance_fee_local' => $this->decimal()->comment('T?ng phi b?o hi?m'),
            'total_vat_amount_local' => $this->decimal()->comment('T?ng phi VAT'),
            'exchange_rate_fee' => $this->decimal()->comment(' T? Gia Tinh Phi Local : ap dung theo t? gia c?a VietCombank Crowler upate t? 1 b?ng systeam_curentcy : T? gia t? USD => ti?n local'),
            'exchange_rate_purchase' => $this->decimal()->comment('T? Gia mua hang : ap dung theo t? gia c?a VietCombank , ?n v?i Khach. T? gia USD / T? gia Yen / T? gia UK .T? gia t? ti?n website g?c => ti?n local. VD: yen => vnd'),
            'currency_purchase' => $this->string(255)->comment(' Lo?i ti?n mua hang la : USD,JPY,AUD .....'),
            'payment_type' => $this->string(255)->notNull()->comment('hinh thuc thanh toan. -online_payment, \'VT\'...'),
            'transaction_code' => $this->string(32),
            'sale_support_id' => $this->integer()->comment('Ng??i support d?n hang'),
            'support_email' => $this->string(255)->comment('email ng??i support'),
            'is_email_sent' => $this->integer()->comment(' danh dau d?n nay d? d??c g?i email t?o thanh cong d?n hang'),
            'is_sms_sent' => $this->integer()->comment('danh dau d?n nay d? d??c g?i SMS t?o thanh cong d?n hang'),
            'difference_money' => $this->integer()->comment('0: mac dinh, 1: lech, 2:?n thong bao b?ng quy?n c?a Admin'),
            'coupon_id' => $this->integer()->comment(' id m? gi?m gia'),
            'revenue_xu' => $this->decimal()->comment('s? xu d??c nh?n'),
            'xu_count' => $this->decimal()->comment('s? xu s? d?ng'),
            'xu_amount' => $this->decimal()->comment('gia tr? quy d?i ra ti?n'),
            'xu_time' => $this->integer()->comment('th?i gian m?c s? d?ng m? xu  '),
            'xu_log' => $this->string(255)->comment('tr? t? xu dang co vao d?n , Quy ch? sinh ra xu la khach hang nh?n d??c hang thanh cong m?i t? d?ng sinh ra xu '),
            'promotion_id' => $this->integer()->comment('id c?a promotion : Id Ch?y ch??ng trinh promotion'),
            'total_weight' => $this->decimal(),
            'total_weight_temporary' => $this->decimal(),
            'created_at' => $this->integer()->comment('Update qua behaviors t? d?ng  '),
            'updated_at' => $this->integer()->comment('Update qua behaviors t? d?ng'),
            'purchase_assignee_id' => $this->integer()->comment('Id nhan vien mua hang'),
            'purchase_order_id' => $this->text()->comment('M? order d?t mua v?i NB la EBAY / AMAZON / ho?c Website ngoai : m? order purchase ( d?ng list, cach nhau = d?u ph?y)'),
            'purchase_transaction_id' => $this->text()->comment('M? thanh toan Paypal v?i eBay, amazon thanh toan b?ng th?, k l?y d??c m? giao d?ch ( d?ng list, cach nhau = d?u ph?y)'),
            'purchase_amount' => $this->decimal()->comment('s? ti?n thanh toan th?c t? v?i ng??i ban EBAY/AMAZON, l?u y : S? d? tr? Buck/Point ( va la d?ng list, cach nhau = d?u ph?y)'),
            'purchase_account_id' => $this->text()->comment('id tai kho?n mua hang'),
            'purchase_account_email' => $this->text()->comment('email tai kho?n mua hang'),
            'purchase_card' => $this->text()->comment('th? thanh toan'),
            'purchase_amount_buck' => $this->decimal()->comment('s? ti?n buck thanh toan'),
            'purchase_amount_refund' => $this->decimal()->comment('s? ti?n ng??i ban hoan'),
            'purchase_refund_transaction_id' => $this->text()->comment('m? giao d?ch hoan'),
            'total_quantity' => $this->integer()->comment(' T?ng s? l??ng khach hang d?t = t?ng cac s? l??ng tren b?ng product'),
            'total_purchase_quantity' => $this->integer()->comment(' T?ng s? l??ng nhan vien di mua hang th?c t? c?a c? d?n = t?ng cac s? l??ng mua th?c t? tren b?ng product'),
            'remove' => $this->integer()->comment('d?n danh d?u 1 la d? xoa , m?c d?nh 0 : ch?a xoa'),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
            'mark_supporting' => $this->integer(),
            'supported' => $this->integer(),
            'ready_purchase' => $this->integer(),
            'supporting' => $this->integer(),
            'check_update_payment' => $this->integer(),
            'confirm_change_price' => $this->integer()->comment('0: la khong co thay d?i gia ho?c co thay d?i nh?ng d? confirm. 1: la co thay d?i c?n xac nh?n'),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108722C00091$$', '{{%ORDER}}', '', true);
        $this->createIndex('SYS_IL0000108722C00086$$', '{{%ORDER}}', '', true);
        $this->createIndex('SYS_IL0000108722C00083$$', '{{%ORDER}}', '', true);
        $this->createIndex('SYS_IL0000108722C00038$$', '{{%ORDER}}', '', true);
        $this->createIndex('SYS_IL0000108722C00088$$', '{{%ORDER}}', '', true);
        $this->createIndex('SYS_IL0000108722C00037$$', '{{%ORDER}}', '', true);
        $this->createIndex('SYS_IL0000108722C00041$$', '{{%ORDER}}', '', true);
        $this->createIndex('SYS_IL0000108722C00084$$', '{{%ORDER}}', '', true);
        $this->createIndex('SYS_IL0000108722C00087$$', '{{%ORDER}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%ORDER}}');
    }
}
