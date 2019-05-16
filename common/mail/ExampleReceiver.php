<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-07-26
 * Time: 11:23
 */

namespace common\mail;


class ExampleReceiver extends \yii\base\BaseObject implements ReceiverInterface
{

    public function extract($target)
    {
        $all = [
            'mail' => 'vinhvv@peacesoft.net',
            'phone' => '0987654321'
        ];
        return \yii\helpers\ArrayHelper::getValue($all,$target->id);
    }
}