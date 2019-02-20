<?php

use yii\db\Migration;

/**
 * Class m190220_063720_package
 */
class m190220_063720_package extends Migration
{
    public $list = [
        [
            'column' => 'warehouse_id',
            'table' => 'warehouse',
        ]
    ];
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('package',[
            'id' => $this->primaryKey()->comment(''),
            'package_code' => $this->integer(11)->comment('mã kiện của weshop'),
            'tracking_seller' => $this->string(255)->comment('mã giao dịch của weshop'),
            'order_ids' => $this->text()->comment('List mã order cách nhau bằng dấu ,'),
            'tracking_reference_1' => $this->text()->comment('mã tracking tham chiếu 1'),
            'tracking_reference_2' => $this->text()->comment('mã tracking tham chiếu 2'),
            'manifest_code' => $this->text()->comment('mã lô hàng'),
            'package_weight' => $this->double()->comment('cân nặng tịnh của cả gói , đơn vị gram'),
            'package_change_weight' => $this->double()->comment('cân nặng quy đổi của cả gói , đơn vị gram'),
            'package_dimension_l' => $this->double()->comment('chiều dài của cả gói , đơn vị cm'),
            'package_dimension_w' => $this->double()->comment('chiều rộng của cả gói , đơn vị cm'),
            'package_dimension_h' => $this->double()->comment('chiều cao của cả gói , đơn vị cm'),
            'SELLER_SHIPPED' => $this->bigInteger()->comment(''),
            'STOCKIN_US' => $this->bigInteger()->comment(''),
            'STOCKOUT_US' => $this->bigInteger()->comment(''),
            'STOCKIN_LOCAL' => $this->bigInteger()->comment(''),
            'LOST' => $this->bigInteger()->comment(''),
            'current_status' => $this->bigInteger()->comment(''),
            'warehouse_id' => $this->integer(11)->comment('id kho nhận'),
            'create_time' => $this->bigInteger()->comment('thời gian tạo'),
            'update_time' => $this->bigInteger()->comment('thời gian cập nhật'),
        ]);

        foreach ($this->list as $data){
            $this->createIndex('idx-package-'.$data['column'],'package',$data['column']);
            $this->addForeignKey('fk-package-'.$data['column'], 'package', $data['column'], $data['table'], 'id');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190220_063720_package cannot be reverted.\n";

        foreach ($this->list as $data){
            $this->dropIndex('idx-package-'.$data['column'], 'package');
            $this->dropForeignKey('fk-package-'.$data['column'], 'package');
        }
        $this->dropTable('package');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190220_063720_package cannot be reverted.\n";

        return false;
    }
    */
}
