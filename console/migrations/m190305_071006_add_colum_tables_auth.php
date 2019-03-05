<?php

use yii\db\Migration;

/**
 * Class m190305_071006_add_colum_tables_auth
 */
class m190305_071006_add_colum_tables_auth extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('auth','created_at','int');
        $this->addColumn('auth','created_by','int');
        $this->addColumn('auth','updated_at','int');
        $this->addColumn('auth','updated_by','int');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190305_071006_add_colum_tables_auth cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190305_071006_add_colum_tables_auth cannot be reverted.\n";

        return false;
    }
    */
}
