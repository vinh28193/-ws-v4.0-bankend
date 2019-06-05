<?php

use yii\db\Migration;

/**
 * Class m190225_031439_add_columns_into_store_table
 */
class m190225_031439_add_columns_into_store_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('store','app_id',$this->string(32)->notNull()->comment('Application Id'));
        $this->addColumn('store','store_alias',$this->string(32)->notNull()->comment('Store Alias'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('store','store_alias');
        $this->dropColumn('store','app_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190225_031439_add_app_id_column_into_store_table cannot be reverted.\n";

        return false;
    }
    */
}
