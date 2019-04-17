<?php

use yii\db\Migration;

/**
 * Class m190329_092204_version_in_tables_trackingcode
 */
class m190329_092204_version_in_tables_trackingcode extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('tracking_code','version',$this->string(255)->defaultValue('4.0')->after('id')->comment('version 4.0'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190329_092204_version_in_tables_trackingcode cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190329_092204_version_in_tables_trackingcode cannot be reverted.\n";

        return false;
    }
    */
}
