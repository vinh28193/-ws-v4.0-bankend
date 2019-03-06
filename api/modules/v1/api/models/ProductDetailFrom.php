<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 14:30
 */

namespace api\modules\v1\api\models;


use common\products\ebay\EbayGate;

class ProductDetailFrom extends \yii\base\Model
{

    const SCENARIO_EBAY = 'ebay';

    public $id;

    public $parent_id;

    public $sub_product_url;

    public $with_detail;

    public function scenarios()
    {
        return [
            self::SCENARIO_EBAY => ['id']
        ];
    }

    public function attributes()
    {
        return [
            'id', 'parent_id', 'sub_product_url', 'with_detail'
        ];
    }

    public function rules()
    {
        return [
            [['id'],'required' , 'on' => self::SCENARIO_EBAY]
        ];
    }

    /**
     * @return array|bool|mixed
     * @throws \yii\web\ServerErrorHttpException
     */
    public function detail(){

        if(!$this->validate()){
            return false;
        }
        switch ($this->getScenario()){
            case self::SCENARIO_EBAY:
                $gate = new EbayGate();
                return $gate->getProduct($this->id);
        }
        return false;
    }
}