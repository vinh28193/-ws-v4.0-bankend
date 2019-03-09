<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-09
 * Time: 11:58
 */

namespace common\behaviors;

use yii\db\BaseActiveRecord;

class TimestampBehavior extends \yii\behaviors\TimestampBehavior
{

    public function init()
    {
        parent::init();
        $this->attributes = [
            BaseActiveRecord::EVENT_BEFORE_INSERT => $this->createdAtAttribute,
            BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->updatedAtAttribute,
        ];
    }
}