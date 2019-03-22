<?php

namespace common\models\queries;

/**
 * This is the ActiveQuery class for [[\common\models\db\PackingItemRaw]].
 *
 * @see \common\models\db\PackingItemRaw
 */
class PackingItemRawQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\db\PackingItemRaw[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\db\PackingItemRaw|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
