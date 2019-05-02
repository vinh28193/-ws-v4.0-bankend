<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 07/03/2019
 * Time: 14:52
 */

namespace common\boxme\models;

use yii\base\Model;

class Parcel extends Model
{
    /**
     * @var Item[] $items
     */
    public $items;
    /**
     * @var integer
     */
    public $weight;
    /**
     * @var string
     */
    public $inspect_note;
    /**
     * @var string
     */
    public $description;
    /**
     * @var
     */
    public $referral_code;
    /**
     * @var string[] $images
     */
    public $images;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['weight', 'items', 'referral_code'], 'required'],
            [['weight'], 'integer'],
            [['description', 'inspect_note'], 'string'],
            [['items'], 'safe'],
            ['items', 'validateItems'],
            ['images', 'validateImages'],
            ['description', 'filter', 'filter' => function ($value) {
                return $value === null ? '' : $value;
            }]
        ];
    }

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        if ($this->items != null) {
            foreach ($this->items as $item) {
                $this->weight += $item->weight;
                $this->inspect_note = ($this->inspect_note === $item->description ? $this->inspect_note : $this->inspect_note . ',' . $item->description);
            }
        }
    }

    /**
     * @param string $attribute
     * @param array $params
     * @param \yii\validators\InlineValidator $validator
     */
    public function validateItems($attribute, $params, $validator)
    {
        if (!$this->hasErrors()) {
            $values = $this->$attribute;
            if (!is_array($values) || count($values) === 0) {
                $this->addError($attribute, 'Invalid Items');
            }
            foreach ($values as $value) {
                if ($value instanceof Item && !$value->validate()) {
                    $this->addError($attribute, $value->getErrors());
                }
            }
        }
    }

    /**
     * @param string $attribute
     * @param array $params
     * @param \yii\validators\InlineValidator $validator
     */
    public function validateImages($attribute, $params, $validator)
    {
        if (!$this->hasErrors()) {
            if (count($this->$attribute) !== count($this->items)) {
                $this->addError($attribute, 'Images Invalid');
            }
        }
    }
}