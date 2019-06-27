<?php

use yii\db\Migration;

/**
 * Class m190627_113001_alter_seller_table
 */
class m190627_113001_alter_seller_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('seller', 'location', $this->string(255)->null());
        $this->addColumn('seller', 'country_code', $this->string(32)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('seller', 'location');
        $this->dropColumn('seller', 'country_code');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190627_113001_alter_seller_table cannot be reverted.\n";

        return false;
    }
    */
}
