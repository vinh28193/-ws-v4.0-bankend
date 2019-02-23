<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-22
 * Time: 13:27
 */

namespace common\components\db;

use Yii;
use yii\helpers\ArrayHelper;

class ActiveRecord extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors = ArrayHelper::merge($behaviors, [
            'timestamp' => [
                'class' => \yii\behaviors\TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            'blameable' => [
                'class' => \yii\behaviors\BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
                'defaultValue' => 1,
            ],
        ]);
        return $behaviors;
    }
}