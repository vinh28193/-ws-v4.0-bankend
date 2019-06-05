<?php

use yii\db\Migration;

class m190605_013403_create_table_system_zipcode extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%system_zipcode}}', [
            'id' => $this->integer(11)->notNull()->append('AUTO_INCREMENT PRIMARY KEY')->comment('ID'),
            'system_country_id' => $this->integer(11)->comment(' Weshop country id'),
            'system_state_province_id' => $this->integer(11)->defaultValue('1')->comment('Weshop state province id'),
            'system_district_id' => $this->integer(11)->comment('Weshop district id'),
            'boxme_country_id' => $this->integer(11)->comment('Boxme country alias'),
            'boxme_state_province_id' => $this->integer(11)->comment('Boxme state province id'),
            'boxme_district_id' => $this->integer(11)->comment('Boxme district id'),
            'zip_code' => $this->integer(11)->comment('Zip code'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%system_zipcode}}');
    }
}
