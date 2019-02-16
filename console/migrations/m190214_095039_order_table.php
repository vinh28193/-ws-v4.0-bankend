<?php

use yii\db\Migration;

/**
 * Class m190214_095039_order_table
 */
class m190214_095039_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $sql="CREATE TABLE `order` (
          `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'order code',
          `binCode` varchar(50) DEFAULT NULL,
          `siteId` int(11) DEFAULT NULL,
          `storeId` int(11) DEFAULT NULL,
          `type` int(11) DEFAULT '0' COMMENT 'hinh thuc mua: mua ngay, bao gia, dau gia  0: SHOP  , 6 : SHIP , 5 : POS , 1: BAO GIA-->0',
          `IsQuotation` tinyint(4) DEFAULT NULL COMMENT 'Là báo giá hay không',
          `Quotation_status` tinyint(4) DEFAULT '0' COMMENT '0- pending, 1- approve, 2- deny',
          `Quotation_Note` varchar(255) DEFAULT NULL,
          `CustomerId` int(11) DEFAULT NULL COMMENT 'FK bảng customer',
          `buyerEmail` varchar(100) DEFAULT NULL,
          `buyerName` varchar(220) DEFAULT NULL,
          `buyerPhone` varchar(50) DEFAULT NULL,
          `buyerAddress` varchar(220) DEFAULT NULL,
          `buyerCountryId` int(11) DEFAULT NULL,
          `billingCountryName` varchar(255) DEFAULT NULL,
          `buyerProvinceId` int(11) DEFAULT NULL,
          `billingCityName` varchar(255) DEFAULT NULL,
          `buyerDistrictId` int(11) DEFAULT NULL,
          `billingDisctrictName` varchar(255) DEFAULT NULL,
          `buyerPostCode` varchar(255) DEFAULT NULL,
          `BillingAddressId` int(11) DEFAULT NULL COMMENT 'FK bảng Address',
          `receiverEmail` varchar(100) DEFAULT NULL,
          `receiverName` varchar(220) DEFAULT NULL,
          `receiverPhone` varchar(50) DEFAULT NULL,
          `receiverAddress` varchar(220) DEFAULT NULL,
          `receiverCountryId` int(11) DEFAULT NULL,
          `receiverCountryName` varchar(255) DEFAULT NULL,
          `receiverCityId` int(11) DEFAULT NULL,
          `receiverCityName` varchar(255) DEFAULT NULL,
          `receiverDistrictId` int(11) DEFAULT NULL,
          `receiverDistrictName` varchar(255) DEFAULT NULL,
          `receiverPostCode` varchar(255) DEFAULT NULL,
          `ShippingAddressId` int(11) DEFAULT NULL COMMENT 'FK bảng Address',
          `paymentType` varchar(50) DEFAULT 'online_payment' COMMENT 'hinh thuc thanh toan. -\"online_payment, ''VT''\"...',
          `paymentTokenCheckType` tinyint(1) DEFAULT '0' COMMENT '0 : Tự động , 1 : manual ',
          `paymentStatus` tinyint(1) DEFAULT '0' COMMENT 'trang thai thanh toan',
          `refundStatus` int(11) DEFAULT '1' COMMENT '1: ko có, 2: 1 phần, 3: toàn bộ, 4: cancel',
          `shippingStatus` int(11) DEFAULT NULL COMMENT 'Trạng thái vận chuyển hàng',
          `shipmentPrice` decimal(18,2) DEFAULT '0.00' COMMENT 'Phí vận chuyển',
          `createTime` datetime DEFAULT NULL,
          `updateTime` datetime DEFAULT NULL,
          `remove` tinyint(1) DEFAULT '0',
          `OrderTotalInLocalShippingAmount` decimal(18,2) DEFAULT '0.00' COMMENT 'Phí vận chuyển của địa phương (indo chuyển từ tháng 7/2018, trước đó ShipmentLocalPerUnit (usd))',
          `complete` tinyint(1) DEFAULT '0',
          `note` text,
          `weight` float DEFAULT '0',
          `xuCount` decimal(18,1) DEFAULT NULL COMMENT 'Số xu sử dụng',
          `xuAmount` decimal(18,2) DEFAULT NULL COMMENT 'Giá trị quy đổi ra tiền',
          `event_discount` decimal(18,2) DEFAULT NULL COMMENT 'lưu lại giá giảm Khi Weshop chay chương trinh liên kết VPBank , VietComBank',
          `discountAmount` decimal(18,2) DEFAULT '0.00' COMMENT 'giam gia',
          `OrderTotalInLocalCurrencyFinal` decimal(18,2) DEFAULT NULL,
          `TotalPaidAmount` decimal(18,2) DEFAULT NULL COMMENT 'Tổng số tiền khách hàng đã thanh toán',
          `totalPrice` decimal(18,2) DEFAULT '0.00' COMMENT 'tong gia cua don hang ',
          `finalPrice` decimal(18,2) DEFAULT '0.00' COMMENT 'Gia thuc su khach hang phai thanh toan',
          `ladingId` varchar(50) DEFAULT NULL COMMENT 'van don',
          `buyerWardId` varchar(10) DEFAULT NULL,
          `receiverWardId` varchar(10) DEFAULT NULL,
          `saleSupportId` int(11) DEFAULT NULL COMMENT 'Nguoi dc phân support don hang (V3)',
          `supportEmail` varchar(50) DEFAULT NULL,
          `supportStatus` int(11) DEFAULT NULL COMMENT 'OrderStatusId',
          `complaint` varchar(200) DEFAULT NULL COMMENT 'Khach hang khieu nai',
          `OrderSubtotalExclTax` decimal(18,2) DEFAULT NULL COMMENT 'Tổng giá của tất cả Item trước thuế',
          `OrderTax` decimal(18,2) DEFAULT NULL COMMENT 'Tổng thuế của tất cả các mặt hàng',
          `OrderSubtotalInclTax` decimal(18,2) DEFAULT NULL COMMENT 'Tổng giá của tất cả Item sau thuế',
          `OrderShipingAmount` decimal(18,2) DEFAULT NULL COMMENT 'Tổng giá trị vận chuyển',
          `OrderShippingInclTax` decimal(18,2) DEFAULT NULL COMMENT 'Tổng giá trị vận chuyển có thuế',
          `OrderFeeServiceAmount` decimal(18,2) DEFAULT NULL COMMENT 'Tổng phí dịch vụ của các Item',
          `OrderCustomFee` decimal(18,2) DEFAULT NULL COMMENT 'Tổng phí hải quan',
          `OrderCustomAdditionFee` decimal(18,2) DEFAULT NULL COMMENT 'Tổng phí hải quan phụ thu',
          `PaymentAdditionalFeeInclTax` decimal(18,2) DEFAULT '0.00' COMMENT 'Phí thu thêm theo hình thức thanh toán',
          `ShipmentLocalPerUnit` decimal(18,2) DEFAULT NULL COMMENT 'Giá trị vận chuyển trên 1 đơn vị đo lường (cân)',
          `ShipmentLocalAmount` decimal(18,2) DEFAULT NULL COMMENT 'Tổng phí vận chuyển nội địa',
          `OrderTotal` decimal(18,2) DEFAULT NULL COMMENT 'Tong tien cua order phai thanh toan',
          `CurrencyId` int(11) DEFAULT NULL COMMENT 'Ma tien te',
          `CurrencyRate` decimal(18,8) DEFAULT NULL COMMENT 'Ti gia',
          `OrderTotalInLocalCurrency` decimal(18,2) DEFAULT NULL,
          `OrderTotalInLocalCurrencyDisplay` decimal(18,2) DEFAULT NULL,
          `couponCode` varchar(50) DEFAULT NULL COMMENT 'Ma coupon',
          `couponTime` datetime DEFAULT NULL COMMENT 'Thoi gian su dung coupon',
          `discountPercent` decimal(18,2) DEFAULT '0.00' COMMENT 'phan tram giam gia',
          `revenueXu` decimal(18,1) DEFAULT NULL,
          `LastPaidTime` datetime DEFAULT NULL COMMENT 'Thời gian thanh toán cuối cùng',
          `paymentMethod` varchar(50) DEFAULT 'none' COMMENT 'Phuong thuc thanh toan',
          `paymentMethodProviderId` int(11) DEFAULT NULL,
          `paymentToken` varchar(1000) DEFAULT NULL COMMENT 'Token lưu khi truyền sang Nhà cung cấp',
          `TotalWalletPaidPrimaryAmount` decimal(18,2) DEFAULT NULL,
          `TotalWalletPaidPromotionAmount` decimal(18,2) DEFAULT NULL,
          `TotalWalletPaidPrimaryAmountFee` decimal(18,2) DEFAULT NULL,
          `TotalWalletPaidPromotionAmountFee` decimal(18,2) DEFAULT NULL,
          `PromotionPointToWallet` decimal(18,2) DEFAULT NULL,
          `RemainAmount` decimal(18,2) DEFAULT NULL COMMENT 'Số tiền KH thiếu cần thanh toán ',
          `AdditionFeeAmount` decimal(18,2) DEFAULT '0.00' COMMENT 'Tổng thu thêm của tất cả Item trong đơn hàng',
          `AdditionFeePaidAmount` decimal(18,2) DEFAULT '0.00' COMMENT 'Tổng thu thêm đã thanh toán bởi KH ',
          `AdditionFeeLocalAmount` decimal(18,2) DEFAULT '0.00' COMMENT 'Tổng thu thêm của tất cả Item trong đơn hàng quy đổi ',
          `ShipmentMethod` varchar(50) DEFAULT NULL,
          `LastShipmentTime` datetime DEFAULT NULL COMMENT 'Thời gian sản phẩm cuối cùng được vận chuyển cho khách',
          `AffiliateId` int(11) DEFAULT NULL COMMENT 'Id với đối tác',
          `CustomerIp` varchar(50) DEFAULT NULL,
          `ManageOrganizationId` int(11) DEFAULT NULL,
          `ManageDepartmentId` int(11) DEFAULT NULL,
          `ManageSaleTeamId` int(11) DEFAULT NULL,
          `ManageEmployeeId` int(11) DEFAULT NULL,
          `ManageApprovedByEmployeeId` int(11) DEFAULT NULL,
          `ManageApprovedStatus` int(11) DEFAULT '0' COMMENT 'Trạng thái duyệt(1, NULL:Chưa duyệt, 2: Đã duyệt)',
          `RefundedAmount` decimal(18,4) DEFAULT NULL COMMENT 'Tổng số tiền đã refund',
          `FileSentToCustomerId` int(11) DEFAULT NULL COMMENT 'Nội dung đơn hàng đã được KH từ hệ thống',
          `FileStoreId` int(11) DEFAULT NULL COMMENT 'Lưu trữ bản cứng file thông tin của đơn hàng khi đơn hàng đã được đóng',
          `BuyerFirstName` varchar(50) DEFAULT NULL,
          `BuyerLastName` varchar(50) DEFAULT NULL,
          `ReceiveFirstName` varchar(50) DEFAULT NULL,
          `ReceiveLastName` varchar(50) DEFAULT NULL,
          `IsEmailSent` tinyint(1) DEFAULT NULL,
          `IsSmsSent` tinyint(1) DEFAULT NULL,
          `SourceId` int(11) DEFAULT NULL COMMENT 'Nguồn KH: 1 - web, 2 - telesale, 3 - tự khai thác ...',
          `CampSourceId` int(11) DEFAULT NULL COMMENT 'Đơn hàng được tạo từ chiến dịch',
          `LastUpdateByEmployeeId` int(11) DEFAULT NULL,
          `OrderStatus` int(11) DEFAULT '0' COMMENT '3: Success, 1: Pending, 2: Proposal',
          `PurchaseStatus` int(11) DEFAULT NULL,
          `BankName` varchar(100) DEFAULT NULL COMMENT 'Tên ngân hàng khách hàng thanh toán',
          `IsRequestInspection` int(11) DEFAULT NULL COMMENT '0: không kiểm, 1: kiểm 1 phần, 2: kiểm toàn bộ',
          `ShipmentOptionsStatus` int(11) DEFAULT '1' COMMENT '1: chuyển ngay khi có hàng, 2: chờ đủ hàng để chuyển',
          `vat` text,
          `totalquantity` int(11) DEFAULT NULL,
          `OrderVersion` varchar(10) DEFAULT NULL COMMENT 'A1E1/A2E1/A2E2/A1E2',
          `installmentRequestData` text COMMENT 'Instalment request data',
          `InstallmentBankStatus` int(10) DEFAULT NULL,
          `customerOrderConfirm` tinyint(1) DEFAULT '0',
          `customerPaymentConfirm` tinyint(1) DEFAULT '0',
          `supporterNote` longtext,
          `supportPaymentConfirm` tinyint(1) DEFAULT NULL,
          `supportTime` datetime DEFAULT NULL,
          `purchaseConfirm` tinyint(1) DEFAULT NULL,
          `isNewOrder` tinyint(1) DEFAULT '0',
          `orderItemIds` varchar(1000) DEFAULT NULL,
          `orderItemCategoryIds` varchar(255) DEFAULT NULL,
          `discountId` int(11) DEFAULT NULL,
          `refundedPaidAmount` decimal(18,2) DEFAULT '0.00',
          `isAuction` bit(1) DEFAULT b'0',
          `stockinStatus` tinyint(1) DEFAULT '0',
          `stockoutStatus` tinyint(1) DEFAULT '0',
          `orderLevel` tinyint(1) DEFAULT '0',
          `operatorCheck` tinyint(1) DEFAULT '0',
          `totalPoint` decimal(18,2) DEFAULT NULL,
          `AdditionFeeTotalLocalAmount` decimal(18,2) DEFAULT NULL,
          `AdditionFeePaidLocalAmount` decimal(18,2) DEFAULT NULL,
          `HistoryToken` text,
          `PurchaseEmployeeId` int(11) DEFAULT NULL,
          `PosSettingId` int(11) DEFAULT NULL,
          `TrackingCodeToCustomer` varchar(255) DEFAULT NULL,
          `SecurityCodeToCustomer` varchar(255) DEFAULT NULL,
          `visaCardNo` varchar(15) DEFAULT NULL COMMENT 'Lay 4 so cuoi cua the visa cua khach hang trong truong hop hang ship',
          `bcardNo` varchar(500) DEFAULT NULL,
          `bcardAddTime` datetime DEFAULT NULL,
          `isLogin` tinyint(1) DEFAULT NULL COMMENT '0: KH mua khong login, 1: KH mua login',
          `markedRefund` tinyint(1) DEFAULT '0',
          `localCurrencyCode` varchar(255) DEFAULT NULL,
          `currencyCode` varchar(255) DEFAULT NULL,
          `trackUrlPrivateKey` varchar(50) DEFAULT NULL,
          `promotionId` int(11) DEFAULT NULL,
          `MarkSalesRefundSO` varchar(200) DEFAULT NULL COMMENT 'SO  : MarkSalesRefundSO  (varchar200) :  RFSO-idso  —> NULL VIEW POPUP va NGUOC LAI',
          `MarkOperationRefundSO` varchar(200) DEFAULT NULL COMMENT 'SO  : MarkOperationRefundSO  (varchar200) :  RFSO-OP-idso  —> NULL VIEW POPUP va NGUOC LAI',
          `customerIdentityCard` varchar(500) DEFAULT NULL COMMENT 'SHIP: URL ảnh chụp chứng minh nd của khách',
          `customerVisaCardImg` varchar(255) DEFAULT NULL COMMENT 'Url ảnh thẻ visa',
          `customerCMNDImg` varchar(255) DEFAULT NULL COMMENT 'Url anh cmnd',
          `DifferenceMoney` tinyint(1) DEFAULT '0' COMMENT '0: mac dinh, 1: lech, 2:ẩn thông báo bằng quyền của Admin',
          `sanbox` tinyint(1) DEFAULT '0' COMMENT '0: cho don tao mau hang , 1:  don test sanbox cac merchance',
          `version` tinyint(1) DEFAULT '0' COMMENT '0: weshop mua ban ||  1: weshop van chuyen',
          `utm_source` varchar(255) DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=495078 DEFAULT CHARSET=utf8;";
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m190214_095039_order_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190214_095039_order_table cannot be reverted.\n";

        return false;
    }
    */
}
