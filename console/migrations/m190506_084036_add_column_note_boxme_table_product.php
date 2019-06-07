<?php

use yii\db\Migration;

/**
 * Class m190506_084036_add_column_note_boxme_table_product
 */
class m190506_084036_add_column_note_boxme_table_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product', 'note_boxme',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190506_084036_add_column_note_boxme_table_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190506_084036_add_column_note_boxme_table_product cannot be reverted.\n";

        return false;
    }
    */
}
