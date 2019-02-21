<?php

use yii\db\Migration;

/**
 * Class m190221_020437_system_district
 */
class m190221_020437_system_district extends Migration
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
        ],$tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

//        foreach ($this->list as $data){
//            $this->dropIndex('idx-system_district-'.$data['column'], 'system_district');
//            $this->dropForeignKey('fk-system_district-'.$data['column'], 'system_district');
//        }
//        $this->dropTable('system_district');

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
