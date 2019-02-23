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
            'status' => $this->smallInteger()->defaultValue(1)->comment('Status (1:Active;2:Inactive)'),
            'created_by' => $this->integer(11)->defaultValue(null)->comment('Created by'),
            'created_at' => $this->integer(11)->defaultValue(null)->comment('Created at (timestamp)'),
            'updated_by' => $this->integer(11)->defaultValue(null)->comment('Updated by'),
            'updated_at' => $this->integer(11)->defaultValue(null)->comment('Updated at (timestamp)'),
        ]);

        $this->batchInsert('store_additional_fee', ['id', 'store_id', 'name', 'currency', 'description', 'is_convert', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at'], [
            [1, 1, 'price_amount', 'VND', 'giá tiền các item', 1, 1, 1, time(), 1, time()],
            [2, 1, 'tax_us_amount', 'VND', 'phí us tax', 1, 1, 1, time(), 1, time()],
            [3, 1, 'shipping_us_amount', 'VND', 'phí shipping us', 1, 1, 1, time(), 1, time()],
            [4, 1, 'weshop_fee_amount', 'VND', 'phí weshop', 1, 1, 1, time(), 1, time()],
            [5, 1, 'intl_shipping_fee_amount', 'VND', 'phí vận chuyển quốc tế', 1, 1, 1, time(), 1, time()],
            [6, 1, 'custom_fee_amount', 'VND', 'phí phụ thu', 1, 1, 1, time(), 1, time()],
            [7, 1, 'delivery_fee_amount', 'VND', 'phí vận chuyển nội địa', 1, 1, 1, time(), 1, time()],
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
