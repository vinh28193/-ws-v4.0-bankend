<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-21
 * Time: 13:32
 */

namespace common\components\db\mysql;

class Schema extends \yii\db\mysql\Schema
{

    /**
     * Creates a query builder for the MSSQL database.
     * @return QueryBuilder query builder interface.
     */
    public function createQueryBuilder()
    {
        return new QueryBuilder($this->db);
    }
}