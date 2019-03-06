<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-07-31
 * Time: 16:53
 */

namespace common\validators;


class CategoryBannedValidator extends \yii\validators\Validator
{

    public $bannedList = [];
    public function init()
    {
        parent::init();
    }
}