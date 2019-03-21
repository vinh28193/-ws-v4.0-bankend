<?php

namespace common\models\queries;

/**
 * This is the ActiveQuery class for [[\common\models\db\TrackingCode]].
 *
 * @see \common\models\db\TrackingCode
 */
class TrackingCodeQuery extends \yii\db\ActiveQuery
{

    /**
     * {@inheritdoc}
     * @return \common\models\db\TrackingCode[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\db\TrackingCode|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function filter($params){

    }
}
