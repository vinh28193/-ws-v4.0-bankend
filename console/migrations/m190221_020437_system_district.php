<?php

use yii\db\Migration;

/**
 * Class m190221_020437_system_district
 */
class m190221_020437_system_district extends Migration
{
    public $list = [
        [
            'column' => 'province_id',
            'table' => 'system_state_province',
        ],
        [
            'column' => 'country_id',
            'table' => 'system_country',
        ]
    ];
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('system_district',[
            'id' => $this->primaryKey()->comment("ID"),
            'name' => $this->string(255)->comment(""),
            'name_local' => $this->string(255)->comment(""),
            'name_alias' => $this->string(255)->comment(""),
            'display_order' => $this->integer(11)->comment(""),
            'province_id' => $this->integer(11)->comment(""),
            'country_id' => $this->integer(11)->comment(""),

            'created_time' => $this->bigInteger()->comment(""),
            'updated_time' => $this->bigInteger()->comment(""),
            'remove' => $this->tinyInteger(4)->comment(""),
        ]);

        foreach ($this->list as $data){
            $this->createIndex('idx-system_district-'.$data['column'],'system_district',$data['column']);
            $this->addForeignKey('fk-system_district-'.$data['column'], 'system_district', $data['column'], $data['table'], 'id');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        foreach ($this->list as $data){
            $this->dropIndex('idx-system_district-'.$data['column'], 'system_district');
            $this->dropForeignKey('fk-system_district-'.$data['column'], 'system_district');
        }
        $this->dropTable('system_district');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190221_020437_system_district cannot be reverted.\n";

        return false;
    }
    */
}
