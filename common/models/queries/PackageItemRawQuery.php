<?php

namespace common\models\queries;

/**
 * This is the ActiveQuery class for [[\common\models\db\PackageItemRaw]].
 *
 * @see \common\models\db\PackageItemRaw
 */
class PackageItemRawQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\db\PackageItemRaw[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\db\PackageItemRaw|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
