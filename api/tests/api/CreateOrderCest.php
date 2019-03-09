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
    public function createOrderViaAPI(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type','application/json');
        $I->haveHttpHeader('X-Access-Token','c2c41e1d66e70e3b671182a0fc6eca56');
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
            "total_quantity" : "Total Quantity",
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
}
