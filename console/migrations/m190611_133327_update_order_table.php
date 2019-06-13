<?php

use yii\db\Migration;

/**
 * Class m190611_133327_update_order_table
 */
class m190611_133327_update_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'buyer_email', $this->string(255)->notNull()->after('quotation_note'));
        $this->addColumn('order', 'buyer_name', $this->string(255)->notNull()->after('buyer_email'));
        $this->addColumn('order', 'buyer_address', $this->string(255)->notNull()->after('buyer_name'));
        $this->addColumn('order', 'buyer_country_id', $this->integer(11)->notNull()->after('buyer_address'));
        $this->addColumn('order', 'buyer_country_name', $this->string(255)->notNull()->after('buyer_country_id'));
        $this->addColumn('order', 'buyer_province_id', $this->integer(11)->notNull()->after('buyer_country_name'));
        $this->addColumn('order', 'buyer_province_name', $this->string(255)->notNull()->after('buyer_province_id'));
        $this->addColumn('order', 'buyer_district_id', $this->integer(11)->notNull()->after('buyer_province_name'));
        $this->addColumn('order', 'buyer_district_name', $this->string(255)->notNull()->after('buyer_district_id'));
        $this->addColumn('order', 'buyer_post_code', $this->string(255)->null()->after('buyer_district_name'));
        $this->alterColumn('order', 'receiver_address_id', $this->integer(11)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'buyer_email');
        $this->dropColumn('order', 'buyer_name');
        $this->dropColumn('order', 'buyer_address');
        $this->dropColumn('order', 'buyer_country_id');
        $this->dropColumn('order', 'buyer_country_name');
        $this->dropColumn('order', 'buyer_province_id');
        $this->dropColumn('order', 'buyer_province_name');
        $this->dropColumn('order', 'buyer_district_id');
        $this->dropColumn('order', 'buyer_district_name');
        $this->dropColumn('order', 'buyer_post_code');
        $this->alterColumn('order', 'receiver_address_id', $this->integer(11)->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190611_133327_update_order_table cannot be reverted.\n";

        return false;
    }
    */
}
