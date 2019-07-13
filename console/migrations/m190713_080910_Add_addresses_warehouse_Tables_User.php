<?php

use yii\db\Migration;

/**
 * Class m190713_080910_Add_addresses_warehouse_Tables_User
 */
class m190713_080910_Add_addresses_warehouse_Tables_User extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user','pickup_id',$this->bigInteger());
        $this->addColumn('user','warehouse_code',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user','pickup_id');
        $this->dropColumn('user','warehouse_code');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190713_080910_Add_addresses_warehouse_Tables_User cannot be reverted.\n";

        return false;
    }
    */
}
