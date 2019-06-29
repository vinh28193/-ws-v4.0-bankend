<?php

use yii\db\Migration;

/**
 * Class m190628_133852_addcolumntracking
 */
class m190628_133852_addcolumntracking extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tracking_code','shipment_bm_code',$this->string()->comment('Mã shipment bên boxme'));
        $this->alterColumn('tracking_code','manifest_code',$this->string()->null());
        $this->alterColumn('tracking_code','manifest_id',$this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tracking_code','shipment_bm_code');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190628_133852_addcolumntracking cannot be reverted.\n";

        return false;
    }
    */
}
