<?php

use yii\db\Migration;

/**
 * Class m190305_044312_auth_table
 */
class m190305_044312_auth_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('auth', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'source' => $this->string()->notNull(),
            'source_id' => $this->string()->notNull(),
        ]);

        $this->addForeignKey('fk-auth-user_id-user-id', 'auth', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');

    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('auth');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190305_044312_auth_table cannot be reverted.\n";

        return false;
    }
    */
}
