<?php


namespace common\models\draft;


class DraftBoxmeTracking extends \common\models\db\DraftBoxmeTracking
{
    /**
     * {@inheritdoc}
     * @return \common\models\queries\DraftBoxmeTrackingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\DraftBoxmeTrackingQuery(get_called_class());
    }
}