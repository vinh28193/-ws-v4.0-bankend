<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-29
 * Time: 18:15
 */

namespace common\products\amazon;

use yii\helpers\ArrayHelper;

class AmazonDetailRequest extends AmazonRequest
{

    public $asin_id;
    public $parent_asin_id;
    public $load_sub_url;

    public function rules()
    {
        $rules = parent::rules();
        $rules = ArrayHelper::merge($rules, [
            [['asin_id', 'parent_asin_id', 'load_sub_url'], 'string'],
            [['asin_id', 'parent_asin_id'], 'string', 'min' => 1],
            [['asin_id', 'parent_asin_id'], 'filter', 'filter' => 'trim'],
            [['asin_id', 'parent_asin_id'], 'filter', 'filter' => '\yii\helpers\Html::encode'],

        ]);

        return $rules;
    }

    public function params()
    {
        $params = parent::params();
        $params['asin_id'] = $this->asin_id;
        if (!$this->isEmpty($this->parent_asin_id)) {
            $params['parent_asin_id'] = $this->parent_asin_id;
        }
        if (!$this->isEmpty($this->load_sub_url)) {
            $params['load_sub_url'] = base64_decode($this->load_sub_url);
        }
        return $params;
    }
}