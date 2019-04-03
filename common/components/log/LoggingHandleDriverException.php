<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-04-02
 * Time: 16:00
 */

namespace common\components\log;


class LoggingHandleDriverException extends \yii\base\Exception
{

    public function getName()
    {
        return 'Push data in file';
    }
}