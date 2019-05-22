<?php

namespace common\models\queries\cms;

use common\models\Category;

/**
 * This is the ActiveQuery class for [[\common\models\db_cms\Category]].
 *
 * @see \common\models\db_cms\Category
 */
class CategoryQuery extends \common\components\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\db_cms\Category[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\db_cms\Category|array|null
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
            $this->andWhere(['siteId' => $site]);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function allSite(){
        $this->andWhere(['IN','siteId',[
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
