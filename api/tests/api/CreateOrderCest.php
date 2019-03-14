<?php namespace api\tests;
use api\tests\ApiTester;
use Codeception\Util\HttpCode;

class CreateOrderCest
{
    public function _before(ApiTester $I)
    { }

    // tests
    public function tryToTest(ApiTester $I)
    { }

    // tests
    public function createOrderViaAPIErrorStore(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type','application/json');
        $I->haveHttpHeader('X-Access-Token','e54e9558e4c0f80cc356647251e24054');
        $I->sendPOST('/1/order/create', '{
            "store_id" : "Store ID",
            "type_order" : "Type Order",
            "portal" : "Portal",
            "is_quotation" : "Is Quotation",
            "quotation_status" : "Quotation Status",
            "quotation_note" : "Quotation Note",
            "customer_id" : 1,
            "receiver_email" : "recever@gmail.com",
            "receiver_name" : "Receiver Name",
            "receiver_phone" : "0946789099",
            "receiver_address" : "18 Tam Trinh , Hoang Mai , Ha Noi",
            "receiver_country_id" : 1,
            "receiver_country_name" : "Viet Nam",
            "receiver_province_id" : 1,
            "receiver_province_name" : "Ho Chi Minh",
            "receiver_district_id" : 1,
            "receiver_district_name" : "Quan 10",
            "receiver_post_code" : "100000",
            "receiver_address_id" : 1,
            "note_by_customer" : "Note By Customer",
            "note" : "Note",
            "payment_type" : "WALLET",
            "sale_support_id" : 1,
            "support_email" : abcadv@gmail.com,
            "coupon_id" : 1, 
            "revenue_xu" : null,
            "xu_count" : null,
            "xu_amount" : 0,
            "is_email_sent" : 0,
            "is_sms_sent" :0, 
            "promotion_id" : null,
            "difference_money" : 0,
            "utm_source" : null,
            "seller_id" : 1,
            "name" : "Parama",
            "link_store" : "http://dan.com",
            "total_final_amount_local" : 900,
            "total_paid_amount_local" : 940,
            "total_refund_amount_local" : 0,
            "total_amount_local" : 940,
            "total_fee_amount_local" : 0,
            "total_counpon_amount_local" : 0,
            "total_promotion_amount_local" : 0,
            "total_origin_fee_local" : 0,
            "total_origin_tax_fee_local" : 0,
            "total_origin_shipping_fee_local" :0,
            "total_weshop_fee_local" : 0,
            "total_intl_shipping_fee_local" : 0,
            "total_custom_fee_amount_local" : 0,
            "total_delivery_fee_local" :0,
            "total_packing_fee_local" :0,
            "total_inspection_fee_local" :1,
            "total_insurance_fee_local" :0,
            "total_vat_amount_local" :0,
            "exchange_rate_fee" : 23000,
            "exchange_rate_purchase" : 120,
            "currency_purchase" : USD,
            "purchase_order_id" : null,
            "purchase_transaction_id" : null,
            "purchase_amount" : 700,
            "purchase_account_id" : 1,
            "purchase_account_email" : "weshopdev@gmail.com",
            "purchase_card" : "7886900",
            "purchase_amount_buck" : 0,
            "purchase_amount_refund" :0,
            "purchase_refund_transaction_id" : null,
            "total_weight" : 2,
            "total_weight_temporary" : null,
            "new" : 145590000,
            "purchased" : null,
            "seller_shipped" : null,
            "stockin_us" : null,
            "stockout_us" : null,
            "stockin_local" : null,
            "stockout_local" : null,
            "at_customer" : null,
            "returned" : null,
            "cancelled" : null,
            "lost" : null,
            "current_status" : null, 
            "remove" : 0
        }');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"store_id":["Store ID must be an integer."]');

    }

    public function createOrderViaAPIDone(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type','application/json');
        $I->haveHttpHeader('X-Access-Token','e54e9558e4c0f80cc356647251e24054');
        $I->sendPOST('/1/order/create', '{
            "store_id" : 1, 
            "type_order" : "SHOP",
            "portal" : "AMAZON",
            "is_quotation" : 0,
            "quotation_status" : null,
            "quotation_note" : null,
            "customer_id" : 1,
            "receiver_email" : "dieu.nghiem@hotmail.com",
            "receiver_name" : "Bạc Vĩ",
            "receiver_phone" : "022 511 1846",
            "receiver_address" : "3, Thôn Diệp Đoàn, Ấp Thạch Đình, Quận Khoát Anh Bắc Giang",
            "receiver_country_id" : 1,
            "receiver_country_name" : "Việt Nam",
            "receiver_province_id" : 3,
            "receiver_province_name" : "Hà Nội",
            "receiver_district_id" : 1,
            "receiver_district_name" : "Phố Bì",
            "receiver_post_code" : "750214",
            "receiver_address_id" : 1,
            "note_by_customer" : "Come on!\" So they sat down with wonder at the.",
            "note" : "As they walked off together, Alice heard the.",
            "payment_type" : "WALLET",
            "sale_support_id" : 1,
            "support_email" : "dcn@yahoo.com",
            "coupon_id" : null, 
            "revenue_xu" : 0,
            "xu_count" : 0,
            "xu_amount" : 0,
            "is_email_sent" : 0,
            "is_sms_sent" : 0,
            "promotion_id" : 1,
            "difference_money" : 0,
            "utm_source" : null,
            "seller_id" : 1,
            "seller_name" : "Em. Giao Luận",
            "seller_store" : "https://www.le.int.vn/sed-expedita-rerum-beatae-consectetur-commodi",
            "total_final_amount_local" : 0,
            "total_paid_amount_local" : 0,
            "total_refund_amount_local" : 0,
            "total_amount_local" : 10716000,
            "total_fee_amount_local" : 0,
            "total_counpon_amount_local" : 0,
            "total_promotion_amount_local" : 0,
            "exchange_rate_fee" : 23500,
            "exchange_rate_purchase" : 2345,
            "currency_purchase" : "0",
            "purchase_order_id" : null,
            "purchase_transaction_id" : null,
            "purchase_amount" : null,
            "purchase_account_id" : null,
            "purchase_account_email" : null,
            "purchase_card" : null,
            "purchase_amount_buck" : null,
            "purchase_amount_refund" : null,
            "purchase_refund_transaction_id" : null,
            "total_weight" : 3,
            "total_weight_temporary" : null,
            "new" : 1213456503,
            "purchased" : null,
            "seller_shipped" : null,
            "stockin_us" : null,
            "stockout_us" : null,
            "stockin_local" : null,
            "stockout_local" : null,
            "at_customer" : null,
            "returned" : null,
            "cancelled" : null,
            "lost" : null,
            "current_status" : "NEW", 
            "remove" : 0
        }');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
    }
}
