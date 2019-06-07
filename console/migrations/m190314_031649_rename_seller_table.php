<?php

use yii\db\Migration;

/**
 * Class m190314_031649_rename_seller_table
 */
class m190314_031649_rename_seller_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('seller','name','seller_name');
        $this->renameColumn('seller','link_store','seller_link_store');
        $this->renameColumn('seller','rate','seller_store_rate');
        $this->renameColumn('seller','description','seller_store_description');
        $this->renameColumn('seller','remove','seller_remove');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190314_031649_rename_seller_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190314_031649_rename_seller_table cannot be reverted.\n";

        return false;
    }
    */
}
