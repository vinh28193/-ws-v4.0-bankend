<?php

use common\components\db\Migration;

/**
 * Handles the creation of table `shopping_cart`.
 * Has foreign keys to the tables:
 */
class m190226_081548_create_shopping_cart_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('shopping_cart', [
            'key' => $this->string(32)->notNull()->comment('card key'),
            'identity' => $this->integer(11)->null()->comment('Identity Id'),
            'data' => $this->binary(),
            'created_at' => $this->integer(11)->defaultValue(null)->comment('Created at (timestamp)'),
        ]);

        $this->addPrimaryKey('pk-key-identity','shopping_cart',['key','identity']);
        $this->createIndex('idx-identity','shopping_cart','identity');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //$this->dropPrimaryKey('pk-key-identity','pk-key-identity');
        $this->dropTable('shopping_cart');
    }
}
