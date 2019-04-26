<?php

use yii\db\Migration;

/**
 * Class m190426_025138_add_package_id
 */
class m190426_025138_add_package_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('draft_package_item', 'package_id' , $this->integer(11));
        $this->addColumn('draft_package_item', 'package_code' , $this->string(32));
        $this->addColumn('package', 'shipment_id' , $this->integer(11));
        $this->addColumn('shipment', 'active' , $this->integer(11)->defaultValue(1));
        $this->addColumn('shipment', 'shipment_send_at' , $this->integer(11));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('shipment', 'shipment_send_at' );
        $this->dropColumn('shipment', 'active' );
        $this->dropColumn('package', 'shipment_id');
        $this->dropColumn('draft_package_item', 'package_code');
        $this->dropColumn('draft_package_item', 'package_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190426_025138_add_package_id cannot be reverted.\n";

        return false;
    }
    */
}
