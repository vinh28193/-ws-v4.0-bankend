<?php

use yii\db\Migration;

class m190605_013401_create_table_package extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%package}}', [
            'id' => $this->primaryKey(),
            'tracking_code' => $this->string(255)->notNull(),
            'product_id' => $this->integer(11),
            'order_id' => $this->integer(11),
            'quantity' => $this->integer(11),
            'weight' => $this->float(),
            'dimension_l' => $this->float(),
            'dimension_w' => $this->float(),
            'dimension_h' => $this->float(),
            'manifest_id' => $this->integer(11),
            'manifest_code' => $this->string(255),
            'purchase_invoice_number' => $this->string(255),
            'status' => $this->string(255),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->integer(11),
            'updated_by' => $this->integer(11),
            'item_name' => $this->string(255)->comment('tên sản phẩm trả về từ boxme'),
            'warehouse_tag_boxme' => $this->string(255)->comment('wtag của boxme'),
            'note_boxme' => $this->string(255)->comment('note của boxme'),
            'image' => $this->text(),
            'tracking_merge' => $this->text()->comment(' List tracking khi merge từ thừa và thiếu '),
            'hold' => $this->integer(11)->comment('Đánh dấu hàng hold. 1 là hold'),
            'type_tracking' => $this->string(255)->comment('split, normal, unknown'),
            'seller_refund_amount' => $this->decimal(18, 2)->comment('Sô tiền seller hoàn'),
            'draft_data_tracking_id' => $this->integer(11),
            'stock_in_local' => $this->integer(11)->comment('Thời gian nhập kho local'),
            'stock_out_local' => $this->integer(11)->comment('Thời gian xuất kho local'),
            'at_customer' => $this->integer(11)->comment('Thời gian tới tay khách hàng'),
            'returned' => $this->integer(11)->comment('Thời gian hoàn trả'),
            'lost' => $this->integer(11)->comment('Thời gian mất hàng'),
            'current_status' => $this->string(255)->comment('Trạng thái hiện tại'),
            'shipment_id' => $this->integer(11),
            'remove' => $this->integer(11)->defaultValue('0')->comment('Xoá'),
            'price' => $this->decimal(18, 2)->comment('Giá trị của 1 sản phẩm'),
            'cod' => $this->decimal(18, 2)->comment('Tiền cod'),
            'version' => $this->string(255)->defaultValue('4.0')->comment('Version'),
            'delivery_note_id' => $this->integer(11),
            'delivery_note_code' => $this->string(32),
            'ws_tracking_code' => $this->string(255)->comment('Mã tracking của weshop'),
            'package_code' => $this->string(255),
            'stock_in_us' => $this->integer(11),
            'stock_out_us' => $this->integer(11),
            'insurance' => $this->integer(11)->defaultValue('0')->comment('0: auto, 1: insurance, 2: unInsurance'),
            'pack_wood' => $this->integer(11)->defaultValue('0')->comment('0: unInsurance, 1: insurance'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%package}}');
    }
}
