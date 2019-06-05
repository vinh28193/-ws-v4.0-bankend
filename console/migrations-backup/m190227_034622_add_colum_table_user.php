<?php

use yii\db\Migration;

/**
 * Class m190227_034622_add_colum_table_user
 */
class m190227_034622_add_colum_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user','scopes','varchar(500)');
        $this->addColumn('user','store_id','int');
        $this->addCommentOnColumn('user','scopes','nhiều scope cách nhau bằng dấu ,. scope chính đặt tại đầu');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190227_034622_add_colum_table_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190227_034622_add_colum_table_user cannot be reverted.\n";

        return false;
    }
    */
}
