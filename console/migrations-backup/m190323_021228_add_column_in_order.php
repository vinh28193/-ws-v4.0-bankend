<?php

use yii\db\Migration;

/**
 * Class m190323_021228_add_column_in_order
 */
class m190323_021228_add_column_in_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order','purchase_assignee_id',"int(0) NULL COMMENT 'Id nhân viên mua hàng' after updated_at");
        $this->addForeignKey('fk-order-user','order','purchase_assignee_id','user','id','RESTRICT','RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190323_021228_add_column_in_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190323_021228_add_column_in_order cannot be reverted.\n";

        return false;
    }
    */
}
