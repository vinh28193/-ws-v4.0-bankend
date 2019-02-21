<?php

use yii\db\Migration;

/**
 * Class m190221_062527_category_custom_policy
 */
class m190221_062527_category_custom_policy extends Migration
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
        $this->createTable('category_custom_policy',[
            'id' => $this->primaryKey()->comment("ID"),
            'name' => $this->string(255)->comment(""),
            'description' => $this->string(255)->comment(""),
            'code' => $this->string(255)->comment(""),
            'limit' => $this->integer(11)->comment(""),
            'is_special' => $this->integer(11)->comment(""),
            'min_price' => $this->decimal(18,2)->comment(""),
            'max_price' => $this->decimal(18,2)->comment(""),
            'custom_rate_fee' => $this->decimal(18,2)->comment(""),
            'use_percentage' => $this->decimal(18,2)->comment(""),
            'custom_fix_fee_per_unit' => $this->decimal(18,2)->comment(""),
            'custom_fix_fee_per_weight' => $this->decimal(18,2)->comment(""),
            'custom_fix_percent_per_weight' => $this->decimal(18,2)->comment(""),
            'store_id' => $this->integer(11)->comment(""),
            'item_maximum_per_category' => $this->integer(11)->comment(""),
            'weight_maximum_per_category' => $this->decimal(18,2)->comment(""),
            'sort_order' => $this->integer(11)->comment(""),

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

//        foreach ($this->list as $data){
//            $this->dropIndex('idx-category_custom_policy-'.$data['column'], 'category_custom_policy');
//            $this->dropForeignKey('fk-category_custom_policy-'.$data['column'], 'category_custom_policy');
//        }
//        $this->dropTable('category_custom_policy');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190221_062527_category_custom_policy cannot be reverted.\n";

        return false;
    }
    */
}
