
-- ************************************** `Customer`

CREATE TABLE `Customer`
(
 `customer_id`   int NOT NULL AUTO_INCREMENT ,
 `customer_name` varchar(40) NOT NULL ,
 `customer_phone`        varchar(20) ,
 `inserted_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
 `updated_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
PRIMARY KEY (`customer_id`),
UNIQUE KEY `AK1_Customer_customer_name` (`customer_name`)
) AUTO_INCREMENT=1 COMMENT='Basic information about Customer';


-- ************************************** `Order`

CREATE TABLE `Order`
(
 `order_id`     int NOT NULL AUTO_INCREMENT ,
 `order_number` varchar(10) ,
 `customer_id`  int NOT NULL ,
 `order_status_id`  int NOT NULL ,
 `order_details` varchar(255) COMMENT 'mô tả đơn hàng' ,
 `order_date`   datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 `total_amount` decimal(12,2) NOT NULL ,
 `inserted_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
 `updated_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
PRIMARY KEY (`order_id`),
UNIQUE KEY `AK1_Order_order_number` (`order_number`),
KEY `FK_Order_customer_id_Customer` (`customer_id`),
CONSTRAINT `FK_Order_customer_id_Customer` FOREIGN KEY `FK_Order_customer_id_Customer` (`customer_id`) REFERENCES `Customer` (`customer_id`),
KEY `FK_Order_order_status_id_OrderStatus` (`order_status_id`),
CONSTRAINT `FK_Order_order_status_id_OrderStatus` FOREIGN KEY `FK_Order_order_status_id_OrderStatus` (`order_status_id`) REFERENCES `OrderStatus` (`order_status_id`)
) AUTO_INCREMENT=1 COMMENT='Order information like Date, Ammount';


-- ************************************** `OrderStatus`

CREATE TABLE `OrderStatus`
(
 `order_status_id`     int NOT NULL AUTO_INCREMENT ,
 `order_status_name` varchar(10) COMMENT ' eg Cancelled , Completed , Refund , không có trường hợp refund 1 phần đơn hàng mà huỷ đi tạo đơn ',
 `order_status_description` varchar(255) COMMENT 'mô tả trang thái',
 `inserted_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
 `updated_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
  PRIMARY KEY (`order_status_id`)
 ) AUTO_INCREMENT=1 COMMENT='Basic information about OrderStatus';

-- ************************************** `Shipment`

CREATE TABLE `Shipment`
(
 `shipment_id`     int NOT NULL AUTO_INCREMENT ,
 `order_id`   int NOT NULL ,
 `invoices_id`   int NOT NULL ,
 `shipment_date`   datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 `shipment_tracking_number` varchar(255) COMMENT 'tracking number',
 `other_shipment_details` varchar(255) COMMENT ' other_shipment_details ',
 `inserted_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
 `updated_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
 PRIMARY KEY (`shipment_id`),
 KEY `FK_Shipment_order_id_Order` (`order_id`),
 CONSTRAINT `FK_Shipment_order_id_Order` FOREIGN KEY `FK_Shipment_order_id_Order` (`order_id`) REFERENCES `Order` (`order_id`),
 KEY `FK_Shipment_invoices_id_Invoices` (`invoices_id`),
 CONSTRAINT `FK_Shipment_invoices_id_Invoices` FOREIGN KEY `FK_Shipment_invoices_id_Invoices` (`invoices_id`) REFERENCES `Invoices` (`invoices_id`)
) AUTO_INCREMENT=1 COMMENT='Basic information about Shipment , Order information like Date, Ammount';


-- ************************************** `ShipmentItems`

CREATE TABLE `ShipmentItems`
(
  `shipment_items_id`     int NOT NULL AUTO_INCREMENT ,
  `order_item_id` int NOT NULL ,
  `shipment_id` int NOT NULL ,
  `inserted_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
  `updated_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
  PRIMARY KEY (`shipment_items_id`),
  KEY `FK_ShipmentItems_order_item_id_OrderItem` (`order_item_id`),
  CONSTRAINT `FK_ShipmentItems_order_item_id_OrderItem` FOREIGN KEY `FK_ShipmentItems_order_item_id_OrderItem` (`order_item_id`) REFERENCES `OrderItem` (`order_item_id`),
  KEY `FK_ShipmentItems_shipment_id_Shipment` (`shipment_id`),
  CONSTRAINT `FK_ShipmentItems_shipment_id_ShipmentItems` FOREIGN KEY `FK_ShipmentItems_shipment_id_ShipmentItems` (`shipment_id`) REFERENCES `Shipment` (`shipment_id`)
) AUTO_INCREMENT=1 COMMENT='Thông tin gửi gói kiện hàng cho Boxme';


-- ************************************** `Invoices`
CREATE TABLE `Invoices`
(
  `invoices_id`     int NOT NULL AUTO_INCREMENT ,
  `invoices_number` varchar(10) COMMENT ' sinh theo quy luât để lẫy mã hoá đơn đưa cho khách hàng chính là mã BIN đang dùng weshop 3.1 ',
  `invoice_status_id` int NOT NULL ,
  `order_id` int NOT NULL ,
  `invoices_details` varchar(255) COMMENT 'mô tả  invoices details',
  `invoices_date`   datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `inserted_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
  `updated_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
  PRIMARY KEY (`invoices_id`),
  KEY `FK_Invoices_invoice_status_id_InvoiceStatus` (`invoice_status_id`),
  CONSTRAINT `FK_Invoices_invoice_status_id_InvoiceStatus` FOREIGN KEY `FK_Invoices_invoice_status_id_InvoiceStatus` (`invoice_status_id`) REFERENCES `InvoiceStatus` (`invoice_status_id`),
  KEY `FK_Invoices_order_id_Order` (`order_id`),
  CONSTRAINT `FK_Invoices_order_id_Order` FOREIGN KEY `FK_Invoices_order_id_Order` (`order_id`) REFERENCES `Order` (`order_id`)
) AUTO_INCREMENT=1 COMMENT='Basic information about Invoices';


-- ************************************** `InvoiceStatus`

CREATE TABLE `InvoiceStatus`
(
  `invoice_status_id`     int NOT NULL AUTO_INCREMENT ,
  `invoice_status_description` varchar(255) COMMENT ' -eg Issued , Paid',
  `inserted_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
  `updated_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
  PRIMARY KEY (`invoice_status_id`)
) AUTO_INCREMENT=1 COMMENT='Basic information about InvoiceStatus';


-- ************************************** `Payments`
CREATE TABLE `Payments`
(
  `payments_id`     int NOT NULL AUTO_INCREMENT ,
  `invoices_id` int NOT NULL ,
  `payments_date`   datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `payments_amount`   int NOT NULL ,
  `inserted_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
  `updated_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
  PRIMARY KEY (`payments_id`),
  KEY `FK_Payments_invoices_id_Invoices` (`invoices_id`),
  CONSTRAINT `FK_Payments_invoices_id_Invoices` FOREIGN KEY `FK_Payments_invoices_id_Invoices` (`invoices_id`) REFERENCES `Invoices` (`invoices_id`)
) AUTO_INCREMENT=1 COMMENT='Basic information about Payments';



-- ************************************** `OrderItem`

CREATE TABLE `OrderItem`
(
 `order_item_id`     int NOT NULL AUTO_INCREMENT ,
 `order_id`   int NOT NULL ,
 `product_id` int NOT NULL ,
 `unit_price` decimal(12,2) NOT NULL ,
 `order_tem_quantity`  int NOT NULL ,
 `inserted_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
 `updated_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
 PRIMARY KEY (`order_item_id`),
 KEY `FK_OrderItem_order_id_Order` (`order_id`),
 CONSTRAINT `FK_OrderItem_order_id_Order` FOREIGN KEY `FK_OrderItem_order_id_Order` (`order_id`) REFERENCES `Order` (`order_id`),
 KEY `FK_OrderItem_product_id_Product` (`product_id`),
 CONSTRAINT `FK_OrderItem_product_id_Product` FOREIGN KEY `FK_OrderItem_product_id_Product` (`product_id`) REFERENCES `Product` (`product_id`)
) AUTO_INCREMENT=1  COMMENT='Information about like Price, quantity';


-- ************************************** `OrderItemStatus`

CREATE TABLE `OrderItemStatus`
(
  `orderitem_status_id`     int NOT NULL AUTO_INCREMENT ,
  `orderitem_status_name` varchar(10) COMMENT ' eg Delivered , Out of Stock ',
  `orderitem_status_description` varchar(255) COMMENT 'mô tả trang thái',
  `inserted_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
  `updated_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
  PRIMARY KEY (`orderitem_status_id`)
) AUTO_INCREMENT=1 COMMENT='Basic information about OrderItemStatus';

-- ************************************** `Delivery`

CREATE TABLE `Delivery`
(
 `delivery_id`  int NOT NULL AUTO_INCREMENT ,
 `delivery_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 `supplier_id`   int NOT NULL ,
  `inserted_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
  `updated_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
  PRIMARY KEY (`delivery_id`),
KEY `FK_Delivery_supplier_id_Supplier` (`supplier_id`),
CONSTRAINT `FK_Delivery_supplier_id_Supplier` FOREIGN KEY `FK_Delivery_supplier_id_Supplier` (`supplier_id`) REFERENCES `Supplier` (`supplier_id`)
) AUTO_INCREMENT=1 COMMENT='Basic information about Delivery - ';


-- ************************************** `OrderDetailDelivery`

CREATE TABLE `OrderDetailDelivery`
(
 `order_detaildelivery_id`     int NOT NULL AUTO_INCREMENT ,
 `order_id` int NOT NULL ,
 `order_item_id`   int NOT NULL ,
 `delivery_id`   int NOT NULL ,
  `inserted_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
  `updated_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
  PRIMARY KEY (`order_detaildelivery_id`),
 KEY `FK_OrderDetailDelivery_order_id_Order` (`order_id`),
 CONSTRAINT `FK_OrderDetailDelivery_order_id_Order` FOREIGN KEY `FK_OrderDetailDelivery_order_id_Order` (`order_id`) REFERENCES `Order` (`order_id`),
 KEY `FK_OrderDetailDelivery_order_item_id_OrderItem` (`order_item_id`),
 CONSTRAINT `FK_OrderItem_product_id_Product` FOREIGN KEY `FK_OrderItem_product_id_Product` (`order_item_id`) REFERENCES `OrderItem` (`order_item_id`),
 KEY `FK_OrderDetailDelivery_delivery_id_Delivery` (`delivery_id`),
 CONSTRAINT `FK_OrderDetailDelivery_delivery_id_Delivery` FOREIGN KEY `FK_OrderDetailDelivery_delivery_id_Delivery` (`delivery_id`) REFERENCES `Delivery` (`delivery_id`)
)AUTO_INCREMENT=1 COMMENT='Basic information about OrderDetailDelivery';


-- ************************************** `Supplier`

CREATE TABLE `Supplier`
(
 `supplier_id`  int NOT NULL AUTO_INCREMENT ,
 `company_name` varchar(40) NOT NULL ,
 `supplier_phone`       varchar(20) ,
  `inserted_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
  `updated_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
  PRIMARY KEY (`supplier_id`),
 UNIQUE KEY `AK1_Supplier_company_name` (`company_name`)
) AUTO_INCREMENT=1 COMMENT='Basic information about Supplier - Shop/ người bán trên AMAZON/EBAY ';

-- ************************************** `Product`

CREATE TABLE `Product`
(
 `product_id`      int NOT NULL AUTO_INCREMENT ,
 `supplier_id`     int NOT NULL ,
 `product_status_id`     int NOT NULL ,
 `product_name`    varchar(50) NOT NULL ,
 `unit_price`      decimal(12,2) ,
 `is_discontinued` bit NOT NULL DEFAULT 0 ,
 `inserted_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
 `updated_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
PRIMARY KEY (`product_id`),
UNIQUE KEY `AK1_Product_supplier_id_product_name` (`product_name`, `supplier_id`),
KEY `FK_Product_supplier_id_Supplier` (`supplier_id`),
CONSTRAINT `FK_Product_supplier_id_Supplier` FOREIGN KEY `FK_Product_supplier_id_Supplier` (`supplier_id`) REFERENCES `Supplier` (`supplier_id`),
KEY `FK_Product_product_status_id_ProductStatus` (`product_status_id`),
CONSTRAINT `FK_Product_product_status_id_ProductStatus` FOREIGN KEY `FK_Product_product_status_id_ProductStatus` (`product_status_id`) REFERENCES `ProductStatus` (`product_status_id`)
) AUTO_INCREMENT=1 COMMENT='Basic information about Product';

-- ************************************** `ProductStatus`

CREATE TABLE `ProductStatus`
(
  `product_status_id`      int NOT NULL AUTO_INCREMENT ,
  `product_status_name`    varchar(50) NOT NULL COMMENT ' Ex : unlock , pedding , lock ' ,
  `product_status_description` varchar(255) COMMENT ' text mô tả trang thái',
  `inserted_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
  `updated_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
  PRIMARY KEY (`product_status_id`)
) AUTO_INCREMENT=1 COMMENT='Basic information abouct ProductStatus Ex : unlock , pedding , lock ';


-- ************************************** `ProductCategory`

CREATE TABLE `ProductCategory`
(
  `product_category_id`      int NOT NULL AUTO_INCREMENT ,
  `category_id`    int NOT NULL ,
  `product_id`     int NOT NULL ,
  `inserted_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
  `updated_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
  PRIMARY KEY (`product_category_id`),
  KEY `FK_ProductCategory_product_id_Product` (`product_id`),
  CONSTRAINT `FK_ProductCategory_product_id_Product` FOREIGN KEY `FK_ProductCategory_product_id_Product` (`product_id`) REFERENCES `Product` (`product_id`),
  KEY `FK_ProductCategory_category_id_Category` (`category_id`),
  CONSTRAINT `FK_ProductCategory_category_id_Category` FOREIGN KEY `FK_ProductCategory_category_id_Category` (`category_id`) REFERENCES `Category` (`category_id`)
) AUTO_INCREMENT=1 COMMENT='Basic information abouct ProductCategory';



-- ************************************** `Category`

CREATE TABLE `Category`
(
 `category_id`      int NOT NULL AUTO_INCREMENT ,
 `category_name`    varchar(50) NOT NULL ,
 `parent_id`     int NOT NULL ,
 `inserted_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
 `updated_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
 PRIMARY KEY (`category_id`)
) AUTO_INCREMENT=1 COMMENT='Basic information abouct Category';



-- ************************************** `SurchargeTheCategory`

CREATE TABLE `SurchargeTheCategory`
(
 `surcharge_the_category_id`      int NOT NULL AUTO_INCREMENT ,
 `SurchargeTheCategory_name`    varchar(50) NOT NULL ,
 `category_id`     int NOT NULL ,
 `user_id`     int NOT NULL COMMENT 'Nhân viên Update phí phụ thu cho danh mục - FK table User' ,
 `inserted_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
 `updated_at`  datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'timestamp with time zone = not null',
 PRIMARY KEY (`surcharge_the_category_id`),
 KEY `FK_SurchargeTheCategory_surcharge_the_category_id_Category` (`category_id`),
 CONSTRAINT `FK_SurchargeTheCategory_surcharge_the_category_id_Category` FOREIGN KEY `FK_SurchargeTheCategory_surcharge_the_category_id_Category` (`category_id`) REFERENCES `Category` (`category_id`)
) AUTO_INCREMENT=1 COMMENT='Basic information abouct SurchargeTheCategory - Phụ Thu của danh mục để thu ';












