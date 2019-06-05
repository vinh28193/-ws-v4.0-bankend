<?php

use yii\db\Migration;

/**
 * Class m190227_020618_update_colum_table_package
 */
class m190227_020618_update_colum_table_package extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('package','package_code','varchar(50)');
        $this->alterColumn('package_item','package_code','varchar(50)');
        $this->alterColumn('package','current_status','varchar(100)');
        $this->alterColumn('package_item','current_status','varchar(100)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190227_020618_update_colum_table_package cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190227_020618_update_colum_table_package cannot be reverted.\n";

        return false;
    }
    */
}
