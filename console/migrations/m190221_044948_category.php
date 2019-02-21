<?php

use yii\db\Migration;

/**
 * Class m190221_044948_category
 */
class m190221_044948_category extends Migration
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
        $this->createTable('category',[
            'id' => $this->primaryKey()->comment("ID"),
            'alias' => $this->string(255)->comment(""),
            'site' => $this->string(255)->comment("ebay / amazon / amazon-jp"),
            'origin_name' => $this->string(255)->comment(""),
            'category_group_id' => $this->integer(11)->comment(""),
            'parent_id' => $this->string(255)->comment(""),
            'description' => $this->string(255)->comment(""),
            'weight' => $this->double()->comment(""),
            'inter_shipping_b' => $this->double()->comment(""),
            'custom_fee' => $this->decimal(18,2)->comment(""),
            'level' => $this->integer(11)->comment(""),
            'path' => $this->string(255)->comment(""),

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
//
//        foreach ($this->list as $data){
//            $this->dropIndex('idx-category-'.$data['column'], 'category');
//            $this->dropForeignKey('fk-category-'.$data['column'], 'category');
//        }
//        $this->dropTable('category');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190221_044948_category cannot be reverted.\n";

        return false;
    }
    */
}
