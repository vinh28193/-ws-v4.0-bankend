<?php

use yii\db\Migration;

/**
 * Class m190405_092322_alter_column_trackingCode
 */
class m190405_092322_alter_column_trackingCode extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('tracking_code','tracking_code','varchar(255)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190405_092322_alter_column_trackingCode cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190405_092322_alter_column_trackingCode cannot be reverted.\n";

        return false;
    }
    */
}
