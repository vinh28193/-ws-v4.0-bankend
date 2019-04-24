<?php


namespace common\boxme\forms;


use common\boxme\BoxmeClientCollection;
use common\boxme\Location;
use common\boxme\models\Config;
use common\boxme\models\ShipTo;
use common\models\Warehouse;

class CalculateForm extends BaseForm
{

    public $warehouseId;
    public $toAddress;
    public $toDistrict;
    public $toProvince;
    public $toCountry;
    public $toZipCode;
    public $toName;
    public $toPhone;
    public $totalParcel = 1;
    public $totalWeight = 0;
    public $totalQuantity = 1;
    public $totalCod = 0;
    public $totalAmount = 0;
    public $isInsurance = false;
    public $sortMode = Config::SORT_MODE_PRICE;

    public function attributes()
    {
        return [
            'warehouseId', 'toAddress', 'toDistrict', 'toProvince', 'toCountry',
            'toZipCode', 'toName', 'toPhone',
            'totalParcel', 'totalWeight', 'totalQuantity',
            'totalCod', 'totalAmount',
            'isInsurance', 'sortMode',
        ];
    }

    public function rules()
    {
       return [
            [
                 [
                     'warehouseId', 'toAddress', 'toDistrict', 'toProvince', 'toCountry',
                     'toZipCode', 'toName', 'toPhone',
                     'totalParcel', 'totalWeight', 'totalQuantity',
                     'totalCod', 'totalAmount'
                 ],'required'
            ],
           [
               [
                   'warehouseId','toDistrict', 'toProvince', 'toCountry','totalQuantity'
               ] , 'integer'
           ],
           [
               [
                   'toName','toPhone', 'sortMode'
               ] , 'string'
           ]
       ];
    }

    public function calculate()
    {
        if (($wh = $this->getWarehouse()) === null) {
            return [false, "Not found warehouse", []];
        }
        $location = $wh->country_id === 1 ? Location::COUNTRY_VN : Location::COUNTRY_ID;
        $params['ship_from']['pickup_id'] = $wh->ref_warehouse_id;
        $params['ship_from']['country'] = $location;
        if ($location === Location::COUNTRY_ID) {
            $params['ship_from']['zipcode'] = $wh->post_code;
        }
        $to = new ShipTo([
            'contact_name' => $this->toName,
            'phone' => $this->toPhone,
            'address' => $this->toAddress,
            'country' => $location,
            'province' => $this->toProvince,
            'district' => $this->toDistrict,
            'zipcode' => $this->toZipCode
        ]);
        if (!$to->validate()) {
            return [false, $to->getFirstErrors(), []];
        }
        $params['ship_to'] = $to->getAttributes();
        $params['shipments']['content'] = '';
        $params['shipments']['total_parcel'] = 1;
        $params['shipments']['total_amount'] = $this->totalAmount ? $this->totalAmount : 0;
        $params['shipments']['chargeable_weight'] = $this->totalWeight ? $this->totalWeight : 0;

        $params['config']['order_type'] = 'normal';
        $params['config']['insurance'] = $this->isInsurance ? Config::ACCEPTED : Config::NOT_ACCEPT;
        $params['config']['document'] = 'N';
        $params['config']['sort_mode'] = $this->sortMode;
        $params['config']['currency'] = $location === Location::COUNTRY_VN ? Location::CURRENCY_VN : Location::CURRENCY_ID;
        $params['payment']['cod_amount'] = $this->totalCod ? $this->totalCod : 0;
        $params['referral']['order_number'] = '';

        $collection = new BoxmeClientCollection();
        $client = $collection->getClient($location);
        $client->env = 'product';
        if (($response = $client->pricingCalculate($params)) === false) {

            return [false, 'can not http to box me now', []];
        }
        return $response;

    }


    /**
     * @param $id
     * @return Warehouse|null
     */
    protected function getWarehouse()
    {
        if ($this->warehouseId === null) {
            return null;
        }
        return Warehouse::findOne($this->warehouseId);
    }
}