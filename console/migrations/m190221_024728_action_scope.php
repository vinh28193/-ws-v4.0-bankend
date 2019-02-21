<?php

use yii\db\Migration;

/**
 * Class m190221_024728_action_scope
 */
class m190221_024728_action_scope extends Migration
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
        $this->createTable('action_scope',[
            'id' => $this->primaryKey()->comment("ID"),
            'action_id' => $this->integer(11)->comment(""),
            'scope_id' => $this->integer(11)->comment(""),

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
//            $this->dropIndex('idx-action_scope-'.$data['column'], 'action_scope');
//            $this->dropForeignKey('fk-action_scope-'.$data['column'], 'action_scope');
//        }
//        $this->dropTable('action_scope');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190221_024728_action_scope cannot be reverted.\n";

        return false;
    }
    */
}
