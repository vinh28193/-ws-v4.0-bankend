<?php

namespace frontend\modules\checkout\methods;

use frontend\modules\checkout\Payment;
use yii\bootstrap4\Widget;
use yii\helpers\ArrayHelper;

class MethodWidget extends Widget
{

    /**
     * @var integer Group
     */
    public $group;
    /**
     * @var array
     */
    public $methods;

    /**
     * @var Payment
     */
    public $payment;


    /**
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render($view, $params = [])
    {
        $params = ArrayHelper::merge([
            'group' => $this->group,
            'methods' => $this->methods,
            'payment' => $this->payment
        ],$params);
        return parent::render($view, $params);
    }

    /**
     * @param $group
     * @param $methods
     * @param $payment
     * @return mixed
     */
    public static function create($group,$methods,$payment){
        $class = get_called_class();
        return $class::widget([
            'group' => $group,
            'methods' => $methods,
            'payment' => $payment
        ]);
    }
}