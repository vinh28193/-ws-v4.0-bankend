<?php

use common\components\db\Migration;

/**
 * Handles the creation of table `system_zipcode`.
 * Has foreign keys to the tables:
 *
 * - `store`
 */
class m190424_084101_create_system_zipcode_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('system_zipcode', [
            'id' => $this->primaryKey()->comment('ID'),
            'system_country_id' => $this->integer(11)->comment(' Weshop country id'),
            'system_state_province_id' => $this->integer(11)->defaultValue(1)->comment('Weshop state province id'),
            'system_district_id' => $this->integer(11)->defaultValue(null)->comment('Weshop district id'),
            'boxme_country_id' => $this->integer(11)->defaultValue(null)->comment('Boxme country alias'),
            'boxme_state_province_id' => $this->integer(11)->defaultValue(null)->comment('Boxme state province id'),
            'boxme_district_id' => $this->integer(11)->defaultValue(null)->comment('Boxme district id'),
            'zip_code' => $this->integer(11)->defaultValue(null)->comment('Zip code'),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('system_zipcode');
    }
}
