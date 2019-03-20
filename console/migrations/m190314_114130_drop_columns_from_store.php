<?php

use yii\db\Migration;

/**
 * Class m190314_114130_drop_columns_from_store
 */
class m190314_114130_drop_columns_from_store extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('store','store_alias');
        $this->dropColumn('store','app_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('store','app_id',$this->string(32)->notNull()->comment('Application Id'));
        $this->addColumn('store','store_alias',$this->string(32)->notNull()->comment('Store Alias'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190314_114130_drop_columns_from_store cannot be reverted.\n";

        return false;
    }
    */
}
