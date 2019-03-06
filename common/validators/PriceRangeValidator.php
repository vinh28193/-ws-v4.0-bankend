<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-08-01
 * Time: 10:38
 */

namespace common\validators;

use Yii;
use yii\validators\Validator;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * Class PriceRangeValidator
 * @package common\validators
 * @property boolean
 */
class PriceRangeValidator extends Validator
{

    public $conditionOperator= '===';

    public $assistAttribute;

    private $validOperator = ['==','===','<','<=','>','>=','!=','!=='];

    /**
     * Todo Yii:t
     * @var array
     */
    private $operatorDeception = [];

    /**
     * {@inheritdoc}
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if($this->assistAttribute === null){
            throw new InvalidConfigException(get_class($this).'::$assistAttribute must be set');
        }
        if(!ArrayHelper::isIn($this->conditionOperator,$this->validOperator)){
            throw new InvalidConfigException(get_class($this).'::$conditionOperator not valid, only accept \'==\',\'===\',\'<\',\'<=\',\'>\',\'>=\',\'!=\',\'!==\'');
        }
        if(!$this->message){
             $this->message = Yii::t('validator','{attribute} must be {operatorDeception} {assistAttribute}');
        }
        $this->operatorDeception = [
            '==' => Yii::t('validator','equal'),
            '===' => Yii::t('validator','equal'),
            '<' => Yii::t('validator','lesser than'),
            '<=' => Yii::t('validator','lesser than or equal'),
            '>' => Yii::t('validator','greater than'),
            '>=' => Yii::t('validator','greater than or equal'),
            '!=' => Yii::t('validator','not equal'),
            '!==' => Yii::t('validator','not equal')
        ];
    }

    /**
     * @param \common\models\weshop\gate\BaseSearchForm $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if(is_string($value)){
            $value = (int)$value;
        }
        if(!$model->hasAttribute($this->assistAttribute)){
            $this->addError($model,$attribute,Yii::t('validator','unknown property {assistAttribute}'),[
                'assistAttribute' => $this->assistAttribute
            ]);
        }
        if(($assistValue = $model->{$this->assistAttribute}) !== null){
            if(is_string($assistValue)){
                $assistValue = (int)$assistValue;
            }
            $operator = $this->conditionOperator;
            $buildCode = "$value $operator $assistValue";
            if(!$this->evalBooleanCondition($buildCode)){
                $this->addError($model,$attribute,$this->message,[
                    'operatorDeception' => $this->extractOperatorDeception($this->conditionOperator),
                    'assistAttribute' => $assistValue
                ]);
            }
        }
    }

    public function extractOperatorDeception($operator){
        // Todo Yii::t for "unknown"
        return ArrayHelper::getValue($this->operatorDeception,$operator,Yii::t('validator','unknown'));
    }

    public function evalBooleanCondition($code){
        return eval("return $code;");
    }
}