<?php

use yii\db\Migration;

/**
 * Class m190405_063426_update_column_image
 */
class m190405_063426_update_column_image extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('draft_missing_tracking','image','text');
        $this->alterColumn('draft_package_item','image','text');
        $this->alterColumn('draft_wasting_tracking','image','text');
        $this->alterColumn('draft_boxme_tracking','image','text');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190405_063426_update_column_image cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190405_063426_update_column_image cannot be reverted.\n";

        return false;
    }
    */
}
