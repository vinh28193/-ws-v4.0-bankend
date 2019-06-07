<?php

use yii\db\Migration;

/**
 * Class m190306_084025_table_manifest
 */
class m190306_084025_table_manifest extends Migration
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

        $this->createTable('manifest',[
            'id' => $this->primaryKey()->notNull(),
            'manifest_code' => $this->string()->notNull()->comment("Mã lô"),
            'send_warehouse_id' => $this->integer()->comment("Kho gửi đi"),
            'receive_warehouse_id' => $this->integer()->comment("Kho nhận"),
            'us_stock_out_time' => $this->dateTime()->comment("ngày xuất kho mỹ"),
            'local_stock_in_time' => $this->dateTime()->comment("ngày nhậo kho việt nam"),
            'local_stock_out_time' => $this->dateTime()->comment("ngày xuất kho"),
            'store_id' => $this->integer()->comment(""),
            'created_by' => $this->integer()->comment("người tạo"),
            'updated_by' => $this->integer()->comment(""),
            'created_at' => $this->integer()->comment("ngày tạo"),
            'updated_at' => $this->integer()->comment(""),
            'active' => $this->integer()->comment("")->defaultValue(1),
        ],$tableOptions);


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190306_084025_table_manifest cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190306_084025_table_manifest cannot be reverted.\n";

        return false;
    }
    */
}
