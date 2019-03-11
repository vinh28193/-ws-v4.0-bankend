<?php

use yii\db\Migration;

/**
 * Class m190306_100633_add_foreignkey_ip_filter_table
 */
class m190306_100633_add_foreignkey_ip_filter_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('visits_ip_idx', '{{%visitor_log}}', 'ip');
        $this->createIndex('visits_timestamp_idx', '{{%visitor_log}}', 'created_at');
        $this->createIndex('fki_vl_va_ua_fkey', '{{%visitor_log}}', 'id');
        $this->addForeignKey('visits_visitor_fkey', '{{%visitor_log}}', 'ip', '{{%visitor}}', 'ip', 'CASCADE', 'CASCADE');
        $this->createIndex('va_ua_vl_fkey', '{{%visitor_agent}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190306_100633_add_foreignkey_ip_filter_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190306_100633_add_foreignkey_ip_filter_table cannot be reverted.\n";

        return false;
    }
    */
}
