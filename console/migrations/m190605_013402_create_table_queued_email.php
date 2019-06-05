<?php

use yii\db\Migration;

class m190605_013402_create_table_queued_email extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%queued_email}}', [
            'id' => $this->primaryKey(),
            'Priority' => $this->integer(11),
            'From' => $this->string(500),
            'FromName' => $this->string(500),
            'To' => $this->string(500),
            'ToName' => $this->string(500),
            'CC' => $this->string(500),
            'Bcc' => $this->string(500),
            'Subject' => $this->string(1000),
            'Body' => $this->text(),
            'CreatedTime' => $this->dateTime(),
            'SentTries' => $this->integer(11)->defaultValue('0'),
            'SentOn' => $this->dateTime(),
            'EmailAccountId' => $this->integer(11),
            'CampaignId' => $this->integer(11),
            'TemplateId' => $this->integer(11),
            'RecipientId' => $this->string(50),
            'Opened' => $this->tinyInteger(1),
            'Openedon' => $this->dateTime(),
            'Status' => $this->char(30)->defaultValue('0'),
            'OrderId' => $this->integer(11),
            'api_id' => $this->string(200),
            'Bounce' => $this->tinyInteger(1),
            'Clicked' => $this->tinyInteger(1),
            'Sent' => $this->tinyInteger(1),
            'Using' => $this->tinyInteger(1)->defaultValue('0')->comment('Flag select email send'),
            'OrderType' => $this->string(10)->comment('loai order'),
            'StatusV3' => $this->tinyInteger(1)->defaultValue('0'),
        ], $tableOptions);

        $this->createIndex('Status', '{{%queued_email}}', 'Status');
        $this->createIndex('OrderId', '{{%queued_email}}', 'OrderId');
        $this->createIndex('TemplateId', '{{%queued_email}}', 'TemplateId');
        $this->createIndex('Priority', '{{%queued_email}}', 'Priority');
        $this->createIndex('CampaignId', '{{%queued_email}}', 'CampaignId');
        $this->createIndex('QueuedEmail_EmailAccount', '{{%queued_email}}', 'EmailAccountId');
        /*
        $this->addForeignKey('queued_email_ibfk_1', '{{%queued_email}}', 'EmailAccountId', '{{%email_account}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('queued_email_ibfk_4', '{{%queued_email}}', 'OrderId', '{{%order}}', 'id', 'RESTRICT', 'RESTRICT');
        */
    }

    public function down()
    {
        $this->dropTable('{{%queued_email}}');
    }
}
