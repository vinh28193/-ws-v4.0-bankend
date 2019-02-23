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
            [1, 1, 'total_final_amount_local', 'VND', 'số tiền cuối cùng khách hàng phải thanh toán', 1, 1, 1, time(), 1, time()],
            [2, 1, 'total_paid_amount_local', 'VND', 'số tiền khách hàng đã thanh toán', 1, 1, 1, time(), 1, time()],
            [3, 1, 'total_refund_amount_local', 'VND', 'số tiền đã hoàn trả cho khách hàng', 1, 1, 1, time(), 1, time()],
            [4, 1, 'total_amount_local', 'VND', 'tổng giá đơn hàng', 1, 1, 1, time(), 1, time()],
            [5, 1, 'total_fee_amount_local', 'VND', 'tổng phí đơn hàng', 1, 1, 1, time(), 1, time()],
            [6, 1, 'total_counpon_amount_local', 'VND', 'Tổng số tiền giảm giá bằng mã counpon', 1, 1, 1, time(), 1, time()],
            [7, 1, 'total_promotion_amount_local', 'VND', 'Tổng số tiền giảm giá do promotion', 1, 1, 1, time(), 1, time()],
            [8, 1, 'total_price_amount_local', 'VND', 'tổng giá tiền các item', 1, 1, 1, time(), 1, time()],
            [9, 1, 'total_tax_us_amount_local', 'VND', 'Tổng phí us tax', 1, 1, 1, time(), 1, time()],
            [10, 1, 'total_shipping_us_amount_local', 'VND', 'Tổng phí shipping us', 1, 1, 1, time(), 1, time()],
            [11, 1, 'total_weshop_fee_amount_local', 'VND', 'Tổng phí weshop', 1, 1, 1, time(), 1, time()],
            [12, 1, 'total_intl_shipping_fee_amount_local', 'VND', 'Tổng phí vận chuyển quốc tế', 1, 1, 1, time(), 1, time()],
            [13, 1, 'total_custom_fee_amount_local', 'VND', 'Tổng phí phụ thu', 1, 1, 1, time(), 1, time()],
            [14, 1, 'total_delivery_fee_amount_local', 'VND', 'Tổng phí vận chuyển nội đị', 1, 1, 1, time(), 1, time()],
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
