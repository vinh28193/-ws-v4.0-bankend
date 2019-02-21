<?php

use yii\db\Migration;

/**
 * Class m190221_025631_seller
 */
class m190221_025631_seller extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('seller',[
            'id' => $this->primaryKey()->comment("ID"),
            'name' => $this->string(255)->comment(""),
            'link_store' => $this->text()->comment(""),
            'rate' => $this->string(255)->comment(""),
            'description' => $this->text()->comment(""),

            'created_time' => $this->bigInteger()->comment(""),
            'updated_time' => $this->bigInteger()->comment(""),
            'remove' => $this->tinyInteger(4)->comment(""),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190221_025631_seller cannot be reverted.\n";

        return false;
    }
    */
}
