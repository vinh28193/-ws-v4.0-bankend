<?php

use yii\db\Migration;

/**
 * Class m190226_082527_add_colum_table_package
 */
class m190226_082527_add_colum_table_package extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('package','remove','int NULL DEFAULT 0');
        $this->addColumn('package_item','remove','int NULL DEFAULT 0');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190226_082527_add_colum_table_package cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190226_082527_add_colum_table_package cannot be reverted.\n";

        return false;
    }
    */
}
