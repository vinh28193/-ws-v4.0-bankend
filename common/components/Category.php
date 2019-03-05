<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 05/03/2019
 * Time: 10:10
 */

namespace common\components;
use yii\caching\Cache;
use common\models\db\Category as CategoryDb;
class Category
{

    public static function getAlias($alias)
    {
        $category = Cache::get('category-alias-' . $alias);
        if (empty($category)) {
            $category = CategoryDb::find()->andWhere(["alias" => $alias])->one();
            Cache::set('category-alias-' . $alias, $category, 60 * 10);
        }
        return $category;
    }

}