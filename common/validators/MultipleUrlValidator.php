<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-08-20
 * Time: 13:09
 */

namespace common\validators;

use Yii;
use yii\validators\UrlValidator;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class MultipleUrlValidator extends UrlValidator
{

    public function init()
    {
        parent::init();
        $this->message = Yii::t('validator', '{attribute} have {url} is not a valid URL.');
        $this->pattern = '/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i';
    }

    /**
     * @param $model
     * @param $attribute
     * @throws \yii\base\NotSupportedException
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if (!is_array($value)) {
            $value = [$value];
        }

        $urls = [];
        foreach ($value as $val) {
            if (($result = $this->validateValue($val)) !== null) {
                $this->addError($model, $attribute, $result[0], $result[1]);
            } elseif ($this->defaultScheme !== null && strpos($val, '://') === false) {
                $urls[] = $this->defaultScheme . '://' . $value;
            }
        }
        if (count($urls) > 0) {
            $model->$attribute = $urls;
        }
    }

    public function validateValue($value)
    {
        if(($result = parent::validateValue($value)) !== null){
            $result[1] = ArrayHelper::merge($result[1], ['url' => $value]);
        }
        return $result;
    }
}