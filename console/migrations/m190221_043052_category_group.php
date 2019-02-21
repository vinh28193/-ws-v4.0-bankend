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
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
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
        ],$tableOptions);
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
