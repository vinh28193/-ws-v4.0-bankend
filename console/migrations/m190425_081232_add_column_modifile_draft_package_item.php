<?php

use yii\db\Migration;

/**
 * Class m190425_081232_add_column_modifile_draft_package_item
 */
class m190425_081232_add_column_modifile_draft_package_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('draft_package_item','stock_in_local',$this->integer()->comment('Thời gian nhập kho local'));
        $this->addColumn('draft_package_item','stock_out_local',$this->integer()->comment('Thời gian xuất kho local'));
        $this->addColumn('draft_package_item','at_customer',$this->integer()->comment('Thời gian tới tay khách hàng'));
        $this->addColumn('draft_package_item','returned',$this->integer()->comment('Thời gian hoàn trả'));
        $this->addColumn('draft_package_item','lost',$this->integer()->comment('Thời gian mất hàng'));
        $this->addColumn('draft_package_item','current_status',$this->string()->comment('Trạng thái hiện tại'));
        $this->addColumn('draft_package_item','shipment_id',$this->integer());
        $this->addColumn('draft_package_item','remove',$this->integer()->comment('Xoá')->defaultValue(0));
        $this->addColumn('draft_package_item','price',$this->decimal(18,2)->comment('Giá trị của 1 sản phẩm'));
        $this->addColumn('draft_package_item','cod',$this->decimal(18,2)->comment('Tiền cod'));
        $this->addColumn('draft_package_item','version',$this->string()->comment('Version')->defaultValue('4.0'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190425_081232_add_column_modifile_draft_package_item cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190425_081232_add_column_modifile_draft_package_item cannot be reverted.\n";

        return false;
    }
    */
}
