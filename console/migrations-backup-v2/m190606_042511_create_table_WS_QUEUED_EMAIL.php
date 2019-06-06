<?php

use yii\db\Migration;

class m190606_042511_create_table_WS_QUEUED_EMAIL extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%QUEUED_EMAIL}}', [
            'id' => $this->integer()->notNull(),
            'priority' => $this->integer(),
            'from' => $this->string(500),
            'fromname' => $this->string(500),
            'to' => $this->string(500),
            'toname' => $this->string(500),
            'cc' => $this->string(500),
            'bcc' => $this->string(500),
            'subject' => $this->string(1000),
            'body' => $this->text(),
            'createdtime' => $this->timestamp(),
            'senttries' => $this->integer()->defaultValue('0'),
            'senton' => $this->timestamp(),
            'emailaccountid' => $this->integer(),
            'campaignid' => $this->integer(),
            'templateid' => $this->integer(),
            'recipientid' => $this->string(50),
            'opened' => $this->integer(),
            'openedon' => $this->timestamp(),
            'status' => $this->string(30)->defaultValue('0'),
            'orderid' => $this->integer(),
            'api_id' => $this->string(200),
            'bounce' => $this->integer(),
            'clicked' => $this->integer(),
            'sent' => $this->integer(),
            'using' => $this->integer()->defaultValue('0')->comment('Flag select email send'),
            'ordertype' => $this->string(10)->comment('loai order'),
            'statusv3' => $this->integer()->defaultValue('0'),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108802C00010$$', '{{%QUEUED_EMAIL}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%QUEUED_EMAIL}}');
    }
}
