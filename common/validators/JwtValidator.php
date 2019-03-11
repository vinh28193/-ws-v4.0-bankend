<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-08-23
 * Time: 11:04
 */

namespace common\validators;

use Yii;
use yii\validators\Validator;
use yii\base\InvalidConfigException;

class JwtValidator extends Validator
{

    public $verifier = 'frontend\models\Customer::verifyJwt';

    public function init()
    {
        parent::init();
        if($this->verifier === null){
            throw new InvalidConfigException(get_class($this).'::$verifier must be set');
        }
    }

    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if(($result = $this->validateValue($value)) === false){
            $this->addError($model,$attribute,$this->message);
        }elseif (is_array($result) && isset($result['is_active']) && $result['is_active'] === true){
            $this->addError($model,$attribute,Yii::t('validator','Invalid, Your account already activated'));
        }else{
            $model->$attribute = [$result['sub'],$result['aud']];
        }

    }

    public function validateValue($value)
    {
        $rawValue = call_user_func($this->verifier, $value);
        if($rawValue[0] === true && is_array($rawValue[1])){
            return $rawValue[1];
        }elseif ($rawValue[0] === false && is_string($rawValue[1])){
            $this->message = $rawValue[1];
        }
        return false;
    }

}