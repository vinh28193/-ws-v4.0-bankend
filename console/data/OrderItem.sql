CREATE TABLE `order_item` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `itemCategoryId` varchar(30) DEFAULT NULL,
              `sku` varchar(255) DEFAULT '' COMMENT 'mã sản phẩm store',
              `skuChange` varchar(255) DEFAULT NULL COMMENT 'mã sản phẩm được xác nhận lại trên website (ebay)',
              `link` varchar(500) DEFAULT NULL COMMENT 'link sản phẩm trên hệ thống',
              `Name` varchar(500) DEFAULT NULL,
              `sellerId` varchar(255) DEFAULT NULL,
              `quantity` int(11) DEFAULT '0',
              `maxQuantity` int(11) DEFAULT '0',
              `weight` float DEFAULT '0',
              `note` varchar(255) DEFAULT NULL,
              `specifics` text,
              `description` varchar(255) DEFAULT NULL,
              `image` text,
              `sourceId` text COMMENT 'mã/link gốc sản phẩm gốc',
              `orderId` int(11) DEFAULT NULL,
              `ParentSku` varchar(50) DEFAULT NULL,
              `ProductId` int(11) DEFAULT NULL COMMENT 'Khi khách hàng mua sản phẩm -> lưu trữ ở bảng product',
              `discount_local_amount` decimal(18,4) DEFAULT NULL COMMENT 'tong tien giam gia',
              `coupon_code` varchar(255) DEFAULT NULL,
              `PriceInclTax` decimal(18,4) DEFAULT NULL COMMENT 'Gia san pham co theu',
              `PriceExclTax` decimal(18,4) DEFAULT NULL COMMENT 'Gia san pham chua theu',
              `DiscountPercentInclTax` decimal(18,4) DEFAULT NULL COMMENT 'Phan tram gia giam co thue',
              `DiscountAmountInclTax` decimal(18,4) DEFAULT NULL COMMENT 'Tong tien giam gia co thue',
              `foreignCurrencyAmount` decimal(18,4) DEFAULT NULL,
              `foreignCurrency` varchar(10) DEFAULT NULL,
              `UnitPriceInclTax` decimal(18,4) DEFAULT NULL COMMENT 'Gia cua 1 san pham co thue',
              `UnitPriceExclTax` decimal(18,4) DEFAULT NULL COMMENT 'Gia cua 1 san pham ko thue',
              `ItemWeight` decimal(18,4) DEFAULT NULL,
              `ItemType` varchar(50) DEFAULT NULL COMMENT 'ItemType : Phân loại Kiểu đơn để gửi mail 22/10/2018',
              `ItemSubtotalExclTax` decimal(18,2) DEFAULT NULL COMMENT 'giá của tất cả Item trước thuế',
              `ItemTaxRate` decimal(18,2) DEFAULT '0.00',
              `ItemTax` decimal(18,2) DEFAULT NULL COMMENT 'thuế của tất cả các mặt hàng',
              `ItemSubtotalInclTax` decimal(18,2) DEFAULT NULL,
              `ItemLocalShippingAmount` decimal(18,2) DEFAULT '0.00' COMMENT 'giá trị vận chuyển',
              `ItemPriceAddition` decimal(18,2) DEFAULT '0.00',
              `ItemPriceMultiple` decimal(18,2) DEFAULT '0.00',
              `ItemInternationalShippingAmount` decimal(18,2) DEFAULT NULL COMMENT 'giá trị vận chuyển có thuế',
              `ItemDomesticShippingAmount` decimal(18,2) DEFAULT NULL,
              `ItemServiceRate` decimal(18,2) DEFAULT NULL COMMENT 'Phi dich vu tinh toan phan tram',
              `ItemFeeServiceAmount` decimal(18,2) DEFAULT NULL COMMENT 'Tổng phí dịch vụ của các Item',
              `ItemCustomFee` decimal(18,2) DEFAULT NULL COMMENT 'phí hải quan',
              `ItemCustomAdditionFee` decimal(18,2) DEFAULT NULL COMMENT 'phí hải quan phụ thu',
              `PaymentAdditionalFeeInclTax` decimal(18,2) DEFAULT NULL COMMENT 'Phí thu thêm theo hình thức thanh toán',
              `AdditionFeeAmount` decimal(18,2) DEFAULT '0.00' COMMENT 'thu thêm của tât ca các Item trong gói hàng',
              `AdditionFeeLocalAmount` decimal(18,2) DEFAULT '0.00' COMMENT 'Cộng thêm phí ở kho Local',
              `AdditionFeePaidAmount` decimal(18,2) DEFAULT '0.00' COMMENT 'Phí bổ sung Số tiền phải trả',
              `AdditionFeeNote` varchar(255) DEFAULT NULL,
              `AdditionFeeConfirmedDate` datetime DEFAULT CURRENT_TIMESTAMP COMMENT ' Ngày Xác nhận Phí bổ sung ',
              `AdditionFeeFreferPaymentMethod` int(11) DEFAULT NULL COMMENT 'Phí bổ sung Phương thức thanh toán ',
              `AdditionConfirmedByEmployeeId` int(11) DEFAULT NULL COMMENT 'Bổ sung xác nhận  EmployeeId ',
              `AdditionFeeConfirmedByCustomerStatus` int(11) DEFAULT '0' COMMENT 'confirm thu thêm từ khách ',
              `AdditionFeePaidLocalAmount` decimal(18,2) DEFAULT NULL,
              `AdditionFeeTotalLocalAmount` decimal(18,2) DEFAULT NULL,
              `OrderItemTotal` decimal(18,2) DEFAULT NULL COMMENT 'Tong gia gia tri don hang',
              `OrderItemTotalDisplay` decimal(18,2) DEFAULT NULL,
              `ExRate` float DEFAULT NULL,
              `TotalAmountInLocalCurrency` decimal(18,2) DEFAULT NULL,
              `TotalAmountInLocalCurrencyDisplay` decimal(18,2) DEFAULT NULL,
              `TotalAmountInLocalCurrencyFinal` decimal(18,2) DEFAULT NULL,
              `RefundedStatus` tinyint(1) DEFAULT '0' COMMENT '0: chưa refund. 1: refund',
              `RefundedAmount` decimal(18,4) DEFAULT NULL,
              `purchaseOrderCode` varchar(255) DEFAULT NULL,
              `TrackingShipingId` int(11) DEFAULT NULL,
              `WarehouseId` int(11) DEFAULT NULL,
              `purchaseOrderItemId` int(11) DEFAULT NULL,
              `ShippingStatus` int(11) DEFAULT '1' COMMENT 'vị trí hiện tại',
              `IsRequestInspection` tinyint(1) DEFAULT NULL COMMENT '0: không yêu cầu kiểm hàng, 1: có yêu cầu kiểm hàng',
              `UnitOfMessureId` int(11) DEFAULT NULL,
              `AdditionFeeRequestStatus` int(11) DEFAULT NULL COMMENT 'Tinh trang thu them. 1: thu them. 2: Khong thu them',
              `CustomerId` int(11) DEFAULT NULL,
              `Type` tinyint(4) DEFAULT NULL,
              `Quotation_Note` varchar(255) DEFAULT NULL,
              `Quotation_Time` datetime DEFAULT CURRENT_TIMESTAMP,
              `Quotation_Supporter` int(11) DEFAULT NULL,
              `saleConfirmStatus` tinyint(1) DEFAULT NULL,
              `siteId` int(11) DEFAULT NULL,
              `refundPaidAmount` decimal(18,2) DEFAULT '0.00',
              `itemEndTime` datetime DEFAULT NULL,
              `purchaseFailStatus` tinyint(1) DEFAULT '1' COMMENT 'trường lưu trạng thái đơn hang bị that bại khi mua',
              `purchaseFailReason` varchar(255) DEFAULT NULL COMMENT 'log lý do mua lỗi',
              `totalItemPoint` decimal(18,2) DEFAULT NULL,
              `currencyId` int(11) DEFAULT NULL,
              `ApproveStatus` int(11) DEFAULT '0' COMMENT '0:chưa duyệt, 1: duyệt , 2 : từ chối',
              `ApproveNote` varchar(255) DEFAULT NULL,
              `ApproveTime` datetime DEFAULT NULL,
              `refundTimes` int(11) DEFAULT NULL COMMENT 'Times of refunds',
              `IsClaim` tinyint(11) DEFAULT '0',
              `purchaseCompleteTime` datetime DEFAULT NULL,
              `sellerShipTime` datetime DEFAULT NULL COMMENT 'Người bán gửi hàng',
              `exportWarehouseStockInTime` datetime DEFAULT NULL,
              `exportWarehouseInpalletTime` datetime DEFAULT NULL,
              `exportWarehouseStockOutTime` datetime DEFAULT NULL,
              `airbillStartTime` datetime DEFAULT NULL,
              `localCustomTime` datetime DEFAULT NULL COMMENT 'localCustomTime',
              `localWarehouseStockinTime` datetime DEFAULT NULL,
              `localWarehouseStockoutTime` datetime DEFAULT NULL,
              `firstReturnTime` datetime DEFAULT NULL,
              `firstReturnStockoutTime` datetime DEFAULT NULL,
              `secondReturnTime` datetime DEFAULT NULL,
              `secondReturnStockoutTime` datetime DEFAULT NULL,
              `customerDeliveryTime` datetime DEFAULT NULL,
              `reportCreateDate` varchar(12) DEFAULT NULL,
              `reportCreateMonth` varchar(7) DEFAULT NULL,
              `saleAssigneeId` int(11) DEFAULT NULL,
              `saleAssignTime` datetime DEFAULT NULL,
              `reportSaleConfirmDate` varchar(12) DEFAULT NULL,
              `reportSaleConfirmMonth` varchar(7) DEFAULT NULL,
              `purchaseStatus` varchar(20) DEFAULT NULL COMMENT 'PENDING/PURCHASING/PURCHASED',
              `purchaseAssigneeId` int(11) DEFAULT NULL,
              `purchaseAssignTime` datetime DEFAULT NULL,
              `requestInspectType` varchar(20) DEFAULT NULL COMMENT 'NO/LOW/HIGH/NORMAL',
              `requestHoldReason` varchar(191) DEFAULT NULL,
              `purchaseUnitPrice` decimal(18,2) DEFAULT NULL,
              `purchaseQuantity` int(11) DEFAULT NULL,
              `purchaseTax` decimal(18,2) DEFAULT NULL,
              `purchaseShippingFee` decimal(18,2) DEFAULT NULL,
              `purchasePaidAmount` decimal(18,2) DEFAULT NULL,
              `purchaseTransactionCode` varchar(191) DEFAULT NULL,
              `purchaseTrackingCode` varchar(191) DEFAULT NULL,
              `customCategoryName` varchar(191) DEFAULT NULL,
              `customCategoryId` int(11) DEFAULT NULL,
              `purchaseNote` varchar(255) DEFAULT NULL,
              `reportPurchaseDate` varchar(12) DEFAULT NULL COMMENT 'VD :2017-01-01',
              `reportPurchaseMonth` varchar(7) DEFAULT NULL COMMENT 'VD :2017-01',
              `refundStatus` varchar(200) DEFAULT NULL COMMENT '1:hold for refund, 0:cancel hold ',
              `refundRequestAmount` decimal(18,4) DEFAULT NULL,
              `refundType` varchar(20) DEFAULT NULL COMMENT 'RF_PART , RF_TOTALLY',
              `reportRefundDate` varchar(12) DEFAULT NULL COMMENT 'VD :2017-01-01',
              `reportRefundMonth` varchar(7) DEFAULT NULL COMMENT 'VD :2017-01',
              `atProxyTime` datetime DEFAULT NULL,
              `localInspectNote` varchar(191) DEFAULT NULL,
              `localInspectStatus` varchar(20) DEFAULT NULL COMMENT 'INSPECTING/ADDFEE/READY2SHIP/SENT',
              `reportLocalInspectDate` varchar(12) DEFAULT NULL COMMENT 'VD :2017-01-01',
              `reportLocalInspectMonth` varchar(7) DEFAULT NULL COMMENT 'VD :2017-01',
              `reportLocalStockoutDate` varchar(12) DEFAULT NULL COMMENT 'VD :2017-01-01',
              `reportLocalStockoutMonth` varchar(7) DEFAULT NULL COMMENT 'VD :2017-01',
              `localShipmentCode` varchar(191) DEFAULT NULL,
              `localShipmentQuantity` int(11) DEFAULT NULL,
              `localShipmentCODAmount` decimal(18,2) DEFAULT NULL,
              `localShipmentStatus` varchar(191) DEFAULT NULL,
              `localShipmentProviderName` varchar(191) DEFAULT NULL,
              `localShipmentProviderLabelUrl` varchar(191) DEFAULT NULL,
              `localShipmentNote` varchar(191) DEFAULT NULL,
              `reportCustomerDeliveryDate` varchar(12) DEFAULT NULL COMMENT 'VD :2017-01-01',
              `reportCustomerDeliveryMonth` varchar(7) DEFAULT NULL COMMENT 'VD :2017-01',
              `returnProcessNote` varchar(191) DEFAULT NULL,
              `purchaseOrderId` int(11) DEFAULT NULL,
              `createTime` datetime DEFAULT CURRENT_TIMESTAMP,
              `supportStartTime` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Tính từ lúc bắt đầu supporting',
              `supportCompleteTime` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Đấu dấu đã support xong',
              `saleAssignManagerId` int(11) DEFAULT NULL COMMENT 'Quản lý sale phân công nhân viên chăm sóc, nếu NULL có nghĩa là phân công tự động',
              `purchaseAssignManagerId` int(11) DEFAULT NULL COMMENT 'Quản lý Purchase phân công nhân viên mua hàng, nếu NULL có nghĩa là phân công tự động',
              `purchaseStartTime` datetime DEFAULT NULL COMMENT 'Tính từ lúc bắt đầu purchasing',
              `promotionId` int(11) DEFAULT NULL,
              `status` varchar(20) DEFAULT 'NEW' COMMENT 'SUPPORTING/SUPPORTED/SUPPORT FAIL/ READY2PURCHASE/PURCHASING/PURCHASE FAIL/PURCHASE PENDING/PURCHASED/ EXPWH_STOCKIN/EXPWH_HOLD/EXPWH_READY2OUT/EXPWH_STOCKOUT/ IMPWH_STOCKIN/IMPWH_HOLD/IMPWH_READY2OUT/IMPWH_STOCKOUT/IMPWH_1RETURN|/IMPWH_2RETURN/ CUSTOMER_R',
              `localProductName` varchar(255) DEFAULT NULL COMMENT 'Tên sản phẩm tiếng địa phương(dùng cho shipment)',
              `isNoTrackingCode` int(11) DEFAULT NULL COMMENT 'Danh dau san pham khong co ma tracking',
              `markSellerRefund` int(11) DEFAULT '0' COMMENT '1:Seller Refund full; 2: Seller refund partial',
              `markRefuseReceive` int(11) DEFAULT '0' COMMENT '1:Refuse receive tracking',
              `markRequestHold` int(11) DEFAULT '0' COMMENT '1 request hold;',
              `markDelivered` int(11) DEFAULT NULL COMMENT '1',
              `inspectionSpecifics` varchar(255) DEFAULT NULL COMMENT 'Mo ta thong tin kiem hang cho boxme va tren label',
              `replaceOrderItemId` int(11) DEFAULT NULL COMMENT 'OrderItemId which is replaced',
              `originCategoryId` varchar(20) DEFAULT NULL COMMENT 'Category L1',
              `subCategoryId` varchar(20) DEFAULT NULL COMMENT 'Category L2',
              `productCategoryAlias` varchar(20) DEFAULT NULL COMMENT 'alias category (amazon,ebay)',
              `exportWarehouseQuantityReceived` int(11) DEFAULT NULL COMMENT 'So luong da nhan duoc tai kho my',
              `parcelCode` varchar(30) DEFAULT NULL COMMENT 'Mã thẻ kho',
              `purchasePaypalAmount` decimal(18,2) DEFAULT NULL COMMENT 'Amount paypal',
              `purchaseMerchantEmail` varchar(255) DEFAULT NULL COMMENT 'Email người bán',
              `storeId` int(11) DEFAULT NULL,
              `manifestCode` varchar(20) DEFAULT NULL COMMENT 'vào bảng order_item để lưu tên kiện',
              `actualWeightReceived` double(8,2) DEFAULT NULL COMMENT 'lưu thông tin cân nặng thực tế của order_item (theo gram)',
              `MarkSalesRefundSOI` varchar(200) DEFAULT NULL COMMENT 'SOI : MarkSalesRefundSOI  (varchar200) :  RFSOI-idsoi  —> NULL VIEW POPUP va NGUOC LAI',
              `MarkOperationRefundSOI` varchar(200) DEFAULT NULL COMMENT 'SOI : MarkOperationRefundSOI  (varchar200) :  RFSOI-OP-idso   —> NULL VIEW POPUP va NGUOC LAI',
              `invoiceUrl` varchar(255) DEFAULT NULL COMMENT 'link upload hoa don hang ship',
              `customerVisaCardNo` int(11) DEFAULT NULL,
              `cusIdentityImg` varchar(250) DEFAULT NULL,
              `purchaseOtherPaymentSource` varchar(255) DEFAULT NULL,
              `purchaseOtherPaymentAmount` double(18,2) DEFAULT NULL,
              `customerVisaCardImg` varchar(255) DEFAULT NULL COMMENT 'Url ảnh thẻ visa',
              `customerCMNDImg` varchar(255) DEFAULT NULL COMMENT 'Url anh cmnd',
              `sanbox` tinyint(1) DEFAULT '0' COMMENT '0: cho don tao mau hang , 1:  don test sanbox cac merchance',
              `payment_status` varchar(255) DEFAULT NULL,
              `itemPriceApi` decimal(20,2) DEFAULT NULL COMMENT 'Giá của sản phẩm khi call api. (jpy - usd - gb)',
              `itemCurrencyApi` varchar(255) DEFAULT NULL COMMENT 'jpy - usd - gb - vnd',
              `itemExRateApi` decimal(20,2) DEFAULT NULL COMMENT 'Tỉ gia chuyển đổi hiện tại từ tiền của api trả về qua VNĐ',
              `NotePurchaseWarehouseId` int(11) DEFAULT NULL,
              `accountPurchaseId` int(11) DEFAULT NULL,
              `changingPrice` decimal(18,2) DEFAULT NULL,
              `packingWood` decimal(18,2) DEFAULT '0.00' COMMENT 'phí đóng gỗ',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=537976 DEFAULT CHARSET=utf8;