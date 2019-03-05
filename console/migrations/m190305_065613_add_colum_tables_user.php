<?php

use yii\db\Migration;

/**
 * Class m190305_065613_add_colum_tables_user
 */
class m190305_065613_add_colum_tables_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user','github','varchar(500)');
        $this->addCommentOnColumn('user','github',' user co link github');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190305_065613_add_colum_tables_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190305_065613_add_colum_tables_user cannot be reverted.\n";

        return false;
    }
    */
}
