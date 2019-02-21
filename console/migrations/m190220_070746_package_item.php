<?php

use yii\db\Migration;

/**
 * Class m190220_070746_package_item
 */
class m190220_070746_package_item extends Migration
{
    public $list = [
        [
            'column' => 'package_id',
            'table' => 'package',
        ],
        [
            'column' => 'order_id',
            'table' => 'order',
        ]
    ];
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('package_item',[
            'id' => $this->primaryKey()->comment(''),
            'package_id' => $this->integer(11)->comment('id của package'),
            'package_code' => $this->integer(11)->comment('mã kiện của weshop'),
            'box_me_warehouse_tag' => $this->string(255)->comment('mã thẻ kho box me'),
            'order_id' => $this->integer(11)->comment('order id'),
            'sku' => $this->string(255)->comment('sku sản phẩm'),
            'quantity' => $this->integer(11)->comment('số lượng'),
            'weight' => $this->double()->comment('cân nặng tịnh , đơn vị gram, box me trả về'),
            'change_weight' => $this->double()->comment('cân nặng quy đổi , đơn vị gram,box me trả về'),
            'dimension_l' => $this->double()->comment('chiều dài , đơn vị cm,box me trả về'),
            'dimension_w' => $this->double()->comment('chiều rộng , đơn vị cm,box me trả về'),
            'dimension_h' => $this->double()->comment('chiều cao , đơn vị cm,box me trả về'),
            'STOCKIN_LOCAL' => $this->bigInteger()->comment(''),
            'STOCKOUT_LOCAL' => $this->bigInteger()->comment(''),
            'AT_CUSTOMER' => $this->bigInteger()->comment(''),
            'RETURNED' => $this->bigInteger()->comment(''),
            'LOST' => $this->bigInteger()->comment(''),
            'current_status' => $this->bigInteger()->comment(''),
            'shipment_id' => $this->integer(11)->comment(''),
            'created_time' => $this->bigInteger()->comment('thời gian tạo'),
            'updated_time' => $this->bigInteger()->comment('thời gian cập nhật'),
        ]);

        foreach ($this->list as $data){
            $this->createIndex('idx-package_item-'.$data['column'],'package_item',$data['column']);
            $this->addForeignKey('fk-package_item-'.$data['column'], 'package_item', $data['column'], $data['table'], 'id');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190220_070746_package_item cannot be reverted.\n";

        foreach ($this->list as $data){
            $this->dropIndex('idx-package_item-'.$data['column'], 'package_item');
            $this->dropForeignKey('fk-package_item-'.$data['column'], 'package_item');
        }
        $this->dropTable('package_item');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190220_070746_package_item cannot be reverted.\n";

        return false;
    }
    */
}
