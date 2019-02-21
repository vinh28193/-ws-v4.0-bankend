<?php

use yii\db\Migration;

/**
 * Class m190221_024421_actions
 */
class m190221_024421_actions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('actions',[
            'id' => $this->primaryKey()->comment("ID"),
            'name' => $this->string(255)->comment(""),
            'action' => $this->string(255)->comment(""),
            'description' => $this->string(255)->comment(""),

            'created_time' => $this->bigInteger()->comment(""),
            'updated_time' => $this->bigInteger()->comment(""),
            'remove' => $this->tinyInteger(4)->comment(""),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190221_024421_actions cannot be reverted.\n";

        return false;
    }
    */
}
