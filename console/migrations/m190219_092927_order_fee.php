<?php

use yii\db\Migration;

/**
 * Class m190219_092927_order_fee
 */
class m190219_092927_order_fee extends Migration
{
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
        $this->createTable('order_fee',[
            'id' => $this->primaryKey()->comment(''),
            'order_id' => $this->integer(11)->comment('order id'),
            'type_fee' => $this->string(255)->comment('loại phí: đơn giá gốc, ship us, ship nhật , weshop fee, ...'),
            'amount_local' => $this->decimal(18,2)->comment('tiền local'),
            'amount' => $this->decimal(18,2)->comment('tiền ngoại tệ'),
            'currency' => $this->string(255)->comment('loại tiền ngoại tệ'),
            'discount_amount' => $this->decimal(18,2)->comment('tiền giảm giá'),
            'created_time' => $this->bigInteger()->comment(""),
            'updated_time' => $this->bigInteger()->comment(""),
            'remove' => $this->tinyInteger(4)->comment(""),
        ],$tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190219_092927_order_fee cannot be reverted.\n";

//        $this->dropIndex('idx-order_fee-order_id', 'order_fee');
//        $this->dropForeignKey('fk-order_fee-order_id', 'order_fee');
//        $this->dropTable('order_fee');
//        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190219_092927_order_fee cannot be reverted.\n";

        return false;
    }
    */
}
