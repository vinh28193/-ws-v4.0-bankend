<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-07-31
 * Time: 16:59
 */

namespace common\validators;

use Yii;

class SpecialKeywordValidator extends \yii\validators\Validator
{
    const CACHE_KEY = 'SPECIAL_KEYWORD';

    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('validator', '{attribute} "{value}" has already banned');
        }
    }

    public function checkKeyword($value){
        $value = "start $value end";
        $pattern = [
            'spy',
            'spy monitor',
            'monitor spy',
            'spy camera',
            'camera spy',
            'camera hidden',
            'camera sneaky',
            'sneaky camera',
            'sneaky',
        ];
        $patternAdj = [
            'spy',
            'hidden',
            'sneaky',
        ];
        $patternNoun = [
            'camera',
            'monitor',
            'gear',
        ];
        $check = false;
        foreach ($pattern as $item) {
            if (strpos($value, $item)) {
                $check = true;
                break;
            }
        }
        if ($check) return $check;
        foreach ($patternAdj as $adj) {
            if (strpos($value, $adj)) {
                foreach ($patternNoun as $noun) {
                    if (strpos($value, $noun)) {
                        $check = true;
                        break;
                    }
                }
            }
        }
        return $check;
    }
    /**
    * {@inheritdoc}
    */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if ($this->checkKeyword($value)) {
            $this->addError($model, $attribute, $this->message, ['value' => $value]);
            return;
        }
    }
    /**
     * {@inheritdoc}
     */
    protected function validateValue($value)
    {
        if ($this->checkKeyword($value)) {
            return [$this->message, ['value' => $value]];
        }
        return null;
    }

}