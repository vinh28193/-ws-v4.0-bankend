<?php

use yii\db\Migration;

class m190606_041946_create_table_WS_IMAGE extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%IMAGE}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'store_id' => $this->integer()->comment('Store ID reference'),
            'reference' => $this->string(100)->comment('Reference key'),
            'reference_id' => $this->integer()->comment('Reference identity'),
            'base_path' => $this->string(255)->comment('basePath'),
            'name' => $this->string(255)->comment('name'),
            'full_path' => $this->string(255)->comment('tmp name'),
            'width' => $this->integer()->comment('saved width'),
            'height' => $this->integer()->comment('saved height'),
            'quality' => $this->integer()->comment('saved quality'),
            'size' => $this->integer()->comment('saved size (mb)'),
            'type' => $this->string(100)->comment('Image Type (jpg,png)'),
            'is_uploaded' => $this->integer()->defaultValue('0')->comment('1 is form upload'),
            'status' => $this->integer()->defaultValue('1')->comment('Status (1:Active;2:Inactive)'),
            'uploaded_by' => $this->integer()->comment('Created by'),
            'uploaded_at' => $this->integer()->comment('Created at (timestamp)'),
            'uploaded_from_ip' => $this->string(20)->comment('Updated from ip address'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%IMAGE}}');
    }
}
