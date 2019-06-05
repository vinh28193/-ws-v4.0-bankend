<?php

use yii\db\Migration;

/**
 * Class m190507_024611_add_column_table_delivery_note
 */
class m190507_024611_add_column_table_delivery_note extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('delivery_note','customer_id',$this->integer());
        $this->addColumn('delivery_note','receiver_name',$this->string());
        $this->addColumn('delivery_note','receiver_email',$this->string());
        $this->addColumn('delivery_note','receiver_phone',$this->string());
        $this->addColumn('delivery_note','receiver_address',$this->string());
        $this->addColumn('delivery_note','receiver_country_id',$this->integer());
        $this->addColumn('delivery_note','receiver_country_name',$this->string());
        $this->addColumn('delivery_note','receiver_province_id',$this->integer());
        $this->addColumn('delivery_note','receiver_province_name',$this->string());
        $this->addColumn('delivery_note','receiver_district_id',$this->integer());
        $this->addColumn('delivery_note','receiver_district_name',$this->string());
        $this->addColumn('delivery_note','receiver_post_code',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190507_024611_add_column_table_delivery_note cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190507_024611_add_column_table_delivery_note cannot be reverted.\n";

        return false;
    }
    */
}
