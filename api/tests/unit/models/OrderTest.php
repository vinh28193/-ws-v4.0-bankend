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

    public function _before(ApiTester $I)
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
            "store_id" => 1,
            "type_order" => "SHOP",
            "portal" => "AMAZON",
            "is_quotation" => 0,
            "quotation_status" => null,
            "quotation_note" => null,
            "customer_id" => 1,
            "receiver_email" => "dieu.nghiem@hotmail.com",
            "receiver_name" => "Bạc Vĩ",
            "receiver_phone" => "022 511 1846",
            "receiver_address" => "3, Thôn Diệp Đoàn, Ấp Thạch Đình, Quận Khoát Anh Bắc Giang",
            "receiver_country_id" => 1,
            "receiver_country_name" => "Việt Nam",
            "receiver_province_id" => 3,
            "receiver_province_name" => "Hà Nội",
            "receiver_district_id" => 1,
            "receiver_district_name" => "Phố Bì",
            "receiver_post_code" => "750214",
            "receiver_address_id" => 1,
            "note_by_customer" => "Come on!\" So they sat down with wonder at the.",
            "note" => "As they walked off together, Alice heard the.",
            "payment_type" => "WALLET",
            "sale_support_id" => 1,
            "support_email" => "dcn@yahoo.com",
            "coupon_id" => null,
            "revenue_xu" => 0,
            "xu_count" => 0,
            "xu_amount" => 0,
            "is_email_sent" => 0,
            "is_sms_sent" => 0,
            "promotion_id" => 1,
            "difference_money" => 0,
            "utm_source" => null,
            "seller_id" => 1,
            "seller_name" => "Em. Giao Luận",
            "seller_store" => "https=>//www.le.int.vn/sed-expedita-rerum-beatae-consectetur-commodi",
            "total_final_amount_local" => 0,
            "total_paid_amount_local" => 0,
            "total_refund_amount_local" => 0,
            "total_amount_local" => 10716000,
            "total_fee_amount_local" => 0,
            "total_counpon_amount_local" => 0,
            "total_promotion_amount_local" => 0,
            "exchange_rate_fee" => 23500,
            "exchange_rate_purchase" => 2345,
            "currency_purchase" => "0",
            "purchase_order_id" => null,
            "purchase_transaction_id" => null,
            "purchase_amount" => null,
            "purchase_account_id" => null,
            "purchase_account_email" => null,
            "purchase_card" => null,
            "purchase_amount_buck" => null,
            "purchase_amount_refund" => null,
            "purchase_refund_transaction_id" => null,
            "total_weight" => 3,
            "total_weight_temporary" => null,
            "new" => 1213456503,
            "purchased" => null,
            "seller_shipped" => null,
            "stockin_us" => null,
            "stockout_us" => null,
            "stockin_local" => null,
            "stockout_local" => null,
            "at_customer" => null,
            "returned" => null,
            "cancelled" => null,
            "lost" => null,
            "current_status" => "NEW",
            "remove" => 0,
        ]);

        expect('model should validate', $model->validate())->true();
    }

    public function testSave()
    {
        $model = new Order([
            "store_id" => 1, 
            "type_order" => "SHOP",
            "portal" => "AMAZON",
            "is_quotation" => 0,
            "quotation_status" => null,
            "quotation_note" => null,
            "customer_id" => 1,
            "receiver_email" => "dieu.nghiem@hotmail.com",
            "receiver_name" => "Bạc Vĩ",
            "receiver_phone" => "022 511 1846",
            "receiver_address" => "3, Thôn Diệp Đoàn, Ấp Thạch Đình, Quận Khoát Anh Bắc Giang",
            "receiver_country_id" => 1,
            "receiver_country_name" => "Việt Nam",
            "receiver_province_id" => 3,
            "receiver_province_name" => "Hà Nội",
            "receiver_district_id" => 1,
            "receiver_district_name" => "Phố Bì",
            "receiver_post_code" => "750214",
            "receiver_address_id" => 1,
            "note_by_customer" => "Come on!\" So they sat down with wonder at the.",
            "note" => "As they walked off together, Alice heard the.",
            "payment_type" => "WALLET",
            "sale_support_id" => 1,
            "support_email" => "dcn@yahoo.com",
            "coupon_id" => null, 
            "revenue_xu" => 0,
            "xu_count" => 0,
            "xu_amount" => 0,
            "is_email_sent" => 0,
            "is_sms_sent" => 0,
            "promotion_id" => 1,
            "difference_money" => 0,
            "utm_source" => null,
            "seller_id" => 1,
            "seller_name" => "Em. Giao Luận",
            "seller_store" => "https=>//www.le.int.vn/sed-expedita-rerum-beatae-consectetur-commodi",
            "total_final_amount_local" => 0,
            "total_paid_amount_local" => 0,
            "total_refund_amount_local" => 0,
            "total_amount_local" => 10716000,
            "total_fee_amount_local" => 0,
            "total_counpon_amount_local" => 0,
            "total_promotion_amount_local" => 0,
            "exchange_rate_fee" => 23500,
            "exchange_rate_purchase" => 2345,
            "currency_purchase" => "0",
            "purchase_order_id" => null,
            "purchase_transaction_id" => null,
            "purchase_amount" => null,
            "purchase_account_id" => null,
            "purchase_account_email" => null,
            "purchase_card" => null,
            "purchase_amount_buck" => null,
            "purchase_amount_refund" => null,
            "purchase_refund_transaction_id" => null,
            "total_weight" => 3,
            "total_weight_temporary" => null,
            "new" => 1213456503,
            "purchased" => null,
            "seller_shipped" => null,
            "stockin_us" => null,
            "stockout_us" => null,
            "stockin_local" => null,
            "stockout_local" => null,
            "at_customer" => null,
            "returned" => null,
            "cancelled" => null,
            "lost" => null,
            "current_status" => "NEW", 
            "remove" => 0,
        ]);

        expect('model should save', $model->save())->true();

        expect('purchase_transaction_id is correct', $model->purchase_transaction_id)->equals('fd6e40e3319d90f245708faa26761e27');
        expect('support_email is correct', $model->support_email)->equals('ho.chi@gmail.com');
        expect('type_order is draft', $model->type_order)->equals('SHOP');
        expect('created_at is generated', $model->created_at)->notEmpty();
        expect('updated_at is generated', $model->updated_at)->notEmpty();
    }


}
