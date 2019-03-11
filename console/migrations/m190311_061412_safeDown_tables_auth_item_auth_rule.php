<?php

use yii\db\Migration;

/**
 * Class m190311_061412_safeDown_tables_auth_item_auth_rule
 */
class m190311_061412_safeDown_tables_auth_item_auth_rule extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190311_061412_safeDown_tables_auth_item_auth_rule.\n";

        $this->dropIndex('rule_name','auth_item');
        $this->dropIndex('idx-auth_item-type','auth_item');

        $this->dropForeignKey('auth_item_ibfk_1','auth_item');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190311_061412_safeDown_tables_auth_item_auth_rule cannot be reverted.\n";

        return false;
    }
    */
}
