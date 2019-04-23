<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 07/03/2019
 * Time: 14:49
 */

namespace common\boxme\forms;

use yii\base\Model;

class ShipmentForm extends Model
{
    /**
     * @var string
     */
    public $content;
    /**
     * @var
     */
    public $cod_amount;
    /**
     * @var integer
     */
    public $total_parcel;

    public $total_amount;
    public $chargeable_weight;
    public $description = 'Cho khách hàng xem khi giao hàng'; // | Show to customers when delivery
    /**
     * @var ParcelForm[] $parcels
     */
    public $parcels;


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['chargeable_weight', 'content', 'total_parcel', 'parcels'], 'required'],
            [['chargeable_weight', 'total_parcel'], 'integer'],
            [['description', 'content'], 'string'],
            [['parcels'], 'safe'],
        ];
    }

    public function init()
    {
        parent::init();
        $this->total_parcel = count($this->parcels);
    }
}