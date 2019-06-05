<?php

use yii\db\Migration;

class m190605_013401_create_table_country extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%country}}', [
            'code' => $this->string(255)->notNull()->append('PRIMARY KEY'),
            'name' => $this->string(255)->notNull(),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
        ], $tableOptions);

        $this->createIndex('country_code_idx', '{{%country}}', 'code', true);
    }

    public function down()
    {
        $this->dropTable('{{%country}}');
    }
}
