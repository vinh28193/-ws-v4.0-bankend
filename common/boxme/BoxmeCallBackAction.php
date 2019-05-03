<?php


namespace common\boxme;


use common\models\Package;
use common\models\DeliveryNote;
use common\models\Shipment;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\base\Action;

class BoxmeCallBackAction extends Action
{

    public function run()
    {
        $post = Yii::$app->request->post();
        $response = null;
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (isset($post['StatusCode']) && isset($post['TrackingCode']) && ($trackingCode = $post['TrackingCode'])) {
            $this->updateOrderTracking($post);
        } elseif (isset($post['packing_code']) && isset($post['packing_code'])) {
            $response = $this->updateItem($post);
            Yii::info($response);
        }
        $success = true;
        $message = 'update callback success, time:' . date('Y-m-d H:i:s');
        if (is_array($response) && count($response) === 2 && isset($response['success'])) {
            $success = $response['success'];
            if ($success === false && isset($response['info']) && is_string($response['info'])) {
                $message = $response['info'];
            }
        }

        return ['success' => $success, 'message' => $message];
    }

    private function updateItem($post)
    {
        $res = WarehouseInspect::update($post);
        $res = array_combine(['success', 'info'], $res);
        return $res;
    }

    private function updateOrderTracking($post)
    {
        if (($statusCode = ArrayHelper::getValue($post, 'StatusCode')) !== null && (($trackingCode = ArrayHelper::getValue($post, 'TrackingCode')) !== null && ($shipment = $this->findShipment($trackingCode)) !== null)) {
            switch ((int)$statusCode) {
                case 200:  // 200: đơn đã duyêt
                    $shipment->shipment_status = Shipment::STATUS_APPROVED;
                    $shipment->save(false);
                    // update package
//                    foreach ($shipment->packages as $package){
//
//                    }
                    DeliveryNote::updateAll([
                        'current_status' => DeliveryNote::STATUS_REQUEST_SHIP_OUT
                    ], ['shipment_id' => $shipment->id]);
                    break;
                case 300:  // 300: Hãng vận chuyển đã lấy hàng
                    $shipment->shipment_status = Shipment::STATUS_PICKING;
                    $shipment->save(false);

                    DeliveryNote::updateAll([
                        'current_status' => DeliveryNote::STATUS_DELIVERING_TO_CUSTOMER,
                    ], ['shipment_id' => $shipment->id]);

                    Package::updateAll([
                        'stock_out_local' => $this->today()
                    ], ['shipment_id' => $shipment->id]);
                    break;
                case 304: // đơn hàng đang vận chuyển
                    $shipment->shipment_status = Shipment::STATUS_DELIVERING;
                    $shipment->save(false);
                    break;
                case 400 : // Out of delivery
                case 410 : // Awaiting to return
                case 420 : // Return processing
                case 430 : // Return approved
                case 500 : // Returning / đang hoàn
                case 510 : // Out of return / Hết tiền
                case 511 : // Return refused
                case 520 : // Awaiting receive at facility / đang chờ nhận hàng tại kho
                    //case 530 : // Return to orgin facility /
                    //case 610 : // Returned at orgin facility
                    $shipment->shipment_status = Shipment::STATUS_RETURNING;
                    $shipment->save(false);
                    break;
                case 600: // Hàng đã chuyển hoàn
                case 810: // Phá hủy bưu kiện / Destroy parcel
                    $shipment->shipment_status = Shipment::STATUS_RETURNED;
                    $shipment->save(false);
                    break;
                case 700: // Cancelled by sellers
                case 701: // Cancelled by operator
                case 702: // Cancelled by partner
                case 703: // Cancelled by system
                case 705: // Hủy đơn khi xuất kho
                    $shipment->shipment_status = Shipment::STATUS_CANCELED;
                    $shipment->save(false);
                    break;
                case 800:
                    $shipment->shipment_status = Shipment::STATUS_DELIVERED;
                    $shipment->save(false);
                    DeliveryNote::updateAll([
                        'current_status' => DeliveryNote::STATUS_DELIVERED,
                    ], ['shipment_id' => $shipment->id]);

                    Package::updateAll([
                        'at_customer' => $this->today()
                    ], ['shipment_id' => $shipment->id]);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * @param $bmCode
     * @return Shipment|null
     */
    protected function findShipment($bmCode)
    {
        return Shipment::findOne(['shipment_code' => $bmCode]);
    }

    public function today()
    {
        return Yii::$app->getFormatter()->asTimestamp('now');
    }
}