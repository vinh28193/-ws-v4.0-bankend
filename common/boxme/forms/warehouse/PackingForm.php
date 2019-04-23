<?php

namespace common\boxme\forms\warehouse;

use yii\base\Model;

class PackingForm extends Model
{

    /**
     * @var string packing name
     */
    public $hawb;
    /**
     * @var integer tổng số lượng parcel
     */
    public $hawb_quantity;
    /**
     * @var string
     */
    public $hawb_email = 'boxme@asia.com';
    /**
     * @var int
     */
    public $hawb_type = 0;
    /**
     * @var
     */
    public $hawb_warehouse;
    /**
     * @var WarehouseParcel[]
     */
    public $parcels;

    public function attributes()
    {
        return [
            'hawb', 'hawb_quantity', 'hawb_email', 'hawb_type', 'hawb_warehouse', 'parcels',
        ];
    }

    public function rules()
    {
        return [
            [['hawb', 'hawb_quantity', 'hawb_email', 'hawb_warehouse'], 'required'],
            [['hawb_type'], 'integer'],
            [['hawb_email'], 'email'],
            [['parcels'], 'safe']
        ];
    }

    public function init()
    {
        parent::init();
        $this->hawb_quantity = count($this->parcels);
    }
}