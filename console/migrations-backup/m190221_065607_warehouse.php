<?php

use yii\db\Migration;

/**
 * Class m190221_065607_warehouse
 */
class m190221_065607_warehouse extends Migration
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
        $this->createTable('warehouse',[
            'id' => $this->primaryKey()->comment(''),
            'name' => $this->string(255)->comment(''),
            'description' => $this->string(255)->comment(''),
            'district_id' => $this->integer(11)->comment(''),
            'province_id' => $this->integer(11)->comment(''),
            'country_id' => $this->integer(11)->comment(''),
            'store_id' => $this->integer(11)->comment(''),
            'address' => $this->string(255)->comment(''),
            'type' => $this->integer(11)->comment('1: Full Operation Warehouse , 2 : Transit Warehouse '),
            'warehouse_group' => $this->string(255)->comment('1: Nhóm Transit , 2 : nhóm lưu trữ, 3 : nhóm note purchase. Các nhóm sẽ đc khai báo const '),
            'post_code' => $this->string(255)->comment(''),
            'telephone' => $this->string(255)->comment(''),
            'email' => $this->string(255)->comment(''),
            'contact_person' => $this->string(255)->comment(''),
            'ref_warehouse_id' => $this->integer(11)->comment(''),

            'created_time' => $this->bigInteger()->comment('thời gian tạo'),
            'updated_time' => $this->bigInteger()->comment('thời gian cập nhật'),
        ],$tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190221_065607_warehouse cannot be reverted.\n";

//        foreach ($this->list as $data){
//            $this->dropIndex('idx-warehouse-'.$data['column'], 'warehouse');
//            $this->dropForeignKey('fk-warehouse-'.$data['column'], 'warehouse');
//        }
//        $this->dropTable('warehouse');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190221_065607_warehouse cannot be reverted.\n";

        return false;
    }
    */
}
