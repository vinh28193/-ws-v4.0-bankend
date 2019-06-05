<?php

use yii\db\Migration;

/**
 * Class m190412_095441_update_column_purchase_product
 */
class m190412_095441_update_column_purchase_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('purchase_product','receive_warehouse_id',"int COMMENT 'Id Kho nhận'");
        $this->addColumn('purchase_product','receive_warehouse_name',"varchar(255) COMMENT 'Tên Kho nhận'");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190412_095441_update_column_purchase_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190412_095441_update_column_purchase_product cannot be reverted.\n";

        return false;
    }
    */
}
