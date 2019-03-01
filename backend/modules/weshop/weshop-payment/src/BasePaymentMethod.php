<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-28
 * Time: 11:09
 */

namespace weshop\payment;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use weshop\payment\traits\OwnerTrait;

class BasePaymentMethod extends \yii\base\BaseObject
{
    use OwnerTrait;

    public function getName(){
        return '';
    }
    /**
     * @return string
     */
    public function getNavigator(){
        return '';
    }

    /**
     * @return string
     */
    public function getDetail(){
        return '';
    }

    public function getAccessUrl($params = []){
        $route = [
            '/payment/method/view',
            'name' => \yii\helpers\Inflector::variablize($this->getName()),
        ];

        if (is_array($params)){
            $route = ArrayHelper::merge($route, $params);
        }

        return Url::toRoute($route);
    }
}