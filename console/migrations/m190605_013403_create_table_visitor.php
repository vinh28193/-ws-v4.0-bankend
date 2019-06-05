<?php

use yii\db\Migration;

class m190605_013403_create_table_visitor extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%visitor}}', [
            'ip' => $this->string(255)->notNull()->append('PRIMARY KEY'),
            'is_blacklisted' => $this->tinyInteger(1)->notNull()->defaultValue('0'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'user_id' => $this->integer(11),
            'name' => $this->string(255),
            'message' => $this->text(),
            'visits' => $this->integer(11)->notNull()->defaultValue('0'),
            'city' => $this->string(255),
            'region' => $this->string(255),
            'country' => $this->string(255),
            'latitude' => $this->double(),
            'longitude' => $this->double(),
            'organization' => $this->string(255),
            'proxy' => $this->string(255),
        ], $tableOptions);

        $this->createIndex('visitor_ip_idx', '{{%visitor}}', 'ip', true);
    }

    public function down()
    {
        $this->dropTable('{{%visitor}}');
    }
}
