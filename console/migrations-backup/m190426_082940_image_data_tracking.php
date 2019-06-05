<?php

use yii\db\Migration;

/**
 * Class m190426_082940_image_data_tracking
 */
class m190426_082940_image_data_tracking extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('draft_data_tracking','image',$this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190426_082940_image_data_tracking cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190426_082940_image_data_tracking cannot be reverted.\n";

        return false;
    }
    */
}
