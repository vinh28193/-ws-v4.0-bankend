<?php


namespace common\models\draft;


class DraftWastingTracking extends \common\models\db\DraftWastingTracking
{
    /**
     * {@inheritdoc}
     * @return \common\models\queries\DraftWastingTrackingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\DraftWastingTrackingQuery(get_called_class());
    }
}