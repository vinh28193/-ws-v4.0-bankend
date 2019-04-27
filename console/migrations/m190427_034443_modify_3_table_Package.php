<?php

use yii\db\Migration;

/**
 * Class m190427_034443_modify_3_table_Package
 */
class m190427_034443_modify_3_table_Package extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('package_item');
        $this->renameTable('package','delivery_note');
        $this->renameTable('draft_package_item','package');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190427_034443_modify_3_table_Package cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190427_034443_modify_3_table_Package cannot be reverted.\n";

        return false;
    }
    */
}
