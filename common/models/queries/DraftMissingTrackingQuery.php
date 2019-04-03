<?php

namespace common\models\queries;

/**
 * This is the ActiveQuery class for [[\common\models\db\DraftMissingTracking]].
 *
 * @see \common\models\db\DraftMissingTracking
 */
class DraftMissingTrackingQuery extends \common\components\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\db\DraftMissingTracking[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\db\DraftMissingTracking|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
