<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-29
 * Time: 20:20
 */

namespace common\products\amazon;

use common\helpers\WeshopHelper;
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
        if (isset($params['store'])) {
            unset($params['store']);
        }
        $unnecessaryAttribute = ['store', 'min_price', 'max_price'];
        foreach ($this->getAttributes() as $name => $value) {
            if (ArrayHelper::isIn($name, $unnecessaryAttribute)) {
                continue;
            }
            if ($name === 'category') {
                $key = 'node';
            } elseif ($name === 'keyword') {
                $key = 'q';
            } else {
                $key = $name;
            }
            if ($key === 'sort' && $value === 'relevancerank') {
                continue;
            }
            if (!WeshopHelper::isEmpty($value)) {
                $params[$key] = $value;
            }
        }

        return $params;
    }
}