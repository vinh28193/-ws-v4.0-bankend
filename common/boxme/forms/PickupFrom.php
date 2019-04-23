<?php

namespace common\boxme\forms;

use yii\base\Model;

class PickupFrom extends Model
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