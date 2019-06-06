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
        $check = false;
        foreach ($dbTableNames as $dbTableName){
            echo $dbTableName ."\n";
//            if($dbTableName == 'ws_migration'){
//                continue;
//            }
//            if($dbTableName == 'ws_queued_email'){
//                $check = true;
//            }
//            if(!$check){
//                continue;
//            }
            $dataColumns = Yii::$app->db->getTableSchema($dbTableName)->columnNames;
            foreach ($dataColumns as $column){
                if(!ctype_upper(preg_replace('/[^a-zA-Z]/','',$column))){
                    $this->renameColumn($dbTableName,$column,strtoupper($column));
                }
            }
            if(!ctype_upper(preg_replace('/[^a-zA-Z]/','',$dbTableName))){
                $this->renameTable($dbTableName,strtoupper($dbTableName));
            }
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
