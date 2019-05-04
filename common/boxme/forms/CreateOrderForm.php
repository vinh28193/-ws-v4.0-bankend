<?php

namespace common\boxme\forms;

use common\models\Package;
use common\models\TrackingCode;
use Yii;
use common\boxme\CourierHelper;
use common\boxme\Location;
use common\boxme\models\Config;
use common\boxme\models\Item;
use common\boxme\models\Parcel;
use common\boxme\models\PickupWarehouse;
use common\boxme\models\Shipment;
use common\boxme\models\ShipTo;
use common\models\Shipment as ModelShipment;
use yii\helpers\ArrayHelper;

use common\boxme\BoxmeClientCollection;

class CreateOrderForm extends BaseForm
{

    public $ids;

    public $rules = [];

    const LIMIT_CREATE = 10;

    public function create()
    {
        $models = $this->findModels();
        $count = count($models);
        if ($count === 0) {
            if ($this->ids !== null) {
                $id = is_string($this->ids) ? $this->ids : implode(', ', $this->ids);
                $this->addError('ids', "not found with ids : $id");
            } else {
                $this->addError('ids', "not found current filter");
            }
            return false;
        }
//        elseif ($count > self::LIMIT_CREATE) {
//            $ids = ArrayHelper::getColumn($models, 'id', false);
//            $total = count($ids);
//            $onQueue = ModelShipment::updateAll([
//                'status' => ModelShipment::STATUS_WAITING,
//            ], ['id' => $ids]);
//            return "pushed $onQueue/$total in queue for waiting create";
//        } else
        {
            $errors = [];
            $success = 0;
            foreach ($models as $model) {
                /* @var $model ModelShipment */
                list($isSuccess, $message) = $this->createByShipment($model);
                if ($isSuccess === true) {
                    $success++;
                } else {
                    $errors[$model->id] = $message;
                }
            }
            Yii::info($errors, __METHOD__);
            $countError = count($errors);
            return "created success $success shipment, $countError errors";
        }
    }

    /**
     * @param $model ModelShipment
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function createByShipment($model)
    {
        $collection = new BoxmeClientCollection();

        if ($model->courier_code === null) {
            $calculatePrice = new CalculateForm();
            $calculatePrice->warehouseId = $model->warehouse_send_id;
            $calculatePrice->toAddress = $model->receiver_address;
            $calculatePrice->toDistrict = $model->receiver_district_id;
            $calculatePrice->toProvince = $model->receiver_province_id;
            $calculatePrice->toCountry = $model->receiver_country_id;
            $calculatePrice->toZipCode = $model->receiver_post_code;
            $calculatePrice->toName = $model->receiver_name;
            $calculatePrice->toPhone = $model->receiver_phone;
            $calculatePrice->totalParcel = count($model->packageItems);
            $calculatePrice->totalWeight = $model->total_weight;
            $calculatePrice->totalQuantity = $model->total_quantity;
            $calculatePrice->totalCod = $model->total_cod;
            $calculatePrice->totalAmount = $model->total_price + (($model->total_cod !== null && $model->total_cod > 0) ? $model->total_cod : 0);
            $calculatePrice->isInsurance = $model->is_insurance;
            $calculateResults = $calculatePrice->calculate();
            if ($calculateResults['error'] === true || !isset($calculateResults['data']['couriers']) || count($calculateResults['data']['couriers']) === 0) {
                return [false, $calculateResults['messages']];
            }
            $couriers = $calculateResults['data']['couriers'];
            $passCourier = CourierHelper::parserRule($couriers, $this->rules, $model);
            $model->courier_code = $passCourier['service_code'];
            $model->courier_logo = $passCourier['courier_logo'];
            $model->total_shipping_fee = $passCourier['total_fee'];
        }
        list($pOk, $pV) = $this->createParams($model);
        if ($pOk === false) {
            return [$pOk, $pV];
        }
        $client = $collection->getClient($model->warehouseSend->country_id === 2 ? Location::COUNTRY_ID : Location::COUNTRY_VN);
        $result = $client->createOrder($pV);
        if ($result['error'] === true) {
            return [false, $result['messages']];
        }
        $model->shipment_code = $result['data']['tracking_number'];
        $model->shipment_status = ModelShipment::STATUS_CREATED;
        $isSave = $model->save(false);
        /** @var Package[] $listTracking */
        $listTracking  = Package::find()->where(['shipment_id' => $model->id])->all();
        foreach ($listTracking as $tracking){
            TrackingCode::UpdateStatusTracking($tracking->tracking_code, TrackingCode::STATUS_CREATED_SHIPMENT);
        }
        return [$isSave, "create order {$model->shipment_code} success"];
    }

    /**
     * @return array|ModelShipment
     */
    protected function findModels()
    {
        $query = ModelShipment::find();
        $query->with(['warehouseSend', 'packages', 'packageItems.product']);
        if ($this->ids !== null) {
            $query->where(['id' => $this->ids]);
        } else {
            return [];
        }
        return $query->all();
    }

    /**
     * @param ModelShipment $model
     */
    public function createParams($model)
    {
        $params = [];
        $wh = $model->warehouseSend;
        $location = $model->warehouseSend->country_id === 2 ? Location::COUNTRY_ID : Location::COUNTRY_VN;
        $params['ship_from']['country'] = $location;
        $params['ship_from']['pickup_id'] = $wh->ref_warehouse_id;
        $to = new ShipTo([
            'contact_name' => $model->receiver_name,
            'phone' => $model->receiver_phone,
            'address' => $model->receiver_address,
            'country' => $model->receiver_country_id === 1 ? Location::COUNTRY_VN : Location::CURRENCY_ID,
            'district' => $model->receiver_district_id,
            'province' => $model->receiver_province_id,
            'zipcode' => $model->receiver_post_code,
        ]);
        $params['ship_to'] = $to->getAttributes();

        if (!$to->validate()) {
            return [false, $to->getFirstErrors()];
        }
        $parcels = [];
        $errors = [];
        foreach ($model->packages as $package) {
            if ((($images = $package->image) === null) || count(($images = explode(',', $images))) === 0) {
                $errors[] = 'package offset image 0 or not have image';
                continue;
            }
            $parcel = new Parcel([
                'weight' => $package->weight,
                'referral_code' => $package->warehouse_tag_boxme,
                'items' => [
                    new Item([
                        'name' => $package->item_name,
                        'weight' => $package->weight,
                        'quantity' => $package->quantity,
                        'amount' => $package->price + (($package->cod !== null && $package->cod > 0) ? (int)$package->cod : 0),
                    ])
                ],
                'images' => [
                    $images[0]
                ]
            ]);
            if (!$parcel->validate()) {
                $errors[] = $parcel->getErrors();
                continue;
            }
            $parcels[] = $parcel->getAttributes();
        }
        if (count($errors) > 0) {
            return [false, $errors];
        }
        $params['shipments']['content'] = ' ';
        $params['shipments']['total_amount'] = $model->total_price + (($model->total_cod !== null && $model->total_cod > 0) ? $model->total_cod : 0);
        $params['shipments']['chargeable_weight'] = $model->total_weight;
        $params['shipments']['description'] = $location === Location::CURRENCY_ID ? 'Cho khách hàng xem khi giao hàng' : 'Customer can be view when delivery';
        $params['shipments']['total_parcel'] = count($parcels);
        $params['shipments']['parcels'] = $parcels;

        $params['config']['sort_mode'] = Config::SORT_MODE_PRICE;
        $params['config']['order_type'] = Config::ORDER_TYPE_CONSOLIDATE;
        $params['config']['return_mode'] = 2;
        $params['config']['auto_approve'] = $model->is_hold ? Config::ACCEPTED : Config::NOT_ACCEPT;
        $params['config']['unit_metric'] = "metric";
        $params['config']['delivery_service'] = $model->courier_code ? $model->courier_code : '';
        $params['config']['insurance'] = $model->courier_code === 'BM_DBS' ? Config::NOT_ACCEPT : ($model->is_insurance ? Config::ACCEPTED : Config::NOT_ACCEPT);
        $params['config']['currency'] = $location === Location::COUNTRY_VN ? Location::CURRENCY_VN : Location::CURRENCY_ID;
        $params['payment']['cod_amount'] = $model->total_cod !== null ? $model->total_cod : 0;
        return [true, $params];
    }

    /**
     * @param ModelShipment $model
     */
    public function createOrderParams($model)
    {

    }
}