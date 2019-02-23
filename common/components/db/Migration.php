<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-21
 * Time: 09:02
 */

namespace common\components\db;

class Migration extends \yii\db\Migration {

    /**
     * @param $tableOptions
     * @return string
     */
    protected function getTableOptions($tableOptions){
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        return $tableOptions;
    }

    /**
     * @inheritdoc
     * @param string $table
     * @param array $columns
     * @param string| null $options
     */
    public function createTable($table, $columns, $options = null)
    {
        $options = $this->getTableOptions($options);
        parent::createTable($table, $columns, $options);
    }
}