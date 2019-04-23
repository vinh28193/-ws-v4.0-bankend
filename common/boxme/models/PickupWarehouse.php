<?php

namespace common\boxme\models;

use yii\base\Model;

class PickupWarehouse extends Model
{
    public $id;
    public $country;

    public function rules()
    {
        return [
            [['id', 'country'], 'required'],
            [['id'], 'integer'],
            [['country'], 'string'],
        ];
    }

}