<?php

use common\components\db\Migration;

/**
 * Handles the creation of table `payment_transaction`.
 * Has foreign keys to the tables:
 *
 * - `store`
 */
class m190515_105221_create_payment_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('payment_transaction', [
            'id' => $this->primaryKey()->comment('ID'),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID reference'),
            'customer_id' => $this->integer(11)->comment('customer id'),
            'transaction_code' => $this->string(32)->comment('mã giao dịch của weshop'),
            'transaction_type' => $this->string(10)->comment('Loại giao dịch: top up , payment, withdraw'),
            'transaction_status' => $this->string(10)->comment('trạng thái giao dịch'),
            'transaction_customer_name' => $this->string(255)->null(),
            'transaction_customer_email' => $this->string(255)->null(),
            'transaction_customer_phone' => $this->string(255)->null(),
            'transaction_customer_address' => $this->string(255)->null(),
            'transaction_customer_city' => $this->string(255)->null(),
            'transaction_customer_postcode' => $this->string(255)->null(),
            'transaction_customer_district' => $this->string(255)->null(),
            'transaction_customer_country' => $this->string(255)->null(),
            'payment_provider' => $this->string(50)->notNull(),
            'payment_method' => $this->string(50)->notNull(),
            'payment_bank_code' => $this->string(32)->notNull(),
            'coupon_code' => $this->string(32)->notNull(),
            'used_xu' => $this->integer(11)->defaultValue(0),
            'bulk_point' => $this->integer(11)->defaultValue(0),
            'orders' => $this->text()->notNull()->comment('list cart id'),
            'shipping' => $this->integer(11)->notNull()->comment('Dia chi ship'),
            'total_discount_amount' => $this->decimal(11, 2)->defaultValue(0),
            'before_discount_amount_local' => $this->decimal(18, 2),
            'transaction_amount_local' => $this->decimal(18, 2)->comment('số tiền giao dịch, có thể âm hoặc dương'),
            'transaction_description' => $this->text()->comment('mô tả giao dịch'),
            'note' => $this->text()->comment('ghi chú của nhân viên'),
            'transaction_reference_code' => $this->string(255)->comment('mã tham chiếu thu tiền , vd : mã vận đơn thu cod'),
            'third_party_transaction_code' => $this->string(255)->comment('mã giao dịch với bên thứ 3. VD: ngân lượng'),
            'third_party_transaction_link' => $this->text()->comment('Link thanh toán bên thứ 3'),
            'third_party_transaction_status' => $this->string(200)->comment('Trạng thái thanh toán của bên thứ 3'),
            'third_party_transaction_time' => $this->bigInteger()->comment('thời gian giao dịch bên thứ 3'),
            'before_transaction_amount_local' => $this->decimal(18, 2)->comment('Số tiền trước giao dịch'),
            'after_transaction_amount_local' => $this->decimal(18, 2)->comment('Số tiền sau giao dịch'),
            'created_at' => $this->integer(11)->defaultValue(null)->comment('Created at (timestamp)'),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('payment_transaction');
    }
}
