<?php

use yii\db\Migration;

/**
 * Class m190221_015306_system_state_province
 */
class m190221_015306_system_state_province extends Migration
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
        $this->createTable('system_state_province',[
            'id' => $this->primaryKey()->comment("ID"),
            'country_id' => $this->integer(11)->comment("id nước"),
            'name' => $this->string(255)->comment(""),
            'name_local' => $this->string(255)->comment(""),
            'name_alias' => $this->string(255)->comment(""),
            'display_order' => $this->integer(11)->comment(""),

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
        echo "m190221_015306_system_state_province cannot be reverted.\n";
//        foreach ($this->list as $data){
//            $this->dropIndex('idx-system_state_province-'.$data['column'], 'system_state_province');
//            $this->dropForeignKey('fk-system_state_province-'.$data['column'], 'system_state_province');
//        }
//        $this->dropTable('system_state_province');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190221_015306_system_state_province cannot be reverted.\n";

        return false;
    }
    */
}
