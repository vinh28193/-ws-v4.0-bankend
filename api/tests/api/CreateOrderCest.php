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
        $I->haveHttpHeader('X-Access-Token','492d2510d799536fb00b18ca662cdfcb');
        $I->sendPOST('/1/order/create', '{
            "store_id" : "Store ID",
            "type_order" : "Type Order",
            "portal" : "Portal",
            "is_quotation" : "Is Quotation",
            "quotation_status" : "Quotation Status",
            "quotation_note" : "Quotation Note",
            "customer_id" : "Customer ID",
            "receiver_email" : "Receiver Email",
            "receiver_name" : "Receiver Name",
            "receiver_phone" : "Receiver Phone",
            "receiver_address" : "Receiver Address",
            "receiver_country_id" : "Receiver Country ID",
            "receiver_country_name" : "Receiver Country Name",
            "receiver_province_id" : "Receiver Province ID",
            "receiver_province_name" : "Receiver Province Name",
            "receiver_district_id" : "Receiver District ID",
            "receiver_district_name" : "Receiver District Name",
            "receiver_post_code" : "Receiver Post Code",
            "receiver_address_id" : "Receiver Address ID",
            "note_by_customer" : "Note By Customer",
            "note" : "Note",
            "payment_type" : "Payment Type",
            "sale_support_id" : "Sale Support ID",
            "support_email" : "Support Email",
            "coupon_id" : "Coupon ID",
            "coupon_code" : "Coupon Code",
            "coupon_time" : "Coupon Time",
            "revenue_xu" : "Revenue Xu",
            "xu_count" : "Xu Count",
            "xu_amount" : "Xu Amount",
            "is_email_sent" : "Is Email Sent",
            "is_sms_sent" : "Is Sms Sent", 
            "promotion_id" : "Promotion ID",
            "difference_money" : "Difference Money",
            "utm_source" : "Utm Source",
            "seller_id" : "Seller ID",
            "seller_name" : "Seller Name",
            "seller_store" : "Seller Store",
            "total_final_amount_local" : "Total Final Amount Local",
            "total_paid_amount_local" : "Total Paid Amount Local",
            "total_refund_amount_local" : "Total Refund Amount Local",
            "total_amount_local" : "Total Amount Local",
            "total_fee_amount_local" : "Total Fee Amount Local",
            "total_counpon_amount_local" : "Total Counpon Amount Local",
            "total_promotion_amount_local" : "Total Promotion Amount Local",
            "total_origin_fee_local" : "Total Origin Fee Local",
            "total_origin_tax_fee_local" : "Total Origin Tax Fee Local",
            "total_origin_shipping_fee_local" : "Total Origin Shipping Fee Local",
            "total_weshop_fee_local" : "Total Weshop Fee Local",
            "total_intl_shipping_fee_local" : "Total Intl Shipping Fee Local",
            "total_custom_fee_amount_local" : "Total Custom Fee Amount Local",
            "total_delivery_fee_local" : "Total Delivery Fee Local",
            "total_packing_fee_local" : "Total Packing Fee Local",
            "total_inspection_fee_local" : "Total Inspection Fee Local",
            "total_insurance_fee_local" : "Total Insurance Fee Local",
            "total_vat_amount_local" : "Total Vat Amount Local",
            "exchange_rate_fee" : "Exchange Rate Fee",
            "exchange_rate_purchase" : "Exchange Rate Purchase",
            "currency_purchase" : "Currency Purchase",
            "purchase_order_id" : "Purchase Order ID",
            "purchase_transaction_id" : "Purchase Transaction ID",
            "purchase_amount" : "Purchase Amount",
            "purchase_account_id" : "Purchase Account ID",
            "purchase_account_email" : "Purchase Account Email",
            "purchase_card" : "Purchase Card",
            "purchase_amount_buck" : "Purchase Amount Buck",
            "purchase_amount_refund" : "Purchase Amount Refund",
            "purchase_refund_transaction_id" : "Purchase Refund Transaction ID",
            "total_weight" : "Total Weight",
            "total_weight_temporary" : "Total Weight Temporary",
            "new" : 1,
            "purchased" : "Purchased",
            "seller_shipped" : "Seller Shipped",
            "stockin_us" : "Stockin Us",
            "stockout_us" : "Stockout Us",
            "stockin_local" : "Stockin Local",
            "stockout_local" : "Stockout Local",
            "at_customer" : "At Customer",
            "returned" : "Returned",
            "cancelled" : "Cancelled",
            "lost" : "Lost",
            "current_status" : "Current Status", 
            "remove" : "Remove"
        }');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"store_id":["Store ID must be an integer."]');

    }

    public function createOrderViaAPIDone(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type','application/json');
        $I->haveHttpHeader('X-Access-Token','492d2510d799536fb00b18ca662cdfcb');
        $I->sendPOST('/1/order/create', '{
            "store_id" : 1, 
            "type_order" : "SHOP",
            "portal" : "AMAZON",
            "is_quotation" : 0,
            "quotation_status" : "1",
            "quotation_note" : "",
            "customer_id" : 249,
            "receiver_email" : "dieu.nghiem@hotmail.com",
            "receiver_name" : "Bạc Vĩ",
            "receiver_phone" : "022 511 1846",
            "receiver_address" : "3, Thôn Diệp Đoàn, Ấp Thạch Đình, Quận Khoát Anh Bắc Giang",
            "receiver_country_id" : 1,
            "receiver_country_name" : "Việt Nam",
            "receiver_province_id" : 3,
            "receiver_province_name" : "Hà Nội",
            "receiver_district_id" : 817,
            "receiver_district_name" : "Phố Bì",
            "receiver_post_code" : "750214",
            "receiver_address_id" : 178,
            "note_by_customer" : "Come on!\" So they sat down with wonder at the.",
            "note" : "As they walked off together, Alice heard the.",
            "payment_type" : "WALLET",
            "sale_support_id" : 1,
            "support_email" : "dcn@yahoo.com",
            "coupon_id" : null,
            "coupon_code" : null,
            "coupon_time" : null,
            "revenue_xu" : 0,
            "xu_count" : 0,
            "xu_amount" : 0,
            "is_email_sent" : 0,
            "is_sms_sent" : 0,
            "promotion_id" : null,
            "difference_money" : 0,
            "utm_source" : null,
            "seller_id" : 699,
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
            "exchange_rate_purchase" : 0,
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
            "total_weight" : null,
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
