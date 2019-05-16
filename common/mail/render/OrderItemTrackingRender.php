<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-08-06
 * Time: 08:55
 */

namespace common\mail\render;

use common\mail\Template;
use Yii;
use common\components\Store;
use common\mail\BaseMailRender;
use common\models\model\Order;
use common\models\model\OrderItem;

class OrderItemTrackingRender extends BaseMailRender
{

    /**
     * @param Template $template
     * @return mixed|void
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    public function extractTemplate($template)
    {
        parent::extractTemplate($template);
        $country = $template->getReplace(Template::NEEDLE_COUNTRY);
        $version = $template->getReplace(Template::NEEDLE_VERSION);
        $title = $content = $day = '';
        $itemTrackings = $message = [];
        if(($item = $template->getActiveModel())!== null && $item instanceof OrderItem){
            switch ($item->ShippingStatus){
                case Order::SHIPPING_STATUS_AT_SELLER:
                    $title = Yii::t("backend","Purchase success");
                    $content = Yii::t("backend","Your order has been purchased successfully");
                    $day = '';
                    break;
                case Order::SHIPPING_STATUS_AT_WAREHOUSE_NOT_INSPECT:
                    $title = Yii::t("backend","ITEM ARRIVES AT AMERICAN WAREHOUSE");
                    $content = Yii::t("backend","The ordered item has just arrived at {version} Warehouse in America",["version" => $version]);
                    $day = '';
                    break;
                case Order::SHIPPING_STATUS_AT_PROXY_PARTIAL:
                case Order::SHIPPING_STATUS_AT_PROXY_FULL:
                    $title = Yii::t("backend","STOCKIN TO AMERICAN WAREHOUSE");
                    $content = Yii::t("backend","The ordered items have been stocked in our {version} Warehouse in America:",["version" => $version]);
                    $day = $item->order->storeId === Store::STORE_WESHOP_VN ? "14 - 21" : "7 - 14";
                    break;
                case Order::SHIPPING_STATUS_DELIVERING_TO_OFFICE:
                    break;
                case Order::SHIPPING_STATUS_AT_OFFICE_PARTIAL:
                case Order::SHIPPING_STATUS_AT_OFFICE_FULL:
                    $title = Yii::t("backend","STOCKIN {country} WAREHOUSE",["country" => $country]);
                    $content = Yii::t("backend","Your ordered items have arrived in {country} and stocked in our warehouse.",["country" => $country]);
                    $day = "3-5" ;
                    break;
                case Order::SHIPPING_STATUS_DELIVERING_TO_CUSTOMER:
                    $title = Yii::t("backend","LAST MILE DELIVERY");
                    $content = Yii::t("backend","Your ordered items have been on Last Mile delivery.");
                    $day = "3-5" ;
                    break;
                case Order::SHIPPING_STATUS_AT_CUSTOMER:
            }
            if($item->ShippingStatus === Order::SHIPPING_STATUS_AT_WAREHOUSE_NOT_INSPECT){
                $message[] = Yii::t("backend","{name} will inspect the item to ensure the item quality before stock-in.",[
                    "name" => implode(" ",[$version,$country])
                ]);
            }
            if ($item->ShippingStatus !== Order::SHIPPING_STATUS_AT_CUSTOMER) {
                if ($item->ShippingStatus > Order::SHIPPING_STATUS_DELIVERING_TO_PROXY && $item->ShippingStatus < Order::SHIPPING_STATUS_AT_OFFICE_PARTIAL){
                    $message[] = Yii::t("backend","The parcel is expected to arrive in {country} in {day} days.",[
                        "country" => $country,
                        "day" => $day,
                    ]);
                }
                if ($item->ShippingStatus > Order::SHIPPING_STATUS_DELIVERING_TO_OFFICE && $item->ShippingStatus < Order::SHIPPING_STATUS_DELIVERING_TO_CUSTOMER){
                    $message[] = Yii::t("backend"," The order would be delivered to you in {day} days.",[
                        "day" => $day,
                    ]);
                }
                if ($item->ShippingStatus === Order::SHIPPING_STATUS_DELIVERING_TO_CUSTOMER){
                    if($item->order->storeId !== Store::STORE_WESHOP_MY){
                        $message[] = Yii::t("backend","Expected to delivery the goods in {day} days. Carrier will contact you.",[
                            "day" => $day,
                        ]);
                        if($item->order->storeId === Store::STORE_WESHOP_VN){
                            $message[] = "Quý khách vui lòng lưu ý điện thoại trong thời gian này.";
                        }
                    }
                }
                if ($item->ShippingStatus < Order::SHIPPING_STATUS_DELIVERING_TO_CUSTOMER){
                    $message[] = Yii::t("backend","We will send notification for next steps of the order");
                }
            } else {
                $message[] = Yii::t("backend","Thank you for using our services. We are looking forward to see you again.");
            }

            // Todo ActiveRecore hasMany use [via()]
            $orderItems = $item->order->getOrderItems()->all();
            foreach ($orderItems as $item) {
                $itemTrackings[$item->id]['Name'] = $item->Name;
                $itemTrackings[$item->id]['link'] = $item->link;
                $itemTrackings[$item->id]['ShippingStatus'] = $item->ShippingStatus;
                $itemTrackings[$item->id]["purchased_time"] = "";
                $itemTrackings[$item->id]["warehouse_time"] = "";
                $itemTrackings[$item->id]["delivering_time"] = "";
                $itemTrackings[$item->id]["local_warehouse_time"] = "";
                $itemTrackings[$item->id]["last_my_shipment"] = "";
                $itemTrackings[$item->id]["finish_time"] = "";

                // Todo ActiveRecore attribute getOrderItemTrackings() => orderItemTrackings]
                $trackings = $item->getOrderItemTrackings()->all();
                foreach ($trackings as $tracking) {
                    if ($tracking->status == Order::SHIPPING_STATUS_AT_SELLER)
                        $itemTrackings[$item->id]["purchased_time"] = $tracking->createdTime;
                    else if (!isset($itemTrackings[$item->id]["purchased_time"]))
                        $itemTrackings[$item->id]["purchased_time"] = "";

                    if ($tracking->status > Order::SHIPPING_STATUS_DELIVERING_TO_PROXY && $tracking->status < Order::SHIPPING_STATUS_DELIVERING_TO_OFFICE)
                        $itemTrackings[$item->id]["warehouse_time"] = $tracking->createdTime;
                    else if (!isset($itemTrackings[$item->id]["warehouse_time"]))
                        $itemTrackings[$item->id]["warehouse_time"] = "";

                    if ($tracking->status == Order::SHIPPING_STATUS_DELIVERING_TO_OFFICE)
                        $itemTrackings[$item->id]["delivering_time"] = $tracking->createdTime;
                    else if (!isset($itemTrackings[$item->id]["delivering_time"]))
                        $itemTrackings[$item->id]["delivering_time"] = "";

                    if ($tracking->status > Order::SHIPPING_STATUS_DELIVERING_TO_OFFICE && $tracking->status < Order::SHIPPING_STATUS_DELIVERING_TO_CUSTOMER)
                        $itemTrackings[$item->id]["local_warehouse_time"] = $tracking->createdTime;
                    else if (!isset($itemTrackings[$item->id]["local_warehouse_time"]))
                        $itemTrackings[$item->id]["local_warehouse_time"] = "";

                    if ($tracking->status == Order::SHIPPING_STATUS_DELIVERING_TO_CUSTOMER)
                        $itemTrackings[$item->id]["last_my_shipment"] = $tracking->createdTime;
                    else if (!isset($itemTrackings[$item->id]["last_my_shipment"]))
                        $itemTrackings[$item->id]["last_my_shipment"] = "";

                    if ($tracking->status == Order::SHIPPING_STATUS_AT_CUSTOMER)
                        $itemTrackings[$item->id]["finish_time"] = $tracking->createdTime;
                    else if (!isset($itemTrackings[$item->id]["finish_time"]))
                        $itemTrackings[$item->id]["finish_time"] = "";
                }
            }
        }
        $this->addParams('title',$title);
        $this->addParams('content',$content);
        $this->addParams('itemTrackings',$itemTrackings);
        $this->addParams('message',$message);
    }

    public function getView()
    {
        $this->addParams('tester','I\'m testing');
        return 'order_item_tracking';
    }
}