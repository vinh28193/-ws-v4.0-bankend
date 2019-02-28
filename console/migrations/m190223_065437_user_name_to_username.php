<?php

use yii\db\Migration;

/**
 * Class m190223_065437_user_name_to_username
 */
class m190223_065437_user_name_to_username extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('customer','user_name','username');
        $this->renameColumn('customer','password','password_hash');
        $sql = "ALTER TABLE `customer`
MODIFY COLUMN `remove` tinyint(4) NULL DEFAULT 0 AFTER `active`";
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190223_065437_user_name_to_username cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190223_065437_user_name_to_username cannot be reverted.\n";

        return false;
    }
    */
}
