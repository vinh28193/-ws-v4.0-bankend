
-- ************************************** `Customer`

CREATE TABLE `Customer`
(
 `customer_id`   int NOT NULL AUTO_INCREMENT ,
 `customer_name` varchar(40) NOT NULL ,
 `customer_phone`        varchar(20) ,
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
 `order_date`   datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 `total_amount` decimal(12,2) NOT NULL ,
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
 `order_status_name` varchar(10) ,
 PRIMARY KEY (`order_status_id`)
 ) AUTO_INCREMENT=1 COMMENT='Basic information about OrderStatus';

-- ************************************** `Shipment`

CREATE TABLE `Shipment`
(
 `shipment_id`     int NOT NULL AUTO_INCREMENT ,
 `order_id`   int NOT NULL ,
 `shipment_date`   datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 PRIMARY KEY (`shipment_id`),
 KEY `FK_Shipment_order_id_Order` (`order_id`),
 CONSTRAINT `FK_Shipment_order_id_Order` FOREIGN KEY `FK_Shipment_order_id_Order` (`order_id`) REFERENCES `Order` (`order_id`)
) AUTO_INCREMENT=1 COMMENT='Order information like Date, Ammount';


-- ************************************** `OrderItem`

CREATE TABLE `OrderItem`
(
 `order_item_id`     int NOT NULL AUTO_INCREMENT ,
 `order_id`   int NOT NULL ,
 `product_id` int NOT NULL ,
 `unit_price` decimal(12,2) NOT NULL ,
 `order_tem_quantity`  int NOT NULL ,
 PRIMARY KEY (`order_item_id`),
 KEY `FK_OrderItem_order_id_Order` (`order_id`),
 CONSTRAINT `FK_OrderItem_order_id_Order` FOREIGN KEY `FK_OrderItem_order_id_Order` (`order_id`) REFERENCES `Order` (`order_id`),
 KEY `FK_OrderItem_product_id_Product` (`product_id`),
 CONSTRAINT `FK_OrderItem_product_id_Product` FOREIGN KEY `FK_OrderItem_product_id_Product` (`product_id`) REFERENCES `Product` (`product_id`)
) AUTO_INCREMENT=1  COMMENT='Information about like Price, quantity';


-- ************************************** `Delivery`

CREATE TABLE `Delivery`
(
 `delivery_id`  int NOT NULL AUTO_INCREMENT ,
 `delivery_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 `supplier_id`   int NOT NULL ,
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
 PRIMARY KEY (`supplier_id`),
 UNIQUE KEY `AK1_Supplier_company_name` (`company_name`)
) AUTO_INCREMENT=1 COMMENT='Basic information about Supplier - Shop/ người bán trên AMAZON/EBAY ';

-- ************************************** `Product`

CREATE TABLE `Product`
(
 `product_id`      int NOT NULL AUTO_INCREMENT ,
 `product_name`    varchar(50) NOT NULL ,
 `supplier_id`     int NOT NULL ,
 `unit_price`      decimal(12,2) ,
 `is_discontinued` bit NOT NULL DEFAULT 0 ,
PRIMARY KEY (`product_id`),
UNIQUE KEY `AK1_Product_supplier_id_product_name` (`product_name`, `supplier_id`),
KEY `FK_Product_supplier_id_Supplier` (`supplier_id`),
CONSTRAINT `FK_Product_supplier_id_Supplier` FOREIGN KEY `FK_Product_supplier_id_Supplier` (`supplier_id`) REFERENCES `Supplier` (`supplier_id`)
) AUTO_INCREMENT=1 COMMENT='Basic information about Product';



-- ************************************** `ProductCategory`

CREATE TABLE `ProductCategory`
(
 `product_category_id`      int NOT NULL AUTO_INCREMENT ,
 `product_category_name`    varchar(50) NOT NULL ,
 `product_id`     int NOT NULL ,
 PRIMARY KEY (`product_category_id`),
 KEY `FK_ProductCategory_product_id_Product` (`product_id`),
 CONSTRAINT `FK_ProductCategory_product_id_Product` FOREIGN KEY `FK_ProductCategory_product_id_Product` (`product_id`) REFERENCES `Product` (`product_id`)
) AUTO_INCREMENT=1 COMMENT='Basic information abouct ProductCategory';



-- ************************************** `SurchargeTheCategory`

CREATE TABLE `SurchargeTheCategory`
(
 `surcharge_the_category_id`      int NOT NULL AUTO_INCREMENT ,
 `SurchargeTheCategory_name`    varchar(50) NOT NULL ,
 `product_id`     int NOT NULL ,
 PRIMARY KEY (`surcharge_the_category_id`),
 KEY `FK_SurchargeTheCategory_surcharge_the_category_id_ProductCategory` (`product_category_id`),
 CONSTRAINT `FK_SurchargeTheCategory_surcharge_the_category_id_ProductCategory` FOREIGN KEY `FK_SurchargeTheCategory_surcharge_the_category_id_ProductCategory` (`product_category_id`) REFERENCES `ProductCategory` (`product_category_id`)
) AUTO_INCREMENT=1 COMMENT='Basic information abouct SurchargeTheCategory';












