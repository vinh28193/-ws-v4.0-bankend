
C:\xampp\htdocs\weshop-v4.0-api>php -S 127.0.0.1:8880 -t api/web
PHP 7.2.14 Development Server started at Sat Mar  9 21:11:32 2019
Listening on http://127.0.0.1:8880
Document root is C:\xampp\htdocs\weshop-v4.0-api\api\web
Press Ctrl-C to quit.
[Sat Mar  9 21:11:46 2019] 127.0.0.1:51984 [200]: /
[Sat Mar  9 21:11:46 2019] 127.0.0.1:51985 [200]: /favicon.ico
[Sat Mar  9 21:21:54 2019] 127.0.0.1:52675 [404]: //1/order/create
[Sat Mar  9 21:22:49 2019] 127.0.0.1:52730 [401]: /1/order/create
[Sat Mar  9 21:23:32 2019] 127.0.0.1:52775 [400]: /1/authorize
[Sat Mar  9 21:25:38 2019] 127.0.0.1:52986 [500]: /1/authorize
[Sat Mar  9 21:30:45 2019] 127.0.0.1:53297 [200]: /1/authorize
[Sat Mar  9 21:31:18 2019] 127.0.0.1:53335 [200]: /1/accesstoken
[Sat Mar  9 21:32:13 2019] 127.0.0.1:53390 [200]: /1/order/create


$ vendor/bin/codecept run --steps --debug -- -c api
Codeception PHP Testing Framework v2.6.0
Powered by PHPUnit 8.1-g36f92d5 by Sebastian Bergmann and contributors.
Running with seed:


Api\tests.api Tests (2) ------------------------------------------------------------------------------------------------
Modules: REST, PhpBrowser, Yii2
------------------------------------------------------------------------------------------------------------------------
CreateOrderCest: Try to test
Signature: api\tests\CreateOrderCest:tryToTest
Test: tests\api\CreateOrderCest.php:tryToTest
Scenario --
  Destroying application
  Starting application
  [ConnectionWatcher] watching new connections
  [Fixtures] Loading fixtures
  [Fixtures] Done
  [TransactionForcer] watching new connections
 PASSED

  [TransactionForcer] no longer watching new connections
  Destroying application
  [ConnectionWatcher] no longer watching new connections
  [ConnectionWatcher] closing all (0) connections
CreateOrderCest: Create order via api
Signature: api\tests\CreateOrderCest:createOrderViaAPI
Test: tests\api\CreateOrderCest.php:createOrderViaAPI
Scenario --
  Destroying application
  Starting application
  [ConnectionWatcher] watching new connections
  [Fixtures] Loading fixtures
  [Fixtures] Done
  [TransactionForcer] watching new connections
 I have http header "Content-Type","application/json"
 I have http header "X-Access-Token","1f7adc17f86342713ca92a71920d5fe4"
 I send post "/1/order/create","{\r\n            "store_id" : "Store ID",\r\n            "type_order" : "Type Order"..."
  [Request] POST http://127.0.0.1:8880/1/order/create {
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
          }
  [Request Headers] {"Content-Type":"application/json","X-Access-Token":"1f7adc17f86342713ca92a71920d5fe4"}
  [Page] http://127.0.0.1:8880/1/order/create
  [Response] 200
  [Request Cookies] []
  [Response Headers] {"Host":["127.0.0.1:8880"],"Date":["Sat, 09 Mar 2019 15:32:13 +0100"],"Connection":["close"],"X-Powered-By":["PHP/7.2.14"],"Set-Cookie":["advanced-backend=kpmnck87l2f68f88oq2vbku6l2; path=/; HttpOnly","_identity-backend=deleted; expires=Thu, 01-Jan-1970 00:00:01 GMT; Max-Age=0; path=/; HttpOnly","_csrf=b2bb5da2934316cd1e64767e6fcdcf0bd2be9a342612d4786236c838fcccaee1a%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22iHCzLPm4t-IobdeQhiyD13XfLeZ82AZy%22%3B%7D; path=/; HttpOnly"],"Expires":["Thu, 19 Nov 1981 08:52:00 GMT"],"Cache-Control":["no-store, no-cache, must-revalidate"],"Pragma":["no-cache"],"Access-Control-Expose-Headers":[""],"Content-Type":["application/json; charset=UTF-8"],"X-Debug-Tag":["5c83ce6da2053"],"X-Debug-Duration":["124"],"X-Debug-Link":["/debug/default/view?tag=5c83ce6da2053"]}
  [Response] {"store_id":["Store ID must be an integer."],"quotation_status":["Quotation Status must be an integer."],"is_quotation":["Is Quotation must be an integer."],"customer_id":["Customer ID must be an integer."],"receiver_country_id":["Receiver Country ID must be an integer."],"receiver_province_id":["Receiver Province ID must be an integer."],"receiver_district_id":["Receiver District ID must be an integer."],"receiver_address_id":["Receiver Address ID must be an integer."],"sale_support_id":["Sale Support ID must be an integer."],"total_quantity":["Total Quantity must be an integer."],"seller_id":["Seller ID must be an integer."],"coupon_time":["Coupon Time must be an integer."],"is_email_sent":["Is Email Sent must be an integer."],"is_sms_sent":["Is Sms Sent must be an integer."],"promotion_id":["Promotion ID must be an integer."],"difference_money":["Difference Money must be an integer."],"purchase_account_id":["Purchase Account ID must be an integer."],"purchased":["Purchased must be an integer."],"seller_shipped":["Seller Shipped must be an integer."],"stockin_us":["Stockin Us must be an integer."],"stockout_us":["Stockout Us must be an integer."],"stockin_local":["Stockin Local must be an integer."],"stockout_local":["Stockout Local must be an integer."],"at_customer":["At Customer must be an integer."],"returned":["Returned must be an integer."],"cancelled":["Cancelled must be an integer."],"lost":["Lost must be an integer."],"remove":["Remove must be an integer."],"total_final_amount_local":["Total Final Amount Local must be a number."],"total_promotion_amount_local":["Total Promotion Amount Local must be a number."],"revenue_xu":["Revenue Xu must be a number."],"xu_count":["Xu Count must be a number."],"xu_amount":["Xu Amount must be a number."],"total_paid_amount_local":["Total Paid Amount Local must be a number."],"total_refund_amount_local":["Total Refund Amount Local must be a number."],"total_amount_local":["Total Amount Local must be a number."],"total_fee_amount_local":["Total Fee Amount Local must be a number."],"total_counpon_amount_local":["Total Counpon Amount Local must be a number."],"total_origin_fee_local":["Total Origin Fee Local must be a number."],"total_origin_tax_fee_local":["Total Origin Tax Fee Local must be a number."],"total_origin_shipping_fee_local":["Total Origin Shipping Fee Local must be a number."],"total_weshop_fee_local":["Total Weshop Fee Local must be a number."],"total_intl_shipping_fee_local":["Total Intl Shipping Fee Local must be a number."],"total_custom_fee_amount_local":["Total Custom Fee Amount Local must be a number."],"total_delivery_fee_local":["Total Delivery Fee Local must be a number."],"total_packing_fee_local":["Total Packing Fee Local must be a number."],"total_inspection_fee_local":["Total Inspection Fee Local must be a number."],"total_insurance_fee_local":["Total Insurance Fee Local must be a number."],"total_vat_amount_local":["Total Vat Amount Local must be a number."],"exchange_rate_fee":["Exchange Rate Fee must be a number."],"exchange_rate_purchase":["Exchange Rate Purchase must be a number."],"purchase_amount_buck":["Purchase Amount Buck must be a number."],"purchase_amount_refund":["Purchase Amount Refund must be a number."],"receiver_email":["Receiver Email is not a valid email address."],"support_email":["Support Email is not a valid email address."],"purchase_account_email":["Purchase Account Email is not a valid email address."],"seller_store":["Seller Store is not a valid URL."]}
 I see response code is 200
 I see response is json
 PASSED
