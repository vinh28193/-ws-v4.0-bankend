<?php

use yii\db\Migration;

class m190606_042613_create_table_WS_STORE extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%STORE}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'country_id' => $this->integer(),
            'locale' => $this->string(255),
            'name' => $this->string(255),
            'country_name' => $this->string(255),
            'address' => $this->text(),
            'url' => $this->string(255),
            'currency' => $this->string(255),
            'currency_id' => $this->integer(),
            'status' => $this->integer(),
            'env' => $this->integer()->comment('PROD or UAT or BETA ...'),
            'version' => $this->string(255)->defaultValue('\'4.0\'')->comment('version 4.0'),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108610C00006$$', '{{%STORE}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%STORE}}');
    }
}
