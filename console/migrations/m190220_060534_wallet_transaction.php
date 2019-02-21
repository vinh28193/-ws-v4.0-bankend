<?php

use yii\db\Migration;

/**
 * Class m190220_060534_wallet_transaction
 */
class m190220_060534_wallet_transaction extends Migration
{
    public $list = [
        [
            'column' => 'order_id',
            'table' => 'order',
        ],
        [
            'column' => 'customer_id',
            'table' => 'customer',
        ]
    ];
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('wallet_transaction',[
            'id' => $this->primaryKey()->comment(''),
            'transaction_code' => $this->string(255)->comment('mã giao dịch của weshop'),
            'created_time' => $this->bigInteger()->comment('thời gian giao dịch'),
            'updated_time' => $this->bigInteger()->comment('thời gian cập nhật giao dịch'),
            'transaction_status' => $this->string(255)->comment('trạng thái giao dịch'),
            'transaction_type' => $this->string(255)->comment('Loại giao dịch: top up , payment, withdraw'),
            'customer_id' => $this->integer(11)->comment(''),
            'order_id' => $this->integer(11)->comment(''),
            'transaction_amount_local' => $this->decimal(18,2)->comment('số tiền giao dịch, có thể âm hoặc dương'),
            'transaction_description' => $this->text()->comment('mô tả giao dịch'),
            'note' => $this->text()->comment('ghi chú của nhân viên'),
            'transaction_reference_code' => $this->string(255)->comment('mã tham chiếu thu tiền , vd : mã vận đơn thu cod'),
            'third_party_transaction_code' => $this->string(255)->comment('mã giao dịch với bên thứ 3. VD: ngân lượng'),
            'third_party_transaction_link' => $this->text()->comment('Link thanh toán bên thứ 3'),
            'third_party_transaction_status' => $this->string(200)->comment('Trạng thái thanh toán của bên thứ 3'),
            'third_party_transaction_time' => $this->bigInteger()->comment('thời gian giao dịch bên thứ 3'),
            'before_transaction_amount_local' => $this->decimal(18,2)->comment('Số tiền trước giao dịch'),
            'after_transaction_amount_local' => $this->decimal(18,2)->comment('Số tiền sau giao dịch'),
        ],$tableOptions);

        foreach ($this->list as $data){
            $this->createIndex('idx-wallet_transaction-'.$data['column'],'wallet_transaction',$data['column']);
            $this->addForeignKey('fk-wallet_transaction-'.$data['column'], 'wallet_transaction', $data['column'], $data['table'], 'id');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190220_060534_wallet_transaction cannot be reverted.\n";

        foreach ($this->list as $data){
            $this->dropIndex('idx-wallet_transaction-'.$data['column'], 'wallet_transaction');
            $this->dropForeignKey('fk-wallet_transaction-'.$data['column'], 'wallet_transaction');
        }
        $this->dropTable('wallet_transaction');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190220_060534_wallet_transaction cannot be reverted.\n";

        return false;
    }
    */
}
