<?php

namespace common\models\queries;

use common\models\Category;

/**
 * This is the ActiveQuery class for [[\common\models\db\Category]].
 *
 * @see \common\models\db\Category
 */
class CategoryQuery extends \common\components\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\db\Category[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\db\Category|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    /**
     * @param null|integer $site
     * @return $this
     */
    public function forSite($site= null){
        if($site === null){
            $this->allSite();
        }else{
            $site = strtolower($site);
            $this->andWhere(['site' => $site]);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function allSite(){
        $this->andWhere(['IN','site',[
            Category::SITE_EBAY,
            Category::SITE_AMAZON_US,
            Category::SITE_AMAZON_UK,
            Category::SITE_AMAZON_JP
        ]]);
        return $this;
    }

    /**
     * @param array|string $alias
     * @return $this
     */
    public function alias($alias){
        $this->andWhere(['alias' => $alias]);
        return $this;
    }
}
