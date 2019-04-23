<?php


namespace common\boxme\forms\warehouse;

use yii\base\Model;

class WarehouseParcel extends Model
{
    public $item_weight;
    public $tracking_code;
    public $tracking_soi;
    public $tracking_type;
    public $item_quantity;
    public $item_content;
    public $item_note;
    public $item_volume;
    public $item_inspect;
    public $arr_images;

    public function attributes()
    {
        return [
            'item_weight', 'tracking_code', 'tracking_soi', 'tracking_type', 'item_quantity',
            'item_content', 'item_note', 'item_volume', 'item_inspect', 'arr_images',
        ];
    }

    public function rules()
    {
        return [
            [['tracking_code', 'tracking_type', 'item_content'], 'required'],
            [['item_weight', 'tracking_code', 'tracking_soi', 'tracking_type', 'item_quantity', 'item_content', 'item_note', 'item_volume', 'item_inspect'], 'string'],
            [['arr_images'], 'safe']
        ];
    }
}