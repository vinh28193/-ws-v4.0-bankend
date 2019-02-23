<?php

use yii\db\Migration;

/**
 * Class m190223_081430_add_column_type_authorzationCode
 */
class m190223_081430_add_column_type_authorzationCode extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql="ALTER TABLE `authorization_codes` 
ADD COLUMN `type` varchar(50) NULL DEFAULT user AFTER `user_id`";
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190223_081430_add_column_type_authorzationCode cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190223_081430_add_column_type_authorzationCode cannot be reverted.\n";

        return false;
    }
    */
}
