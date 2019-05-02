<?php

namespace common\models\queries;

use common\models\Package;

/**
 * This is the ActiveQuery class for [[\common\models\db\DraftPackageItem]].
 *
 * @see \common\models\db\Package
 */
class DraftPackageItemQuery extends \common\components\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere(['or',['status' => ''],['status' => null],['status' => 0]]);
        return $this;
    }

    /**
     * {@inheritdoc}
     * @return \common\models\db\Package[]|array
     */
    public function all($db = null)
    {
        $this->active();
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\db\Package|array|null
     */
    public function one($db = null)
    {
        $this->active();
        return parent::one($db);
    }
}
