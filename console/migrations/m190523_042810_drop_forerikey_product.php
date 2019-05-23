<?php

use yii\db\Migration;

/**
 * Class m190523_042810_drop_forerikey_product
 */
class m190523_042810_drop_forerikey_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-product-category_id','product');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addForeignKey('fk-product-category_id','product','category_id','category','id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190523_042810_drop_forerikey_product cannot be reverted.\n";

        return false;
    }
    */
}
