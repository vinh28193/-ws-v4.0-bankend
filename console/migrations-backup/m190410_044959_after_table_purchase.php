<?php

use yii\db\Migration;

/**
 * Class m190410_044959_after_table_purchase
 */
class m190410_044959_after_table_purchase extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('purchase_order','total_type_changing','varchar(255) COMMENT \'kiểu chênh lệch: up, down\'');
        $this->alterColumn('purchase_product','type_changing','varchar(255) COMMENT\'kiểu chênh lệch: up, down\'');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190410_044959_after_table_purchase cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190410_044959_after_table_purchase cannot be reverted.\n";

        return false;
    }
    */
}
