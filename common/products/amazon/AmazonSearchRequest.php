<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-29
 * Time: 20:20
 */

namespace common\products\amazon;

use Yii;
use yii\helpers\ArrayHelper;

class AmazonSearchRequest extends AmazonRequest
{

    public function rules()
    {
        $rules = parent::rules();
        $rules = ArrayHelper::merge($rules, [
//            ['sort', 'default', 'value' => 'date-desc-rank'],
//            ['sort', function ($attribute, $params, $validator) {
//                if (!$this->hasErrors()) {
//                    $value = $this->$attribute;
//                    if (ArrayHelper::isIn($value, ['price-asc-rank', 'price-desc-rank', 'relevancerank', 'review-rank'])) {
//                        $this->addError($attribute, Yii::t('frontend', 'Unknown {attribute}: {sorter}', [
//                            'attribute' => $this->getAttributeLabel($attribute),
//                            'sorter' => $value,
//                        ]));
//                    }
//                }
//            }],
        ]);

        return $rules;
    }

    public function params()
    {
        $params = parent::params();
        $unnecessaryAttribute = ['min_price', 'max_price'];
        foreach ($this->getAttributes() as $name => $value) {
            if (ArrayHelper::isIn($name, $unnecessaryAttribute)) {
                continue;
            }
            if ($name === 'category') {
                $key = 'node_ids';
            } elseif ($name === 'filter') {
                $key = 'rh';
            } else {
                $key = $name;
            }
            if ($key === 'sort' && $value === 'relevancerank') {
                $value = '';
            }
            if($value){
                $params[$key] = $value;
            }
        }
        return $params;
    }
}