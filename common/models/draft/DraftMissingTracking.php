<?php


namespace common\models\draft;


class DraftMissingTracking extends \common\models\db\DraftMissingTracking
{
    /**
     * {@inheritdoc}
     * @return \common\models\queries\DraftMissingTrackingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\DraftMissingTrackingQuery(get_called_class());
    }
}