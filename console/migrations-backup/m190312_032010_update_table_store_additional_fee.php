<?php

use yii\db\Migration;

/**
 * Class m190312_032010_update_table_store_additional_fee
 */
class m190312_032010_update_table_store_additional_fee extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->update('store_additional_fee', ['name' => 'product_price_origin', 'description' => 'Giá gốc tại xuất sứ . Ví Dụ : Giá gốc sản phẩm EBAY / giá gốc AMAZON'], ['id' => 1]);
        $this->update('store_additional_fee', ['name' => 'tax_fee_origin', 'description' => 'Phí tax tại ( xuất sứ) Tại EBAY / AMAZON'], ['id' => 2]);
        $this->update('store_additional_fee', ['name' => 'delivery_fee_local'], ['id' => 7]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->update('store_additional_fee', ['name' => 'delivery_fee'], ['id' => 7]);
        $this->update('store_additional_fee', ['name' => 'tax_fee_origin', 'description' => 'Phí tax tại xuất sứ'], ['id' => 2]);
        $this->update('store_additional_fee', ['name' => 'origin_tax_fee', 'description' => 'Phí tax tại xuất sứ'], ['id' => 1]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190312_032010_update_table_store_additional_fee cannot be reverted.\n";

        return false;
    }
    */
}
