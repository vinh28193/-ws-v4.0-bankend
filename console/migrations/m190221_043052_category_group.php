<?php

use yii\db\Migration;

/**
 * Class m190221_043052_category_group
 */
class m190221_043052_category_group extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('category_group',[
            'id' => $this->primaryKey()->comment("ID"),
            'name' => $this->string(255)->comment(""),
            'description' => $this->string(255)->comment(""),
            'store_id' => $this->integer(11)->comment(""),
            'parent_id' => $this->integer(11)->comment(""),
            'rule' => $this->text()->comment(""),
            'rule_description' => $this->text()->comment(""),

            'created_time' => $this->bigInteger()->comment(""),
            'updated_time' => $this->bigInteger()->comment(""),
            'active' => $this->tinyInteger(4)->comment(""),
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
        echo "m190221_043052_category_group cannot be reverted.\n";

        return false;
    }
    */
}
