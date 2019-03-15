<?php

namespace api\unit\models;

use common\models\Order;
use Codeception\Test\Unit;
use tests\fixtures\OrderFixture;

class OrderTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function _before()
    {
        $this->tester->haveFixtures([
            'order' => [
                'class' => OrderFixture::className(),
                'dataFile' => codecept_data_dir() . 'order.php'
            ]
        ]);
    }

//    public function testValidateEmpty()
//    {
//        $model = new Order();
//
//        expect('model should not validate', $model->validate())->false();
//
//        expect('type_order has error', $model->errors)->hasKey('type_order');
//        expect('is_quotation has error', $model->errors)->hasKey('is_quotation');
//    }

    public function testValidateCorrect()
    {
        $model = new Order([
            'store_id' => 1,
            'type_order' => 'SHOP',
            'customer_id' => 3,
            'customer_type' => 'Wholesale',
            'portal' => 'AMAZON_JAPAN',
            'utm_source' => null,
            'new' => 1346090982,
            'purchased' => null,
            'seller_shipped' => null,
            'stockin_us' => null,
            'stockout_us' => null,
            'stockin_local' => null,
            'stockout_local' => null,
            'at_customer' => null,
            'returned' => null,
            'cancelled' => null,
            'lost' => null,
            'current_status' => 'NEW',
            'is_quotation' => 0,
            'quotation_status' => null,
            'quotation_note' => null,
            'receiver_email' => 'than.chieu@yahoo.com',
            'receiver_name' => 'Lã Sương',
            'receiver_phone' => '84-510-113-8181',
            'receiver_address' => '1, Thôn Hà Sử, Xã Văn Diệu Chung, Quận 34
Ninh Bình',
            'receiver_country_id' => 1,
            'receiver_country_name' => 'Việt Nam',
            'receiver_province_id' => 99,
            'receiver_province_name' => 'Đà Nẵng',
            'receiver_district_id' => 1,
            'receiver_district_name' => 'Hồ Chí Minh',
            'receiver_post_code' => '02106',
            'receiver_address_id' => 4,
            'note_by_customer' => 'Queen, who had been all the unjust things--\'.',
            'note' => 'Cheshire cat,\' said the Mouse, who was a table.',
            'seller_id' => 2,
            'seller_name' => 'Stuart Friesen',
            'seller_store' => 'http://www.conn.com/',
            'total_final_amount_local' => 0,
            'total_amount_local' => 0,
            'total_origin_fee_local' => 0,
            'total_price_amount_origin' => 0,
            'total_paid_amount_local' => 0,
            'total_refund_amount_local' => 0,
            'total_counpon_amount_local' => 0,
            'total_promotion_amount_local' => 0,
            'total_fee_amount_local' => 0,
            'total_origin_tax_fee_local' => 0,
            'total_origin_shipping_fee_local' => 0,
            'total_weshop_fee_local' => 0,
            'total_intl_shipping_fee_local' => 0,
            'total_custom_fee_amount_local' => 0,
            'total_delivery_fee_local' => 0,
            'total_packing_fee_local' => 0,
            'total_inspection_fee_local' => 0,
            'total_insurance_fee_local' => 0,
            'total_vat_amount_local' => 0,
            'exchange_rate_fee' => 23000,
            'exchange_rate_purchase' => 0,
            'currency_purchase' => 'JPY',
            'payment_type' => 'WALLET',
            'sale_support_id' => 2,
            'support_email' => 'ho.chi@gmail.com',
            'is_email_sent' => 0,
            'is_sms_sent' => 0,
            'difference_money' => 2,
            'coupon_id' => null,
            'revenue_xu' => 0,
            'xu_count' => 0,
            'xu_amount' => 0,
            'xu_time' => null,
            'xu_log' => 'Alice waited till the puppy\'s bark sounded quite.',
            'promotion_id' => null,
            'total_weight' => 0.5,
            'total_weight_temporary' => 1,
            'created_at' => 966336214,
            'updated_at' => 347100788,
            'purchase_order_id' => '551802034655,717893730370923,69499395179341755790,728941414',
            'purchase_transaction_id' => 'fd6e40e3319d90f245708faa26761e27',
            'purchase_amount' => 6,
            'purchase_account_id' => '07039248548',
            'purchase_account_email' => 'uninh@giang.net.vn',
            'purchase_card' => '4455497622345841',
            'purchase_amount_buck' => 7576939,
            'purchase_amount_refund' => 27188979,
            'purchase_refund_transaction_id' => null,
            'total_quantity' => 5,
            'total_purchase_quantity' => 34,
            'remove' => 0,
        ]);

        expect('model should validate', $model->validate())->true();
    }

    public function testSave()
    {
        $model = new Order([
            'store_id' => 1,
            'type_order' => 'SHOP',
            'customer_id' => 3,
            'customer_type' => 'Wholesale',
            'portal' => 'AMAZON_JAPAN',
            'utm_source' => null,
            'new' => 1346090982,
            'purchased' => null,
            'seller_shipped' => null,
            'stockin_us' => null,
            'stockout_us' => null,
            'stockin_local' => null,
            'stockout_local' => null,
            'at_customer' => null,
            'returned' => null,
            'cancelled' => null,
            'lost' => null,
            'current_status' => 'NEW',
            'is_quotation' => 0,
            'quotation_status' => null,
            'quotation_note' => null,
            'receiver_email' => 'than.chieu@yahoo.com',
            'receiver_name' => 'Lã Sương',
            'receiver_phone' => '84-510-113-8181',
            'receiver_address' => '1, Thôn Hà Sử, Xã Văn Diệu Chung, Quận 34
Ninh Bình',
            'receiver_country_id' => 1,
            'receiver_country_name' => 'Việt Nam',
            'receiver_province_id' => 99,
            'receiver_province_name' => 'Đà Nẵng',
            'receiver_district_id' => 1,
            'receiver_district_name' => 'Hồ Chí Minh',
            'receiver_post_code' => '02106',
            'receiver_address_id' => 4,
            'note_by_customer' => 'Queen, who had been all the unjust things--\'.',
            'note' => 'Cheshire cat,\' said the Mouse, who was a table.',
            'seller_id' => 2,
            'seller_name' => 'Stuart Friesen',
            'seller_store' => 'http://www.conn.com/',
            'total_final_amount_local' => 0,
            'total_amount_local' => 0,
            'total_origin_fee_local' => 0,
            'total_price_amount_origin' => 0,
            'total_paid_amount_local' => 0,
            'total_refund_amount_local' => 0,
            'total_counpon_amount_local' => 0,
            'total_promotion_amount_local' => 0,
            'total_fee_amount_local' => 0,
            'total_origin_tax_fee_local' => 0,
            'total_origin_shipping_fee_local' => 0,
            'total_weshop_fee_local' => 0,
            'total_intl_shipping_fee_local' => 0,
            'total_custom_fee_amount_local' => 0,
            'total_delivery_fee_local' => 0,
            'total_packing_fee_local' => 0,
            'total_inspection_fee_local' => 0,
            'total_insurance_fee_local' => 0,
            'total_vat_amount_local' => 0,
            'exchange_rate_fee' => 23000,
            'exchange_rate_purchase' => 0,
            'currency_purchase' => 'JPY',
            'payment_type' => 'WALLET',
            'sale_support_id' => 2,
            'support_email' => 'ho.chi@gmail.com',
            'is_email_sent' => 0,
            'is_sms_sent' => 0,
            'difference_money' => 2,
            'coupon_id' => null,
            'revenue_xu' => 0,
            'xu_count' => 0,
            'xu_amount' => 0,
            'xu_time' => null,
            'xu_log' => 'Alice waited till the puppy\'s bark sounded quite.',
            'promotion_id' => null,
            'total_weight' => 0.5,
            'total_weight_temporary' => 1,
            'created_at' => 966336214,
            'updated_at' => 347100788,
            'purchase_order_id' => '551802034655,717893730370923,69499395179341755790,728941414',
            'purchase_transaction_id' => 'fd6e40e3319d90f245708faa26761e27',
            'purchase_amount' => 6,
            'purchase_account_id' => '07039248548',
            'purchase_account_email' => 'uninh@giang.net.vn',
            'purchase_card' => '4455497622345841',
            'purchase_amount_buck' => 7576939,
            'purchase_amount_refund' => 27188979,
            'purchase_refund_transaction_id' => null,
            'total_quantity' => 5,
            'total_purchase_quantity' => 34,
            'remove' => 0,
        ]);

        expect('model should save', $model->save())->true();

        expect('purchase_transaction_id is correct', $model->purchase_transaction_id)->equals('fd6e40e3319d90f245708faa26761e27');
        expect('support_email is correct', $model->support_email)->equals('ho.chi@gmail.com');
        expect('type_order is draft', $model->type_order)->equals('SHOP');
        expect('created_at is generated', $model->created_at)->notEmpty();
        expect('updated_at is generated', $model->updated_at)->notEmpty();
    }


}
