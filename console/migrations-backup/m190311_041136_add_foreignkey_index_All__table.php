<?php

use yii\db\Migration;

/**
 * Class m190311_041136_add_foreignkey_index_All__table
 */
class m190311_041136_add_foreignkey_index_All__table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //Tables manifest

        $this->addForeignKey('idx-product-currency','product','currency_id','system_currency','id');
        $this->createIndex('idx-product-currency','product','currency_id');

        $this->createIndex('idx-manifest-warehouse_send','manifest','send_warehouse_id');
        $this->addForeignKey('fk-manifest-warehouse_send','manifest','send_warehouse_id','warehouse','id');

        $this->createIndex('idx-manifest-receive_warehouse','manifest','receive_warehouse_id');
        $this->addForeignKey('fk-manifest-receive_warehouse','manifest','receive_warehouse_id','warehouse','id');

        $this->createIndex('idx-manifest-store','manifest','store_id');
        $this->addForeignKey('fk-manifest-store','manifest','store_id','store','id');

        $this->createIndex('idx-manifest-create_by','manifest','created_by');
        $this->addForeignKey('fk-manifest-create_by','manifest','created_by','user','id');

        $this->createIndex('idx-manifest-updated_by','manifest','updated_by');
        $this->addForeignKey('fk-manifest-updated_by','manifest','updated_by','user','id');

         // district_mapping
         $this->addForeignKey('fk-sys_district_mapping-district','system_district_mapping','district_id','system_district','id');
         $this->addForeignKey('fk-sys_district_mapping-province','system_district_mapping','province_id','system_state_province','id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190311_041136_add_foreignkey_index_All__table SafeDown be reverted.\n";

        //Tables manifest

        $this->dropIndex('idx-manifest-warehouse_send','manifest','send_warehouse_id');
        $this->dropForeignKey('fk-manifest-warehouse_send','manifest','send_warehouse_id','warehouse','id');

        $this->dropIndex('idx-manifest-receive_warehouse','manifest','receive_warehouse_id');
        $this->dropForeignKey('fk-manifest-receive_warehouse','manifest','receive_warehouse_id','warehouse','id');

        $this->dropIndex('idx-manifest-store','manifest','store_id');
        $this->dropForeignKey('fk-manifest-store','manifest','store_id','store','id');

        $this->dropIndex('idx-manifest-create_by','manifest','created_by');
        $this->dropForeignKey('fk-manifest-create_by','manifest','created_by','user','id');

        $this->dropIndex('idx-manifest-updated_by','manifest','updated_by');
        $this->dropForeignKey('fk-manifest-updated_by','manifest','updated_by','user','id');

        // district_mapping
        $this->dropIndex('fk-sys_district_mapping-district','system_district_mapping','district_id','system_district','id');
        $this->dropForeignKey('fk-sys_district_mapping-province','system_district_mapping','province_id','system_state_province','id');


    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190311_041136_add_foreignkey_index_All__table cannot be reverted.\n";

        return false;
    }
    */
}
