<?php

namespace common\boxme\forms;


use common\boxme\Location;
use common\boxme\models\Config;
use common\boxme\models\Item;
use common\boxme\models\Parcel;
use common\boxme\models\PickupWarehouse;
use common\boxme\models\ShipTo;
use common\models\Shipment as ModelShipment;
use yii\helpers\ArrayHelper;

class CreateOrderForm extends BaseForm
{

    public $ids;
    public $filters;

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
        } elseif ($count > self::LIMIT_CREATE) {
            $ids = ArrayHelper::getColumn($models, 'id', false);
            $total = count($ids);
            $onQueue = ModelShipment::updateAll([
                'status' => ModelShipment::STATUS_WAITING,
            ], ['id' => $ids]);
            return "pushed $onQueue/$total in queue for waiting create";
        } else {
            foreach ($models as $model) {

            }
        }
    }

    public function calculate($shipment)
    {

    }

    /**
     * @return array|ModelShipment
     */
    protected function findModels()
    {
        $query = ModelShipment::find();
        if ($this->ids !== null) {
            $query->where(['id' => $this->ids]);
        } elseif ($this->filters !== null) {
            $query->filter($this->filters);
        } else {
            return [];
        }
        return $query->all();
    }

    /**
     * @param ModelShipment $model
     */
    public function calculateParams($model)
    {
        $params = [];
        $wh = $model->warehouseSend;

        $from = [
            'pickup_id' => $wh->ref_warehouse_id,
            'country' => Location::COUNTRY_VN
        ];

        $params['form'] = $from;
        $to = new ShipTo([
            'contact_name' => $model->receiver_name,
            'phone' => $model->receiver_phone,
            'address' => $model->receiver_address,
            'country' => $model->receiver_country_id,
            'district' => $model->receiver_district_id,
            'province' => $model->receiver_province_id,
            'zipcode' => $model->receiver_post_code,
        ]);
        $params['to'] = $to->getAttributes();

        if (!$to->validate()) {
            $model->updateAttributes([
                'current_status' => ModelShipment::STATUS_FAILED
            ]);
            return false;
        }
        $parcels = [];
        $errors = [];
        foreach ($model->packageItems as $packageItem) {
            $product = $packageItem->product;
            $parcel = new Parcel([
                'weight' => $packageItem->weight,
                'referral_code' => $packageItem->box_me_warehouse_tag,
                'items' => [
                    new Item([
                        'name' => $product->product_name,
                        'weight' => $packageItem->weight,
                        'quantity' => $packageItem->quantity,
                        'amount' => $packageItem->price + (($packageItem->cod !== null && (int)$packageItem->cod > 0) ? (int)$packageItem->cod : 0),
                    ])
                ],
                'images' => [
                    $product->link_img
                ]
            ]);
            if (!$parcel->validate()) {
                $errors[] = $parcel->getErrors();
                continue;
            }
            $parcels[] = $parcel;
        }
        if (count($errors) > 0) {
            $model->updateAttributes([
                'current_status' => ModelShipment::STATUS_FAILED
            ]);
            return false;
        }

        $config = new Config([
            'sort_mode' => Config::SORT_MODE_PRICE,
            'order_type' => Config::ORDER_TYPE_CONSOLIDATE,
            'auto_approve' => $model->is_hold ? Config::ACCEPTED : Config::NOT_ACCEPT,
            'insurance' => $model->is_insurance ? Config::ACCEPTED : Config::NOT_ACCEPT,
            'currency' => $model->receiverCountry->country_code
        ]);
    }

    /**
     * @param ModelShipment $model
     */
    public function createOrderParams($model)
    {

    }
}