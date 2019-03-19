<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-19
 * Time: 21:01
 */

namespace common\components\db;


class ResolveFieldEvent extends \yii\base\Event
{
    /**
     * @var array
     */
    public $fields;

    /**
     * @var array
     */
    public $expand;

}