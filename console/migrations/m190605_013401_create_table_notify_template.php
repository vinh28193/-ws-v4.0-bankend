<?php

use yii\db\Migration;

class m190605_013401_create_table_notify_template extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%notify_template}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string(255),
            'receive' => $this->string(255)->comment(' 0 : phone | 1: email'),
            'store' => $this->integer(11),
            'from_name' => $this->string(255),
            'from_address' => $this->string(255),
            'to_name' => $this->string(255),
            'to_address' => $this->string(255),
            'subject' => $this->text(),
            'html_content' => $this->text(),
            'text_content' => $this->string(255),
            'status' => $this->integer(11),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%notify_template}}');
    }
}
