<?php

use yii\db\Migration;

/**
 * Class m190606_014837_uptocase_columtable
 */
class m190606_014837_uptocase_columtable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $dbTableNames = Yii::$app->db->schema->getTableNames();
        foreach ($dbTableNames as $dbTableName){
            echo $dbTableName ."\n";
            $dataColumns = Yii::$app->db->getTableSchema($dbTableName)->columnNames;
            foreach ($dataColumns as $column){
                $this->renameColumn($dbTableName,$column,strtoupper($column));
            }
            $this->renameTable($dbTableName,strtoupper($dbTableName));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190606_014837_uptocase_columtable cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190606_014837_uptocase_columtable cannot be reverted.\n";

        return false;
    }
    */
}
