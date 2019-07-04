<?php

use yii\db\Migration;

/**
 * Class m190704_045843_update_column_user
 */
class m190704_045843_update_column_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropIndex('username', 'user');
        $this->dropIndex('email', 'user');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createIndex('username', 'user','username',true);
        $this->createIndex('email', 'user','email',true);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190704_045843_update_column_user cannot be reverted.\n";

        return false;
    }
    */
}
