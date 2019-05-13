<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 25/02/2019
 * Time: 16:48
 */

namespace common\models;


use yii\helpers\ArrayHelper;

class Address extends \common\models\db\Address
{
    const TYPE_PRIMARY = 'primary';
    const TYPE_SHIPPING = 'shipping';

    const IS_DEFAULT = 1;


}