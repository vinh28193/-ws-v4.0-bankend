<?php

use yii\db\Migration;

class m190606_041711_create_table_WS_CATEGORY_GROUP extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%CATEGORY_GROUP}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'name' => $this->string(255),
            'description' => $this->string(255),
            'store_id' => $this->integer(),
            'parent_id' => $this->integer(),
            'rule' => $this->text(),
            'rule_description' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'active' => $this->integer(),
            'remove' => $this->integer(),
            'version' => $this->string(255)->defaultValue('\'4.0\'')->comment('version 4.0'),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108632C00006$$', '{{%CATEGORY_GROUP}}', '', true);
        $this->createIndex('SYS_IL0000108632C00007$$', '{{%CATEGORY_GROUP}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%CATEGORY_GROUP}}');
    }
}
