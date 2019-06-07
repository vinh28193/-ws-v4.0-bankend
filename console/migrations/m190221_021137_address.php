<?php

use yii\db\Migration;

/**
 * Class m190221_021137_address
 */
class m190221_021137_address extends Migration
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
        $this->createTable('address',[
            'id' => $this->primaryKey()->comment("ID"),
            'first_name' => $this->string(255)->comment(""),
            'last_name' => $this->string(255)->comment(""),
            'email' => $this->string(255)->comment(""),
            'phone' => $this->string(255)->comment(""),
            'country_id' => $this->integer(11)->comment(""),
            'country_name' => $this->string(255)->comment(""),
            'province_id' => $this->integer(11)->comment(""),
            'province_name' => $this->string(255)->comment(""),
            'district_id' => $this->integer(11)->comment(""),
            'district_name' => $this->string(255)->comment(""),
            'address' => $this->text()->comment(""),
            'post_code' => $this->string(255)->comment(""),
            'store_id' => $this->integer(11)->comment(""),
            'type' => $this->string(255)->comment(""),
            'is_default' => $this->integer(11)->comment(""),
            'customer_id' => $this->integer(11)->comment(" Từ 16/05/2019 customer_id --> User_id"),

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

//        foreach ($this->list as $data){
//            $this->dropIndex('idx-address-'.$data['column'], 'address');
//            $this->dropForeignKey('fk-address-'.$data['column'], 'address');
//        }
//        $this->dropTable('address');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190221_021137_address cannot be reverted.\n";

        return false;
    }
    */
}
