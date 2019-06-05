<?php

use yii\db\Migration;

/**
 * Class m190423_080414_alter_package_item
 */
class m190423_080414_alter_package_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('package_item','sku','product_id');
        $this->alterColumn('package_item','product_id', $this->integer(11)->comment('Product id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('package_item','product_id', $this->string(255)->comment('sku sản phẩm'));
        $this->renameColumn('package_item','product_id','sku');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190423_080414_alter_package_item cannot be reverted.\n";

        return false;
    }
    */
}
