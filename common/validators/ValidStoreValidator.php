<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-08-21
 * Time: 17:31
 */

namespace common\validators;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class ValidStoreValidator
 * @package common\validators
 */
class ValidStoreValidator extends \yii\validators\Validator
{

    const VN = 1;
    const ID = 7;
    const MY = 6;

    public $forceToInt = true;

    private $_storeAlias = [
        self::VN => 'vn',
        self::ID => 'id',
        self::MY => 'my'
    ];

    public function init()
    {
        parent::init();
        $this->message = Yii::t('validator', '{attribute} "{value}" not exist in system');
    }

    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if (($result = $this->validateValue($value)) !== null) {
            list($message, $params) = $result;
            $this->addError($model, $attribute, $message, $params);
        }else if (($isNotInt = !$this->isInteger($value)) && $this->forceToInt ){
            $model->$attribute = (int)array_flip($this->getValidStore($isNotInt))[$value];
        }else if ($this->forceToInt) {
            $model->$attribute = (int)$value;
        }
    }

    public function validateValue($value)
    {
        $isNotInt = !$this->isInteger($value);
        $validStore = $this->getValidStore($isNotInt);
        if ($isNotInt) {
            $validStore = array_keys(array_flip($validStore));
        }
        if (!ArrayHelper::isIn($value, $validStore)) {
            return [$this->message, ['value' => $value]];
        }
        return null;
    }

    public function getValidStore($useAlias = true)
    {
        return $useAlias ? $this->_storeAlias : array_keys($this->_storeAlias);
    }

    public function isInteger($value){
        return $value === 0 ? true : (int)$value > 0;
    }
}