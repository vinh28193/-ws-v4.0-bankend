<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-30
 * Time: 09:56
 */

namespace common\products\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class BaseForm extends Model
{

    /**
     * @var string
     */
    public $type;

    /**
     * @var string| \common\products\ProductManager
     */
    private $_productManager = 'productManager';

    /**
     * @return \common\products\ProductManager|null|object|string
     * @throws \yii\base\InvalidConfigException
     */
    public function getProductManager()
    {
        if (is_string($this->_productManager)) {
            $this->_productManager = Yii::$app->get($this->_productManager);
        }
        return $this->_productManager;
    }

    /**
     * @param $name
     * @return \common\products\BaseGate|null
     * @throws \yii\base\InvalidConfigException
     */
    public function getGate($name)
    {
        return $this->getProductManager()->getGate($name);
    }

    /**
     * @return \common\products\BaseGate|null
     * @throws \yii\base\InvalidConfigException
     */
    public function getActiveGate(){
        return $this->getGate($this->type);
    }
    /**
     * @param array $data
     * @param string $formName
     * @return bool
     */
    public function load($data, $formName = '')
    {
        return parent::load($data, $formName);
    }

    /**
     * @return array|mixed
     */
    public function getFirstErrors()
    {
        $error = parent::getFirstErrors();
        return reset($error);
    }

    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(),['type']);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),[
            ['type', 'required'],
            ['type', function ($attribute, $params) {
                if (!$this->hasErrors()) {
                    $types = array_keys($this->getProductManager()->getGates());
                    $value = $this->$attribute;
                    if (!ArrayHelper::isIn($value, $types)) {
                        $this->addError($attribute, "invalid type $value");
                    }
//                    $this->$attribute = $this->getGate($value);
                }
            }],
//            ['type', function ($attribute, $params) {
//                if (!$this->hasErrors()) {
//                    if (!($value = $this->$attribute) instanceof BaseGate || $value === null) {
//                        $this->addError($attribute, "called form unknown type");
//                    }
//                }
//            }],
        ]);
    }
}