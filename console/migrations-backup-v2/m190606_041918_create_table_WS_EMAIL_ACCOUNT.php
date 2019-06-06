<?php

use yii\db\Migration;

class m190606_041918_create_table_WS_EMAIL_ACCOUNT extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%EMAIL_ACCOUNT}}', [
            'id' => $this->integer()->notNull(),
            'email' => $this->string(255)->comment('email'),
            'displayname' => $this->string(255)->comment('Ten hi?n th?'),
            'host' => $this->string(255)->comment('Ten host'),
            'port' => $this->integer()->comment('C?ng '),
            'username' => $this->string(255)->comment('Ten dang nh?p'),
            'password' => $this->string(255)->comment('M?t kh?u'),
            'enablessl' => $this->integer()->comment('Cho phep s? d?ng ssl?'),
            'usedefaultcredentials' => $this->integer(),
            'organizationid' => $this->integer(),
            'storeid' => $this->integer(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%EMAIL_ACCOUNT}}');
    }
}
