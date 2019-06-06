<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-21
 * Time: 13:31
 */

namespace common\components\db;


class Connection extends \yii\db\Connection
{

    protected $overrideSchemaMap = [
//        'mysql' => 'common\components\db\mysql\Schema', // MySQL
        'oci' => 'common\components\db\oci\Schema' // Oracle
    ];


    public function init()
    {
        parent::init();
        $this->schemaMap = array_merge($this->schemaMap, $this->overrideSchemaMap);
        if ($this->getDriverName() === 'oci') {
            $this->getSlavePdo()->setAttribute(\PDO::ATTR_CASE, \PDO::CASE_LOWER);
        }
    }

}