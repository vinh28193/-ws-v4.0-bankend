<?php

use common\fixtures\components\FixtureUtility;

/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 14:57
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$id = $index + 1;
$customer = $faker->randomElement(FixtureUtility::getDataWithColumn('.\common\fixtures\data\data_fixed\customer.php', $columnName = null));
$address = $faker->randomElement(FixtureUtility::getDataWithColumn('.\common\fixtures\data\data_fixed\address.php', $columnName = null));
$user = $faker->randomElement(FixtureUtility::getDataWithColumn('.\common\fixtures\data\data_fixed\user.php', $columnName = null));
$coupon = $faker->randomElement(FixtureUtility::getDataWithColumn('.\common\fixtures\data\data_fixed\coupon.php', $columnName = null, ['type_coupon' => ['COUPON', 'REFUND'], 'remove' => 0]));
//$promotion = $faker->randomElement(FixtureUtility::getDataWithColumn('.\common\fixtures\data\data_fixed\coupon.php',['type_coupon' => 'PROMOTION']));
$product = FixtureUtility::getDataWithColumn('.\common\fixtures\data\data_fixed\product.php', $columnName = null, ['order_id' => $id]);
if (!empty($product)) {
    $seller = $faker->randomElement(FixtureUtility::getDataWithColumn('.\common\fixtures\data\data_fixed\seller.php', $columnName = null, ['id' => $product[0]['seller_id']]));
} else {
    $seller = $faker->randomElement(FixtureUtility::getDataWithColumn('.\common\fixtures\data\data_fixed\seller.php',$columnName = null));
}

if( $seller['id'] == null or isset($seller['id']) == true )
{
    $seller = [
        "id" => 2,
        "name" => "Stuart Friesen",
        "link_store" => "http://www.conn.com/",
        "rate" => 7.4,
        "description" => "I will tell you what year it is?' 'Of course it.",
        "created_at" => 1335568772,
        "updated_at" => 879839191,
        "remove" => 1,
        "portal" => "AMAZON-JP",
    ];
}

//$amountDiscount = $coupon['type_amount'] ?

/*****@TODO : Viết Code Sinh Phụ Thuộc Hàng 'SHOP','REQUEST','SHIP','NEXTTECH' *******/

$typeOrder = $faker->randomElement(['SHOP','REQUEST','SHIP','NEXTTECH']); // 'NEXTTECH' : là loại hàng quan hệ trong nội bộ công ty
if($typeOrder == 'SHOP' ){
    $is_quotation  = 0;  // "Đánh dấu đơn báo giá",
    $quotation_status = null;  //Duyệt đơn báo giá nên đơn có Trạng thái báo giá. null : là hàng SHOP , 0 - pending, 1- approve, 2- deny,
    $quotation_note = null ;
}
if($typeOrder == 'REQUEST' ) {
        $is_quotation  = 1;  // "Đánh dấu đơn báo giá",
        $quotation_status = $faker->randomElement([  0, 1 , 2]); //Duyệt đơn báo giá nên đơn có Trạng thái báo giá, 0 - pending, 1- approve, 2- deny,
        $quotation_note = $faker->realText(50);   //note đơn request",
}
if($typeOrder == 'SHIP' ) {
    $is_quotation  = 0;
    $quotation_status = null;
    $quotation_note = null ;
}

if($typeOrder == 'NEXTTECH' ) {
    $is_quotation  = 0;
    $quotation_status = null;
    $quotation_note = null ;
}



return [
    'id' => $id,
    'store_id' => 1,
    'fees' => [
        'product_price_origin' => $faker->numberBetween(0, 60),
        'tax_fee_origin' => $faker->randomFloat(1.9),
        'origin_shipping_fee' => $faker->numberBetween(0, 20),
    ],

    // Order
    'type_order' => $typeOrder , //Hình thức mua hàng: SHOP | REQUEST | POS | SHIP,
    'customer_id' => $customer['id'], // Mã id của customer : có thể là khách buôn hoặc khách lẻ ",
    'customer_type' => $faker->randomElement(['Retail', 'Wholesale']), // Mã id của customer : Retail Customer : Khách lẻ . Wholesale customers ",
    'portal' => $faker->randomElement(['EBAY', 'AMAZON_US', 'AMAZON_JAPAN', 'OTHER']), //$seller['portal'], //portal ebay, amazon us, amazon jp ...: EBAY/ AMAZON_US / AMAZON_JAPAN / OTHER / WEBSITE NGOÀI ,

    // Đơn Tạo từ chiến dịch nào hay mua ngay tai website Weshop
    'utm_source' => null, //Đơn theo viết được tạo ra bới chiến dịch nào : Facebook ads, Google ads , eomobi , etc ,,,, ,


    // Trạng Thái + thời gian
    'new' => $faker->unixTime(), //time NEW,
    'purchased' => null, //time PURCHASED,
    'seller_shipped' => null, //time SELLER_SHIPPED,
    'stockin_us' => null, //time STOCKIN_US,
    'stockout_us' => null, //time STOCKOUT_US,
    'stockin_local' => null, //time STOCKIN_LOCAL,
    'stockout_local' => null, //time STOCKOUT_LOCAL,
    'at_customer' => null, //time AT_CUSTOMER,
    'returned' => null, //time RETURNED : null,
    'cancelled' => null, // time CANCELLED : null :  Đơn hàng đã  thanh toán --> thì hoàn  tiền ; Đơn hàng chưa thanh toán --> thì Hủy,
    'lost' => null, // time LOST : null : Hàng mất ở kho Mỹ hoặc hải quan hoặc kho VN hoặc trên đường giao cho KH ,
    'current_status' => 'NEW', //Trạng thái hiện tại của order : update theo trạng thái của sản phẩm cuối ,


    // Thông tin đơn báo giá
    'is_quotation' => $is_quotation, // "Đánh dấu đơn báo giá",
    'quotation_status' => $quotation_status, //Duyệt đơn báo giá nên đơn có Trạng thái báo giá. null : là hàng SHOP , 0 - pending, 1- approve, 2- deny,
    'quotation_note' => $quotation_note,  //note đơn request",

    // Thông tin người nhận
    'receiver_email' => $address['email'], //Email người nhận,
    'receiver_name' => $address['last_name'] . " " . $address['first_name'], //Họ tên người nhận,
    'receiver_phone' => $address['phone'], //Số điện thoại người nhận,
    'receiver_address' => $address['address'],  //Địa chỉ người nhận,
    'receiver_country_id' => $address['country_id'], // "Mã Country người nhận,
    'receiver_country_name' => $address['country_name'], //Country người nhận,
    'receiver_province_id' => $address['province_id'], // mã Tỉnh thành người nhận,
    'receiver_province_name' => $address['province_name'], //Tên Tỉnh thành người nhận,
    'receiver_district_id' => $address['district_id'], //Mã Quận huyện người nhận,
    'receiver_district_name' => $address['district_name'], // Tên Quận huyện người nhận,
    'receiver_post_code' => $address['post_code'], // Mã bưu điện người nhận,
    'receiver_address_id' => $address['id'], //id address của người nhận trong bảng address,
    'note_by_customer' => $faker->realText(50), //Ghi chú của customer hoặc ghi chú cho người nhận ,
    'note' => $faker->realText(50), //Ghi chú cho đơn hàng",

    // Thông Tin Người bán
    'seller_id' => $seller['id']  , //Mã người bán ,
    'seller_name' => $seller['name'], //Tên người bán,
    'seller_store' => $seller['link_store'], //Link shop của người bán,

    // Tiền
    'total_final_amount_local' => 0, // Tổng giá trị đơn hàng ( Số tiền đã trừ đi giảm giá ) : số tiền cuối cùng khách hàng phải thanh toán và tính theo tiền local,
    'total_amount_local' => 0, // Tổng giá trị đơn hàng : Số tiền chưa tính giảm giá ,

    'total_price_amount_local' => 0, // Tổng Tiền Hàng ( Theo tiền tê của nước Weshop Indo hoặc Weshop Viet Nam ) : Tổng giá tiền gốc các item theo tiền local ,
    'total_price_amount_origin' => 0, // Tổng Tiền Hàng ( Theo tiền ngoại tê của EBAY / AMAZON  / WEBSITE NGOÀI) : Tổng giá tiền gốc các item theo ngoại tệ ,


    'total_paid_amount_local' => 0, //Tổng số tiền khách hàng đã thanh toán : Theo tiền local ,
    'total_refund_amount_local' => 0, //số tiền đã hoàn trả cho khách hàng : Theo tiền local,


    'total_counpon_amount_local' => 0, //Tổng số tiền giảm giá bằng mã counpon . Ví dụ MÃ VALENTIN200 áp dụng cho khách hàng mới ,
    'total_promotion_amount_local' => 0, //Tổng số tiền giảm giá do promotion . Vi Dụ : Chương trình giảm giá trừ 200.000 VNĐ cho cả đơn ,


    // Tổng các Phí Weshop
    'total_fee_amount_local' => 0,  //tổng phí đơn hàng,
    'total_tax_us_amount_local' => 0, //Tổng phí us tax,
    'total_shipping_us_amount_local' => 0, //Tổng phí shipping us,
    'total_weshop_fee_amount_local' => 0, //Tổng phí weshop,
    'total_intl_shipping_fee_amount_local' => 0,//Tổng phí vận chuyển quốc tế,
    'total_custom_fee_amount_local' => 0,//Tổng phí phụ thu,
    'total_delivery_fee_amount_local' => 0, //Tổng phí vận chuyển nội địa,
    'total_packing_fee_amount_local' => 0, //tổng phí đóng gỗ,
    'total_inspection_fee_amount_local' => 0, //Tổng phí kiểm hàng,
    'total_insurance_fee_amount_local' => 0, //Tổng phí bảo hiểm,
    'total_vat_amount_local' => 0, // "Tổng phí VAT,

    // Update từ bảng tỉ giá Vietcombank  Crowler để lưu vào order tại thời điểm khách hàng đặt đơn mua hàng
    'exchange_rate_fee' => 23000, // $product ? $product[0]['exchange_rate'] : 0, // Tỉ Giá Tính Phí Local : áp dung theo tỉ giá của VietCombank Crowler upate từ 1 bảng systeam_curentcy : Tỷ giá từ USD => tiền local,
    'exchange_rate_purchase' => 0, //Tỉ Giá mua hàng : áp dung theo tỉ giá của VietCombank , Ẩn với Khách. Tỉ giá USD / Tỉ giá Yên / Tỉ giá UK .Tỷ giá từ tiền website gốc => tiền local. VD: yên => vnd,
    'currency_purchase' => $faker->randomElement(['USD', "JPY", "AUD"]), // Loại tiền mua hàng là : USD,JPY,AUD .....,


    // Payment  : 1 order - 1 Coupon  ?????
    'payment_type' => 'WALLET', //hinh thuc thanh toan. -online_payment, 'VT'...,

    // Sales Supports Order
    'sale_support_id' => $user['id'], //Người support đơn hàng",
    'support_email' => $user['email'], //email người support",


    // Systeam process
    'is_email_sent' => 0, // đánh đâu đơn này đã được gửi email tạo thành công đơn hàng,
    'is_sms_sent' => 0, //đánh đâu đơn này đã được gửi SMS tạo thành công đơn hàng,
    'difference_money' => $faker->randomElement([0, 1, 2]), //0: mac dinh, 1: lech, 2:ẩn thông báo bằng quyền của Admin,


    // Coupon : 1 order - 1 Coupon
    'coupon_id' => $coupon_id = $faker->randomElement([null, null, null, null, null, null, null, null, null, $coupon['id']]), // id mã giảm giá,
//  'coupon_code' => $this->string(255)->comment("mã giảm giá,
//  'coupon_time' => $this->bigInteger()->comment("thời gian sử dụng mã coupon ,
//  'coupon_amount' => $this->decimal(18,2)->comment("số tiền áp dụng cho mã coupon này ,

    // XU : 1 order - 1 Xu được tích lũy hoặc sinh ra
    'revenue_xu' => 0, //số xu được nhận,
    'xu_count' => 0,//số xu sử dụng,
    'xu_amount' => 0, //giá trị quy đổi ra tiền,
    'xu_time' => $coupon_id ? $faker->unixTime() : null, //thời gian mốc sử dụng mã xu  ,
    'xu_log' => $faker->realText(50), //trừ từ xu đang có vào đơn , Quy chế sinh ra xu là khách hàng nhận được hàng thành công mới tự động sinh ra xu ,

    // Promotion : 1 order - 1 promotion
    'promotion_id' => $coupon_id, //"id của promotion : Id Chạy chương trình promotion,
//  'promotion_code' => $this->string(255)->comment("mã khuyến mại,
//  'promotion_time' => $this->bigInteger()->comment("thời gian sử dụng mã promotion,
//  'promotion_amount' => $this->decimal(18,2)->comment("số tiền áp dụng cho mã coupon này ,

    // Boxme + Kho
    'total_weight' => $faker->randomElement([0.5, 1, 2]), //cân nặng tính phí,
    'total_weight_temporary' => $faker->randomElement([0.5, 1, 2]), //cân nặng tạm tính,

    'created_at' => $faker->unixTime, //Update qua behaviors tự động  ,
    'updated_at' => $faker->unixTime, //Update qua behaviors tự động,


    // LƯU THONG TIN đã mua của EBAY / AMAZON :   Đơn này đươc phân cho nhân viên Mua Hàng
    'purchase_order_id' => $faker->bankAccountNumber.','.$faker->bankAccountNumber.','.$faker->bankAccountNumber.$faker->randomNumber().','.$faker->bankAccountNumber, //Mã order đặt mua với NB là EBAY / AMAZON / hoặc Website ngoài : mã order purchase ( dạng list, cách nhau = dấu phẩy),
    'purchase_transaction_id' => $faker->md5(), //Mã thanh toán Paypal với eBay, amazon thanh toán bằng thẻ, k lấy được mã giao dịch ( dạng list, cách nhau = dấu phẩy),
    'purchase_amount' => $faker->unique()->randomDigit(), //số tiền thanh toán thực tế với người bán EBAY/AMAZON, lưu ý : Số đã trừ Buck/Point ( và là dạng list, cách nhau = dấu phẩy),
    'purchase_account_id' => $faker->bankAccountNumber, //id tài khoản mua hàng,
    'purchase_account_email' => $faker->unique()->email, //email tài khoản mua hàng,
    'purchase_card' => $faker->creditCardNumber(), //thẻ thanh toán,
    'purchase_amount_buck' => $faker->randomNumber(), //số tiền buck thanh toán,
    'purchase_amount_refund' => $faker->randomNumber(), //số tiền người bán hoàn,
    'purchase_refund_transaction_id' => null, //mã giao dịch hoàn,

    // Tổng Số lượng
    'total_quantity' => $faker->randomElement([1, 34, 2, 4, 5, 6, 9]), // Tổng số lượng khách hàng đặt = tổng các số lượng trên bảng product,
    'total_purchase_quantity' => $faker->randomElement([1, 34, 2, 4, 5, 6, 9]), // Tổng số lượng nhân viên đi mua hàng thực tế của cả đơn = tổng các số lượng mua thực tế trên bảng product,

    'remove' => $faker->randomElement([0]), //đơn đánh đấu 1 là đã xóa , mặc định 0 : chưa xóa")
];
