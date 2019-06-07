<?php

use yii\db\Migration;

/**
 * Class m190309_014741_create_table_district_mapping
 */
class m190309_014741_create_table_district_mapping extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('system_district_mapping',[
            'id' => $this->primaryKey(),
            'district_id' => $this->integer()->comment("id system_district"),
            'province_id' => $this->integer()->comment("id system_province"),
            'box_me_district_id' => $this->integer()->comment("id district box me"),
            'box_me_province_id' => $this->integer()->comment("id province box me"),
            'district_name' => $this->string(),
            'province_name' => $this->string(),
        ]);
       }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190309_014741_create_table_district_mapping cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190309_014741_create_table_district_mapping cannot be reverted.\n";

        return false;
    }
    */
}
