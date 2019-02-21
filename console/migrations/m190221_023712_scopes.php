<?php

use yii\db\Migration;

/**
 * Class m190221_023712_scopes
 */
class m190221_023712_scopes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('scopes',[
            'id' => $this->primaryKey()->comment("ID"),
            'name' => $this->string(255)->comment(""),
            'slug' => $this->string(255)->comment(""),
            'description' => $this->string(255)->comment(""),
            'level' => $this->integer(11)->comment(""),

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
        echo "m190221_023712_scopes cannot be reverted.\n";

        return false;
    }
    */
}
