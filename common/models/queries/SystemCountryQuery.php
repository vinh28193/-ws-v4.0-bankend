<?php

namespace common\models\queries;

/**
 * This is the ActiveQuery class for [[\common\models\db\SystemDistrict]].
 *
 * @see \common\models\db\SystemDistrict
 */
class SystemCountryQuery extends \common\components\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\db\SystemDistrict[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\db\SystemDistrict|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
