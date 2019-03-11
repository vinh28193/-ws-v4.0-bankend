<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-08-01
 * Time: 09:39
 */

namespace common\validators;

use Yii;

class GoogleTranslationFilterValidator extends \yii\validators\FilterValidator
{
    public $filter = 'common\components\GoogleTranslation::t';

}