<?php

use yii\db\Migration;

class m190606_042727_create_table_WS_SYSTEM_ZIPCODE extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%SYSTEM_ZIPCODE}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'system_country_id' => $this->integer()->comment(' Weshop country id'),
            'system_state_province_id' => $this->integer()->defaultValue('1')->comment('Weshop state province id'),
            'system_district_id' => $this->integer()->comment('Weshop district id'),
            'boxme_country_id' => $this->integer()->comment('Boxme country alias'),
            'boxme_state_province_id' => $this->integer()->comment('Boxme state province id'),
            'boxme_district_id' => $this->integer()->comment('Boxme district id'),
            'zip_code' => $this->integer()->comment('Zip code'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%SYSTEM_ZIPCODE}}');
    }
}
