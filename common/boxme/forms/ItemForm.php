<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 07/03/2019
 * Time: 14:56
 */

namespace common\boxme\forms;

use yii\base\Model;


class ItemForm extends Model
{
    public $sku;
    public $origin_country;
    public $name;
    public $description;
    public $weight;
    public $amount;
    public $quantity;

    public function rules()
    {
        return [
            [['weight', 'quantity'], 'integer'],
            [['description', 'amount', 'name', 'sku'], 'string'],
            [['weight', 'description', 'quantity', 'amount', 'name'], 'required'],
            ['weight', 'integer', 'min' => 1],
            ['amount', function ($attribute, $param) {
                if (!$this->hasErrors() && ((int)($value = $this->$attribute) < 1)) {
                    $this->addError($attribute, "{$this->name} amount '{$value}' invalid");
                }
            }],
            ['amount', 'filter', 'filter' => function ($value) {
                return (string)$value;
            }],
            ['description', 'filter', 'filter' => function ($value) {
                return $value === null ? '' : $value;
            }]
        ];
    }
}