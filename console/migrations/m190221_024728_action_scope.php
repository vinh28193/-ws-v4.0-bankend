<?php

use yii\db\Migration;

/**
 * Class m190221_024728_action_scope
 */
class m190221_024728_action_scope extends Migration
{
    public $list = [
        [
            'column' => 'action_id',
            'table' => 'actions',
        ],
        [
            'column' => 'scope_id',
            'table' => 'scopes',
        ]
    ];
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('action_scope',[
            'id' => $this->primaryKey()->comment("ID"),
            'action_id' => $this->integer(11)->comment(""),
            'scope_id' => $this->integer(11)->comment(""),

            'created_time' => $this->bigInteger()->comment(""),
            'updated_time' => $this->bigInteger()->comment(""),
            'remove' => $this->tinyInteger(4)->comment(""),
        ]);

        foreach ($this->list as $data){
            $this->createIndex('idx-action_scope-'.$data['column'],'action_scope',$data['column']);
            $this->addForeignKey('fk-action_scope-'.$data['column'], 'action_scope', $data['column'], $data['table'], 'id');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        foreach ($this->list as $data){
            $this->dropIndex('idx-action_scope-'.$data['column'], 'action_scope');
            $this->dropForeignKey('fk-action_scope-'.$data['column'], 'action_scope');
        }
        $this->dropTable('action_scope');

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
