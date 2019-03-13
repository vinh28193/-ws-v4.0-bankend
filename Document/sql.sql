
-- ************************************** `Customer`

CREATE TABLE `Customer`
(
 `CustomerId`   int NOT NULL AUTO_INCREMENT ,
 `CustomerName` varchar(40) NOT NULL ,
 `Phone`        varchar(20) ,
PRIMARY KEY (`CustomerId`),
UNIQUE KEY `AK1_Customer_CustomerName` (`CustomerName`)
) AUTO_INCREMENT=1 COMMENT='Basic information about Customer';


-- ************************************** `Order`

CREATE TABLE `Order`
(
 `OrderId`     int NOT NULL AUTO_INCREMENT ,
 `OrderNumber` varchar(10) ,
 `CustomerId`  int NOT NULL ,
 `OrderStatusId`  int NOT NULL ,
 `OrderDate`   datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 `TotalAmount` decimal(12,2) NOT NULL ,
PRIMARY KEY (`OrderId`),
UNIQUE KEY `AK1_Order_OrderNumber` (`OrderNumber`),
KEY `FK_Order_CustomerId_Customer` (`CustomerId`),
CONSTRAINT `FK_Order_CustomerId_Customer` FOREIGN KEY `FK_Order_CustomerId_Customer` (`CustomerId`) REFERENCES `Customer` (`CustomerId`),
KEY `FK_Order_OrderStatusId_OrderStatus` (`OrderStatusId`),
CONSTRAINT `FK_Order_OrderStatusId_OrderStatus` FOREIGN KEY `FK_Order_OrderStatusId_OrderStatus` (`OrderStatusId`) REFERENCES `OrderStatus` (`OrderStatusId`)
) AUTO_INCREMENT=1 COMMENT='Order information like Date, Ammount';


-- ************************************** `OrderStatus`

CREATE TABLE `OrderStatus`
(
 `OrderStatusId`     int NOT NULL AUTO_INCREMENT ,
 `Name` varchar(10) ,
 PRIMARY KEY (`OrderStatusId`)
 ) AUTO_INCREMENT=1 COMMENT='Basic information about OrderStatus';

-- ************************************** `Shipment`

CREATE TABLE `Shipment`
(
 `ShipmentId`     int NOT NULL AUTO_INCREMENT ,
 `OrderId`   int NOT NULL ,
 `ShipmentDate`   datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 PRIMARY KEY (`ShipmentId`),
 KEY `FK_Shipment_OrderId_Order` (`OrderId`),
 CONSTRAINT `FK_Shipment_OrderId_Order` FOREIGN KEY `FK_Shipment_OrderId_Order` (`OrderId`) REFERENCES `Order` (`OrderId`)
) AUTO_INCREMENT=1 COMMENT='Order information like Date, Ammount';

/*
-- ************************************** `OrderItem`

CREATE TABLE `OrderItem`
(
 `OrderId`   int NOT NULL ,
 `ProductId` int NOT NULL ,
 `UnitPrice` decimal(12,2) NOT NULL ,
 `Quantity`  int NOT NULL ,
PRIMARY KEY (`OrderId`, `ProductId`),
KEY `FK_OrderItem_OrderId_Order` (`OrderId`),
CONSTRAINT `FK_OrderItem_OrderId_Order` FOREIGN KEY `FK_OrderItem_OrderId_Order` (`OrderId`) REFERENCES `Order` (`OrderId`),
KEY `FK_OrderItem_ProductId_Product` (`ProductId`),
CONSTRAINT `FK_OrderItem_ProductId_Product` FOREIGN KEY `FK_OrderItem_ProductId_Product` (`ProductId`) REFERENCES `Product` (`ProductId`)
) COMMENT='Information about like Price, Quantity';
*/

-- ************************************** `OrderItem`

CREATE TABLE `OrderItem`
(
 `OrderItemId`     int NOT NULL AUTO_INCREMENT ,
 `OrderId`   int NOT NULL ,
 `ProductId` int NOT NULL ,
 `UnitPrice` decimal(12,2) NOT NULL ,
 `Quantity`  int NOT NULL ,
 PRIMARY KEY (`OrderItemId`),
 KEY `FK_OrderItem_OrderId_Order` (`OrderId`),
 CONSTRAINT `FK_OrderItem_OrderId_Order` FOREIGN KEY `FK_OrderItem_OrderId_Order` (`OrderId`) REFERENCES `Order` (`OrderId`),
 KEY `FK_OrderItem_ProductId_Product` (`ProductId`),
 CONSTRAINT `FK_OrderItem_ProductId_Product` FOREIGN KEY `FK_OrderItem_ProductId_Product` (`ProductId`) REFERENCES `Product` (`ProductId`)
) AUTO_INCREMENT=1  COMMENT='Information about like Price, Quantity';


-- ************************************** `Delivery`

CREATE TABLE `Delivery`
(
 `DeliveryId`  int NOT NULL AUTO_INCREMENT ,
 `Deliverydate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 `SupplierId`   int NOT NULL ,
PRIMARY KEY (`DeliveryId`),
KEY `FK_Delivery_SupplierId_Supplier` (`SupplierId`),
CONSTRAINT `FK_Delivery_SupplierId_Supplier` FOREIGN KEY `FK_Delivery_SupplierId_Supplier` (`SupplierId`) REFERENCES `Supplier` (`SupplierId`)
) AUTO_INCREMENT=1 COMMENT='Basic information about Delivery - ';


-- ************************************** `OrderDetailDelivery`

CREATE TABLE `OrderDetailDelivery`
(
 `OrderDetailDeliveryId`     int NOT NULL AUTO_INCREMENT ,
 `OrderId` int NOT NULL ,
 `OrderItemId`   int NOT NULL ,
 `DeliveryId`   int NOT NULL ,
 PRIMARY KEY (`OrderDetailDeliveryId`),
 KEY `FK_OrderDetailDelivery_OrderId_Order` (`OrderId`),
 CONSTRAINT `FK_OrderDetailDelivery_OrderId_Order` FOREIGN KEY `FK_OrderDetailDelivery_OrderId_Order` (`OrderId`) REFERENCES `Order` (`OrderId`),
 KEY `FK_OrderDetailDelivery_OrderItemId_OrderItem` (`OrderItemId`),
 CONSTRAINT `FK_OrderItem_ProductId_Product` FOREIGN KEY `FK_OrderItem_ProductId_Product` (`OrderItemId`) REFERENCES `OrderItem` (`OrderItemId`),
 KEY `FK_OrderDetailDelivery_DeliveryId_Delivery` (`DeliveryId`),
 CONSTRAINT `FK_OrderDetailDelivery_DeliveryId_Delivery` FOREIGN KEY `FK_OrderDetailDelivery_DeliveryId_Delivery` (`DeliveryId`) REFERENCES `Delivery` (`DeliveryId`)
)AUTO_INCREMENT=1 COMMENT='Basic information about OrderDetailDelivery';


-- ************************************** `Supplier`

CREATE TABLE `Supplier`
(
 `SupplierId`  int NOT NULL AUTO_INCREMENT ,
 `CompanyName` varchar(40) NOT NULL ,
 `Phone`       varchar(20) ,
 PRIMARY KEY (`SupplierId`),
 UNIQUE KEY `AK1_Supplier_CompanyName` (`CompanyName`)
) AUTO_INCREMENT=1 COMMENT='Basic information about Supplier - Shop/ người bán trên AMAZON/EBAY ';

-- ************************************** `Product`

CREATE TABLE `Product`
(
 `ProductId`      int NOT NULL AUTO_INCREMENT ,
 `ProductName`    varchar(50) NOT NULL ,
 `SupplierId`     int NOT NULL ,
 `UnitPrice`      decimal(12,2) ,
 `IsDiscontinued` bit NOT NULL DEFAULT 0 ,
PRIMARY KEY (`ProductId`),
UNIQUE KEY `AK1_Product_SupplierId_ProductName` (`ProductName`, `SupplierId`),
KEY `FK_Product_SupplierId_Supplier` (`SupplierId`),
CONSTRAINT `FK_Product_SupplierId_Supplier` FOREIGN KEY `FK_Product_SupplierId_Supplier` (`SupplierId`) REFERENCES `Supplier` (`SupplierId`)
) AUTO_INCREMENT=1 COMMENT='Basic information about Product';



-- ************************************** `ProductCategory`

CREATE TABLE `ProductCategory`
(
 `ProductCategoryId`      int NOT NULL AUTO_INCREMENT ,
 `ProductCategoryName`    varchar(50) NOT NULL ,
 `ProductId`     int NOT NULL ,
 PRIMARY KEY (`ProductCategoryId`),
 KEY `FK_ProductCategory_ProductId_Product` (`ProductId`),
 CONSTRAINT `FK_ProductCategory_ProductId_Product` FOREIGN KEY `FK_ProductCategory_ProductId_Product` (`ProductId`) REFERENCES `Product` (`ProductId`)
) AUTO_INCREMENT=1 COMMENT='Basic information about ProductCategory';











