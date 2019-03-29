<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 09:34
 */

namespace common\products;

use Yii;
use yii\helpers\ArrayHelper;

abstract class BaseRequest extends \yii\base\Model
{

    public $keyword;
    public $category;
    public $filter;
    public $page = 1;
    public $min_price;
    public $max_price;
    public $sort;

    /**
     * @var  \common\components\StoreManager|void
     */
    private $_storeManager;

    /**
     * @return \common\components\StoreManager|void
     */
    public function getStoreManager()
    {
        if ($this->_storeManager === null) {
            $app = Yii::$app;
            if ($app instanceof \yii\console\Application) {
                $app->storeManager->setStore(1);
            }
            $this->_storeManager = $app->storeManager;

        }
        return $this->_storeManager;
    }

    public function rules()
    {
        $rules = parent::rules();
        if ($this->getStoreManager()->getId() === 7) {
            $rules = ArrayHelper::merge($rules, [
                [['keyword'], 'common\validators\SpecialKeywordValidator'],
            ]);
        }
        $rules = ArrayHelper::merge($rules, [
            // Todo complate translate keyword
            [['keyword'], 'common\validators\GoogleTranslationFilterValidator'],
            [['keyword', 'sort', 'filter'], 'string'],
            [['page', 'min_price', 'max_price','category'], 'integer'],
            [['keyword', 'sort', 'filter'], 'filter', 'filter' => 'trim'],
            [['keyword', 'sort', 'filter'], 'filter', 'filter' => '\yii\helpers\Html::encode'],
            [['page'], 'integer', 'min' => 1],
            [['page'], 'default', 'value' => 1],
            ['min_price', 'common\validators\PriceRangeValidator', 'conditionOperator' => '<', 'assistAttribute' => 'max_price'],
            ['max_price', 'common\validators\PriceRangeValidator', 'conditionOperator' => '>', 'assistAttribute' => 'min_price'],
        ]);

        return $rules;
    }

    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(),[
            'keyword', 'category', 'filter', 'page', 'min_price', 'max_price', 'sort',
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(),[
            'keyword' => 'Keyword',
            'category' => 'category',
            'filter' => 'filter',
            'page' => 'page',
            'min_price' => 'min_price',
            'max_price' => 'max_price',
            'sort' => 'sort',

        ]);
    }
    public function hasAttribute($name = null){
        if($name === null){
            return true;
        }
        $has = false;
        $attributes = $this->attributes();
        foreach ($attributes as $attribute){
            if($attribute === $name){
                $has = true;
                break;
            }
        }
        return $has;
    }
    public function getFirstErrors()
    {
        $errors = parent::getFirstErrors();
        reset($errors);
        return $errors;
    }

    /**
     * @param $value
     * @return bool
     */
    public function isEmpty($value){
        return \common\helpers\WeshopHelper::isEmpty($value);
    }

    /**
     * @return array
     */
    public function getCacheKey()
    {
        return [
            get_called_class(),
            $this->getStoreManager()->getId(),
            $this->buildParams()
        ];
    }


    /**
     * Build Parameter as Array
     * @return array|mixed
     */
    abstract function params();
}