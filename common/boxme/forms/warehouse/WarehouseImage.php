<?php


namespace common\boxme\forms\warehouse;

use yii\base\Model;

class WarehouseImage extends Model
{

    public $urls;

    public function attributes()
    {
        return ['urls'];
    }

    public function rules()
    {
        return [['urls', 'url']];
    }
}