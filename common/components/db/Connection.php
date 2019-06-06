<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-21
 * Time: 13:31
 */

namespace common\components\db;


use common\components\db\oci\Pdo;
use Yii;
use yii\helpers\ArrayHelper;

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


    }

    protected function createPdoInstance()
    {
        $pdoClass = $this->pdoClass;
        $attributes = $this->attributes;
        if ($pdoClass === null) {
            $pdoClass = 'PDO';
            if ($this->getDriverName() !== null) {
                $driver = $this->getDriverName();
            } elseif (($pos = strpos($this->dsn, ':')) !== false) {
                $driver = strtolower(substr($this->dsn, 0, $pos));
            }
            if (isset($driver)) {
                if ($driver === 'mssql' || $driver === 'dblib') {
                    $pdoClass = 'yii\db\mssql\PDO';
                } elseif ($driver === 'sqlsrv') {
                    $pdoClass = 'yii\db\mssql\SqlsrvPDO';
                } elseif ($driver === 'oci') {

                    $pdoClass = 'common\components\db\oci\Pdo';
                    if (!$attributes || !is_array($attributes)) {
                        $attributes = [];
                    }
                    $attributes = ArrayHelper::merge($attributes, [
                        PDO::ATTR_CASE => PDO::CASE_LOWER,
                    ]);
                }
            }
        }

        $dsn = $this->dsn;
        if (strncmp('sqlite:@', $dsn, 8) === 0) {
            $dsn = 'sqlite:' . Yii::getAlias(substr($dsn, 7));
        }
        return new $pdoClass($dsn, $this->username, $this->password, $attributes);
    }


}