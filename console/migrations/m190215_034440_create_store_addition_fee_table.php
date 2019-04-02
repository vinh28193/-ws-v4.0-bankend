<?php

use common\components\db\Migration;

/**
 * Handles the creation of table `store_additional_fee`.
 */
class m190215_034440_create_store_addition_fee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('store_additional_fee', [
            'id' => $this->primaryKey()->comment('ID'),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID reference'),
            'name' => $this->string(50)->notNull()->comment('Fee Name'),
            'currency' => $this->string(11)->notNull()->defaultValue('USD')->comment('Currency (USD/VND)'),
            'description' => $this->text()->null()->comment('Description'),
            'is_convert' => $this->smallInteger()->defaultValue(1)->comment('Is Convert (1:Can Convert;2:Can Not)'),
            'is_read_only' => $this->smallInteger()->defaultValue(1)->comment('Is Read Only'),
            'status' => $this->smallInteger()->defaultValue(1)->comment('Status (1:Active;2:Inactive)'),
            'created_by' => $this->integer(11)->defaultValue(null)->comment('Created by'),
            'created_time' => $this->integer(11)->defaultValue(null)->comment('Created at (timestamp)'),
            'updated_by' => $this->integer(11)->defaultValue(null)->comment('Updated by'),
            'updated_time' => $this->integer(11)->defaultValue(null)->comment('Updated at (timestamp)'),
        ]);

        $this->batchInsert('store_additional_fee', ['id', 'store_id', 'name', 'currency', 'description', 'is_convert', 'is_read_only', 'status', 'created_by', 'created_time', 'updated_by', 'updated_time'], [
            [1, 1, 'origin_fee', 'VND', 'Phí gốc tại xuất sứ', 1, 0, 1, 1, time(), 1, time()],
            [2, 1, 'origin_tax_fee', 'VND', 'Phí tax tại xuất sứ', 1, 0, 1, 1, time(), 1, time()],
            [3, 1, 'origin_shipping_fee', 'VND', 'Phí shipping tại xuất sứ', 1, 0, 1, 1, time(), 1, time()],
            [4, 1, 'weshop_fee', 'VND', 'Phí weshop', 1, 1, 0, 1, time(), 1, time()],
            [5, 1, 'intl_shipping_fee', 'VND', 'Phí vận chuyển quốc tế', 1, 1, 0, 1, time(), 1, time()],
            [6, 1, 'custom_fee', 'VND', 'Phí phụ thu', 1, 1, 0, 1, time(), 1, time()],
            [7, 1, 'delivery_fee', 'VND', 'Phí vận chuyển nội địa', 1, 1, 1, 1, time(), 1, time()],
            [8, 1, 'packing_fee', 'VND', 'Phí đóng hàng', 1, 1, 1, 1, time(), 1, time()],
            [9, 1, 'inspection_fee', 'VND', 'Phí kiểm hàng', 1, 1, 1, 1, time(), 1, time()],
            [10, 1, 'insurance_fee', 'VND', 'Phí bảo hiểm', 1, 1, 1, 1, time(), 1, time()],
            [11, 1, 'vat_fee', 'VND', 'Phí VAT', 1, 0, 1, 1,time(), 1, time()],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('store_additional_fee');
    }
}
