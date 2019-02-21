<?php

use yii\db\Migration;

/**
 * Class m190221_021137_address
 */
class m190221_021137_address extends Migration
{
    public $list = [
        [
            'column' => 'country_id',
            'table' => 'system_country',
        ],
        [
            'column' => 'province_id',
            'table' => 'system_state_province',
        ],
        [
            'column' => 'district_id',
            'table' => 'system_district',
        ],
        [
            'column' => 'store_id',
            'table' => 'store',
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
            'customer_id' => $this->integer(11)->comment(""),

            'created_time' => $this->bigInteger()->comment(""),
            'updated_time' => $this->bigInteger()->comment(""),
            'remove' => $this->tinyInteger(4)->comment(""),
        ]);

        foreach ($this->list as $data){
            $this->createIndex('idx-address-'.$data['column'],'address',$data['column']);
            $this->addForeignKey('fk-address-'.$data['column'], 'address', $data['column'], $data['table'], 'id');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        foreach ($this->list as $data){
            $this->dropIndex('idx-address-'.$data['column'], 'address');
            $this->dropForeignKey('fk-address-'.$data['column'], 'address');
        }
        $this->dropTable('address');

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
