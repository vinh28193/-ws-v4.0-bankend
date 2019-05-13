<?php

use yii\db\Migration;

/**
 * Class m190509_090819_add_column_promotion_table_promotion_user
 */
class m190509_090819_add_column_promotion_table_promotion_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('promotion_user', 'promotion_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190509_090819_add_column_promotion_table_promotion_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190509_090819_add_column_promotion_table_promotion_user cannot be reverted.\n";

        return false;
    }
    */
}
