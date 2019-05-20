<?php

namespace frontend\modules\payment\methods;

use frontend\modules\payment\Payment;
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
     * @var bool
     */
    public $selected = false;

    /**
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render($view, $params = [])
    {
//        var_dump($this->selected);
        $params = ArrayHelper::merge([
            'group' => $this->group,
            'methods' => $this->methods,
            'payment' => $this->payment,
            'selected' => $this->selected,
        ],$params);
        return parent::render($view, $params);
    }

    /**
     * @param $group integer
     * @param $methods array
     * @param $payment Payment
     * @return mixed
     */
    public static function create($group,$methods,$payment){
        $class = get_called_class();
        $show = false;
        foreach ($methods as $method) {
            if ($method['payment_method_id'] == $payment->payment_method && $method['payment_provider_id'] == $payment->payment_provider) {
                $show = true;
                break;
            } else {
                continue;
            }
        }
        return $class::widget([
            'group' => $group,
            'methods' => $methods,
            'payment' => $payment,
            'selected' => $show
        ]);
    }
}