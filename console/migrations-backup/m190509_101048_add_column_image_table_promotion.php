<?php

use yii\db\Migration;

/**
 * Class m190509_101048_add_column_image_table_promotion
 */
class m190509_101048_add_column_image_table_promotion extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('promotion', 'promotion_image',$this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190509_101048_add_column_image_table_promotion cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190509_101048_add_column_image_table_promotion cannot be reverted.\n";

        return false;
    }
    */
}
