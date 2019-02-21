<?php

use yii\db\Migration;

/**
 * Class m190221_025443_scope_user
 */
class m190221_025443_scope_user extends Migration
{
    public $list = [
        [
            'column' => 'user_id',
            'table' => 'users',
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
        $this->createTable('scope_user',[
            'id' => $this->primaryKey()->comment("ID"),
            'user_id' => $this->integer(11)->comment(""),
            'scope_id' => $this->integer(11)->comment(""),

            'created_time' => $this->bigInteger()->comment(""),
            'updated_time' => $this->bigInteger()->comment(""),
            'remove' => $this->tinyInteger(4)->comment(""),
        ]);

        foreach ($this->list as $data){
            $this->createIndex('idx-scope_user-'.$data['column'],'scope_user',$data['column']);
            $this->addForeignKey('fk-scope_user-'.$data['column'], 'scope_user', $data['column'], $data['table'], 'id');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        foreach ($this->list as $data){
            $this->dropIndex('idx-scope_user-'.$data['column'], 'scope_user');
            $this->dropForeignKey('fk-scope_user-'.$data['column'], 'scope_user');
        }
        $this->dropTable('scope_user');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190221_025443_scope_user cannot be reverted.\n";

        return false;
    }
    */
}
