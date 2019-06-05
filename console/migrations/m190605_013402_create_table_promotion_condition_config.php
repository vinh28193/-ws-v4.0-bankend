<?php

use yii\db\Migration;

class m190605_013402_create_table_promotion_condition_config extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%promotion_condition_config}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID'),
            'name' => $this->string(80)->notNull()->comment('name of condition'),
            'operator' => $this->string(10)->notNull()->comment('Operator of condition'),
            'type_cast' => $this->string(10)->notNull()->defaultValue('integer')->comment('php type cast (integer,string,float ..etc)'),
            'description' => $this->text()->comment('description'),
            'status' => $this->smallInteger(6)->defaultValue('1')->comment('Status (1:Active;2:Inactive)'),
            'created_by' => $this->integer(11)->comment('Created by'),
            'created_at' => $this->integer(11)->comment('Created at (timestamp)'),
            'updated_by' => $this->integer(11)->comment('Updated by'),
            'updated_at' => $this->integer(11)->comment('Updated at (timestamp)'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%promotion_condition_config}}');
    }
}
